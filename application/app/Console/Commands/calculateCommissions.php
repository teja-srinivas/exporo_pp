<?php
declare(strict_types=1);


namespace App\Console\Commands;

ini_set('memory_limit', '1G');
use App\Commission;
use App\Investment;
use App\PartnerPercentages;
use App\Services\CalculateCommissionsService;
use App\User;
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

            $sums = $this->calculationService->calculateCommission($investment, 100.00);
            Commission::create([
                'model_type' => 'investment',
                'model_id' => $investment->id,
                'user_id' => $investment->investor->user->id,
                'net' => $sums['net'],
                'gross' => $sums['gross']
            ]);

            $user = User::find($investment->investor->user->parent_id);
            $counter = 0;

            while($user)
            {
                $this->calculationService->calculateCommission($investment);
                $percentage = PartnerPercentages::find($counter);
                $sums = $this->calculationService->calculateCommission($investment, $percentage);
                 Commission::create([
                    'model_type' => 'subpartner',
                    'model_id' => $investment->id,
                    'user_id' => $investment->investor->user->id,
                    'net' => $sums['net'],
                    'gross' => $sums['gross']
                 ]);
                 $user = User::find($user->parent_id);
                 $counter++;
            }
        }
    }

    private function getInvestmentsWithoutCommission(): Collection
    {
        return $this->investmentRepo->getInvestmentsWithoutCommission();
    }
}
