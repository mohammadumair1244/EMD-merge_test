<?php

namespace App\Http\Middleware;

use App\Repositories\EmdWebUserRepository;
use Closure;
use Illuminate\Http\Request;

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
        $tool_key = null;
        if ($request->filled('tool_key')) {
            $tool_key = @$request?->tool_key ?? 'none';
        }
        $p_tool_id = null;
        if ($request->filled('p_tool_id')) {
            $p_tool_id = @$request?->p_tool_id ?? 0;
        }
        $query_available = EmdWebUserRepository::EmdWebsiteQueryUse(error_mess: true, tool_key: $tool_key, tool_id: $p_tool_id);
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
