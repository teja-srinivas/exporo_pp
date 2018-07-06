<?php

use App\Agb;
use App\Company;
use App\Investment;
use App\Investor;
use App\Project;
use App\Role;
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
        $company = factory(Company::class)->create();

        // AGBs
        $agbs = factory(Agb::class, 15)->create()->sortByDesc('created_at');

        collect(Agb::TYPES)->each(function (string $type) use ($agbs) {
            $agbs->where('type', $type)->first()->fill(['is_default' => true])->save();
        });

        $agbs = $agbs->random(10);

        // Investors
        $investors = factory(Investor::class, 200)->create([
            'project_id' => 0,
            'client_id' => 0,
            'partner_id' => 0,
            'ext_user_id' => 0,
        ]);

        // Create sets of users (per role)
        $userExtras = ['company_id' => $company->id];

        factory(User::class, 2)->create($userExtras)->each(function (User $user) {
            $user->assignRole(Role::ADMIN);
        });

        factory(User::class, 10)->create($userExtras)->each(function (User $user) {
            $user->assignRole(Role::INTERNAL);
        });

        factory(User::class, 50)->create($userExtras)->each(function (User $user) use ($agbs, $investors) {
            $user->assignRole(Role::PARTNER);
            $user->agbs()->attach($agbs->random(2));

            $user->details()->create(factory(UserDetails::class)->raw());

            $investors->random(25)->each(function (Investor $investor) use ($user) {
                $created = \Faker\Provider\DateTime::date();

                DB::table('investor_user')->insert([
                    'user_id' => $user->id,
                    'investor_id' => $investor->id,
                    'editor_id' => rand(1, 1000),
                    'note' => Lorem::sentence(),
                    'created_at' => $created,
                    'updated_at' => $created,
                ]);
            });
        });

        $investorById = $investors->keyBy('id');

        DB::table('investor_user')->oldest()->get()->each(function ($row) use ($investorById) {
            $investorById[$row->investor_id]->last_user_id = $row->user_id;
        });

        $investors->each->save();

        factory(Project::class, 10)->create();
        factory(Investment::class, 100)->create();
    }
}
