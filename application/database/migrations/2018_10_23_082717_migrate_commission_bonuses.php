<?php

declare(strict_types=1);

use App\Models\CommissionBonus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Collection;

class MigrateCommissionBonuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', static function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('commission_bonuses', static function (Blueprint $table) {
            $table->string('calculation_type')->default('')->after('user_id');
            $table->decimal('value', 10, 4)->default(0)->after('calculation_type');
            $table->boolean('is_percentage')->default(false)->after('value');
            $table->timestamp('accepted_at')->nullable()->after('is_percentage');
            $table->softDeletes();

            $table->index('accepted_at');
        });

        $this->migrateBonusData();

        Schema::table('commission_bonuses', static function (Blueprint $table) {
            $table->dropColumn([
                'registration',
                'first_investment',
                'further_investment',
            ]);
        });
    }

    private function migrateBonusData()
    {
        $instance = new CommissionBonus();
        $now = now();

        $timestamps = [
            $instance->getCreatedAtColumn() => $now,
            $instance->getUpdatedAtColumn() => $now,
        ];

        CommissionBonus::query()->chunk(1000, static function (Collection $chunk) use ($timestamps) {
            echo "Migrating chunk with {$chunk->count()} items\n";

            $valid = $chunk->filter(static function (CommissionBonus $bonus) {
                return $bonus->created_at === null;
            });

            $models = $valid->map(static function (CommissionBonus $bonus) {
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

            CommissionBonus::query()->insert($models->flatten(1)->map(static function (array $entry) use ($timestamps) {
                return $timestamps + $entry;
            })->toArray());
        });

        // Delete the old models
        CommissionBonus::query()->whereNull('created_at')->delete();
    }
}
