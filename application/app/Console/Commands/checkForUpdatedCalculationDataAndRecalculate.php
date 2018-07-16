<?php
declare(strict_types=1);


namespace App\Console\Commands;

use App\Services\CalculateCommissionsService;
use Illuminate\Console\Command;
use App\Repositorys\InvestmentRepository;
use App\Investment;

final class checkForUpdatedCalculationDataAndRecalculate extends Command
{
    private $investmentRepo;
    private $calculationService;
    protected $signature = 'recalculateUpdatedData';
    protected $description = 'recalculates commissions if data changed';

    public function __construct(InvestmentRepository $investmentRepository, CalculateCommissionsService $calculationService)
    {
        parent::__construct();
        $this->calculationService = $calculationService;
        $this->investmentRepo = $investmentRepository;
    }

    public function handle()
    {
      $investments =  $this->investmentRepo->getInvestmentsWhereCalculationChanged();
         foreach ($investments as $investment)
         {
           $sums = $this->calculationService->calculateCommission($investment);
           $this->touchModels($investment);
           $commission = $investment->commissions;
           $commission->fill([
                 'net' => $sums['net'],
                 'gross' => $sums['gross']
             ]);
           $commission->save();
         }
    }

    private function touchModels(Investment $investment): void
    {
        $investment->investor->touch();
        $investment->project->touch();
        $investment->project->schema->touch();
        $investment->touch();
        return;
    }

}
