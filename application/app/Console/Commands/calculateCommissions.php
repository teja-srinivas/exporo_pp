<?php
declare(strict_types=1);


namespace App\Console\Commands;


use App\Commission;
use App\Investment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Repositorys\InvestmentRepository;

use Illuminate\Database\Eloquent\Collection;

final class calculateCommissions extends Command
{

    private $investmentRepo;

    protected $signature = 'calculateCommissions {updated_at?}';
    protected $description = 'calculates commissions';

    public function __construct(InvestmentRepository $investmentRepository)
    {
        parent::__construct();

        $this->investmentRepo = $investmentRepository;
    }

    public function handle()
    {
        $investments = $this->getInvestmentsWithoutCommission();
        foreach ($investments as $investment)
        {
            $sums =  $this->calculateCommission($investment);
            Commission::create([
                'model_type' => 'investment',
                'model_id' => $investment->id,
                'user_id' => $investment->investor->user->id,
                'net'     => $sums['net'],
                'gross'   => $sums['gross']
            ]);
        }
    }

    private function calculateCommission(Investment $investment): array
    {
       $schema = $investment->project->schema->first();
       $runtime = $this->calcRuntimeInMonths($investment);
       if($this->checkIfIsFirstInvestment($investment)) {
           $sum = $schema->calculate($investment->investsum, $runtime, $investment->investor->user->details->first_investment_bonus);
       }
       else{
           $sum = $schema->calculate($investment->investsum, $runtime, $investment->investor->user->details->further_investment_bonus);
       }
       $sums['net'] = $sum * 0.81;
       $sums['gross'] = $sum * 1.19;
       return $sums;
    }

    private function checkIfIsFirstInvestment(Investment $investment): bool
    {
       return $investment->is_first_investment;
    }
    private function getInvestmentsWithoutCommission(): Collection
    {
        return $this->investmentRepo->getInvestmentsWithoutCommission();
    }

    private function calcRuntimeInMonths(Investment $investment)
    {
        $start = $investment->project->launched_at;
        $end = Carbon::parse($investment->project->payback_min_at);
        return $end->diffInMonths($start);

    }
}
