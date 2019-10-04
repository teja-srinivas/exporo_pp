<?php

use App\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;

class AddBannerLinkPermissions extends Migration
{
    /**
     * Splits up the "view" permission into "links" and "banners".
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::create(['name' => 'features.link-shortener.banners']);

        /** @var Permission $permission */
        $permission = Permission::findByName('features.link-shortener.view');
        $permission->update(['name' => 'features.link-shortener.links']);
    }
}
