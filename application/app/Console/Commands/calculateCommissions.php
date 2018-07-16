<?php
declare(strict_types=1);


namespace App\Console\Commands;

ini_set('memory_limit', '1G');
use App\Commission;
use App\Investment;
use App\Services\CalculateCommissionsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Repositorys\InvestmentRepository;

use Illuminate\Database\Eloquent\Collection;

final class calculateCommissions extends Command
{
    private $investmentRepo;
    private $calculationService;
    protected $signature = 'calculateCommissions {updated_at?}';
    protected $description = 'calculates commissions';

    public function __construct(InvestmentRepository $investmentRepository, CalculateCommissionsService $calculationService)
    {
        parent::__construct();

        $this->calculationService = $calculationService;
        $this->investmentRepo = $investmentRepository;
    }

    public function handle()
    {
        $investments = $this->getInvestmentsWithoutCommission();
        foreach ($investments as $investment) {
            $sums = $this->calculationService->calculateCommission($investment);
            Commission::create([
                'model_type' => 'investment',
                'model_id' => $investment->id,
                'user_id' => $investment->investor->user->id,
                'net' => $sums['net'],
                'gross' => $sums['gross']
            ]);
        }
    }

    private function getInvestmentsWithoutCommission(): Collection
    {
        return $this->investmentRepo->getInvestmentsWithoutCommission();
    }
}
