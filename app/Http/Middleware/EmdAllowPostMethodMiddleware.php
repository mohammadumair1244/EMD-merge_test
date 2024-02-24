<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmdAllowPostMethodMiddleware
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
        if ($request->isMethod('POST')) {
            if (config('emd_setting_keys.emd_website_request_allow') != "1") {
                return response()->json(['request' => false, 'message' => config('emd_setting_keys.emd_website_request_allow_mess') ?? "POST Request not allowed"]);
            }
        }
        return $next($request);
    }
}
