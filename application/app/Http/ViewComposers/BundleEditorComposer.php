<?php

declare(strict_types=1);

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\CommissionType;
use App\Models\CommissionBonus;

class BundleEditorComposer
{
    public function compose(View $view)
    {
        $view->with('defaults', [
            'calculationTypes' => CommissionBonus::DISPLAY_NAMES,
            'commissionTypes' => CommissionType::query()->pluck('name', 'id'),
        ]);
    }
}
