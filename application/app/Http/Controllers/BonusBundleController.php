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
     */
    public function index()
    {
        $bundles = BonusBundle::query()
            ->with('bonuses')
            ->orderBy('name')
            ->get();

        return view('commissions.bundles.index', [
            'bundles' => $bundles->groupBy('selectable'),
            'count' => $bundles->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('commissions.bundles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'bonuses.*.type_id' => Rule::exists('commission_types', 'id'),
            'bonuses.*.value' => 'numeric',
            'bonuses.*.calculation_type' => Rule::in(CommissionBonus::TYPES),
            'bonuses.*.is_percentage' => 'boolean',
            'bonuses.*.is_overhead' => 'boolean',
        ]);

        $bundle = new BonusBundle();
        $bundle->fill([
            'name' => $data['name'],
            'selectable' => $request->has('selectable'),
        ])->saveOrFail();

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
     * @param  \App\Models\BonusBundle  $bonusBundle
     * @return \Illuminate\Http\Response
     */
    public function edit(BonusBundle $bonusBundle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BonusBundle  $bonusBundle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BonusBundle $bonusBundle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BonusBundle  $bonusBundle
     * @return \Illuminate\Http\Response
     */
    public function destroy(BonusBundle $bonusBundle)
    {
        //
    }
}
