<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\EmdFeedback;
use App\Models\EmdUserTransaction;
use App\Models\EmdWebUser;
use App\Models\Media;
use App\Models\Setting;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }
    public function dashboard()
    {
        $audits = Tool::with('audits')->get();
        $dashboard_stats = [
            'total_users_count' => User::count(),
            'total_web_users_count' => User::where('admin_level', User::WEB_REGISTER)->where('email', '!=', '')->whereNotNull('email')->count(),
            'total_web_null_users_count' => User::whereNull('email')->orWhere('email', '')->where('admin_level', User::WEB_REGISTER)->count(),
            'total_admins_count' => User::whereNot('admin_level', User::WEB_REGISTER)->count(),
            'total_web_premium' => EmdWebUser::where('is_web_premium', EmdWebUser::PREMIUM_USER)->where('is_api_premium', EmdWebUser::FREE_USER)->count(),
            'total_api_premium' => EmdWebUser::where('is_web_premium', EmdWebUser::FREE_USER)->where('is_api_premium', EmdWebUser::PREMIUM_USER)->count(),
            'total_web_api_premium' => EmdWebUser::where('is_web_premium', EmdWebUser::PREMIUM_USER)->where('is_api_premium', EmdWebUser::PREMIUM_USER)->count(),
            'total_feedbacks_count' => EmdFeedback::count(),
            'total_contacts_count' => Contact::count(),
            'total_PTrans_count' => EmdUserTransaction::where('order_status', EmdUserTransaction::OS_PROCESSED)->count(),
            'total_CTrans_count' => EmdUserTransaction::where('order_status', EmdUserTransaction::OS_CANCELED)->count(),
            'total_RTrans_count' => EmdUserTransaction::where('order_status', EmdUserTransaction::OS_REFUNDED)->count(),
        ];
        return view('admin.dashboard')->with(['tools_with_audits' => $audits, 'dashboard_stats' => $dashboard_stats]);
    }

    public function settings()
    {
        $this->authorize('view_setting');
        $media = Setting::where('section', 'media')->get();
        $content = Setting::where('section', 'content')->get();
        return view('admin.settings', compact('media', 'content'));
    }
    public function update_settings(Request $request)
    {
        $this->authorize('add_update_setting');
        Setting::updateOrInsert(
            ['key' => 'large_image_width'],
            ['value' => $request->large_image_width, 'section' => 'media', 'type' => 'text']
        );
        Setting::updateOrInsert(
            ['key' => 'thumbnail_width'],
            ['value' => $request->thumbnail_width, 'section' => 'media', 'type' => 'text']
        );
        Setting::updateOrInsert(
            ['key' => 'small_image_width'],
            ['value' => $request->small_image_width, 'section' => 'media', 'type' => 'text']
        );
        Setting::updateOrInsert(
            ['key' => 'small_image_height'],
            ['value' => $request->small_image_height, 'section' => 'media', 'type' => 'text']
        );

        $contentKey = !empty($request->contentKey) ? $request->contentKey : '';
        $sectionType = !empty($request->sectionType) ? $request->sectionType : '';
        $contentValue = !empty($request->contentKey) ? $request->contentValue : '';
        $inputType = !empty($request->contentKey) ? $request->inputType : '';
        $autoload = !empty($request->autoload) ? $request->autoload : '';
        for ($i = 0; $i < count($contentKey); ++$i) {
            Setting::updateOrInsert(
                ['key' => $contentKey[$i]],
                ['value' => $contentValue[$i], 'type' => $inputType[$i], 'section' => $sectionType[$i], 'autoload' => $autoload[$i]]
            );
        }
        return back();
    }
    public function modals()
    {
        $this->authorize('view_modals');
        $images = Media::get_media();
        return view('admin.components')->with([
            'images' => $images,
        ]);
    }
    public function settings_delete(Request $request)
    {
        $this->authorize('delete_setting');
        if (Setting::destroy($request->id)) {
            return 1;
        }
    }
    public function setCookie(Request $request, $hash)
    {
        $admin = User::where('hash', $hash)->first();
        if ($admin != null) {
            return to_route('admin_login')->withCookie(cookie()->forever('admin_hash', $hash));
        } else {
            Cookie::queue(Cookie::forget('admin_hash'));
            return redirect('/');
        }
    }
}
