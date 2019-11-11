<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Eloquent\Collection;
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

    protected function createResourcePermission(string $resource, array $roles = []): Collection
    {
        $permissions = (new Permission())->newCollection();

        foreach (['create', 'delete', 'update', 'view'] as $action) {
            $permission = $this->createPermission("$resource.$action");
            $permission->assignRole($roles);

            if ($action !== 'view') {
                $permission->protected = [Role::PARTNER];
            }

            $permissions->push($permission);
        }

        return $permissions;
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
