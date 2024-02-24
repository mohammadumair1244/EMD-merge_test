<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdEmailSettingInterface;
use Illuminate\Http\Request;

class EmdEmailSettingController extends Controller
{
    public function __construct(protected EmdEmailSettingInterface $emd_email_setting_interface)
    {

    }
    public function emd_email_setting_page()
    {
        $this->authorize('view_email_setting');
        return view('admin.emd-email-setting.index')->with(['email_settings' => $this->emd_email_setting_interface->emd_email_setting_page()]);
    }
    public function emd_email_setting_req(Request $request, $type)
    {
        $this->authorize('add_update_email_setting');
        $this->emd_email_setting_interface->emd_email_setting_req($request, $type);
        return back();
    }
    public function emd_email_setting_type_page($type)
    {
        $this->authorize('view_email_setting');
        return view('admin.emd-email-setting.type')->with(['email_setting' => $this->emd_email_setting_interface->emd_email_setting_type_page($type)]);
    }
}
