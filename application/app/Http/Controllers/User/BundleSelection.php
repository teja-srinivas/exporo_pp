<?php

namespace App\Http\Controllers\User;

use App\Models\BonusBundle;
use App\Models\CommissionBonus;
use App\Models\User;
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

            if ($user->canBeProcessed() && $user->bonuses()->exists()) {
                return redirect()->home();
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.bundle-selection', [
            'bundles' => BonusBundle::query()
                ->with('bonuses.type:name')
                ->selectable()
                ->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            // Only allow picking from our selected bundles
            'bundle' => ['required', Rule::exists(
                (new BonusBundle)->getTable(),
                'id'
            )->where('selectable', true)],
        ]);

        /** @var User $user */
        $user = $request->user();

        /** @var BonusBundle $bundle */
        $bundle = BonusBundle::query()->findOrFail($data['bundle']);

        DB::transaction(function () use ($user, $bundle) {
            $userId = $user->getKey();

            $user->bonuses()->delete();

            $bundle->bonuses->each(function (CommissionBonus $bonus) use ($userId) {
                $copy = $bonus->replicate(['user_id']);
                $copy->user_id = $userId;
                $copy->accepted_at = now();
                $copy->saveOrFail();

                return $copy;
            });
        });

        return redirect()->home();
    }
}
