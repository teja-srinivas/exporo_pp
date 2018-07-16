<?php
declare(strict_types=1);


namespace App\Repositorys;

use App\Investment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class InvestmentRepository
{
    public function getInvestmentsForInvestorNotCancelled($investorID)
    {
        Investment::all()
            ->where('investor_id', $investorID)
            ->where('cancelled_at', 'NULL');
    }

    public function getInvestmentsWithoutCommission(): Collection
    {
       return Investment::doesntHave('commissions')->get();
    }

    public function getInvestmentsWhereCalculationChanged(): Collection
    {

          $investmentDatabase =  DB::table('investments')
            ->leftJoin('projects', 'investments.project_id', 'projects.id')
            ->leftJoin('schemas', 'projects.schema_id', 'schemas.id')
            ->leftJoin('investors', 'investments.investor_id', 'investors.id')
            ->leftJoin('commissions',function($join){
                $join->on('investments.id', '=', 'commissions.model_id')
                    ->where('commissions.model_type', '=', 'investment');
            })
            ->whereColumn('commissions.updated_at', '<', 'investments.updated_at')
            ->orWhereColumn('commissions.updated_at', '<', 'projects.updated_at')
            ->orWhereColumn('commissions.updated_at', '<', 'schemas.updated_at')
            ->orWhereColumn('commissions.updated_at', '<', 'investors.updated_at')->get(['investments.*'])->toArray();

            return Investment::hydrate($investmentDatabase);



    }

}
