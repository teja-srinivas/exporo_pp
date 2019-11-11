<?php

declare(strict_types=1);

class AddAcceptContractsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createPermission('features.contracts.accept');
    }
}
