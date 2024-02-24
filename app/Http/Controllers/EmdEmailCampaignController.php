<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdEmailCampaignInterface;
use Illuminate\Http\Request;

class EmdEmailCampaignController extends Controller
{
    public function __construct(protected EmdEmailCampaignInterface $emd_email_campaign_interface)
    {

    }
    public function view_users_page()
    {
        $this->authorize('email_campaign_user_list');
        return view('admin.emd-email-campaign.users')->with([
            'users' => $this->emd_email_campaign_interface->view_users_page(),
        ]);
    }
    public function add_page()
    {
        $this->authorize('email_campaign_manage');
        $add_page_repo = $this->emd_email_campaign_interface->add_page();
        return view('admin.emd-email-campaign.add')->with([
            'user_status' => $add_page_repo['user_status'],
            'emails_list' => $add_page_repo['emails_list'],
            'campaign_lists' => $add_page_repo['campaign_lists'],
            'email_templates' => $add_page_repo['email_templates'],
        ]);
    }
    public function create_page(Request $request)
    {
        $this->authorize('email_campaign_add');
        $this->emd_email_campaign_interface->create_page($request);
        return back();
    }
    public function change_status($id, $status)
    {
        $this->authorize('email_campaign_change_status');
        $this->emd_email_campaign_interface->change_status($id, $status);
        return back();
    }
    public function send_test_email(Request $request)
    {
        $this->authorize('email_campaign_change_status');
        $this->emd_email_campaign_interface->send_test_email($request);
        return back();
    }
}
