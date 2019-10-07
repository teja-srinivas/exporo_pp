<?php

declare(strict_types=1);

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration as BaseMigration;

abstract class Migration extends BaseMigration
{
    protected function clearPermissionCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function createPermission(string $name): Permission
    {
        return Permission::create(['name' => $name]);
    }

    protected function createResourcePermission(string $resource, array $roles = []): void
    {
        foreach (['create', 'delete', 'update', 'view'] as $action) {
            $this->createPermission("$resource.$action")->assignRole($roles);
        }
    }

    /**
     * Fix for renaming/updating a column inside a table that has enums.
     *
     * @see https://github.com/laravel/framework/issues/1186
     */
    protected function fixEnumSupport(): void
    {
        $doctrine = DB::connection($this->getConnection())->getDoctrineConnection();
        $dbPlatform = $doctrine->getSchemaManager()->getDatabasePlatform();
        $dbPlatform->registerDoctrineTypeMapping('enum', 'string');
    }
}
