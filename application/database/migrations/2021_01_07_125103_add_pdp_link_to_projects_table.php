<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPdpLinkToProjectsTable extends Migration
{
    private const TABLE = 'projects';
    private const PDP_LINK = 'pdp_link';

    public function up(): void
    {
        Schema::table(self::TABLE, static function (Blueprint $table) {
            $table->text(self::PDP_LINK)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table(self::TABLE, static function (Blueprint $table) {
            $table->dropColumn(self::PDP_LINK);
        });
    }
}
