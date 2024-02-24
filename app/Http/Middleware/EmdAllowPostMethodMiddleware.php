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
            if (@get_setting_by_key("emd_website_request_allow")->value != "1") {
                return response()->json(['request' => false, 'message' => @get_setting_by_key("emd_website_request_allow_mess")->value]);
            }
        }
        return $next($request);
    }
}
