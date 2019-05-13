<?php

namespace App\Http\Controllers;

use App\Models\BonusBundle;
use App\Models\CommissionBonus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BonusBundleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', BonusBundle::class);

        $bundles = BonusBundle::query()
            ->with('bonuses')
            ->orderBy('name')
            ->get();

        return response()->view('commissions.bundles.index', [
            'bundles' => $bundles->groupBy('selectable'),
            'count' => $bundles->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', BonusBundle::class);

        return response()->view('commissions.bundles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->authorize('create', BonusBundle::class);

        $data = $this->validate($request, [
            'name' => 'required',
            'bonuses.*.type_id' => Rule::exists('commission_types', 'id'),
            'bonuses.*.value' => 'numeric',
            'bonuses.*.calculation_type' => Rule::in(CommissionBonus::TYPES),
            'bonuses.*.is_percentage' => 'boolean',
            'bonuses.*.is_overhead' => 'boolean',
        ]);

        $bundle = new BonusBundle([
            'name' => $data['name'],
            'selectable' => $request->has('selectable'),
            'child_user_selectable' => $request->has('child_user_selectable'),
        ]);

        $bundle->saveOrFail();

        $bonusIds = [];

        foreach ($data['bonuses'] ?? [] as $id => $values) {
            $bonus = $id > 0 ? CommissionBonus::query()->findOrFail($id) : new CommissionBonus();
            $bonus->fill($values);
            $bonus->user_id = 0; // Marks this as "not attached"
            $bonus->saveOrFail();

            $bonusIds[] = $bonus->getKey();
        }

        $bundle->bonuses()->sync($bonusIds);

        return redirect()->route('commissions.bundles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BonusBundle  $bonusBundle
     * @return \Illuminate\Http\Response
     */
    public function show(BonusBundle $bonusBundle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BonusBundle $bundle
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(BonusBundle $bundle)
    {
        $this->authorize('update', BonusBundle::class);

        return view('commissions.bundles.edit', [
            'bundle' => $bundle,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\BonusBundle $bundle
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, BonusBundle $bundle)
    {
        $this->authorize('create', BonusBundle::class);

        $data = $this->validate($request, [
            'name' => 'required|string',
        ]);

        $bundle->update($data + [
            'selectable' => $request->has('selectable'),
            'child_user_selectable' => $request->has('child_user_selectable'),
        ]);

        flash_success();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BonusBundle $bundle
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(BonusBundle $bundle)
    {
        $this->authorize('delete', $bundle);

        $bundle->delete();

        return redirect()->route('commissions.bundles.index');
    }
}
