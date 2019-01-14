<?php
declare(strict_types=1);


namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Models\Role;

class UserIsAdmin
{
    public function handle(Request $request, \Closure $next)
    {
        $user = $request->user();
        if ($user->roles[0]->name === Role::ADMIN) {
            return $next($request);
        }
        return response()->view('message', [
            'message' => __('users.message.status.cancelled'),
        ]);
    }
}
