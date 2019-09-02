<?php

use App\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;

class CreateLinkShortenerPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::create(['name' => 'features.link-shortener.dashboard']);
        Permission::create(['name' => 'features.link-shortener.view']);
    }
}
