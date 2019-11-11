<?php

declare(strict_types=1);

use App\Models\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsExclusiveAndAllowOverheadFieldsToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', static function (Blueprint $table) {
            $table->boolean('is_exclusive')->default(false)->after('vat_amount');
            $table->boolean('allow_overhead')->default(false)->after('is_exclusive');
        });

        Schema::table('contract_templates', static function (Blueprint $table) {
            $table->boolean('is_exclusive')->default(false)->after('vat_amount');
            $table->boolean('allow_overhead')->default(false)->after('is_exclusive');
        });

        $this->clearPermissionCache();

        Role::findByName(Role::ADMIN)->givePermissionTo(
            $this->createPermission('management.contracts.update-is-exclusive'),
            $this->createPermission('management.contracts.update-allow-overhead')
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', static function (Blueprint $table) {
            $table->dropColumn(['is_exclusive', 'allow_overhead']);
        });

        Schema::table('contract_templates', static function (Blueprint $table) {
            $table->dropColumn(['is_exclusive', 'allow_overhead']);
        });
    }
}
