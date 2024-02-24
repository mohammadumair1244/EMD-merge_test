<?php

namespace App\Http\Middleware;

use App\Http\Controllers\EmdWebUserController;
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
        $check_is_user_premium = EmdWebUserRepository::EmdIsUserPremium();
        EmdWebUserController::$is_user_premium = $check_is_user_premium;
        view()->share('EmdIsUserPremium', $check_is_user_premium);
        return $next($request);
    }
}
