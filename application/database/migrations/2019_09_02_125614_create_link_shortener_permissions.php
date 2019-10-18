<?php

declare(strict_types=1);

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

        $this->createPermission('features.link-shortener.dashboard');
        $this->createPermission('features.link-shortener.view');
    }
}
