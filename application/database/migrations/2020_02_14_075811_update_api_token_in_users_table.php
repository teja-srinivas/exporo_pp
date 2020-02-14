<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class UpdateApiTokenInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::query()
            ->get()
            ->each(static function (User $user) {
                $user->api_token = Str::random(64);
                $user->save();
            });
    }
}
