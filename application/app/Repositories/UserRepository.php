<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Models\Contract;
use App\Traits\Encryptable;
use App\Models\CommissionBonus;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\User as UserResource;

class UserRepository
{
    public function forTableView(Builder $query = null)
    {
        if ($query === null) {
            $query = User::query();
        }

        return $query
            ->leftJoin('user_details', 'user_details.id', 'users.id')
            ->addSelect('company')
            ->addSelect('users.id')
            ->addSelect('users.first_name')
            ->addSelect('users.last_name')
            ->addSelect('users.created_at')
            ->addSelect('users.accepted_at')
            ->addSelect('users.rejected_at')
            ->selectRaw('CASE WHEN EXISTS('.CommissionBonus::query()
                ->joinSub(Contract::query()
                    ->selectRaw('ANY_VALUE(id) as id')
                    ->addSelect('user_id')
                    ->selectRaw('MAX(accepted_at)')
                    ->groupBy('user_id'), 'contracts', 'contracts.id', 'contract_id')
                ->whereRaw('is_overhead = true')
                ->whereRaw('user_id = users.id')
                ->toSql().') THEN TRUE ELSE FALSE END as has_overhead')
            ->with('roles', 'contract')
            ->get()
            ->map(function (User $user) {
                return [
                    'user' => UserResource::make($user),
                    'company' => Encryptable::decrypt($user['company']),
                    'status' => $user->rejected()
                        ? '<div class="badge badge-danger">Abgelehnt</div>'
                        : ($user->notYetAccepted() ? '<div class="badge badge-warning">Ausstehend</div>' : null)
                        .($user->has_overhead ? '<div class="badge badge-primary">Overhead</div>' : ''),
                    'roles' => $user->roles->pluck('id'),
                    'createdAt' => $user->created_at->format('Y-m-d'),
                ];
            });
    }
}
