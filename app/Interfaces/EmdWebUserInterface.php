<?php
namespace App\Interfaces;

interface EmdWebUserInterface
{
    public function view_web_users();
    public function view_web_users_random_register();
    public function view_web_user_detail($id);
    public function emd_callback_from_google($request, $ip);
    public function emd_web_user_logout();
    public function view_web_users_trash();
    public function emd_register_with_web($request, $ip, $register_from);
    public function emd_login_with_web($request, $ip, $login_from);
    public function emd_forgot_password($request);
    public function emd_reset_password($request, $token);
    public function emd_user_account_delete();
    public function emd_cancel_plan_membership($request);
    public function emd_verify_user_account($token);
    public function emd_update_user_password($request);
    public function emd_user_search_by_email($request);
    public function emd_add_more_user_query($request, $user_id);
    public function emd_update_user_info($request, $user_id);
    public function emd_deactive_user_account($request, $user_id);
    public function emd_query_availability($transaction_id);
    public function emd_change_user_password($request, $user_id);
    public function emd_web_user_date_filter_page($start_date, $end_date);
    public function web_users_export_page();
    public function view_web_users_type_wise($type);
    public function emd_user_info($api_key);
}
