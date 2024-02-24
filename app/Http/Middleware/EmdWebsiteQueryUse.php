<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Repositories\EmdWebUserRepository;

class EmdWebsiteQueryUse
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
        $query_available = EmdWebUserRepository::EmdWebsiteQueryUse(error_mess: true);
        if (!$query_available[0]) {
            return response()->json(['emd_middleware_mess' => $query_available[1]]);
        }
        return $next($request);
    }
    public function terminate($request, $response)
    {
        if ($response->status() !== 200 && EmdWebUserRepository::EmdIsUserPremium()) {
            EmdWebUserRepository::EmdWebsiteQueryUse(query_no: -1);
        }
    }
}
