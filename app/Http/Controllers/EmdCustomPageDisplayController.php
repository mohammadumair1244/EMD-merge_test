<?php

namespace App\Http\Controllers;

use App\Models\EmdCustomPage;
use App\Repositories\EmdCustomFieldRepository;
use App\Repositories\EmdMicrosoftClarityRepository;
use App\Repositories\EmdPricingPlanRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class EmdCustomPageDisplayController extends Controller
{
    protected $custom_array = [];
    public function custom_page_display(Request $request)
    {
        $custom_page = EmdCustomPage::where("page_key", request()->segments() ?: '')->first();
        if (!$custom_page) {
            abort(404);
        }
        $this->custom_array = [];
        
        if (Route::currentRouteNamed('EmdCustomPage.testing')) {
            $this->pricing_plan(@$request->header()['x-real-ip'][0] ?: '127.0.0.1');
        }

        $allow_json = [];
        foreach (EmdCustomFieldRepository::custom_page_wise_filter($custom_page->id) as $custom_field) {
            $allow_json[$custom_field->key] = $custom_field->default_val;
        }

        $clarity_check = [];
        $get_clarity = EmdMicrosoftClarityRepository::clarity_get(emd_custom_page_id: (int) $custom_page->id);
        if ($get_clarity) {
            $clarity_check['display'] = true;
            $clarity_check['percent'] = $get_clarity->show_percentage;
        } else {
            $clarity_check['display'] = false;
            $clarity_check['percent'] = 0;
        }

        return view("layout.frontend.pages.emd-custom-pages." . $custom_page->blade_file)->with([
            'custom_page' => $custom_page,
            'content' => json_decode($custom_page->content),
            'meta_title' => $custom_page->meta_title,
            'meta_description' => $custom_page->meta_description,
            'custom_array' => $this->custom_array,
            'allow_json' => json_decode(json_encode($allow_json)),
            'clarity_check' => json_decode(json_encode($clarity_check)),
        ]);
    }

    public function pricing_plan($ip)
    {
        $this->custom_array['emd_our_pricing_plans'] = EmdPricingPlanRepository::emd_our_pricing_plans_static(ip: $ip);
    }
}
