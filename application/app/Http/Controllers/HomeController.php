<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Commission;
use App\Models\User;
use App\Repositories\BillRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @param BillRepository $billRepository
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, BillRepository $billRepository)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Collection $bills */
        $bills = $billRepository->getDetails($user->id, function (Builder $query) {
            $query->released()->visible()->latest('bills.created_at');
        });

        return response()->view('home', [
            'bills' => $bills->map(function (Bill $bill) {
                $date = optional($bill->released_at)->format('Y-m-d');

                return [
                    'displayName' => $bill->getDisplayName(),
                    'name' => $date,
                    'date' => $date,
                    'links' => [
                        'download' => route('bills.show', $bill),
                    ],
                ];
            }),
        ]);
    }
}
