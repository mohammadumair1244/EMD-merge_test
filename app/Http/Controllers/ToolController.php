<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view_tool');
        $tools = Tool::select('id', 'name', 'parent_id', 'lang', 'slug')->with('parent')->oldest()->get();
        return view('admin.tools.index')->with('tools', $tools);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('add_tool');
        $all_files = array_reverse(Storage::files('media'));
        $parent = Tool::select('id', 'name')->whereColumn('id', 'parent_id')->get();
        if ($parent) {
            return view('admin.tools.add')->with('parent', $parent);
        }
        $html = '';
        if (count($all_files) > 0) {
            foreach ($all_files as $image) {
                $html .= view('admin.partials.gallary_image')->with('image', $image)->render();
            }
            return view('admin.tools.add')->with('images', $html);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('add_tool');
        $request->validate([
            'is_home' => 'unique:tools,is_home',
            'slug' => 'unique:tools,slug',
        ]);
        $contentKey = !empty($request->contentKey) ? $request->contentKey : '';
        $contentValue = !empty($request->contentKey) ? $request->contentValue : '';
        $inputType = !empty($request->contentKey) ? $request->inputType : '';
        $contentArr = [];
        if (!empty($contentKey) && !empty($contentValue)) {
            for ($i = 0; $i < count($contentKey); $i++) {
                $contentArr[$contentKey[$i]]['type'] = $inputType[$i];
                $contentArr[$contentKey[$i]]['value'] = $contentValue[$i];
            }
        }
        $is_home = 0;
        if ($request->has('is_home')) {
            $is_home = $request->is_home;
        }
        if ($request->parent == 0) {
            $content_json = json_encode($contentArr);
            if (!File::exists(resource_path('views/layout/frontend/pages/emd-tools-pages/' . $request->slug . '.blade.php')) && config('app.env') == 'local' && $is_home == 0) {
                File::put(resource_path('views/layout/frontend/pages/emd-tools-pages/' . $request->slug . '.blade.php'), "@extends('main')
                    @section('content')
                    @endsection");
            }
        } else {
            $content_json = Tool::select('content')->where('id', $request->parent)->get();
            $content_json = $content_json[0]->content;
        }

        $tool = Tool::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'lang' => $request->lang,
            'parent_id' => $request->parent,
            'content' => $content_json,
            'is_home' => $is_home,
        ]);

        if ($request->parent == 0) {
            Tool::where('id', $tool->id)->update(['parent_id' => $tool->id]);
        }
        $created_tool = Tool::where('id', $tool->id)->first();
        $this->update_tool_redis($created_tool);
        if ($tool) {
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function show(Tool $tool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function edit(Tool $tool)
    {
        $this->authorize('edit_tool');
        $images = Media::get_media();
        $parent = Tool::select('id', 'name')->whereColumn('id', 'parent_id')->get();
        $tools = Tool::get();
        return view('admin.tools.edit')->with([
            'tool' => $tool,
            'parent' => $parent,
            'images' => $images,
            'tools' => $tools,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tool $tool)
    {
        $this->authorize('edit_tool');
        $tool->name = $request->name;
        if ($request->filled('slug')) {
            $tool->slug = $request->slug;
        }
        $tool->meta_title = $request->meta_title;
        $tool->meta_description = $request->meta_description;
        if (@$request->parent_id) {
            $tool->parent_id = $request->parent_id;
        }
        if (@$request->request_limit) {
            $tool->request_limit = $request->request_limit;
            Tool::where('parent_id', $tool->id)->update(['request_limit' => $request->request_limit]);
        }
        $contentKey = !empty($request->contentKey) ? $request->contentKey : '';
        $contentValue = !empty($request->contentKey) ? $request->contentValue : '';
        $inputType = !empty($request->contentKey) ? $request->inputType : '';
        $contentArr = [];
        if (!empty($contentKey) && !empty($contentValue)) {
            for ($i = 0; $i < count($contentKey); $i++) {
                $regex = '/[^A-Za-z1-9\_]/gi';
                $contentKey[$i] = Str::replace($regex, "_", $contentKey[$i]);
                $contentArr[$contentKey[$i]]['type'] = $inputType[$i];
                $contentArr[$contentKey[$i]]['value'] = $contentValue[$i];
            }
        }
        $content_json = json_encode($contentArr);
        $tool->content = $content_json;
        $tool->save();
        $this->update_tool_redis($tool);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tool $tool)
    {
        $this->authorize('delete_tool');
        $this->delete_tool_redis($tool);
        $tool->delete();
        return back();
    }
    public function parent_tools()
    {
        $this->authorize('view_tool');
        $tools = Tool::select('id', 'name', 'parent_id', 'lang', 'slug')->with('parent')->whereColumn('id', 'parent_id')->oldest()->get();
        return view('admin.tools.parent_view')->with('tools', $tools);
    }
    public function parent_wise_child_tools($parent_id)
    {
        $this->authorize('view_tool');
        $tools = Tool::select('id', 'name', 'parent_id', 'lang', 'slug')->with('parent')->where('parent_id', $parent_id)->orderBy('id', "ASC")->get();
        return view('admin.tools.child_view')->with('tools', $tools);
    }
    public function trash_list()
    {
        $this->authorize('view_trash_tool');
        $tools = Tool::onlyTrashed()->get();
        return view('admin.tools.trash')->with(['tools' => $tools]);
    }
    public function tool_permanent_destroy($id)
    {
        $this->authorize('delete_tool');
        $tool = Tool::onlyTrashed()->find($id);
        $this->delete_tool_redis($tool);
        Tool::onlyTrashed()->find($id)->forceDelete();
        return back();
    }

    public function tool_restore($id)
    {
        $this->authorize('restore_tool');
        $tool = Tool::withTrashed()->find($id);
        $this->update_tool_redis($tool);
        Tool::withTrashed()->find($id)->restore();
        return back();
    }

    public function download(Tool $tool)
    {
        if ($tool) {
            header('Content-Type: application/json');
            $content = $tool['content'];
            $content_to_store = json_encode(json_decode($content), JSON_PRETTY_PRINT);
            $target = "tool_content";
            if (!Storage::exists($target)) {
                Storage::makeDirectory($target, 0777, true, true);
            }
            $fileName = "content_" . time() . ".json";
            $path = $target . '/' . $fileName;
            Storage::path($path);
            if (Storage::put($target . '/' . $fileName, $content_to_store)) {
                return Storage::download($path);
            }
        }
    }
    public function upload_tool_content(Request $request, Tool $tool)
    {
        $type = (int) $request->get('btnradio');
        if ($type == 1) {
            $array1 = json_decode($tool->content, true);
            $array2 = json_decode($request->upload_json, true);
            $mergedJson = json_encode(array_merge($array1, $array2));
            $tool->content = $mergedJson;
        } else if ($type == 2) {
            $tool->content = $request->upload_json;
        }
        $tool->save();
        $this->update_tool_redis($tool);
        return redirect()->back();
    }
    public function tool_audit($id)
    {
        $audits = Tool::find($id)->audits->reverse();
        return view('admin.tools.audit')->with([
            'audits' => $audits,
        ]);
    }
    public function add_tool_key($id)
    {
        $this->authorize('edit_tool');
        $tool = Tool::find($id);
        if ($tool->id != $tool->parent_id) {
            return redirect()->route('tool.index');
        }
        return view('admin.tools.add-key')->with(['tool' => $tool]);
    }
    public function create_tool_key(Request $request, $id, $type)
    {
        $this->authorize('edit_tool');
        $tools = Tool::where('parent_id', $id)->get();
        foreach ($tools as $tool) {
            foreach (json_decode($tool->content, true) as $key => $value) {
                $contentKey = str_replace(' ', '', $request->contentKey);
                if ($key != $contentKey) {

                    $data = json_decode($tool->content, true);
                    $data[$contentKey] = [
                        'type' => $type,
                        'value' => $request->contentValue,
                    ];
                    $new_content = json_encode($data);
                    $tool->content = $new_content;
                    $tool->save();
                    break 1;
                }
            }
            $this->update_tool_redis($tool);
        }
        return redirect()->back()->with('message', 'Successfully Add Key in all child tool');

    }
    public function key_translate(Request $request)
    {
        $this->authorize('tool_translation_btn');
        try {
            $respn = Http::asForm()->post('http://143.110.156.45:7011/trans', [
                'text' => $request->content_text,
                'dlang' => $request->lang,
            ]);
        } catch (\Throwable $th) {
            $respn = $request->content_text;
        }

        return $respn;
    }
    public function all_key_translate(Request $request)
    {
        $this->authorize('tool_translation_btn');
        $current_tool = Tool::where('id', $request->tool_id)->first();
        $parent_tool = Tool::where('id', $current_tool->parent_id)->first();
        $translated_content = json_decode('', true);
        $condkd = [];
        foreach (json_decode($parent_tool->content, true) as $key => $value) {
            $original_content = $value['value'];
            try {
                $respn = Http::asForm()->post('http://143.110.156.45:7011/trans', [
                    'text' => $original_content,
                    'dlang' => $request->lang,
                ]);
            } catch (\Throwable $th) {
                $respn = $original_content;
            }
            $translated_content[$key] = [
                'type' => $value['type'],
                'value' => (string) $respn,
            ];
        }
        $current_tool->content = json_encode($translated_content);
        $current_tool->save();
        return true;
    }

    public function get_tools_list_for_api(Request $request)
    {
        if (@$request->lang && @$request->slug) {
            $tools = Tool::select('name', 'slug', 'content', 'lang', 'meta_title', 'meta_description')->where('lang', $request->lang)->where('slug', $request->slug)->get();
        } else {
            $tools = Tool::select('name', 'lang', 'slug')->get();
        }
        return response()->json(['tools' => $tools]);
    }

    public function emd_tool_get_page()
    {
        $this->authorize('get_live_tool');
        $tools = [];
        if (config('emd_setting_keys.emd_tool_api_route_for_live') != "" && config('emd_setting_keys.emd_tool_api_key_for_live') != "") {
            $token = trim(config('emd_setting_keys.emd_tool_api_key_for_live'));
            $base_url = trim(config('emd_setting_keys.emd_tool_api_route_for_live'));
            $response = Http::withToken($token)->post($base_url . "/api/get-tools-list");
            if ($response->successful()) {
                $tools = $response->json()['tools'];
            }
        }
        return view('admin.emd-live-tool.index')->with(['tools' => $tools]);
    }
    public function emd_get_single_tool_api(Request $request)
    {
        $this->authorize('get_live_tool');
        if (config('emd_setting_keys.emd_tool_api_route_for_live') != "" && config('emd_setting_keys.emd_tool_api_key_for_live') != "") {
            $token = trim(config('emd_setting_keys.emd_tool_api_key_for_live'));
            $base_url = trim(config('emd_setting_keys.emd_tool_api_route_for_live'));
            $response = Http::withToken($token)->post($base_url . "/api/get-tools-list", [
                'lang' => $request->d_lang,
                'slug' => $request->d_slug,
            ]);
            if ($response->successful()) {
                $tool = $response->json()['tools'][0];
                Tool::updateOrCreate(
                    ['lang' => $tool['lang'], 'slug' => $tool['slug']],
                    ['meta_description' => $tool['meta_description'], 'meta_title' => $tool['meta_title'], 'content' => $tool['content'], 'name' => $tool['name']]
                );
            }
        }
        return response()->json(['d_loop' => $request->d_loop, 'result' => $tool]);
    }

    public function tool_canonicals_redis(int $parent_id)
    {
        try {
            $links = Tool::select('name', 'slug', 'lang')->where('parent_id', $parent_id)->get();
            Redis::del('tool_canonicals_redis_' . $parent_id);
            Redis::set('tool_canonicals_redis_' . $parent_id, json_encode($links->toArray()));
        } catch (\Throwable $th) {

        }
    }

    public function update_tool_redis($tool)
    {
        if ($tool->is_home == 1) {
            try {
                Redis::del('home_tool_native');
                Redis::hMset('home_tool_native', $tool->toArray());
            } catch (\Throwable $th) {

            }
        }
        if (config('constants.native_languge') == $tool->lang) {
            try {
                Redis::del('other_tool_native_' . $tool->slug);
                Redis::hMset('other_tool_native_' . $tool->slug, $tool->toArray());
            } catch (\Throwable $th) {

            }
        }
        if (config('constants.native_languge') != $tool->lang) {
            try {
                Redis::del('other_lang_tool_' . $tool->lang . "_" . $tool->slug);
                Redis::hMset('other_lang_tool_' . $tool->lang . "_" . $tool->slug, $tool->toArray());
            } catch (\Throwable $th) {

            }
        }
        $this->tool_canonicals_redis(parent_id: (int) $tool->parent_id);
    }

    public function delete_tool_redis($tool)
    {
        if ($tool->is_home == 1) {
            try {
                Redis::del('home_tool_native');
            } catch (\Throwable $th) {

            }
        }
        if (config('constants.native_languge') == $tool->lang) {
            try {
                Redis::del('other_tool_native_' . $tool->slug);
            } catch (\Throwable $th) {

            }
        }
        if (config('constants.native_languge') != $tool->lang) {
            try {
                Redis::del('other_lang_tool_' . $tool->lang . "_" . $tool->slug);
            } catch (\Throwable $th) {

            }
        }
        $this->tool_canonicals_redis(parent_id: (int) $tool->parent_id);
    }

}
