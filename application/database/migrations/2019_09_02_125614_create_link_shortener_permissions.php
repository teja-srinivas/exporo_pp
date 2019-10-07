<?php

use App\Models\Permission;

class CreateLinkShortenerPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        Permission::create(['name' => 'features.link-shortener.dashboard']);
        Permission::create(['name' => 'features.link-shortener.view']);
    }
}
