<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use App\Traits\Person;
use App\Models\Investor;
use App\Models\Commission;
use App\Models\Investment;
use App\Traits\Encryptable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\BillGenerator;
use Illuminate\Routing\Redirector;
use App\Repositories\BillRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\User as UserResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection as BaseCollection;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request, BillRepository $bills)
    {
        $this->authorize('viewAny', Bill::class);

        return response()->view('bills.index', [
            'bills' => $bills->getDetails()->load('user')->map(function (Bill $bill) use ($request) {
                return [
                    'id' => $bill->getKey(),
                    'name' => $bill->getBillingMonth()->format('Y-m'),
                    'displayName' => $bill->getDisplayName(),
                    'date' => $bill->created_at->format('Y-m-d'),
                    'user' => UserResource::make($bill->user)->toArray($request),
                    'gross' => $bill->gross,
                    'commissions' => $bill->commissions,
                    'links' => [
                        'self' => route('bills.show', $bill),
                    ],
                ];
            }),
        ]);
    }

    /**
     * @param User $user
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function preview(User $user)
    {
        $bill = new Bill([
            'user_id' => $user->id,
            'released_at' => now(),
        ]);

        $this->authorize('view', $bill);

        $investments = $this->mapForView($this->getBillableCommissionsForUser($user));

        return response()->view('bills.pdf.bill', $investments + [
            'bill' => $bill,
            'user' => $user,
            'company' => optional($user->company),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Bill::class);

        $bills = $this->getBillableCommissions()->map(function (Commission $row) {
            return [
                'userId' => $row->user_id,
                'billable' => $row->user->canBeBilled(),
                'firstName' => Encryptable::decrypt($row->first_name),
                'lastName' => Encryptable::decrypt($row->last_name),
                'sum' => $row->sum,
                'firstTime' => $row->user->bills()->count() === 0,
            ];
        })->sortNatural('lastName');

        return response()->view('bills.create', [
            'bills' => $bills,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Redirector $redirect
     * @param BillGenerator $bills
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Redirector $redirect, BillGenerator $bills)
    {
        $this->authorize('create', Bill::class);

        $data = $this->validate($request, [
            'release_at' => ['required', 'date'],
        ]);

        $count = $bills->generate(Carbon::parse($data['release_at']))->count();

        flash_success("$count Rechnung(en) wurden erstellt");

        return $redirect->to('/bills/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill $bill
     * @param  FilesystemManager $storage
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Bill $bill, FilesystemManager $storage)
    {
        $this->authorize('download', $bill);

        return $this->downloadBillFromS3($bill, $storage->cloud());
    }

    public function billPdf(Bill $bill)
    {
        $bill->load('user');

        $investments = $this->mapForView($bill->commissions());

        return response()->view('bills.pdf.bill', $investments + [
            'bill' => $bill,
            'user' => $bill->user,
            'company' => optional($bill->user->company),
        ]);
    }

    protected function downloadBillFromS3(Bill $bill, Filesystem $disk)
    {
        $filePath = 'statements/'.$bill->id;

        abort_unless($disk->exists($filePath), Response::HTTP_NOT_FOUND);

        $stream = $disk->readStream($filePath);

        return response()->streamDownload(function () use ($stream) {
            fpassthru($stream);
        }, $bill->getFileName(), [
            'Content-Type' => $disk->mimeType($filePath),
            'Content-Length' => $disk->size($filePath),
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
            ->selectRaw('SUM(gross) as sum')
            ->groupBy('commissions.user_id')
            ->orderBy('commissions.user_id')
            ->with('user')
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

        $collection = $query->get()->groupBy(function (Commission $commission) {
            if ($commission->model_type === Investment::LEGACY_MORPH_NAME) {
                return Investment::MORPH_NAME;
            }

            return $commission->model_type;
        });

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
                'id' => $investment->id,
                'investorId' => $investor->id,
                'firstName' => Person::anonymizeFirstName($investor->first_name),
                'lastName' => trim($investor->last_name),
                'investsum' => $investment->amount,
                'investDate' => $investment->created_at->format('d.m.Y'),
                'net' => $row->net,
                'gross' => $row->gross,
                'bonus' => $row->cBonus,
                'projectName' => $project->description,
                'projectMargin' => $project->margin,
                'projectRuntime' => $project->runtimeInMonths(),
                'projectFactor' => $project->runtimeFactor(),
                'note' => $row->note_public,
            ];
        })->toBase();
    }

    private function mapInvestors(?Collection $investors): ?BaseCollection
    {
        if ($investors === null) {
            return collect();
        }

        return $investors->load('investor:id,first_name,last_name,activation_at')->map(function (Commission $row) {
            $activationDate = Carbon::make($row['activation_at'] ?? $row->investor->activation_at);

            return [
                'id' => $row->investor->id,
                'firstName' => Person::anonymizeFirstName($row->investor->first_name),
                'lastName' => ucfirst(trim($row->investor->last_name)),
                'activationAt' => $activationDate->format('d.m.Y'),
                'note' => $row->note_public,
                'net' => $row->net,
                'gross' => $row->gross,
            ];
        })->sortNatural('lastName');
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
                'bonus' => $row->cBonus,
                'projectName' => $project->description,
                'projectMargin' => $project->margin,
                'projectRuntime' => $project->runtimeInMonths(),
                'projectFactor' => $project->runtimeFactor(),
                'note' => $row->note_public,
            ];
        });
    }
}
