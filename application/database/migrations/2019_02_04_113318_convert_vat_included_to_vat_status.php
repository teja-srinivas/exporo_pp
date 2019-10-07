<?php

use App\Models\UserDetails;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ConvertVatIncludedToVatStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UserDetails::query()
            ->whereNull('vat_included')
            ->orWhere('vat_included', '')
            ->update([
                'vat_included' => false,
                'vat_amount' => 0,
            ]);

        $this->fixEnumSupport();

        Schema::table('user_details', function (Blueprint $table) {
            $table->decimal('vat_amount', 8, 2)->default(0)->change();
            $table->boolean('vat_included')->nullable(false)->default(false)->change();
        });
    }
}
