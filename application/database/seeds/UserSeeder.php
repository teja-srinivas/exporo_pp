<?php

declare(strict_types=1);

use App\Models\Agb;
use App\Models\User;
use App\Models\Role;
use App\Models\Bill;
use App\Models\Company;
use App\Models\Project;
use App\Models\Investor;
use App\Models\Investment;
use App\Models\UserDetails;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class UserSeeder extends Seeder
{
    /**
     * Creates a reasonable set of dummy users.
     *
     * @return void
     */
    public function run()
    {
        $userExtras = ['company_id' => Company::query()->toBase()->value('id')];

        $this->createAdmins($userExtras);

        $this->createInternals($userExtras);

        $this->createPartners($userExtras);
    }

    /**
     * @param  array  $userExtras
     */
    protected function createAdmins(array $userExtras): void
    {
        $userExtras['email'] = 'pp@exporo.de';

        factory(User::class)->state('accepted')->create($userExtras)->each(static function (User $user) {
            $user->assignRole(Role::ADMIN);
        });
    }

    /**
     * @param  array  $userExtras
     */
    protected function createInternals(array $userExtras): void
    {
        factory(User::class, 3)->state('accepted')->create($userExtras)->each(static function (User $user) {
            $user->assignRole(Role::INTERNAL);
        });
    }

    /**
     * @param  array  $userExtras
     */
    protected function createPartners(array $userExtras): void
    {
        /** @var Collection $agbs */
        $agbs = Agb::query()->isDefault()->get();

        /** @var Collection $projectIds */
        $projectIds = Project::query()->pluck('id');

        factory(User::class, 30)->create($userExtras)->each(static function (User $user) use ($agbs, $projectIds) {
            $user->assignRole(Role::PARTNER);
            $user->agbs()->attach($agbs);

            $user->details->update(factory(UserDetails::class)->raw());

            factory(Bill::class, rand(0, 8))->create([
                'user_id' => $user->id,
            ]);

            factory(Bill::class, rand(0, 5))->state('released')->create([
                'user_id' => $user->id,
            ]);

            // Investors and their investments
            factory(Investor::class, rand(0, 30))->create([
                'user_id' => $user->id,
            ])->each(static function (Investor $investor) use ($projectIds) {
                $investor->investments()->createMany(factory(Investment::class, rand(0, 30))->raw([
                    'project_id' => $projectIds->random(),
                ]));
            });
        });
    }
}
