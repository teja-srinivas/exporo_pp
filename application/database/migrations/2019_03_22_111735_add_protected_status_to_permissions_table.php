<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddProtectedStatusToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', static function (Blueprint $table) {
            $table->string('protected')->nullable()->after('guard_name');
        });

        $this->protectFromRole(Role::PARTNER, [
            'features.audits.view',
            'features.bills.download',
            'features.bills.export',
            'features.users.process',
            'management.affiliate.banner-sets.create',
            'management.affiliate.banner-sets.delete',
            'management.affiliate.banner-sets.update',
            'management.affiliate.banner-sets.view',
            'management.affiliate.banners.create',
            'management.affiliate.banners.delete',
            'management.affiliate.banners.update',
            'management.affiliate.links.create',
            'management.affiliate.links.delete',
            'management.affiliate.links.update',
            'management.affiliate.mailings.create',
            'management.affiliate.mailings.delete',
            'management.affiliate.mailings.update',
            'management.agbs.create',
            'management.agbs.delete',
            'management.agbs.update',
            'management.agbs.view',
            'management.authorization.create',
            'management.authorization.delete',
            'management.authorization.update',
            'management.authorization.view',
            'management.bills.create',
            'management.bills.delete',
            'management.bills.update',
            'management.bills.view',
            'management.commission-bonus-bundles.create',
            'management.commission-bonus-bundles.delete',
            'management.commission-bonus-bundles.update',
            'management.commission-bonus-bundles.view',
            'management.commission-bonuses.create',
            'management.commission-bonuses.delete',
            'management.commission-bonuses.update',
            'management.commission-bonuses.view',
            'management.commission-types.create',
            'management.commission-types.delete',
            'management.commission-types.update',
            'management.commission-types.view',
            'management.documents.create',
            'management.documents.delete',
            'management.documents.update',
            'management.documents.view',
            'management.investments.create',
            'management.investments.delete',
            'management.investments.update',
            'management.investments.view',
            'management.investors.create',
            'management.investors.delete',
            'management.investors.update',
            'management.investors.view',
            'management.projects.create',
            'management.projects.delete',
            'management.projects.update',
            'management.projects.view',
            'management.schemas.create',
            'management.schemas.delete',
            'management.schemas.update',
            'management.schemas.view',
            'management.users.create',
            'management.users.delete',
            'management.users.update',
            'management.users.view',
            'viewNova',
        ]);
    }

    private function protectFromRole(string $role, array $roles)
    {
        Permission::query()->whereIn('name', $roles)->update([
            'protected' => json_encode([$role]),
        ]);
    }
}
