<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;

class CommissionDetails extends Controller
{
    /**
     * Shows the commission bonus details page for the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return response()->view('users.commission-details', [
            'bonuses' => $user->bonuses,
        ]);
    }
}
