<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Models\Contract;
use App\Traits\Encryptable;
use App\Builders\UserBuilder;
use App\Models\PartnerContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserDetails as UserDetailsResource;

class UserRepository
{
    public function forTableView(?Builder $query = null)
    {
        if ($query === null) {
            $query = User::query();
        }

        $showDetails = (bool) request()->get('user_table_details', false);

        return $query
            ->leftJoin('user_details', 'user_details.id', 'users.id')
            ->leftJoinSub(Contract::query()
                ->selectRaw('ANY_VALUE(id) as id')
                ->addSelect('user_id')
                ->selectRaw('ANY_VALUE(allow_overhead) as allow_overhead')
                ->where('type', PartnerContract::STI_TYPE)
                ->whereNotNull('accepted_at')
                ->whereNull('terminated_at')
                ->groupBy('user_id'), 'contracts', 'contracts.user_id', 'users.id')
            ->addSelect('users.id')
            ->addSelect('display_name')
            ->addSelect('users.created_at')
            ->addSelect('users.accepted_at')
            ->addSelect('users.rejected_at')
            ->selectRaw('IFNULL(contracts.allow_overhead, 0) as has_overhead')
            ->selectSub(Role::query()
                ->selectRaw('group_concat(id)')
                ->join('model_has_roles', 'roles.id', 'model_has_roles.role_id')
                ->where('model_id', DB::raw('users.id'))
                ->where('model_type', User::class)->toBase(), 'roles')
            ->when($showDetails, static function (UserBuilder $query) {
                $query->addSelect('company');
                $query->addSelect('users.first_name');
                $query->addSelect('users.last_name');
            })
            ->get()
            ->map(static function (User $user) use ($showDetails) {
                $data = $showDetails
                    ? [
                        'user' => UserResource::make($user),
                        'company' => Encryptable::decrypt($user['company']),
                    ]
                    : [
                        'user' => UserDetailsResource::make($user),
                    ];

                return $data + [
                    'status' => $user->rejected()
                        ? '<div class="badge badge-danger">Abgelehnt</div>'
                        : (!$user->accepted() ? '<div class="badge badge-warning">Ausstehend</div>' : null)
                        .($user->has_overhead ? ' <div class="badge badge-primary">Overhead</div>' : ''),
                    'roles' => array_map('intval', explode(',', $user->roles ?? '')),
                    'createdAt' => $user->created_at->format('Y-m-d'),
                ];
            });
    }
}
