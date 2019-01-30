<?php

namespace App\Http\ViewComposers;

use App\Models\BonusBundle;
use App\Models\CommissionBonus;
use App\Models\CommissionType;
use Illuminate\View\View;

class BundleEditorComposer
{
    /**
     * @var array
     */
    private $defaults;


    public function compose(View $view)
    {
        $view->with('defaults', $this->getDefaults($view['ajax'] ?? true));
    }

    private function getDefaults(bool $useAjax): array
    {
        if ($this->defaults) {
            return $this->defaults;
        }

        $editable = auth()->user()->can('create', BonusBundle::class);

        return $this->defaults = [
            'calculationTypes' => CommissionBonus::DISPLAY_NAMES,
            'commissionTypes' => CommissionType::query()->pluck('name', 'id'),
            'editable' => $editable,
            'legacy' => !$editable,
        ] + ($useAjax ? [
            'api' => route('api.commissions.bonuses.store'),
        ] : []);
    }
}
