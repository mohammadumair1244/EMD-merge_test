<?php
namespace App\Repositories;

use App\Interfaces\EmdCustomFieldInterface;
use App\Models\EmdCustomField;
use App\Models\EmdCustomPage;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Collection;

class EmdCustomFieldRepository implements EmdCustomFieldInterface
{
    public function __construct(
        protected EmdCustomField $emd_custom_field_model,
        protected Tool $tool_model,
        protected EmdCustomPage $emd_custom_page_model) {
    }

    public function view_page(): EmdCustomField | Collection
    {
        return $this->emd_custom_field_model->with('tool', 'emd_custom_page')->get();
    }
    public function add_page(): array
    {
        return [
            'custom_pages' => $this->emd_custom_page_model->get(),
            'tools' => $this->tool_model->select("id", "name")->whereColumn('id', 'parent_id')->get(),
        ];
    }
    public function add_req($request): bool
    {
        $request['is_all_pages'] = 0;
        $request['is_tool_pages'] = 0;
        $request['is_custom_pages'] = 0;
        if ($request['field_type'] == "all_pages") {
            $request['is_all_pages'] = 1;
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
        $this->emd_custom_field_model->create($request->except(['_token', 'field_type', 'tool_or_custom_page']));
        return true;
    }
    public function delete_link($id): bool
    {
        $this->emd_custom_field_model->destroy($id);
        return true;
    }
    public function restore_link($id): bool
    {
        $this->emd_custom_field_model->withTrashed()->find($id)->restore();
        return true;
    }
    public function trash_view_page(): EmdCustomField | Collection
    {
        return $this->emd_custom_field_model->onlyTrashed()->get();
    }
    public function edit_page($id): array
    {
        $add_page_data = $this->add_page();
        return [
            'emd_custom_field' => $this->emd_custom_field_model->where('id', $id)->first(),
            'custom_pages' => $add_page_data['custom_pages'],
            'tools' => $add_page_data['tools'],
        ];
    }
    public function edit_req($request, $id): bool
    {
        $request['is_all_pages'] = 0;
        $request['is_tool_pages'] = 0;
        $request['is_custom_pages'] = 0;
        if ($request['field_type'] == "all_pages") {
            $request['is_all_pages'] = 1;
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
        $this->emd_custom_field_model->where('id', $id)->update($request->except(['_token', 'field_type', 'tool_or_custom_page']));
        return true;
    }
    public function get_key_tool_filter($request): EmdCustomField | Collection
    {
        return $this->tool_wise_filter($request['tool_id']);
    }
    public static function tool_wise_filter($tool_id): EmdCustomField | Collection
    {
        $tool_key_filter = EmdCustomField::orWhere('is_all_pages', 1)->orWhere('is_tool_pages', 1);
        if ((int) $tool_id == 0) {
            $tool_key_filter = $tool_key_filter->orWhere('tool_id', '!=', 0);
        } else {
            $tool_key_filter = $tool_key_filter->orWhere('tool_id', $tool_id);
        }
        $tool_key_filter = $tool_key_filter->get();
        return $tool_key_filter;
    }
    public static function custom_page_wise_filter($custom_page_id): EmdCustomField | Collection
    {
        $tool_key_filter = EmdCustomField::orWhere('is_all_pages', 1)->orWhere('is_custom_pages', 1);
        if ((int) $custom_page_id == 0) {
            $tool_key_filter = $tool_key_filter->orWhere('emd_custom_page_id', '!=', 0);
        } else {
            $tool_key_filter = $tool_key_filter->orWhere('emd_custom_page_id', $custom_page_id);
        }
        $tool_key_filter = $tool_key_filter->get();
        return $tool_key_filter;
    }
}
