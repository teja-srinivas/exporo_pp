<?php

use App\Models\CommissionBonus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateCommissionBonuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('commission_bonuses', function (Blueprint $table) {
            $table->string('calculation_type')->after('user_id');
            $table->decimal('value', 10, 4)->after('calculation_type');
            $table->boolean('is_percentage')->after('value');
            $table->timestamp('accepted_at')->nullable()->after('is_percentage');
            $table->softDeletes();

            $table->index('accepted_at');
        });

        $this->migrateBonusData();

        Schema::table('commission_bonuses', function (Blueprint $table) {
            $table->dropColumn([
                'registration',
                'first_investment',
                'further_investment',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Irreversable
    }

    private function migrateBonusData()
    {
        $instance = new CommissionBonus();
        $now = now();

        $timestamps = [
            $instance->getCreatedAtColumn() => $now,
            $instance->getUpdatedAtColumn() => $now,
        ];

        CommissionBonus::query()->chunk(1000, function (Collection $chunk) use ($timestamps) {
            echo "Migrating chunk with {$chunk->count()} items\n";

            $valid = $chunk->filter(function (CommissionBonus $bonus) {
                return $bonus->created_at === null;
            });

            $models = $valid->map(function (CommissionBonus $bonus) {
                $models = [];
                $data = [
                    'user_id' => $bonus->user_id,
                    'type_id' => $bonus->type_id,
                ];

                if ($bonus->registration > 0) {
                    $models[] = $data + [
                        'calculation_type' => 'registration',
                        'is_percentage' => false,
                        'value' => $bonus->registration,
                    ];
                }

                if ($bonus->first_investment > 0) {
                    $models[] = $data + [
                        'calculation_type' => 'first_investment',
                        'is_percentage' => true,
                        'value' => $bonus->first_investment,
                    ];
                }

                if ($bonus->further_investment > 0) {
                    $models[] = $data + [
                        'calculation_type' => 'further_investment',
                        'is_percentage' => true,
                        'value' => $bonus->further_investment,
                    ];
                }

                return $models;
            });

            CommissionBonus::query()->insert($models->flatten(1)->map(function (array $entry) use ($timestamps) {
                return $timestamps + $entry;
            })->toArray());
        });

        // Delete the old models
        CommissionBonus::query()->whereNull('created_at')->delete();
    }
}
