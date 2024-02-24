<?php

namespace Database\Seeders;

use App\Http\Controllers\AdminController;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = [
            [
                'key' => 'large_image_width',
                'value' => '900',
                'section' => 'media',
                'type' => "text",
                'autoload' => 0,
            ],
            [
                'key' => 'thumbnail_width',
                'value' => '300',
                'section' => 'media',
                'type' => "text",
                'autoload' => 0,
            ],
            [
                'key' => 'small_image_width',
                'value' => '150',
                'section' => 'media',
                'type' => "text",
                'autoload' => 0,
            ],
            [
                'key' => 'small_image_height',
                'value' => '150',
                'section' => 'media',
                'type' => "text",
                'autoload' => 0,
            ],
            // [
            //     'key' => 'privacy-policy',
            //     'value' => " ",
            //     'section' => 'content',
            //     'type' => "richText",
            //     'autoload' => 1,
            // ],
            // [
            //     'key' => 'terms-and-condition',
            //     'value' => " ",
            //     'section' => 'content',
            //     'type' => "richText",
            //     'autoload' => 1,
            // ],
            // [
            //     'key' => 'about-us',
            //     'value' => " ",
            //     'section' => 'content',
            //     'type' => "richText",
            //     'autoload' => 1,
            // ],
            // [
            //     'key' => 'google_analytics',
            //     'value' => " ",
            //     'section' => 'head',
            //     'type' => "textarea",
            //     'autoload' => 1,
            // ],
            [
                'key' => 'emd_forgot_password_slug',
                'value' => "emd-forgot-password",
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_verify_account_slug',
                'value' => "emd-verify-account",
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_website_request_allow',
                'value' => "1",
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_website_request_allow_mess',
                'value' => "website is down for 1 hour",
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_chat_status',
                'value' => "0",
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_tawk_chat_url',
                'value' => "",
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_microsoft_clarity_key',
                'value' => "",
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_tool_api_key_for_live',
                'value' => "",
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_tool_api_route_for_live',
                'value' => "",
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_tool_api_key_for_staging',
                'value' => time(),
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_throttle_tool_limit',
                'value' => 20,
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_mobile_api_key_access',
                'value' => md5(time()),
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
            [
                'key' => 'emd_dashboard_stats',
                'value' => 0,
                'section' => 'content',
                'type' => "inputField",
                'autoload' => 0,
            ],
        ];
        foreach ($setting as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $value['key']],
                [
                    'value' => $value['value'],
                    'section' => $value['section'],
                    'type' => $value['type'],
                    'autoload' => $value['autoload'],
                ]
            );
        }
        AdminController::create_setting_key_file();
    }
}
