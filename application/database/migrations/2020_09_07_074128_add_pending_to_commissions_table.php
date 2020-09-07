<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPendingToCommissionsTable extends Migration {

    private const TABLE = 'commissions';
    private const PENDING = 'pending';

    public function up(): void {
        Schema::table(self::TABLE, static function (Blueprint $table) {
            $table->boolean(self::PENDING)->after('on_hold')->default(false);
        });
    }

    public function down(): void {
        Schema::table(self::TABLE, static function (Blueprint $table) {
            $table->dropColumn(self::PENDING);
        });
    }
}
