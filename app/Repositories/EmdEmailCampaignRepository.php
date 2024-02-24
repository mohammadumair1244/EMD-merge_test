<?php
namespace App\Repositories;

use App\Http\Controllers\EmdSendEmailController;
use App\Interfaces\EmdEmailCampaignInterface;
use App\Models\EmdEmailCampaign;
use App\Models\EmdEmailList;
use App\Models\EmdEmailTemplate;
use App\Models\EmdUserTransaction;
use App\Models\User;

class EmdEmailCampaignRepository implements EmdEmailCampaignInterface
{
    public function __construct(
        protected User $user_model,
        protected EmdEmailCampaign $emd_email_campaign_model,
        protected EmdEmailTemplate $email_email_template_model,
        protected EmdEmailList $email_email_list_model) {

    }
    public function view_users_page($paginate = true)
    {
        $usersQuery = $this->user_model
            ->with('emd_user_transactions')
            ->where('admin_level', $this->user_model::WEB_REGISTER);
        if ($paginate) {
            $usersQuery = $usersQuery->paginate(100);
            $users = $usersQuery->getCollection();
        } else {
            $users = $usersQuery->get();
        }

        $users = $users->map(function ($user) {
            if ($user->emd_user_transactions->where('is_test_mode', '!=', EmdUserTransaction::REGISTER_MODE)->isNotEmpty()) {
                if ($user->emd_user_transactions->where('expiry_date', '>=', date("Y-m-d"))->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', EmdUserTransaction::OS_PROCESSED)->count() > 0) {
                    $user->is_user_status = 'Premium';
                } else if ($user->emd_user_transactions->where('order_status', EmdUserTransaction::OS_PROCESSED)->count() == $user->emd_user_transactions->where('order_status', EmdUserTransaction::OS_REFUNDED)->count()) {
                    $user->is_user_status = 'Refunded';
                } else {
                    $user->is_user_status = 'Expired';
                }
            } else {
                $user->is_user_status = 'Free';
            }
            unset($user->emd_user_transactions);
            return $user;
        });
        if ($paginate) {
            return $usersQuery->setCollection($users);
        } else {
            return $users;
        }

    }
    public static function count_email_status_wise(): array
    {
        $get_user_status_count = User::with('emd_user_transactions')
            ->where('admin_level', User::WEB_REGISTER)
            ->get();
        $premium = 0;
        $expired = 0;
        $free = 0;
        $refunded = 0;
        foreach ($get_user_status_count as $user) {
            if ($user->emd_user_transactions->where('is_test_mode', '!=', EmdUserTransaction::REGISTER_MODE)->isNotEmpty()) {
                if ($user->emd_user_transactions->where('expiry_date', '>=', date("Y-m-d"))->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', EmdUserTransaction::OS_PROCESSED)->count() > 0) {
                    $premium++;
                } else if ($user->emd_user_transactions->where('order_status', EmdUserTransaction::OS_PROCESSED)->count() == $user->emd_user_transactions->where('order_status', EmdUserTransaction::OS_REFUNDED)->count()) {
                    $refunded++;
                } else {
                    $expired++;
                }
            } else {
                $free++;
            }
        }
        return ['Premium' => $premium, 'Expired' => $expired, 'Free' => $free, 'Refunded' => $refunded];
    }
    public static function get_email_with_user_status($user_status): string
    {
        $user_status_and_emails = [];
        $get_user_status_count = User::with('emd_user_transactions')
            ->where('admin_level', User::WEB_REGISTER)
            ->get();
        foreach ($get_user_status_count as $user) {
            if ($user->emd_user_transactions->where('is_test_mode', '!=', EmdUserTransaction::REGISTER_MODE)->isNotEmpty()) {
                if ($user->emd_user_transactions->where('expiry_date', '>=', date("Y-m-d"))->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', EmdUserTransaction::OS_PROCESSED)->count() > 0) {
                    array_push($user_status_and_emails, (object) ['status' => 'Premium', 'email' => $user->email]);
                } else if ($user->emd_user_transactions->where('order_status', EmdUserTransaction::OS_PROCESSED)->count() == $user->emd_user_transactions->where('order_status', EmdUserTransaction::OS_REFUNDED)->count()) {
                    array_push($user_status_and_emails, (object) ['status' => 'Refunded', 'email' => $user->email]);
                } else {
                    array_push($user_status_and_emails, (object) ['status' => 'Expired', 'email' => $user->email]);
                }
            } else {
                array_push($user_status_and_emails, (object) ['status' => 'Free', 'email' => $user->email]);
            }
        }
        $user_emails = '';
        $user_status = explode(",", $user_status);
        foreach ($user_status_and_emails as $value) {
            if (in_array($value->status, $user_status)) {
                $user_emails .= $value->email . ",";
            }
        }
        return substr($user_emails, 0, -1);
    }
    public function add_page(): array
    {
        return [
            'user_status' => $this->view_users_page(paginate: false)->groupBy('is_user_status'),
            'campaign_lists' => $this->emd_email_campaign_model->with('emd_email_template')->get(),
            'email_templates' => $this->email_email_template_model->get(),
            'emails_list' => $this->email_email_list_model->get()->groupBY('title'),
        ];
    }
    public function create_page($request): bool
    {
        $total_emails = 0;
        $array_key = ['Premium', 'Expired', 'Free', 'Refunded'];
        foreach ($request['user_status'] as $val) {
            if (in_array($val, $array_key)) {
                $total_emails += $this->count_email_status_wise()[$val];
            } else {
                $total_emails += $this->email_email_list_model->where('title', $val)->count();
            }
        }
        $request["total_emails"] = $total_emails;
        $request["user_status"] = implode(",", $request['user_status']);
        $this->emd_email_campaign_model->create($request->except('_token'));
        return true;
    }
    public function change_status($id, $status): bool
    {
        $this->emd_email_campaign_model->where('id', $id)->update(['status' => $status]);
        return true;
    }
    public function send_test_email($request): bool
    {
        $email_campaign = $this->emd_email_campaign_model->with('emd_email_template')->where('id', $request['id'])->first();
        if ($email_campaign) {
            EmdSendEmailController::sendBccEmail($email_campaign->from_email, $email_campaign->from_name, $email_campaign->from_subject, $email_campaign->emd_email_template->body, $email_campaign->testing_email);
        }
        return true;
    }

}
