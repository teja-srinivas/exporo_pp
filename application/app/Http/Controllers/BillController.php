<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\Models\Bill;
use App\Models\Commission;
use App\Models\Investment;
use App\Models\Investor;
use App\Models\User;
use App\Jobs\SendMail;
use App\Traits\Encryptable;
use App\Traits\Person;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('bills.index', [
            'bills' => Bill::getDetailsPerUser()->with('user')->get()->map(function (Bill $bill) use ($request) {
                return [
                    'name' => $bill->getDisplayName(),
                    'date' => $bill->created_at,
                    'user' => UserResource::make($bill->user)->toArray($request),
                    'meta' => [
                        'gross' => format_money($bill->gross),
                        'commissions' => $bill->commissions,
                    ],
                    'links' => [
                        'self' => route('bills.show', $bill),
                    ],
                ];
            })->sortBy(function (array $bill) {
                // Sort bills by date, but ignore the creation time, just use the date
                return $bill['date']->format('Ymd');
            })->sortNatural('user.lastName'),
        ]);
    }

    public function preview(User $user)
    {
        $investments = $this->mapForView($this->getBillableCommissionsForUser($user));

        return response()->view('bills.pdf.bill', $investments + [
                'user' => $user,
                'company' => optional($user->company),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bills = $this->getBillableCommissions()->map(function (Commission $row) {
            return [
                'userId' => $row->user_id,
                'firstName' => Encryptable::decrypt($row->first_name),
                'lastName' => Encryptable::decrypt($row->last_name),
                'sum' => $row->sum,
            ];
        })->sortNatural('lastName');

        return response()->view('bills.create', [
            'bills' => $bills,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'release_at' => 'required|date',
        ]);

        $releaseAt = Carbon::parse($data['release_at']);

        // Fetch all users that can be billed
        $users = User::query()->whereKey(Commission::query()
            ->isBillable()
            ->distinct()
            ->pluck('commissions.user_id')
        );

        // Pre-select all valid commission IDs
        // Doing the isBillable check for each updates eats up DB time
        $commissionIds = Commission::query()->select('user_id', 'id')->get()->mapToGroups(function ($row) {
            return [
                $row['user_id'] => $row['id'],
            ];
        });

        Bill::disableAuditing();

        // Create bills for each user and assign it to their commissions
        $users->each(function (User $user) use ($commissionIds, $releaseAt) {
            /** @var Bill $bill */
            $bill = Bill::query()->forceCreate([
                'user_id' => $user->id,
                'released_at' => $releaseAt,
            ]);

            Commission::query()->whereIn('id', $commissionIds[$user->id])->update([
                'bill_id' => $bill->getKey(),
            ]);

            SendMail::dispatch([
                'Provision' => format_money($bill->getTotalNet()),
                'Link' => 'exporo.com'
            ], $user, config('mail.templateIds.commissionCreated'))->onQueue('emailsLow');
        });

        Bill::enableAuditing();

        flash_success($users->count() . ' Rechnung(en) wurden erstellt');

        return redirect('/bills/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        $bill->load('user');

        return view('bills.show', $this->mapForView($bill->commissions()) + [
                'bill' => $bill,
                'user' => $bill->user,
                'company' => optional($bill->user->company),
            ]);
    }

    public function billPdf(Bill $bill)
    {
        $bill->load('user');

        $investments = $this->mapForView($bill->commissions());

        return response()->view('bills.pdf.bill', $investments + [
                'user' => $bill->user,
                'company' => optional($bill->user->company),
            ]);
    }

    public function downloadBillFromS3(Bill $bill)
    {
        abort_unless($bill->user->id === Auth::user()->id,Response::HTTP_FORBIDDEN);
        abort_unless(Storage::disk('s3')->exists('statements/' . $bill->id . '.pdf'), Response::HTTP_NOT_FOUND);

        $billName = $this->getBillName($bill);
        $file = Storage::disk('s3')->get('statements/' . $bill->id . '.pdf');

        return response($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename=Exporo AG Abrechnung vom' . $billName,
            'filename' => 'Exporo AG Abrechnung vom $bill->created_at' . $billName
        ]);
    }

    private function getBillName(Bill $bill)
    {
        return 'Exporo AG Abrechnung vom' . $bill->created_at->format('d.m.Y') . '.pdf';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        //
    }

    private function getBillableCommissions(): Collection
    {
        return Commission::query()
            ->join('users', 'user_id', 'users.id')
            ->addSelect(['users.first_name', 'users.last_name'])
            ->addSelect(['commissions.user_id'])
            ->selectRaw('SUM(gross) as sum')
            ->groupBy('commissions.user_id')
            ->orderBy('commissions.user_id')
            ->isBillable()
            ->get();
    }

    private function getBillableCommissionsForUser(User $user): Builder
    {
        return Commission::query()->forUser($user)->isBillable();
    }

    /**
     * @param Builder $query
     * @return array
     */
    protected function mapForView($query): array
    {
        $query->select('*');
        $query->selectRaw('commissions.bonus as cBonus');
        $query->selectRaw('commissions.created_at as created_at');

        $collection = $query->get()->groupBy('model_type');

        $investments = $this->mapInvestments($collection->get(Investment::MORPH_NAME));
        $investors = $this->mapInvestors($collection->get(Investor::MORPH_NAME));
        $overhead = $this->mapOverhead($collection->get(Investment::MORPH_NAME));
        $custom = $collection->get(Commission::TYPE_CORRECTION) ?? collect();

        return [
            'investments' => $investments->sortNatural('lastName')->groupBy('projectName')->sortKeys(),
            'investmentSum' => $investments->sum('investsum'),
            'investmentNetSum' => $investments->sum('net'),
            'investmentGrossSum' => $investments->sum('gross'),

            'investors' => $investors,
            'investorsNetSum' => $investors->sum('net'),
            'investorsGrossSum' => $investors->sum('gross'),

            'overheads' => $overhead->sortBy('lastName')->groupBy('projectName')->sortKeys(),
            'overheadSum' => $overhead->sum('investsum'),
            'overheadNetSum' => $overhead->sum('net'),
            'overheadGrossSum' => $overhead->sum('gross'),

            'custom' => $custom,
            'customNetSum' => $custom->sum('net'),
            'customGrossSum' => $custom->sum('gross'),
        ];
    }

    private function mapInvestments(?Collection $investments): BaseCollection
    {
        if ($investments === null) {
            return collect();
        }

        return $investments->filter(function (Commission $commission) {
            return $commission->child_user_id === 0;
        })->load(
            'model.investor:id,first_name,last_name',
            'model.project'
        )->map(function (Commission $row) {
            /** @var Investment $investment */
            $investment = $row->model;
            $investor = $investment->investor;
            $project = $investment->project;

            return [
                'investorId' => $investor->id,
                'firstName' => Person::anonymizeFirstName($investor->first_name),
                'lastName' => trim($investor->last_name),
                'investsum' => $investment->amount,
                'investDate' => $investment->created_at->format('d.m.Y'),
                'net' => $row->net,
                'gross' => $row->gross,
                'bonus' => $row->cBonus * 100,
                'projectName' => $project->description,
                'projectMargin' => $project->margin,
                'projectRuntime' => $project->runtimeInMonths(),
                'projectFactor' => $project->runtimeFactor(),
            ];
        });
    }

    private function mapInvestors(?Collection $investors): ?BaseCollection
    {
        if ($investors === null) {
            return collect();
        }

        return $investors->load('investor:id,first_name,last_name,activation_at')->map(function (Commission $row) {
            $activationDate = Carbon::make($row['activation_at'] ?? $row->investor->activation_at);

            return $row + [
                    'first_name' => Person::anonymizeFirstName($row->investor->first_name),
                    'last_name' => trim($row->investor->last_name),
                    'activation_at' => $activationDate->format('d.m.Y'),
                ];
        })->sortNatural('last_name');
    }

    private function mapOverhead(?Collection $overheads): ?BaseCollection
    {
        if ($overheads === null) {
            return collect();
        }

        return $overheads->filter(function (Commission $commission) {
            return $commission->child_user_id > 0;
        })->load(
            'model.project',
            'childUser'
        )->map(function (Commission $row) {
            /** @var Investment $investment */
            $investment = $row->model;
            $project = $investment->project;
            $partner = $row->childUser;

            return [
                'partnerName' => Person::anonymizeName($partner->first_name, $partner->last_name),
                'lastName' => trim($partner->last_name),
                'investsum' => $investment->amount,
                'investDate' => $investment->created_at->format('d.m.Y'),
                'net' => $row->net,
                'gross' => $row->gross,
                'bonus' => $row->cBonus * 100,
                'projectName' => $project->description,
                'projectMargin' => $project->margin,
                'projectRuntime' => $project->runtimeInMonths(),
                'projectFactor' => $project->runtimeFactor(),
            ];
        });
    }
}
