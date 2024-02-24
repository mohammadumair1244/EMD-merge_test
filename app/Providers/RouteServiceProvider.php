<?php

namespace App\Providers;

use App\Models\EmdCustomField;
use App\Models\Tool;
use App\Repositories\EmdUserTransactionRepository;
use App\Repositories\EmdWebUserRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = 'admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();
        $this->configureToolRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            if (file_exists(base_path('routes/emd-testing-route.php'))) {
                Route::middleware('web')
                    ->prefix('emd-testing')
                    ->group(base_path('routes/emd-testing-route.php'));
            }
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    protected function configureToolRateLimiting()
    {
        Cache::forget('throttle:toolNew');
        Cache::forget('throttle:tool');
        RateLimiter::for('toolNew', function (Request $request) {
            $default_throttle_tool_limit = EmdCustomField::where('key', 'throttle_tool_limit')->first();
            if ($default_throttle_tool_limit == null) {
                $limit = 10;
            } else {
                $limit = (int) $default_throttle_tool_limit->default_val;
            }
            if ($request->filled('parent_id')) {
                $parent_id = (int) $request->input('parent_id');
                $_tool = cache()->remember('tool-' . $parent_id, 86400, function () use ($parent_id) {
                    return Tool::select(['request_limit'])->where('parent_id', $parent_id)->first() ?: 1;
                });
                if ($limit < (int) $_tool['request_limit']) {
                    $limit = (int) $_tool['request_limit'];
                }
            }
            if (EmdWebUserRepository::EmdIsUserPremium()) {
                if ($request->input('parent_id')) {
                    $parent_tool_id = $request->input('parent_id');
                } else {
                    $parent_tool_id = 0;
                }
                $emd_user_transaction_allows = EmdUserTransactionRepository::AvailablePlanDetail((int) $parent_tool_id, (int) $request->user()?->id);
                if (count($emd_user_transaction_allows->toArray()) > 0) {
                    foreach ($emd_user_transaction_allows as $emd_user_transaction_allows_item) {
                        $query_allow_json = json_decode($emd_user_transaction_allows_item->allow_json);
                        if (gettype($query_allow_json) == 'object') {
                            if (property_exists($query_allow_json, 'throttle_tool_limit')) {
                                if ($limit < (int) (@$query_allow_json?->throttle_tool_limit ?: 0)) {
                                    $limit = (int) $query_allow_json->throttle_tool_limit;
                                }
                            }
                        }
                    }
                }
            }
            return Limit::perMinute($limit)->by($request->user()?->id ?: $request->ip())->response(function (Request $request) {
                return response('IP Blocked', 429);
            });
        });
    }
}
