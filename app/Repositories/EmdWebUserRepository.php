<?php

namespace App\Repositories;

use App\Http\Controllers\EmdSendEmailController;
use App\Http\Controllers\EmdUserProfileCommentController;
use App\Interfaces\EmdWebUserInterface;
use App\Models\EmdEmailSetting;
use App\Models\EmdPricingPlan;
use App\Models\EmdUserProfileComment;
use App\Models\EmdUserTransaction;
use App\Models\EmdUserTransactionAllow;
use App\Models\EmdWebUser;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Facades\Agent;
use Laravel\Socialite\Facades\Socialite;

class EmdWebUserRepository implements EmdWebUserInterface
{
    public function __construct(
        protected EmdWebUser $emd_web_user_model,
        protected EmdUserTransaction $emd_user_transaction_model,
        protected User $user_model,
        protected EmdPricingPlan $emd_pricing_plan_model,
        protected EmdEmailSetting $emd_email_setting_model,
        protected EmdSendEmailController $emd_send_email_controller,
        protected EmdUserTransactionAllow $emd_user_transaction_allow,
        protected EmdUserProfileComment $emd_user_profile_comment_model,
        protected EmdUserProfileCommentController $emd_user_profile_comment_controller) {
    }

    public function view_web_users(): LengthAwarePaginator
    {
        return $this->emd_web_user_model->whereHas('user', function ($query) {
            return $query->whereNull('deleted_at');
        })->orderByDESC('id')->paginate(100);
    }
    public function emd_web_user_date_filter_page($start_date, $end_date): EmdWebUser | Collection
    {
        return $this->emd_web_user_model->whereHas('user', function ($query) {
            return $query->whereNull('deleted_at');
        })->whereBetween('created_at', [$start_date, $end_date])->get();
    }
    public function view_web_user_detail($id): array
    {
        return [
            $this->emd_web_user_model->with('user')->where('user_id', $id)->first(),
            $this->emd_user_transaction_model
                ->with('emd_pricing_plan')
                ->withSum('emd_user_transaction_allows', 'queries_limit')
                ->withSum('emd_user_transaction_allows', 'queries_used')
                ->where('user_id', $id)
                ->where('order_status', $this->emd_user_transaction_model::OS_PROCESSED)
                ->get(),
            $this->emd_pricing_plan_model->whereIn('is_custom', [$this->emd_pricing_plan_model::SIMPLE_PLAN, $this->emd_pricing_plan_model::CUSTOM_PLAN])->orWhereNull('is_custom')->get(),
            $this->emd_user_profile_comment_model->with('action_user')->where('user_id', $id)->get(),
            $this->emd_user_transaction_model->with('emd_pricing_plan')->where('user_id', $id)->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', $this->emd_user_transaction_model::OS_PROCESSED)->where('expiry_date', '>=', date("Y-m-d"))->get(),
        ];
    }
    public function emd_query_availability($transaction_id): EmdUserTransactionAllow | Collection
    {
        return $this->emd_user_transaction_allow->with('tool')->where('emd_user_transaction_id', $transaction_id)->get();
    }
    public function view_web_users_trash(): EmdWebUser | Collection
    {
        return $this->emd_web_user_model->withWhereHas('user', function ($query) {
            return $query->onlyTrashed();
        })->get();
    }

