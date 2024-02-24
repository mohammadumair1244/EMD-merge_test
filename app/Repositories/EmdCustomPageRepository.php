<?php
namespace App\Repositories;

use App\Interfaces\EmdCustomPageInterface;
use App\Models\EmdCustomPage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmdCustomPageRepository implements EmdCustomPageInterface
{
    public function __construct(protected EmdCustomPage $emd_custom_page_model)
    {
    }

    public function view_page(): EmdCustomPage | Collection
    {
        return $this->emd_custom_page_model->get();
    }
    public function create_page($request): bool
    {
        $contentKey = !empty($request['contentKey']) ? $request['contentKey'] : '';
        $contentValue = !empty($request['contentKey']) ? $request['contentValue'] : '';
        $inputType = !empty($request['contentKey']) ? $request['inputType'] : '';
        $contentArr = [];
        if (!empty($contentKey) && !empty($contentValue)) {
            for ($i = 0; $i < count($contentKey); $i++) {
                $contentArr[$contentKey[$i]]['type'] = $inputType[$i];
                $contentArr[$contentKey[$i]]['value'] = $contentValue[$i];
            }
        }
        $content_json = json_encode($contentArr);
        $request['content'] = $content_json;
        $blade_file_name = Str::lower(str_replace(" ", "_", $request['blade_file']));
        $this->emd_custom_page_model->create($request->except(['_token', 'inputType', 'contentKey', 'contentValue', 'add_more_type']));
        if (!File::exists(resource_path('views/layout/frontend/pages/emd-custom-pages/' . $blade_file_name . '.blade.php')) && config('app.env') == 'local') {
            File::put(resource_path('views/layout/frontend/pages/emd-custom-pages/' . $blade_file_name . '.blade.php'), "@extends('main')
            @section('content')
            @endsection");

            $filePath = base_path('routes/custom_pages.php');
            $fileContent = File::get($filePath);
            $targetLine = "Route::controller(EmdCustomPageDisplayController::class)->group(function () {";
            $newCode = "Route::get('" . $request['slug'] . "', 'custom_page_display')->name('EmdCustomPage." . $blade_file_name . "');";
            $position = strpos($fileContent, $targetLine);
            if ($position !== false) {
                $modifiedContent = substr_replace($fileContent, $newCode . PHP_EOL, $position + strlen($targetLine) + 1, 0);
                File::put($filePath, $modifiedContent);
            }
        }
        return true;
    }
    public function trash_page(): EmdCustomPage | Collection
    {
        return $this->emd_custom_page_model->onlyTrashed()->get();
    }
    public function destroy($id): bool
    {
        $this->emd_custom_page_model->destroy($id);
        return true;
    }
    public function restore($id): bool
    {
        $this->emd_custom_page_model->withTrashed()->find($id)->restore();
        return true;
    }
    public function edit_page($id): ?EmdCustomPage
    {
        return $this->emd_custom_page_model->where('id', $id)->first();
    }
    public function update_page($request, $id): bool
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
        $request['content'] = json_encode($contentArr);
        $this->emd_custom_page_model->where('id', $id)->update($request->except(['_token', 'inputType', 'contentKey', 'contentValue', 'add_more_type', 'blade_file', 'slug', 'page_key']));
        return true;
    }
    public function download_content($id): array
    {
        $custom_page = $this->emd_custom_page_model->where('id', $id)->first();
        if ($custom_page) {
            header('Content-Type: application/json');
            $content = $custom_page['content'];
            $content_to_store = json_encode(json_decode($content), JSON_PRETTY_PRINT);
            $target = "page_content";
            if (!Storage::exists($target)) {
                Storage::makeDirectory($target, 0777, true, true);
            }
            $fileName = "content_" . time() . ".json";
            $path = $target . '/' . $fileName;
            Storage::path($path);
            return ['target' => $target, 'fileName' => $fileName, 'path' => $path, 'content_to_store' => $content_to_store];
        } else {
            return ['target' => '', 'fileName' => '', 'path' => '', 'content_to_store' => ''];
        }
    }
    public function upload_content($request, $id): bool
    {
        $custom_page = $this->emd_custom_page_model->where('id', $id)->first();
        $type = (int) $request['btnradio'];
        if ($type == 1) {
            $array1 = json_decode($custom_page->content, true);
            $array2 = json_decode($request->upload_json, true);
            $mergedJson = json_encode(array_merge($array1, $array2));
            $custom_page->content = $mergedJson;
        } else if ($type == 2) {
            $custom_page->content = $request->upload_json;
        }
        $custom_page->save();
        return true;
    }

}
