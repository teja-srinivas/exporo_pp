<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contract;

use App\Models\Agb;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Middleware\RequireAcceptedPartnerContract;

class AcceptController extends Controller
{
    public function index(Contract $contract)
    {
        if (!$this->canBeAccepted($contract)) {
            return redirect()
                ->intended(route('home'))
                ->withErrors('Vertrag kann derzeitig nicht angenommen werden.');
        }

        $user = $contract->user;
        $productContract = $user->productContract;

        return response()->view('contracts.accept', [
            'bonuses' => $productContract->bonuses,
            'company' => $user->company,
            'pdfPartner' => url()->signedRoute('contract-pdf.show', [$contract]),
            'pdfProduct' => url()->signedRoute('contract-pdf.show', [$productContract]),
            'user' => $user,
        ]);
    }

    /**
     * @param  Contract  $contract
     * @param  Request  $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Contract $contract, Request $request)
    {
        if (!$this->canBeAccepted($contract)) {
            return redirect()->back()
                ->withErrors('Vertrag kann derzeitig nicht angenommen werden.');
        }

        if ($request->has('dismiss')) {
            session()->put(RequireAcceptedPartnerContract::SESSION_KEY, $contract->getRouteKey());

            return redirect()->home();
        }

        $data = $this->validate(
            $request,
            [
                'legal_agb' => ['required', 'accepted'],
                'legal_agb_ag' => ['required', 'accepted'],
                'legal_contract' => ['required', 'accepted'],
                'signature' => ['required', 'string', 'min:4'],
            ],
            [
                'legal_agb.required' => 'Bitte stimmen Sie den AGB zu, um fortfahren zu können',
                'legal_agb_ag.required' => 'Bitte stimmen Sie den AGB der Exporo AG zu, um fortfahren zu können',
                'legal_contract.required' => 'Bitte stimmen Sie dem Vertrag zu, um fortfahren zu können',
                'signature.required' => 'Bitte geben Sie Ihre Unterschrift ein, um fortfahren zu können',
                'signature.min' => 'Unterschrift muss mindestens 4 Zeichen lang sein.',
            ]
        );

        $contract->signature = $data['signature'];
        $contract->accepted_at = now();
        $contract->save();

        $user = $request->user();

        //attach agb if necessary
        foreach (Agb::TYPES as $value) {
            if ($user->activeAgbByType($value) !== null) {
                continue;
            }

            $agb = Agb::query()
                ->where('type', $value)
                ->where('is_default', true)
                ->where('effective_from', '<=', now())
                ->latest()
                ->first();

            if ($agb === null) {
                continue;
            }

            $user->agbs()->attach($agb);
        }

        session()->forget(RequireAcceptedPartnerContract::SESSION_KEY);

        return redirect()->home();
    }

    /**
     * @param  Contract  $contract
     * @return bool
     */
    protected function canBeAccepted(Contract $contract): bool
    {
        return $contract->isReleased() && $contract->accepted_at === null;
    }
}
