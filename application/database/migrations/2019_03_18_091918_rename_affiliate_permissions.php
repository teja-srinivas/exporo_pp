<?php

declare(strict_types=1);

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RenameAffiliatePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        // Let's better be safe than sorry
        DB::transaction(function () {
            $this->renameAll('management.banners', 'management.affiliate.banners');
            $this->renameAll('management.banner-sets', 'management.affiliate.banner-sets');
        });
    }

    protected function renameAll(string $prefixFrom, string $prefixTo)
    {
        Permission::all()->filter(static function (Permission $permission) use ($prefixFrom) {
            return Str::startsWith($permission->name, $prefixFrom);
        })->each(static function (Permission $permission) use ($prefixTo, $prefixFrom) {
            $permission->update(['name' => Str::replaceFirst($prefixFrom, $prefixTo, $permission->name)]);
        });
    }
}
