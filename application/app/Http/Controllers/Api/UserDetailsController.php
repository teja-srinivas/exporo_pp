<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserDetails;

class UserDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return UserDetails::query()
            ->get(['id', 'display_name', 'vat_amount', 'vat_included'])
            ->mapWithKeys(function (UserDetails $details) {
                return [
                    $details->getKey() => [
                        'displayName' => $details->display_name,
                        'vatAmount' => $details->vat_amount,
                        'vatIncluded' => $details->vat_included,
                    ],
                ];
            });
    }
}
