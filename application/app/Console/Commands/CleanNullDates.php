<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\Investor;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CleanNullDates extends Command
{
    protected $signature = 'maintenance:clean-null-dates';
    protected $description = 'Replaces all "legacy dates" with NULL where possible';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $default = config('database.connections.mysql.strict', true);
        config()->set('database.connections.mysql.strict', false);
        DB::reconnect();

        DB::transaction(function () {
            $this->clean(Investment::class, 'acknowledged_at', 'datetime');
            $this->clean(Investment::class, 'cancelled_at', 'datetime');
            $this->clean(Investment::class, 'paid_at', 'datetime');
            $this->clean(Investor::class, 'claim_end', 'date');
            $this->clean(Investor::class, 'created_at', 'timestamp');
            $this->clean(Investor::class, 'updated_at', 'timestamp');
        });

        config()->set('database.connections.mysql.strict', $default);
        DB::reconnect();
    }

    protected function clean(string $class, string $field, string $type)
    {
        /** @var Model $model */
        $model = new $class;

        $this->line("Cleaning '$field' in '{$model->getTable()}' table");

        $query = $model->newQuery();

        switch ($type) {
            case 'date':
                $query->where($field, '0000-00-00');
                break;
            case 'datetime':
                $query->where($field, LEGACY_NULL);
                break;
            case 'timestamp':
                $query->where($field, LEGACY_NULL);
                $query->orWhere($field, '0000-00-00 00:00:00');
                break;
            default:
                throw new Exception("Unsupported type: '$type'");
        }

        $query->update([$field => null]);
    }
}
