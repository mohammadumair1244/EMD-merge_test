<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Dev\CustomArrayController;
use App\Models\EmdCustomPage;
use App\Repositories\EmdCustomFieldRepository;
use App\Repositories\EmdMicrosoftClarityRepository;
use Illuminate\Http\Request;

class EmdCustomPageDisplayController extends Controller
{
    public static $custom_array__custom_page_static = [];
    public function custom_page_display(Request $request)
    {
        $custom_page = EmdCustomPage::where("page_key", implode('-', request()->segments()) ?: '')->first();
        if (!$custom_page) {
            abort(404);
        }
        self::$custom_array__custom_page_static = [];
        CustomArrayController::custom_array_function_custom_pages(request: $request, ip: @$request->header()[config('constants.user_ip_get')][0] ?? '127.0.0.1');
        $allow_json = [];
        foreach (EmdCustomFieldRepository::custom_page_wise_filter($custom_page->id) as $custom_field) {
            $allow_json[$custom_field->key] = $custom_field->default_val;
        }

        $clarity_check = [];
        $clarity_check['display'] = false;
        $clarity_check['percent'] = 0;
        if ((config('emd_setting_keys.emd_microsoft_clarity_key') ?? '') != "") {
            $get_clarity = EmdMicrosoftClarityRepository::clarity_get(emd_custom_page_id: (int) $custom_page->id);
            if ($get_clarity) {
                $clarity_check['display'] = true;
                $clarity_check['percent'] = $get_clarity;
                // $clarity_check['percent'] = $get_clarity->show_percentage;
            }
        }

        return view("layout.frontend.pages.emd-custom-pages." . $custom_page->blade_file)->with([
            'custom_page' => $custom_page,
            'content' => json_decode($custom_page->content),
            'meta_title' => $custom_page->meta_title,
            'meta_description' => $custom_page->meta_description,
            'custom_array' => self::$custom_array__custom_page_static,
            'allow_json' => json_decode(json_encode($allow_json)),
            'clarity_check' => json_decode(json_encode($clarity_check)),
        ]);
    }
}
