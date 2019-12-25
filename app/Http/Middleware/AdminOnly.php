<?php

namespace App\Http\Middleware;

use Closure;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (!$user->isAdmin|| !$user->role || $user->role->name != 'admin') {
            return response(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
