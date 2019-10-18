<?php

declare(strict_types=1);

use App\Models\Role;

class AddInitialRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::create(['name' => Role::PARTNER]);
        Role::create(['name' => Role::INTERNAL]);
        Role::create(['name' => Role::ADMIN]);

        $this->createPermission('manage agbs');
        $this->createPermission('manage documents');
        $this->createPermission('manage users');
        $this->createPermission('manage authorization');
        $this->createPermission('process partners');
        $this->createPermission('view audits')->assignRole(Role::ADMIN);
        $this->createPermission('view partner dashboard')->assignRole(Role::PARTNER);
    }
}
