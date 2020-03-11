<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Agb;
use App\Models\User;
use App\Models\Contract;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Builders\ContractBuilder;

class DocumentController extends Controller
{
    public function index(User $user, Request $request)
    {
        $this->authorizeViewingUser($user, $request);

        $documents = $user->documents->map(static function (Document $document) {
            return [
                'type' => 'Dokument',
                'title' => $document->name,
                'link' => $document->getDownloadUrl(),
                'created_at' => $document->created_at,
            ];
        });

        if (
            $user->can('management.documents.view-contracts')
                && $user->can('features.contracts.accept')
                && $user->onlyApprovedPartnerContract === null
        ) {
            $contracts = $user->contracts()->when(true, static function (ContractBuilder $builder) {
                $builder->onlyActive();
            })
            ->where('accepted_at', '>=', Contract::EARLIEST)
            ->get()->map(static function (Contract $contract) {
                return [
                    'type' => 'Vertrag',
                    'title' => $contract->getTitle(),
                    'link' => $contract->getDownloadUrl(),
                    'created_at' => $contract->accepted_at,
                    'pdf_generated_at' => $contract->pdf_generated_at,
                ];
            });
        } else {
            $contracts = [];
        }

        $agbs = $user->agbs
            ->where('effective_from', '<=', now())
            ->map(static function (Agb $agb) use ($user) {
                $activeAgb = $user->activeAgbByType($agb->type);

                if ($activeAgb !== null && !$agb->is_default) {
                    $effictiveFrom = new Carbon($activeAgb->effective_from);
                    $diffInWeeks = $effictiveFrom->diffInWeeks(Carbon::now());

                    if ($agb !== $activeAgb && $diffInWeeks >= 4) {
                        return false;
                    }
                }

                return [
                    'type' => __('AGB'),
                    'title' => $agb->name,
                    'link' => $agb->getDownloadUrl(),
                    'created_at' => $agb->pivot->created_at,
                ];
            });

        return response()->view('users.documents', [
            'documents' => collect()
                ->merge($documents)
                ->merge($contracts)
                ->merge($agbs->filter())
                ->sortByDesc('created_at'),
        ]);
    }
}
