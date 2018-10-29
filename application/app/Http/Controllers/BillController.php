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
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as BaseCollection;

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
                        'net' => format_money($bill->net),
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

        return response()->view('bills.bill', $investments + [
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
        $users = Commission::query()
            ->isBillable()
            ->distinct()
            ->pluck('commissions.user_id');

        // Pre-select all valid commission IDs
        // Doing the isBillable check for each updates eats up DB time
        $commissionIds = Commission::query()->select('user_id', 'id')->get()->mapToGroups(function ($row) {
            return [
                $row['user_id'] => $row['id'],
            ];
        });

        Bill::disableAuditing();
        // Create bills for each user and assign it to their commissions
        $users->each(function (int $userId) use ($commissionIds, $releaseAt) {
            $user = User::find($userId);
            /** @var Bill $bill */
            $bill = Bill::query()->forceCreate([
                'user_id' => $userId,
                'released_at' => $releaseAt,
            ]);

            Commission::query()->whereIn('id', $commissionIds[$userId])->update([
                'bill_id' => $bill->getKey(),
            ]);

            SendMail::dispatch([
                'Anrede' => $user->salutation,
                'Nachname' => $user->last_name,
                'Provision' => format_money($bill->getTotalNet()),
                'Link' => 'exporo.com'
            ], $user, config('mail.templateIds.commissionCreated'))->onQueue('emails');
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
        ]);
    }

    public function billPdf(int $bill)
    {
       $bill = Bill::findOrFail($bill);
        $bill->load('user');

        $investments = $this->mapForView($bill->commissions());

        return response()->view('bills.bill', $investments + [
                'user' => $bill->user,
                'company' => optional($bill->user->company),
            ]);
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
            ->selectRaw('SUM(net) as sum')
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
        $collection = $query->get()->groupBy('model_type');

        $investments = $this->mapInvestments($collection->get(Investment::MORPH_NAME));
        $investors = $this->mapInvestors($collection->get(Investor::MORPH_NAME));

        $investmentsNetSum = $investments->sum('net');
        $investorsNetSum = $investors->sum('net');

        return [
            'investments' => $investments
                ->sortBy('created_at')
                ->sortBy('projectName')
                ->groupBy('projectName'),
            'investmentSum' => $investments->sum('investsum'),
            'investmentNetSum' => $investmentsNetSum,
            'investors' => $investors->sortBy('last_name'),
            'investorsNetSum' => $investorsNetSum,
            'totalCommission' => $investorsNetSum + $investmentsNetSum,
        ];
    }

    private function mapInvestments(?Collection $investments): BaseCollection
    {
        if ($investments === null) {
            return collect();
        }

        return $investments->load(
            'model.investor:id,first_name,last_name',
            'model.project'
        )->map(function (Commission $row) {
            /** @var Investment $investment */
            $investment = $row->model;
            $investor = $investment->investor;
            $project = $investment->project;

            return [
                'investorId' => $investor->id,
                'firstName' => $investor->first_name,
                'lastName' => $investor->last_name,
                'investsum' => $investment->amount,
                'investDate' => $investment->created_at->format('d.m.Y'),
                'net' => $row->net,
                'gross' => $row->gross,
                'projectName' => $project->description,
                'projectMargin' => $project->margin,
                'projectRuntime' => $project->runtimeInMonths(),
            ];
        });
    }

    private function mapInvestors(?Collection $investors): ?BaseCollection
    {
        if ($investors === null) {
            return collect();
        }

        return $investors->map(function (Commission $row) {
            $row['first_name'] = $row->investor->first_name;
            $row['last_name'] = $row->investor->last_name;

            return $row;
        });
    }
}
