<?php

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class SplitUpPermissionsIntoResources extends Migration
{
    /**
     * Updates our permissions to be "CRUDdy":
     * - manage is being split into "view", "create", "update" and "delete"
     * - other permissions are being changed to fit into their proper category.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Let's better be safe than sorry
        DB::transaction(function () {
            $this->convertManaged('management', [
                'links' => 'affiliate.links',
                'mailings' => 'affiliate.mailings',
            ]);

            $this->rename('can be billed', 'features.bills.receive');
            $this->rename('download bills', 'features.bills.download');
            $this->rename('process partners', 'features.users.process');
            $this->rename('view audits', 'features.audits.view');
            $this->rename('view partner dashboard', 'features.users.dashboard');
        });

        Permission::create(['name' => 'features.bills.export'])->assignRole(
            Role::findByName(Role::ADMIN)
        );

        Permission::create(['name' => 'viewNova'])->assignRole(
            Role::findByName(Role::ADMIN)
        );
    }

    protected function convertManaged(string $prefix, array $replacements = [])
    {
        // Find all the ones that start with "managed"
        $managed = Permission::all()->filter(function (Permission $permission) {
            return Str::startsWith($permission->name, 'manage');
        });

        $managed->flatMap(function (Permission $permission) use ($prefix, $replacements) {
            // "Explode" in the proper CRUD verbs
            $name = Str::slug(substr($permission->name, strlen('manage')), '-', null);

            // Add support for replacing names
            $name = "$prefix.".($replacements[$name] ?? $name);

            return [
                "$name.view" => $permission,
                "$name.create" => $permission,
                "$name.update" => $permission,
                "$name.delete" => $permission,
            ];
        })->map(function (Permission $original, string $name) {
            // Create new permissions and copy the properties from the original
            /** @var Permission $permission */
            $permission = Permission::create(['name' => $name]);
            $permission->users()->sync($original->users()->pluck('id'));
            $permission->roles()->sync($original->roles()->pluck('id'));
        });

        // Bulk-delete the managed ones
        Permission::query()->whereKey($managed->modelKeys())->delete();
    }

    protected function rename(string $from, string $to)
    {
        try {
            /** @var Permission $permission */
            $permission = Permission::findByName($from);
            $permission->update(['name' => $to]);
        } catch (PermissionDoesNotExist $ignore) {
            // Most likely because of a unit test
        }
    }
}
