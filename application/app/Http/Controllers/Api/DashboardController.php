<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Repositories\BillRepository;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    /**
     * @param  Request  $request
     * @return Array
     * @throws ValidationException
     */
    public function getInvestments(Request $request)
    {
        $user = $request->user();

        $data = $this->validate($request, [
            'period' => ['nullable', 'in:this_month,last_month,default'],
        ]);

        switch ($data['period']) {
            case 'this':
                $period = Carbon::now()->startOfMonth();
                break;
            case 'last':
                $period = Carbon::now()->startOfMonth()->subMonth();
                break;
            case 'default':
                $period = Carbon::now()->subDays(30);
                break;
        }

        $investments = $user->investments()
                ->get();

        return $investments;
    }
}
