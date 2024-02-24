<?php
namespace App\Repositories;

use App\Interfaces\EmdEmailSettingInterface;
use App\Models\EmdEmailSetting;
use Illuminate\Database\Eloquent\Collection;

class EmdEmailSettingRepository implements EmdEmailSettingInterface
{
    public function __construct(protected EmdEmailSetting $emd_email_setting_model)
    {
    }

    public function emd_email_setting_page(): EmdEmailSetting | Collection
    {
        return $this->emd_email_setting_model->get();
    }
    public function emd_email_setting_req($request, $type): bool
    {
        $this->emd_email_setting_model->where('email_type', $type)->update($request->except("_token"));
        return true;
    }
    public function emd_email_setting_type_page($type): ?EmdEmailSetting
    {
        return $this->emd_email_setting_model->where('email_type', $type)->first();
    }
}
