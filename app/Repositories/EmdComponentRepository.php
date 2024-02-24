<?php
namespace App\Repositories;

use App\Interfaces\EmdComponentInterface;
use App\Models\EmdComponent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class EmdComponentRepository implements EmdComponentInterface
{
    public function __construct(protected EmdComponent $emd_component_model)
    {
    }

    public function view_page(): EmdComponent | Collection
    {
        return $this->emd_component_model->withCount('self_child')->whereColumn('id', 'parent_id')->get();
    }
    public function add_page(): EmdComponent | Collection
    {
        return $this->emd_component_model->select('id', 'name')->whereColumn('id', 'parent_id')->get();
    }
    public function add_req($request): bool
    {
        $contentKey = !empty($request['contentKey']) ? $request['contentKey'] : '';
        $contentValue = !empty($request['contentKey']) ? $request['contentValue'] : '';
        $inputType = !empty($request['contentKey']) ? $request['inputType'] : '';
        $contentArr = [];
        if (!empty($contentKey) && !empty($contentValue)) {
            for ($i = 0; $i < count($contentKey); $i++) {
                $regex = '/[^A-Za-z1-9\_]/gi';
                $contentKey[$i] = Str::replace($regex, "_", $contentKey[$i]);
                $contentArr[$contentKey[$i]]['type'] = $inputType[$i];
                $contentArr[$contentKey[$i]]['value'] = $contentValue[$i];
            }
        }

        $data['lang'] = $request['lang'];
        $data['name'] = $request['name'];
        if ((int) $request['parent_id'] == 0) {
            $data['json_body'] = json_encode($contentArr);
            $data['name'] = $request['name'];
            $data['key'] = $request['key'];
            $res = $this->emd_component_model->create($data);
            $res->parent_id = $res->id;
            $res->save();
        } else {
            $parent_row = $this->emd_component_model->where('id', $request['parent_id'])->first();
            $dublicate_row = $parent_row->replicate();
            $data['key'] = $dublicate_row->key;
            $data['parent_id'] = $dublicate_row->parent_id;
            $data['json_body'] = $dublicate_row->json_body;
            $this->emd_component_model->create($data);
        }
        return true;
    }
    public function delete_link($id): bool
    {
        $this->emd_component_model->destroy($id);
        return true;
    }
    public function restore_link($id): bool
    {
        $this->emd_component_model->withTrashed()->find($id)->restore();
        return true;
    }
    public function trash_view_page(): EmdComponent | Collection
    {
        return $this->emd_component_model->onlyTrashed()->get();
    }
    public function edit_page($id): ?EmdComponent
    {
        return $this->emd_component_model->where('id', $id)->first();
    }
    public function edit_req($request, $id): bool
    {
        $contentKey = !empty($request['contentKey']) ? $request['contentKey'] : '';
        $contentValue = !empty($request['contentKey']) ? $request['contentValue'] : '';
        $inputType = !empty($request['contentKey']) ? $request['inputType'] : '';
        $contentArr = [];
        if (!empty($contentKey) && !empty($contentValue)) {
            for ($i = 0; $i < count($contentKey); $i++) {
                $regex = '/[^A-Za-z1-9\_]/gi';
                $contentKey[$i] = Str::replace($regex, "_", $contentKey[$i]);
                $contentArr[$contentKey[$i]]['type'] = $inputType[$i];
                $contentArr[$contentKey[$i]]['value'] = $contentValue[$i];
            }
        }
        $data['json_body'] = json_encode($contentArr);
        $data['name'] = $request['name'];
        $data['key'] = $request['key'];
        $data['lang'] = $request['lang'];
        $this->emd_component_model->where('id', $id)->update($data);
        return true;
    }
    public function child_page($id): EmdComponent | Collection
    {
        return $this->emd_component_model->with('self_parent')->where('parent_id', $id)->get();
    }
    public function get_component($request): array
    {
        $result = $this->emd_component_model->where('key', $request['key'])->where('lang', $request['lang'])->first();
        if ($result) {
            return ['error' => false, 'result' => $result];
        } else {
            $result_key = $this->emd_component_model->where('key', $request['key'])->first();
            if ($result_key) {
                return ['error' => false, 'result' => $result_key];
            }
            return ['error' => true, 'result' => $result];
        }
    }
}
