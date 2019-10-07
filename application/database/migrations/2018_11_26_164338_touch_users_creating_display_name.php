<?php

use App\Models\User;

class TouchUsersCreatingDisplayName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->touch();
        }
    }
}
