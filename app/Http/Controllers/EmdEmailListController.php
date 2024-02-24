<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdEmailListInterface;
use Illuminate\Http\Request;

class EmdEmailListController extends Controller
{
    public function __construct(protected EmdEmailListInterface $emd_email_list_interface)
    {

    }
    public function emd_email_list_page()
    {
        $this->authorize('email_campaign_email_list');
        return view('admin.emd-email-campaign.emails')->with(['emails' => $this->emd_email_list_interface->emd_email_list_page()]);
    }
    public function emd_email_list_create(Request $request)
    {
        $this->authorize('email_campaign_email_list_upload');
        $request->validate([
            'email_csv' => 'required|mimes:csv,txt',
        ]);
        $this->emd_email_list_interface->emd_email_list_create($request);
        return back();
    }
    public function emd_email_list_delete($id)
    {
        $this->authorize('email_campaign_email_list_delete');
        $this->emd_email_list_interface->emd_email_list_delete($id);
        return back();
    }
}
