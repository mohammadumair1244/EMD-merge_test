<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmdCustomPageDisplayController;
use App\Http\Controllers\FrontendController;
use App\Repositories\EmdPricingPlanRepository;
use Illuminate\Support\Facades\Route;

class CustomArrayController extends Controller
{
    // for tool custom value
    public static function custom_array_function_tool_pages($tool): bool
    {
        if (Route::currentRouteNamed('home_testing')) {
            FrontendController::$custom_array_tool_page_static['emd'] = array('emd_version' => config('constants.version'));
        }

        return true;
    }

    // for blog pages custom value
    public static function custom_array_function_blog_pages($lang = null, $slug = null): bool
    {
        if (Route::currentRouteNamed('blog_testing')) {
            FrontendController::$custom_array_blog_page_static['emd'] = array('emd_version' => config('constants.version'));
        }

        return true;
    }

    // for blog pages custom value
    public static function custom_array_function_sitemap_links(): bool
    {
        FrontendController::$custom_array_sitemap_link_static['emd'] = route('home');

        return true;
    }

    // for custom pages custom value
    public static function custom_array_function_custom_pages($request, $ip): bool
    {
        if (Route::currentRouteNamed('EmdCustomPage.testing')) {
            EmdCustomPageDisplayController::$custom_array__custom_page_static['emd_our_pricing_plans'] = EmdPricingPlanRepository::emd_our_pricing_plans_static(ip: $ip);
        }

        return true;
    }

}
