<?php

use App\Agb;
use App\Bill;
use App\Company;
use App\Investment;
use App\Investor;
use App\Project;
use App\Role;
use App\Schema;
use App\User;
use App\UserDetails;
use Faker\Provider\Address;
use Faker\Provider\Lorem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = factory(Company::class)->create([
            'name' => 'Exporo AG',
        ]);

        // AGBs
        $agbs = factory(Agb::class, 15)->create()->sortByDesc('created_at');

        collect(Agb::TYPES)->each(function (string $type) use ($agbs) {
            $agbs->where('type', $type)->first()->fill(['is_default' => true])->save();
        });

        $agbs = $agbs->random(10);

        // Create some projects
        $projects = factory(Schema::class, 3)->create()->flatMap(function (Schema $schema) {
            return factory(Project::class, 30)->create([
                'schema_id' => $schema->id,
            ]);
        });

        // Create sets of users (per role)
        $userExtras = ['company_id' => $company->id];

        factory(User::class, 2)->create($userExtras)->each(function (User $user) {
            $user->assignRole(Role::ADMIN);
        });

        factory(User::class, 10)->create($userExtras)->each(function (User $user) {
            $user->assignRole(Role::INTERNAL);
        });

        factory(User::class, 50)->create($userExtras)->each(function (User $user) use ($agbs, $projects) {
            $user->assignRole(Role::PARTNER);
            $user->agbs()->attach($agbs->random(2));

            $user->details()->create(factory(UserDetails::class)->raw());

            factory(Bill::class, 15)->create([
                'user_id' => $user->id,
            ]);

            // Investors and their investments
            factory(Investor::class, rand(0, 30))->create([
                'user_id' => $user->id,
            ])->each(function (Investor $investor) use ($projects) {
                $investor->investments()->createMany(factory(Investment::class, rand(0, 30))->raw([
                    'project_id' => $projects->random()->id,
                ]));
            });
        });
    }
}
