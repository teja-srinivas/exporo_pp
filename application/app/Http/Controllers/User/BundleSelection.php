<?php

namespace App\Http\Controllers\User;

use App\Models\BonusBundle;
use App\Models\CommissionBonus;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BundleSelection extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            /** @var User $user */
            $user = $request->user();

            if (!$user->canBeProcessed() || $user->hasBundleSelected()) {
                return redirect()->home();
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Collection $bundles */
        $bundles = BonusBundle::query()
            ->with('bonuses.type:name')
            ->selectable($user->parent_id > 0)
            ->get();

        if ($bundles->count() === 1) {
            return $this->selectAndRedirect($user, $bundles->first());
        }

        return response()->view('users.bundle-selection', [
            'bundles' => $bundles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            // Only allow picking from our selected bundles
            'bundle' => ['required', Rule::exists(
                (new BonusBundle)->getTable(),
                'id'
            )->where('selectable', '1')],
        ]);

        return $this->selectAndRedirect(
            $request->user(),
            BonusBundle::query()->findOrFail($data['bundle'])
        );
    }

    /**
     * @param User $user
     * @param BonusBundle $bundle
     * @return \Illuminate\Http\RedirectResponse
     */
    private function selectAndRedirect(User $user, BonusBundle $bundle): \Illuminate\Http\RedirectResponse
    {
        $user->switchToBundle($bundle);

        return redirect()->home();
    }
}
