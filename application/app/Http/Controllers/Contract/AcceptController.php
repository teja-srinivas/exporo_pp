<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contract;

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

        $data = $this->validate($request, [
            'legal_agb' => ['required', 'accepted'],
            'legal_contract' => ['required', 'accepted'],
            'signature' => ['required', 'string', 'min:16'],
        ]);

        $contract->signature = $data['signature'];
        $contract->accepted_at = now();
        $contract->save();

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
