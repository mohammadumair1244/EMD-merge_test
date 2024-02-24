<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\EmdWebUserInterface;
use App\Models\EmdWebUser;
use App\Repositories\EmdEmailCampaignRepository;
use App\Repositories\EmdWebUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;

class EmdWebUserController extends Controller
{
    public static $is_user_premium = null;
    public function __construct(protected EmdWebUserInterface $emd_web_user_interface)
    {
    }
    public function view_web_users()
    {
        $this->authorize('view_users');
        return view('admin.emd-web-user.index')->with([
            'emd_web_users' => $this->emd_web_user_interface->view_web_users(),
        ]);
    }
    public function view_web_users_random_register()
    {
        $this->authorize('view_random_users');
        return view('admin.emd-web-user.random')->with([
            'emd_web_users' => $this->emd_web_user_interface->view_web_users_random_register(),
        ]);
    }
    public function view_web_users_type_wise($type = 0)
    {
        $this->authorize('view_users');
        return view('admin.emd-web-user.type_wise')->with([
            'emd_web_users' => $this->emd_web_user_interface->view_web_users_type_wise($type),
        ]);
    }
    public function view_web_user_detail($id)
    {
        $this->authorize('view_user_detail');
        $web_user_detail = $this->emd_web_user_interface->view_web_user_detail($id);
        return view('admin.emd-web-user.detail')->with([
            'emd_web_user' => $web_user_detail[0],
            'user_transactions' => $web_user_detail[1],
            'emd_pricing_plans' => $web_user_detail[2],
            'emd_user_profile_comments' => $web_user_detail[3],
            'emd_user_active_plans' => $web_user_detail[4],
        ]);
    }
    public function view_web_users_trash()
    {
        $this->authorize('view_trash_user');
        return view('admin.emd-web-user.trash')->with([
            'emd_web_users' => $this->emd_web_user_interface->view_web_users_trash(),
        ]);
    }
    public function emd_user_search_by_email(Request $request)
    {
        $this->authorize('view_user_detail');
        $user = $this->emd_web_user_interface->emd_user_search_by_email($request);
        if ($user[0]) {
            return to_route('emd_view_web_user_detail', ['id' => $user[1]->id]);
        } else {
            return back()->with('error', 'email not found');
        }
    }
    public function emd_add_more_user_query(Request $request, $user_id)
    {
        $result = $this->emd_web_user_interface->emd_add_more_user_query($request, $user_id);
        return back()->with('error', $result ? 'Successfully added Query' : 'Transaction Not Available');
    }
    public function emd_update_user_info(Request $request, $user_id)
    {
        $this->authorize('user_update');
        $update_user = $this->emd_web_user_interface->emd_update_user_info($request, $user_id);
        if ($update_user[0]) {
            return back()->with('error', $update_user[1]);
        } else {
            return back()->with('error', $update_user[1]);
        }
    }
    public function emd_deactive_user_account(Request $request, $user_id)
    {
        $this->authorize('user_delete');
        $this->emd_web_user_interface->emd_deactive_user_account($request, $user_id);
        return back()->with('error', "Successfully deactivated user");
    }
    public function emd_query_availability($transaction_id)
    {
        $this->authorize('view_user_query_detail');
        return view('admin.emd-web-user.query-availability')->with([
            'emd_user_transaction_allows' => $this->emd_web_user_interface->emd_query_availability($transaction_id),
        ]);
    }
    public function user_login_by_admin(Request $request, $id)
    {
        Auth::guard('web_user_sess')->loginUsingId($id);
        return redirect()->route("home");
    }
    public function emd_change_user_password(Request $request, $user_id)
    {
        $this->authorize('update_user_password');
        $this->emd_web_user_interface->emd_change_user_password($request, $user_id);
        return back()->with('error', "Successfully Update Password");
    }
    public function emd_web_user_date_filter_page($start_date, $end_date)
    {
        return view('admin.emd-web-user.date-filter')->with([
            'emd_web_users' => $this->emd_web_user_interface->emd_web_user_date_filter_page($start_date, $end_date),
        ]);
    }
    public function web_users_export_page()
    {
        $this->authorize('export_web_users');
        return view('admin.emd-web-user.export')->with([
            'emails_count_status_wise' => $this->emd_web_user_interface->web_users_export_page(),
        ]);
    }
    public function web_users_export_req(Request $request)
    {
        $this->authorize('export_web_users');
        $emails = EmdEmailCampaignRepository::get_email_with_user_status(implode(',', $request['user_status']));
        $emails = explode(',', $emails);
        $fileName = config('app.name') . "-" . implode('-', $request['user_status']) . "-(" . count($emails) . ').csv';
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );
        $columns = array('Email');
        $callback = function () use ($emails, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($emails as $email) {
                $row['Email'] = $email;
                fputcsv($file, array($row['Email']));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    // for emd website function
    public static function EmdIsUserPremium(bool $web = false, bool $api = false, ?string $api_key = null): int
    {
        return EmdWebUserRepository::EmdIsUserPremium($web, $api, $api_key);
    }
    public static function EmdAvailableQuery(bool $api = false, bool $both_web_api = false, bool $separate = false, ?string $api_key = null): int | array
    {
        return EmdWebUserRepository::EmdAvailableQuery(api: $api, both_web_api: $both_web_api, separate: $separate, api_key: $api_key);
    }
    public static function EmdWebsiteQueryUse($tool_id = null, $query_no = 1, $api_key = null, $error_mess = false, $query_use = true): bool | array
    {
        return EmdWebUserRepository::EmdWebsiteQueryUse($tool_id, $query_no, $api_key, $error_mess, $query_use);
    }

    public function emd_login_with_google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function emd_callback_from_google(Request $request)
    {
        $response_check = $this->emd_web_user_interface->emd_callback_from_google($request, @$request->header()[config('constants.user_ip_get')][0] ?? '127.0.0.1');
        if ($response_check) {
            return redirect()->route('home');
        } else {
            return redirect()->route('home')->with('error', 'Account already deleted with this email');
        }
    }

    public function emd_user_account_delete()
    {
        $delete_account = $this->emd_web_user_interface->emd_user_account_delete();
        if (!$delete_account[0]) {
            return response()->json(['status' => false, 'mess' => $delete_account[1]]);
            // return redirect()->route('home');
        } else {
            return response()->json(['status' => true, 'mess' => $delete_account[1]]);
            // return redirect()->route('home')->with('error', $delete_account[1]);
        }
    }

    public function emd_web_user_logout()
    {
        $this->emd_web_user_interface->emd_web_user_logout();
        return redirect()->route('home');
    }
    public function emd_cancel_plan_membership(Request $request)
    {
        $cancel_membership = $this->emd_web_user_interface->emd_cancel_plan_membership($request->except("_token"));
        return response()->json(['status' => $cancel_membership[0], 'mess' => $cancel_membership[1]]);
        // return redirect()->route('home')->with('cancel_membership', $cancel_membership[1]);
    }
    public function emd_register_with_web(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|string',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $register = $this->emd_web_user_interface->emd_register_with_web($request, @$request->header()[config('constants.user_ip_get')][0] ?? '127.0.0.1', EmdWebUser::REGISTER_FROM_WEB);
        if (!$register[0]) {
            return response()->json(['status' => false, 'mess' => $register[1]]);
        } else {
            return response()->json(['status' => true, 'mess' => $register[1]]);
            // return redirect()->route('home');
        }
    }
    public function emd_update_user_password(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
            'new_password' => 'required|min:8|confirmed',
        ]);
        $update_password = $this->emd_web_user_interface->emd_update_user_password($request);
        if (!$update_password[0]) {
            return response()->json(['status' => false, 'mess' => $update_password[1]]);
        } else {
            return response()->json(['status' => true, 'mess' => $update_password[1]]);
            // return redirect()->route('home');
        }
    }
    public function emd_verify_user_account($token)
    {
        $is_verified = $this->emd_web_user_interface->emd_verify_user_account($token);
        if (!$is_verified[0]) {
            return redirect()->back()->with('error', $is_verified[1]);
        } else {
            return redirect()->back()->with('success', $is_verified[1]);
        }
    }
    public function emd_login_with_web(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $login = $this->emd_web_user_interface->emd_login_with_web($request, @$request->header()[config('constants.user_ip_get')][0] ?? '127.0.0.1', EmdWebUser::REGISTER_FROM_WEB);
        if (!$login[0]) {
            return response()->json(['status' => false, 'mess' => $login[1]]);
        } else {
            return response()->json(['status' => true, 'mess' => $login[1]]);
            // return redirect()->route('home');
        }
    }
    public function emd_forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $forgot = $this->emd_web_user_interface->emd_forgot_password($request);
        if (!$forgot[0]) {
            return response()->json(['status' => false, 'mess' => $forgot[1]]);
        } else {
            return response()->json(['status' => true, 'mess' => $forgot[1]]);
            // return redirect()->route('home');
        }
    }
    public function emd_reset_password(Request $request, $token = null)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);
        $reset = $this->emd_web_user_interface->emd_reset_password($request, $token);
        if (!$reset[0]) {
            return response()->json(['status' => false, 'mess' => $reset[1]]);
        } else {
            return response()->json(['status' => true, 'mess' => $reset[1]]);
            // return redirect()->route('home');
        }
    }
}
