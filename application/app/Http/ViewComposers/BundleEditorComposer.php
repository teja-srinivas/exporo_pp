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
        $types = CommissionType::all();

        $view->with('defaults', [
            'calculationTypes' => CommissionBonus::DISPLAY_NAMES,
            'commissionTypes' => $types->pluck('name', 'id'),
            'publicTypes' => $types->where('is_public', true)->modelKeys(),
        ]);
    }
}
