<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

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
