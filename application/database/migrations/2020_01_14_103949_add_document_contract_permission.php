<?php

declare(strict_types=1);

use App\Models\Role;

class AddDocumentContractPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission('management.documents.view-contracts')->assignRole([
            Role::ADMIN,
            Role::INTERNAL,
        ]);
    }
}
