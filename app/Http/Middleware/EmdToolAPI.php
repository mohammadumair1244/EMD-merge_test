<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmdToolAPI
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
        if (@$request->header('Authorization') == null) {
            return response()->json(['error' => ["auth" => "Authorization token required"]], 401);
        }
        $response = explode(' ', @$request->header('Authorization'));
        if (@$response[1] == "" || @$response[1] == null) {
            return response()->json(['error' => ["auth" => "Authorization token is empty"]], 401);
        }
        if (@$response[1] != config('emd_setting_keys.emd_tool_api_key_for_staging')) {
            return response()->json(['error' => ["auth" => "Invalid API Key"]], 401);
        }

        return $next($request);
    }
}
