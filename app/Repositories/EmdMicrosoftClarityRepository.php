<?php
namespace App\Repositories;

use App\Interfaces\EmdMicrosoftClarityInterface;
use App\Models\EmdCustomPage;
use App\Models\EmdMicrosoftClarity;
use App\Models\Tool;

class EmdMicrosoftClarityRepository implements EmdMicrosoftClarityInterface
{
    public function __construct(
        protected EmdMicrosoftClarity $emd_microsoft_clarity_model,
        protected EmdCustomPage $emd_custom_page_model,
        protected Tool $tool_model) {
    }
    public function view_page(): array
    {
        return [
            'custom_pages' => $this->emd_custom_page_model->get(),
            'tools' => $this->tool_model->select("id", "name")->whereColumn('id', 'parent_id')->get(),
            'emd_microsoft_clarity' => $this->emd_microsoft_clarity_model->with('tool', 'emd_custom_page')->get(),
        ];
    }
    public function add_req($request): bool
    {
        $request['is_tool_pages'] = 0;
        $request['is_custom_pages'] = 0;
        if ($request['field_type'] == "all_pages") {
            $request['is_tool_pages'] = 1;
            $request['is_custom_pages'] = 1;
            $request['emd_custom_page_id'] = 0;
            $request['tool_id'] = 0;
        } else if ($request['field_type'] == "tool_pages") {
            $request['is_tool_pages'] = 1;
            $request['emd_custom_page_id'] = 0;
            $request['tool_id'] = 0;
        } else if ($request['field_type'] == "custom_pages") {
            $request['is_custom_pages'] = 1;
            $request['emd_custom_page_id'] = 0;
            $request['tool_id'] = 0;
        } else {
            if ($request['tool_or_custom_page'] == "tool") {
                $request['tool_id'] = $request['tool_id'];
                $request['emd_custom_page_id'] = 0;
            } else {
                $request['emd_custom_page_id'] = $request['emd_custom_page_id'];
                $request['tool_id'] = 0;
            }
        }
        $allow_json = [];
        foreach ($request['keys'] as $key => $val) {
            $allow_json[$val] = (int) $request['default_values'][$key];
        }
        $request['clarity_json'] = json_encode($allow_json);

        $this->emd_microsoft_clarity_model->create($request->except(['_token', 'field_type', 'tool_or_custom_page', 'keys', 'default_values']));
        $this->create_microsoft_clarity_file();
        return true;
    }

    public static function clarity_get(int | null $emd_tool_id = null, int | null $emd_custom_page_id = null)
    {
        $device = EmdWebUserRepository::UserDevice();
        $device_start = match ($device) {
            "Desktop" => "D",
            "Tablet" => "T",
            "Mobile" => "M",
            default => "N",
        };

        $check_user_premium = EmdWebUserRepository::EmdIsUserPremium() == 1 ? 1 : 0;
        if ($emd_tool_id != null) {
            $specific_tool = config('emd_microsoft_clarity.T' . $emd_tool_id . $device_start . $check_user_premium) ?? 0;
            $all_tools = config('emd_microsoft_clarity.TA' . $device_start . $check_user_premium) ?? 0;
            return $all_tools > $specific_tool ? $all_tools : $specific_tool;
        }

        if ($emd_custom_page_id != null) {
            $specific_tool = config('emd_microsoft_clarity.T' . $emd_custom_page_id . $device_start . $check_user_premium) ?? 0;
            $all_tools = config('emd_microsoft_clarity.TA' . $device_start . $check_user_premium) ?? 0;
            return $all_tools > $specific_tool ? $all_tools : $specific_tool;
        }

        return 0;
    }
    public function delete_link($id): bool
    {
        $this->emd_microsoft_clarity_model->destroy($id);
        $this->create_microsoft_clarity_file();
        return true;
    }
    public function create_microsoft_clarity_file()
    {
        $microsoft_clarities = $this->emd_microsoft_clarity_model->get();
        $configData = [];
        foreach ($microsoft_clarities as $item) {
            $clarity_allow = json_decode($item->clarity_json);
            if ($item->tool_id != 0) {
                if ($clarity_allow->Mobile) {
                    $configData["T" . $item->tool_id . "M" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
                if ($clarity_allow->Desktop) {
                    $configData["T" . $item->tool_id . "D" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
                if ($clarity_allow->Tablet) {
                    $configData["T" . $item->tool_id . "T" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
            }
            if ($item->emd_custom_page_id != 0) {
                if ($clarity_allow->Mobile) {
                    $configData["C" . $item->emd_custom_page_id . "M" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
                if ($clarity_allow->Desktop) {
                    $configData["C" . $item->emd_custom_page_id . "D" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
                if ($clarity_allow->Tablet) {
                    $configData["C" . $item->emd_custom_page_id . "T" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
            }
            if ($item->is_tool_pages != 0) {
                if ($clarity_allow->Mobile) {
                    $configData["TA" . "M" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
                if ($clarity_allow->Desktop) {
                    $configData["TA" . "D" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
                if ($clarity_allow->Tablet) {
                    $configData["TA" . "T" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
            }
            if ($item->is_custom_pages != 0) {
                if ($clarity_allow->Mobile) {
                    $configData["CA" . "M" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
                if ($clarity_allow->Desktop) {
                    $configData["CA" . "D" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
                if ($clarity_allow->Tablet) {
                    $configData["CA" . "T" . $clarity_allow->PremiumUser] = $item->show_percentage;
                }
            }
        }
        $configPath = config_path('emd_microsoft_clarity.php');
        file_put_contents($configPath, '<?php return ' . var_export($configData, true) . ';');
        return true;
    }

}
