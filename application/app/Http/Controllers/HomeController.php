<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Repositories\BillRepository;

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
        $bills = $billRepository->getDetails($user->id)
            ->released()
            ->visible()
            ->latest('bills.created_at')
            ->get();

        return response()->view('home', [
            'bills' => $bills->map(static function (Bill $bill) {
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
