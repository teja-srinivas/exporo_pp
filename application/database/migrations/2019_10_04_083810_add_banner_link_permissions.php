<?php

use App\Models\Permission;

class AddBannerLinkPermissions extends Migration
{
    /**
     * Splits up the "view" permission into "links" and "banners".
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission('features.link-shortener.banners');

        /** @var Permission $permission */
        $permission = Permission::findByName('features.link-shortener.view');
        $permission->update(['name' => 'features.link-shortener.links']);
    }
}
