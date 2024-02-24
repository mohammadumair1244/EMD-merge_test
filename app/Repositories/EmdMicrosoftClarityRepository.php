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
        return true;
    }

    public static function clarity_get(int | null $emd_tool_id = null, int | null $emd_custom_page_id = null)
    {
        if ($emd_tool_id !== null) {
            $clarity_row = EmdMicrosoftClarity::where('tool_id', $emd_tool_id);
            if ($emd_tool_id == 0) {
                $clarity_row = $clarity_row->where('is_tool_pages', 1);
            }
        }

        if ($emd_custom_page_id !== null) {
            $clarity_row = EmdMicrosoftClarity::where('emd_custom_page_id', $emd_custom_page_id);
            if ($emd_custom_page_id == 0) {
                $clarity_row = $clarity_row->where('is_custom_pages', 1);
            }
        }

        $device = EmdWebUserRepository::UserDevice();
        if ($device == "Desktop") {
            $clarity_row = $clarity_row->whereJsonContains('clarity_json->Desktop', 1);
        } else if ($device == "Tablet") {
            $clarity_row = $clarity_row->whereJsonContains('clarity_json->Tablet', 1);
        } else if ($device == "Mobile") {
            $clarity_row = $clarity_row->whereJsonContains('clarity_json->Mobile', 1);
        }
        $clarity_row = $clarity_row->whereJsonContains('clarity_json->PremiumUser', EmdWebUserRepository::EmdIsUserPremium())->latest()->first();
        if ($clarity_row == null && $emd_tool_id !== null && $emd_tool_id != 0) {
            return EmdMicrosoftClarityRepository::clarity_get(emd_tool_id: 0);
        } else if ($clarity_row == null && $emd_custom_page_id !== null && $emd_custom_page_id != 0) {
            return EmdMicrosoftClarityRepository::clarity_get(emd_custom_page_id: 0);
        } else {
            return $clarity_row;
        }
    }
    public function delete_link($id): bool
    {
        $this->emd_microsoft_clarity_model->destroy($id);
        return true;
    }

}