    public function emd_user_search_by_email($request): array
    {
        $user = $this->user_model->where('email', $request['email'])->where('admin_level', $this->user_model::WEB_REGISTER)->first();
        if ($user) {
            return [true, $user];
        } else {
            return [false, null];
        }
    }
    public function emd_add_more_user_query($request, $user_id): bool
    {
        $action_type = "";
        $is_api = $request['is_web_or_api'];
        $user_latest_transaction = $this->emd_user_transaction_model->whereHas('emd_pricing_plan', function ($query) use ($is_api) {
            return $query->where('is_api', $is_api);
        })->where('user_id', $user_id)->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', $this->emd_user_transaction_model::OS_PROCESSED)->where('expiry_date', '>=', date("Y-m-d"))->latest()->first();
        if ($user_latest_transaction) {
            if ($user_latest_transaction->expiry_date != $request->expiry_date) {
                $user_latest_transaction->expiry_date = $request->expiry_date;
                $user_latest_transaction->save();
                $action_type = " & ExpiryChange";
            }
        }
        $emd_user_transaction_allow_row = $this->emd_user_transaction_allow->whereHas('emd_user_transaction', function ($query) use ($is_api) {
            return $query->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', $this->emd_user_transaction_model::OS_PROCESSED)->where('expiry_date', '>=', date("Y-m-d"))->whereHas('emd_pricing_plan', function ($query) use ($is_api) {
                return $query->where('is_api', $is_api);
            });
        })->where('user_id', $user_id);
        if (((int) $request['queries']) < 0) {
            $emd_user_transaction_allow_row = $emd_user_transaction_allow_row->where('queries_limit', '>', 0);
        }
        $emd_user_transaction_allow_row = $emd_user_transaction_allow_row->first();
        if (@$emd_user_transaction_allow_row == null) {
            return false;
        }
        if ($is_api == $this->emd_pricing_plan_model::API_PLAN) {
            $action_type = "AddQueryLimitAPI " . $action_type;
        } elseif ($is_api == $this->emd_pricing_plan_model::WEB_AND_API_PLAN) {
            $action_type = "AddQueryLimitWeb&API " . $action_type;
        } else {
            $action_type = "AddQueryLimitWeb " . $action_type;
        }
        $new_query_limits = $emd_user_transaction_allow_row->queries_limit + $request['queries'];
        $emd_user_transaction_allow_row->queries_limit = (int) $new_query_limits < 0 ? 0 : $new_query_limits;
        $emd_user_transaction_allow_row->save();
        $this->emd_user_profile_comment_controller->add_profile_comment(array('user_id' => $user_id, 'action_type' => $action_type, 'detail' => $request['queries'] . " -- " . $request['detail']));
        return true;
    }
    public function emd_update_user_info($request, $user_id): array
    {
        $email = $this->user_model->where('email', $request['email'])->where('id', '!=', $user_id)->withTrashed()->first();
        if ($email) {
            return [false, 'Email already exists'];
        } else {
            $user = $this->user_model->where('id', $user_id)->first();
            $user->email = $request['email'];
            $user->name = $request['name'];
            $user->save();
            $this->emd_user_profile_comment_controller->add_profile_comment(array('user_id' => $user->id, 'action_type' => 'ProfileUpdate', 'detail' => $request['detail']));
            return [true, 'Successfully Update'];
        }
    }
    public function emd_deactive_user_account($request, $user_id): bool
    {
        $user = $this->user_model->where('id', $user_id)->first();
        $user->delete();
        $this->emd_user_profile_comment_controller->add_profile_comment(array('user_id' => $user_id, 'action_type' => 'AccountDeActive', 'detail' => $request['detail']));
        return true;
    }
    public function get_user_ip_detail($ip): object
    {
        $data = [];
        try {
            $response = Http::get('http://ip-api.com/json/' . $ip);
            $res = $response->collect()->only(['query', 'country', 'city', 'org'])->toJson();
            $data['country'] = json_decode($res)->country;
            $data['query'] = json_decode($res)->query;
            $data['city'] = json_decode($res)->city;
            $data['org'] = json_decode($res)->org;
        } catch (\Throwable $th) {
            $data['country'] = 'none';
            $data['query'] = $ip;
            $data['city'] = 'none';
            $data['org'] = 'none';
        }
        return json_decode(json_encode($data));
    }
    public static function UserDevice(): string
    {
        $device = match (true) {
            Agent::isDesktop() => 'Desktop',
            Agent::isTablet() => 'Tablet',
            Agent::isMobile() => 'Mobile',
            default => 'None',
        };
        return $device;
    }
    public function get_user_device_detail(): string
    {
        return $this->UserDevice();
    }
    public function emd_change_user_password($request, $user_id): bool
    {
        $this->user_model->where('id', $user_id)->update(['password' => Hash::make($request['password'])]);
        $this->emd_user_profile_comment_controller->add_profile_comment(array('user_id' => $user_id, 'action_type' => 'PasswordUpdate', 'detail' => $request['detail']));
        return true;
    }
    public function emd_callback_from_google($request, $ip): bool
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = $this->user_model->where('email', $user->email)->first();
            if ($finduser) {
                Auth::guard('web_user_sess')->login($finduser);
                return true;
            } else {
                $is_already = $this->user_model->onlyTrashed()->where('email', $user->email)->first();
                if ($is_already) {
                    return false;
                } else {
                    DB::beginTransaction();
                    try {
                        $cookie = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 16);
                        $new_user['name'] = $user->name;
                        $new_user['email'] = $user->email;
                        $new_user['password'] = Hash::make($user->name);
                        $new_user['hash'] = $cookie;
                        $new_user['admin_level'] = $this->user_model::WEB_REGISTER;
                        $new_user['email_verified_at'] = date("Y-m-d");
                        $register_user = $this->user_model->create($new_user);

                        $device_name = $this->get_user_device_detail();
                        $ip_detail = $this->get_user_ip_detail($ip);

                        $web_user['user_id'] = $register_user->id;
                        $web_user['social_id'] = $user->id;
                        $web_user['register_from'] = 'google';
                        $web_user['api_key'] = sha1(md5($user->email . ":" . time()));
                        $web_user['is_web_premium'] = $this->emd_web_user_model::FREE_USER;
                        $web_user['is_api_premium'] = $this->emd_web_user_model::FREE_USER;
                        $web_user['ip'] = @$ip ?: '';
                        $web_user['country'] = @$ip_detail?->country ?: '';
                        $web_user['city'] = @$ip_detail->city ?: '';
                        $web_user['browser'] = Agent::browser() ?: '';
                        $web_user['device'] = @$device_name ?: '';
                        $this->emd_web_user_model->create($web_user);

                        $register_plan_available = $this->emd_pricing_plan_model->where('is_custom', $this->emd_pricing_plan_model::REGISTERED_PLAN)->active()->first();
                        if ($register_plan_available) {
                            $data2['product_no'] = $register_plan_available->paypro_product_id;
                            $data2['order_status'] = $this->emd_user_transaction_model::OS_REGISTER_PLAN;
                            EmdUserTransactionRepository::assign_plan_to_user($data2, $register_user->id);
                            $this->emd_web_user_model->where('user_id', $register_user->id)->update(['is_web_premium' => $this->emd_web_user_model::FREE_PLAN_USER]);
                        }

                        Auth::guard('web_user_sess')->login($register_user);
                        DB::commit();
                        return true;
                    } catch (\Exception $e) {
                        DB::rollback();
                        Auth::guard('web_user_sess')->logout();
                        return false;
                    }
                }
            }
        } catch (Exception $e) {
            info($e->getMessage());
            return false;
        }
    }
    public function emd_web_user_logout(): bool
    {
        if (auth()->guard('web_user_sess')->check()) {
            Auth::guard('web_user_sess')->logout();
        }
        return true;
    }

    public function emd_register_with_web($request, $ip): array
    {
        $user = $this->user_model->where('email', $request['email']);
        $finduser = $user->first();
        $trashuser = $user->onlyTrashed()->first();
        if ($finduser || $trashuser) {
            return [false, config('emd-response-string.email_already_registered')];
        }
        DB::beginTransaction();
        try {
            $cookie = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 16);
            $new_user['name'] = $request['name'];
            $new_user['email'] = $request['email'];
            $new_user['password'] = Hash::make($request['password']);
            $new_user['hash'] = $cookie;
            $new_user['admin_level'] = $this->user_model::WEB_REGISTER;
            $emd_email_setting = $this->emd_email_setting_model->where('email_type', $this->emd_email_setting_model::NEW_ACCOUNT_EMAIL)->first();
            if ($emd_email_setting->is_active) {
                $token = substr(sha1(md5($request['email'] . ":" . time())), 0, 90);
                $new_user['remember_token'] = $token;
                $emd_verify_account_slug = @get_setting_by_key("emd_verify_account_slug")->value ?: "emd-verify-account";
                $verify_link = url($emd_verify_account_slug) . "/" . $token;
                $link_url = "<a href='{$verify_link}'>{$verify_link}</a>";
                $html_body = @$emd_email_setting->template ?: "Hi @name Click to link for verify account @verify_link";
                $html_body = str_replace("@name", $request['name'], $html_body);
                $html_body = str_replace("@email", $request['email'], $html_body);
                $html_body = str_replace("@verify_link", $link_url, $html_body);
                $this->emd_send_email_controller::sendToEmail($emd_email_setting->sender_email, $emd_email_setting->send_from, $emd_email_setting->subject, $html_body, $request['email']);
            } else {
                $new_user['email_verified_at'] = date("Y-m-d");
            }
            $register_user = $this->user_model->create($new_user);

            $device_name = $this->get_user_device_detail();
            $ip_detail = $this->get_user_ip_detail($ip);

            $web_user['user_id'] = $register_user->id;
            $web_user['social_id'] = 0;
            $web_user['register_from'] = 'web';
            $web_user['api_key'] = sha1(md5($request['email'] . ":" . time()));
            $web_user['is_web_premium'] = $this->emd_web_user_model::FREE_USER;
            $web_user['is_api_premium'] = $this->emd_web_user_model::FREE_USER;
            $web_user['ip'] = @$ip ?: '';
            $web_user['country'] = @$ip_detail?->country ?: '';
            $web_user['city'] = @$ip_detail?->city ?: '';
            $web_user['browser'] = Agent::browser() ?: '';
            $web_user['device'] = @$device_name ?: '';
            $this->emd_web_user_model->create($web_user);

            $register_plan_available = $this->emd_pricing_plan_model->where('is_custom', $this->emd_pricing_plan_model::REGISTERED_PLAN)->active()->first();
            if ($register_plan_available) {
                $data2['product_no'] = $register_plan_available->paypro_product_id;
                $data2['order_status'] = $this->emd_user_transaction_model::OS_REGISTER_PLAN;
                EmdUserTransactionRepository::assign_plan_to_user($data2, $register_user->id);
                $this->emd_web_user_model->where('user_id', $register_user->id)->update(['is_web_premium' => $this->emd_web_user_model::FREE_PLAN_USER]);
            }

            Auth::guard('web_user_sess')->login($register_user);
            DB::commit();
            return [true, config('emd-response-string.register_done')];
        } catch (\Exception $e) {
            DB::rollback();
            Auth::guard('web_user_sess')->logout();
            return [false, config('emd-response-string.register_try_again')];
        }
    }
    public function emd_verify_user_account($token): array
    {
        if ($token == null) {
            return [false, config('emd-response-string.verify_account_token_required')];
        }
        $finduser = $this->user_model->where('remember_token', $token)->where('admin_level', $this->user_model::WEB_REGISTER)->first();
        if ($finduser) {
            $finduser->email_verified_at = date("Y-m-d");
            $finduser->remember_token = null;
            $finduser->save();
            return [true, config('emd-response-string.verify_account_done')];
        } else {
            return [false, config('emd-response-string.verify_account_invalid_token')];
        }
    }
    public function emd_update_user_password($request): array
    {
        $finduser = $this->user_model->where('email', auth()->guard('web_user_sess')->user()->email)->where('admin_level', $this->user_model::WEB_REGISTER)->first();
        if ($finduser) {
            if (password_verify($request['password'], $finduser->password)) {
                $finduser->password = Hash::make($request['new_password']);
                $finduser->save();
                return [true, config('emd-response-string.pass_update_done')];
            } else {
                return [false, config('emd-response-string.invalid_old_pass')];
            }
        } else {
            return [false, config('emd-response-string.update_pass_email_not_avail')];
        }
    }
    public function emd_login_with_web($request): array
    {
        $finduser = $this->user_model->where('email', $request['email'])->where('admin_level', $this->user_model::WEB_REGISTER)->first();
        if ($finduser) {
            if (password_verify($request['password'], $finduser->password)) {
                Auth::guard('web_user_sess')->login($finduser);
                return [true, config('emd-response-string.login_done')];
            } else {
                return [false, config('emd-response-string.login_pass_wrong')];
            }
        } else {
            return [false, config('emd-response-string.login_email_not_avail')];
        }
    }
    public function emd_forgot_password($request): array
    {
        $finduser = $this->user_model->where('email', $request['email'])->where('admin_level', $this->user_model::WEB_REGISTER)->first();
        if ($finduser) {
            $emd_email_setting = $this->emd_email_setting_model->where('email_type', $this->emd_email_setting_model::FORGOT_EMAIL)->first();
            if ($emd_email_setting->is_active) {
                $token = substr(sha1(md5($finduser->email . time())), 0, 90);
                $finduser->remember_token = $token;
                $finduser->save();
                $emd_forgot_password_slug = @get_setting_by_key("emd_forgot_password_slug")->value ?: "emd-forgot-password";
                $forgot_link = url($emd_forgot_password_slug) . "/" . $token;
                $link_url = "<a href='{$forgot_link}'>{$forgot_link}</a>";
                $html_body = @$emd_email_setting->template ?: "Hi @name Click to link for forgot password @forgot_link";
                $html_body = str_replace("@name", $finduser->name, $html_body);
                $html_body = str_replace("@email", $finduser->email, $html_body);
                $html_body = str_replace("@forgot_url", $link_url, $html_body);
                $this->emd_send_email_controller::sendToEmail($emd_email_setting->sender_email, $emd_email_setting->send_from, $emd_email_setting->subject, $html_body, $request['email']);
                return [true, config('emd-response-string.check_emaiL_for_forgot_pass')];
            }
            return [true, config('emd-response-string.forgot_pass_send_email_not_active')];
        } else {
            return [false, config('emd-response-string.forgot_pass_email_not_avail')];
        }
    }
    public function emd_user_account_delete(): array
    {
        $finduser = $this->user_model->where('id', auth()->guard('web_user_sess')->user()->id)->where('admin_level', $this->user_model::WEB_REGISTER)->first();
        if ($finduser) {
            $emd_delete_account_email_setting = $this->emd_email_setting_model->where('email_type', $this->emd_email_setting_model::DELETE_ACCOUNT)->first();
            if ($emd_delete_account_email_setting->is_active) {
                $html_body = @$emd_delete_account_email_setting->template ?: "<p>Hi @name</p><p>Your Account Successfully deleted</p>";
                $html_body = str_replace("@name", $finduser->name, $html_body);
                $html_body = str_replace("@email", $finduser->email, $html_body);
                $this->emd_send_email_controller::sendToEmail($emd_delete_account_email_setting->sender_email, $emd_delete_account_email_setting->send_from, $emd_delete_account_email_setting->subject, $html_body, $finduser->email);
            }
            $emd_cancel_membership_email_setting = $this->emd_email_setting_model->where('email_type', $this->emd_email_setting_model::CANCEL_MEMBERSHIP)->first();
            if ($this->EmdIsUserPremium() == 1 && $emd_cancel_membership_email_setting != null) {
                $emd_user_transaction = $this->emd_user_transaction_model->where('user_id', $finduser->id);
                if ($emd_user_transaction->latest()->first()?->renewal_type == $this->emd_user_transaction_model::RENEWAL_AUTO) {
                    $html_body_2 = @$emd_cancel_membership_email_setting->template ?: "<p>Hi @name</p><p>account email @account_email</p><p>All transaction order no @order_no</p><p>Request for website @website plan cancel</p><p>Request for API @api plan cancel</p>";
                    $html_body_2 = str_replace("@name", $finduser->name, $html_body_2);
                    $html_body_2 = str_replace("@account_email", $finduser->email, $html_body_2);
                    $html_body_2 = str_replace("@website", ($request['website'] ?? 'no'), $html_body_2);
                    $html_body_2 = str_replace("@api", ($request['api'] ?? 'no'), $html_body_2);
                    $html_body_2 = str_replace("@order_no", ($emd_user_transaction->pluck('order_no')), $html_body_2);
                    $html_body_2 = $html_body_2 . "<p>Must Cancel Membership because User Delete his/her Account. OR Restore his/her Account from Admin Side</p>";
                    $this->emd_send_email_controller::sendToEmail($finduser->email, $emd_cancel_membership_email_setting->send_from, $emd_cancel_membership_email_setting->subject, $html_body_2, $emd_cancel_membership_email_setting->receiver_email);
                }
            }
            $finduser->delete();
            return [true, config('emd-response-string.account_delete_done')];
        } else {
            return [false, config('emd-response-string.delete_account_not_avail')];
        }
    }
    public function emd_cancel_plan_membership($request): array
    {
        $emd_email_setting = $this->emd_email_setting_model->where('email_type', $this->emd_email_setting_model::CANCEL_MEMBERSHIP)->first();
        if ($emd_email_setting->is_active) {
            $finduser = $this->user_model->where('id', auth()->guard('web_user_sess')->user()->id)->where('admin_level', $this->user_model::WEB_REGISTER)->first();
            $emd_user_transaction = $this->emd_user_transaction_model->where('user_id', $finduser->id)->pluck('order_no');
            $html_body = @$emd_email_setting->template ?: "<p>Hi @name</p><p>account email @account_email</p><p>All transaction order no @order_no</p><p>Request for website @website plan cancel</p><p>Request for API @api plan cancel</p>";
            $html_body = str_replace("@name", $finduser->name, $html_body);
            $html_body = str_replace("@account_email", $finduser->email, $html_body);
            $html_body = str_replace("@website", ($request['website'] ?? 'no'), $html_body);
            $html_body = str_replace("@api", ($request['api'] ?? 'no'), $html_body);
            $html_body = str_replace("@order_no", ($emd_user_transaction), $html_body);
            $this->emd_send_email_controller::sendToEmail($finduser->email, $emd_email_setting->send_from, $emd_email_setting->subject, $html_body, $emd_email_setting->receiver_email);
            return [true, config('emd-response-string.plan_cancellation_request_send')];
        }
        return [false, config('emd-response-string.plan_cancellation_request_pending')];
    }
    public function emd_reset_password($request, $token): array
    {
        $token = $token;
        if ($token == null) {
            $token = $request['token'];
        }
        if ($token == null) {
            return [false, config('emd-response-string.reset_pass_token_required')];
        }
        $finduser = $this->user_model->where('remember_token', $token)->where('admin_level', $this->user_model::WEB_REGISTER)->first();
        if ($finduser) {
            $finduser->password = Hash::make($request['password']);
            $finduser->remember_token = null;
            $finduser->save();
            return [true, config('emd-response-string.reset_pass_done')];
        } else {
            return [false, config('emd-response-string.reset_pass_invalid_token')];
        }
    }
    public static function EmdIsUserPremium(int $web = 0, int $api = 0, string | null $api_key = null): int
    {
        if ($api_key != null) {
            if ($web) {
                return EmdWebUser::where("api_key", $api_key ?: '')->first()?->is_web_premium ?: 0;
            } else {
                return EmdWebUser::where("api_key", $api_key ?: '')->first()?->is_api_premium ?: 0;
            }
        }
        if (auth()->guard('web_user_sess')->check()) {
            if ($api) {
                return EmdWebUser::where("user_id", auth()->guard('web_user_sess')->id())->first()?->is_api_premium ?: 0;
            } else {
                return EmdWebUser::where("user_id", auth()->guard('web_user_sess')->id())->first()?->is_web_premium ?: 0;
            }
        } else {
            return 0;
        }
    }
    public static function EmdAvailableQuery(int $api = 0, int $both_web_api = 0, bool $separate = false, string | null $api_key = null): int | array
    {
        if (auth()->guard('web_user_sess')->check() || $api_key != null) {
            if ($api_key != null) {
                $user_id = EmdWebUser::where('api_key', $api_key)->where('is_api_premium', EmdWebUser::PREMIUM_USER)->first()?->user_id ?: 0;
            } else {
                $user_id = auth()->guard('web_user_sess')->id();
            }

            $is_api = match (true) {
                $both_web_api => [EmdPricingPlan::WEB_PLAN, EmdPricingPlan::API_PLAN, EmdPricingPlan::WEB_AND_API_PLAN],
                $api => [EmdPricingPlan::API_PLAN, EmdPricingPlan::WEB_AND_API_PLAN],
                default => [EmdPricingPlan::WEB_PLAN, EmdPricingPlan::WEB_AND_API_PLAN],
            };
            $queries_check = EmdUserTransactionAllow::select(['queries_limit', 'queries_used'])->whereHas('emd_user_transaction', function ($query) use ($user_id, $is_api) {
                $query->where('user_id', $user_id)->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', EmdUserTransaction::OS_PROCESSED)->where('expiry_date', '>=', date("Y-m-d"))->whereHas('emd_pricing_plan', function ($innerQuery) use ($is_api) {
                    $innerQuery->whereIn('is_api', $is_api);
                });
            })->where('queries_limit', '>', 1)->get();
            if ($separate) {
                return [@$queries_check->sum('queries_limit') ?: 0, @$queries_check->sum('queries_used') ?: 0];
            }
            $remaining_query = $queries_check->sum('queries_limit') - $queries_check->sum('queries_used');
            return $remaining_query > 0 ? $remaining_query : 0;
        } else {
            return 0;
        }
    }
    public static function emd_available_query_row(int | null $tool_id, int $user_id, bool $type = true, int $api = 0): ?EmdUserTransactionAllow
    {
        $is_api = match ((int) $api) {
            1 => [EmdPricingPlan::API_PLAN, EmdPricingPlan::WEB_AND_API_PLAN],
            default => [EmdPricingPlan::WEB_PLAN, EmdPricingPlan::WEB_AND_API_PLAN]
        };
        $is_available_plan = EmdUserTransactionAllow::whereHas('emd_user_transaction', function ($query) use ($is_api) {
            return $query->whereHas('emd_pricing_plan', function ($query1) use ($is_api) {
                return $query1->whereIn('is_api', $is_api);
            })->where('expiry_date', '>=', date("Y-m-d"))->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', EmdUserTransaction::OS_PROCESSED);
        });
        if ($tool_id != null) {
            $is_available_plan = $is_available_plan->where('tool_id', $tool_id ?: 0);
        }
        $is_available_plan = $is_available_plan->where('user_id', $user_id)->whereColumn('queries_limit', '>', 'queries_used');
        if ($type) {
            return $is_available_plan->first();
        } else {
            return $is_available_plan->skip(1)->first();
        }
    }
    public static function EmdWebsiteQueryUse(int | null $tool_id = null, int $query_no = 1, string | null $api_key = null, bool $error_mess = false, bool $query_use = true): bool | array
    {
        if (auth()->guard('web_user_sess')->check() || $api_key != null) {
            $for_api_or_web = EmdPricingPlan::WEB_PLAN;
            if ($api_key != null) {
                $is_api_premium = EmdWebUser::where('api_key', $api_key)->where('is_api_premium', EmdWebUser::PREMIUM_USER)->first();
                if (!$is_api_premium) {
                    return $error_mess ? [false, config('emd-response-string.not_premium_for_api')] : false;
                }
                $user_id = $is_api_premium->user_id;
                $for_api_or_web = EmdPricingPlan::API_PLAN;
            } else {
                $user_id = auth()->guard('web_user_sess')->id();
                $is_web_premium = EmdWebUser::where('user_id', $user_id)->where('is_web_premium', EmdWebUser::PREMIUM_USER)->first();
                if ($is_web_premium == null) {
                    $is_web_premium = EmdWebUser::where('user_id', $user_id)->where('is_web_premium', EmdWebUser::FREE_PLAN_USER)->first();
                }
            }

            if (@$is_web_premium || @$is_api_premium) {
                $is_available_query_row_first = EmdWebUserRepository::emd_available_query_row(tool_id: $tool_id, user_id: $user_id, api: $for_api_or_web);
                if ($is_available_query_row_first) {
                    if ((int) $is_available_query_row_first->queries_limit != 1) {
                        if (EmdWebUserRepository::EmdAvailableQuery(api: $for_api_or_web, api_key: $api_key) < $query_no) {
                            return $error_mess ? [false, config('emd-response-string.query_limit_is_less')] : false;
                        }
                        $first_query_deduction = $is_available_query_row_first->queries_used + $query_no;
                        if (($is_available_query_row_first->queries_limit - $is_available_query_row_first->queries_used) < $query_no) {
                            $remaining_previous_row_query = $query_no - ($is_available_query_row_first->queries_limit - $is_available_query_row_first->queries_used);
                            $is_available_query_row_second = EmdWebUserRepository::emd_available_query_row(tool_id: $tool_id, user_id: $user_id, type: false, api: $for_api_or_web);
                            if ($is_available_query_row_second) {
                                $is_available_query_row_second->queries_used = $is_available_query_row_second->queries_used + $remaining_previous_row_query;
                                if ($query_use) {
                                    $is_available_query_row_second->save();
                                }
                                $first_query_deduction = $is_available_query_row_first->queries_limit;
                            } else {
                                return $error_mess ? [false, config('emd-response-string.query_limit_is_less')] : false;
                            }
                        }
                        $is_available_query_row_first->queries_used = $first_query_deduction;
                        if ($query_use) {
                            $is_available_query_row_first->save();
                        }
                    }
                    return $error_mess ? [true, $query_no > 0 ? config('emd-response-string.query_detected') : config('emd-response-string.query_add_to_back')] : true;
                } else {
                    $complete_use_plan_row = EmdUserTransaction::whereHas('emd_pricing_plan', function ($query_run) use ($for_api_or_web) {
                        return $query_run->whereIn('is_api', [$for_api_or_web, EmdPricingPlan::WEB_AND_API_PLAN]);
                    });
                    if ($tool_id != null) {
                        $complete_use_plan_row = $complete_use_plan_row->whereHas('emd_user_transaction_allows', function ($query_check) use ($tool_id) {
                            return $query_check->where('tool_id', $tool_id);
                        });
                    }
                    $complete_use_plan_row = $complete_use_plan_row->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', EmdUserTransaction::OS_PROCESSED)->where('expiry_date', '>=', date("Y-m-d"))->where('user_id', $user_id)->first();
                    if ($complete_use_plan_row) {
                        $complete_use_plan_row->is_refund = EmdUserTransaction::TRAN_EXP_USED;
                        $complete_use_plan_row->save();
                    }
                    if (@$is_api_premium) {
                        $is_api_premium->is_api_premium = EmdWebUser::FREE_USER;
                        $is_api_premium->save();
                    } else {
                        $is_web_premium->is_web_premium = EmdWebUser::FREE_USER;
                        $is_web_premium->save();
                    }
                    return $error_mess ? [false, config('emd-response-string.query_not_avail_set_free_mode')] : false;
                }
            } else {
                return $error_mess ? [true, config('emd-response-string.not_premium_user')] : true;
            }
        } else {
            return $error_mess ? [true, config('emd-response-string.free_user')] : true;
        }
    }
    public function web_users_export_page(): array
    {
        return EmdEmailCampaignRepository::count_email_status_wise();
    }
}
