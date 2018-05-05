<?php

use App\Agb;
use App\Role;
use App\User;
use App\UserDetails;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // AGBs
        $agbs = factory(Agb::class, 15)->create();
        $agbs->random()->fill(['is_default' => true])->save();

        $agbs = $agbs->random(10);

        // Create sets of users (per role)
        factory(User::class, 2)->create()->each(function (User $user) {
            $user->assignRole(Role::ADMIN);
        });

        factory(User::class, 10)->create()->each(function (User $user) {
            $user->assignRole(Role::INTERNAL);
        });

        factory(User::class, 50)->create()->each(function (User $user) use ($agbs) {
            $user->assignRole(Role::PARTNER);
            $user->agbs()->attach($agbs->random(2));

            factory(UserDetails::class)->create(['id' => $user->id]);
        });
    }
}
