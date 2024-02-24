<?php
namespace App\Interfaces;

interface EmdEmailSettingInterface
{
    public function emd_email_setting_page();
    public function emd_email_setting_req($request, $type);
    public function emd_email_setting_type_page($type);
}
