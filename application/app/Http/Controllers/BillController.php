<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Commission;
use App\Http\Resources\User as UserResource;
use Carbon\Carbon;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
            }),
        ]);
    }

    public function preview(int $id)
    {
        $investments = $this->getBillableInvestmentCommissionsForUser($id)->map(function (Commission $row){
            return [
                'investorId' => $row->investment->investor->id,
                'firstName' => $row->investment->investor->first_name,
                'lastName' => $row->investment->investor->last_name,
                'investsum' => $row->investment->amount,
                'investDate' => $row->investment->created_at->format('d-m-Y'),
                'net' => $row->net,
                'gross' => $row->gross,
                'projectName' => $row->investment->project->name,
                'projectMargin' => $row->investment->project->margin,
                'projectRuntime' => $row->investment->project->runtime ?? $row->investment->project->runtimeInMonths(),
            ];
        })->groupBy('projectName');



        $investmentSum = $investments->sum(function($investments) {
            return $investments->sum('investsum');
        });

        $investmentNetSum = $investments->sum(function($investments) {
            return $investments->sum('net');
        });


        $user = User::find($id);
        $company = $user->company;

        return response()->view('bills.bill', [
            'investments' => $investments,
            'investmentSum' => $investmentSum,
            'investmentNetSum' => $investmentNetSum,
            'user' => $user,
            'company' => $company,
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
                'firstName' => decrypt($row->first_name),
                'lastName' => decrypt($row->last_name),
                'sum' => $row->sum,
            ];
        });

        return response()->view('bills.create', [
            'bills' => $bills,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
            ->pluck('user_id');

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
            $bill = Bill::query()->forceCreate([
                'user_id' => $userId,
                'released_at' => $releaseAt,
            ]);

            Commission::query()->whereIn('id', $commissionIds[$userId])->update([
                'bill_id' => $bill->getKey(),
            ]);
        });

        Bill::enableAuditing();

        flash_success($users->count() . ' Rechnung(en) wurden erstellt');

        return redirect('/bills/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        //
    }

    private function getBillableCommissions(): Collection
    {
        return Commission::query()
            ->isBillable()
            ->join('users', 'user_id', 'users.id')
            ->addSelect(['users.first_name', 'users.last_name'])
            ->addSelect(['user_id'])
            ->selectRaw('SUM(net) as sum')
            ->groupBy('user_id')
            ->orderBy('user_id')
            ->get();
    }

    private function getBillableInvestmentCommissionsForUser(int $id): Collection
    {
        return Commission::query()
            ->isBillable()
            ->where('user_id', $id)
            ->where('model_type', 'investment')
            ->get();
    }
}
