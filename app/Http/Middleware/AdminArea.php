<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Enums\UserRole;

class AdminArea
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role !== UserRole::Admin
            && $request->user()->role !== UserRole::Contributor
        ) {
            abort(404);
        }

        return $next($request);
    }
}
