<?php

namespace App\Http\Middleware;

use App\Repositories\EmdWebUserRepository;
use Closure;
use Illuminate\Http\Request;

class EmdIsUserPremium
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
        view()->share('EmdIsUserPremium', EmdWebUserRepository::EmdIsUserPremium());
        return $next($request);
    }
}
