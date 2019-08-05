<?php

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;

class RenameAffiliatePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Let's better be safe than sorry
        DB::transaction(function () {
            $this->renameAll('management.banners', 'management.affiliate.banners');
            $this->renameAll('management.banner-sets', 'management.affiliate.banner-sets');
        });
    }

    protected function renameAll(string $prefixFrom, string $prefixTo)
    {
        Permission::all()->filter(function (Permission $permission) use ($prefixFrom) {
            return Str::startsWith($permission->name, $prefixFrom);
        })->each(function (Permission $permission) use ($prefixTo, $prefixFrom) {
            $permission->update(['name' => Str::replaceFirst($prefixFrom, $prefixTo, $permission->name)]);
        });
    }
}
