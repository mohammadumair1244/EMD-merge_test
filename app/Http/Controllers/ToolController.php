<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;
use Smalot\PdfParser\Parser;

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
        $tool->delete();
        return back();
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
        Tool::onlyTrashed()->find($id)->forceDelete();
        return back();
    }

    public function tool_restore($id)
    {
        $this->authorize('restore_tool');
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
        if (@get_setting_by_key('emd_tool_api_route_for_live')->value != "" && @get_setting_by_key('emd_tool_api_key_for_live')->value != "") {
            $token = trim(get_setting_by_key('emd_tool_api_key_for_live')->value);
            $base_url = trim(get_setting_by_key('emd_tool_api_route_for_live')->value);
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
        if (@get_setting_by_key('emd_tool_api_route_for_live')->value != "" && @get_setting_by_key('emd_tool_api_key_for_live')->value != "") {
            $token = trim(get_setting_by_key('emd_tool_api_key_for_live')->value);
            $base_url = trim(get_setting_by_key('emd_tool_api_route_for_live')->value);
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

    public function getText(Request $request)
    {

        $file = $request->file('file');

        $validExtensions = ['docx', 'txt' , 'pdf'];
        $extension = $file->getClientOriginalExtension();
        if (!in_array($extension, $validExtensions)) {
            return response()->json(['error' => 'Extensión de archivo inválida. Las extensiones permitidas son: docx, txt y pdf','head'=>'Archivo inválido'], 400);
        }

        $path = $request->file('file')->storeAs(
            'files',
            time() . '_' . $request->file('file')->getClientOriginalName()
        );

        if ($request->file('file')->getClientOriginalExtension() == 'docx') {
            $text = $this->docx_to_txt($path);
            Storage::delete($path);
            return $text;
        } elseif ($request->file('file')->getClientOriginalExtension() == 'doc') {
            $text = $this->docx_to_txt($request, "doc");
            Storage::delete($path);
            return $text;
        } elseif ($request->file('file')->getClientOriginalExtension() == 'txt') {
            $text = $this->readTXTFile($path);
            Storage::delete($path);
            return $text;
        } elseif ($request->file('file')->getClientOriginalExtension() == 'pdf') {
            $text = $this->get_text_pdf($path);
            Storage::delete($path);
            return $text;
        }
    }

    protected function docx_to_txt($filePath)
    {
        $zip = new ZipArchive;
        $dataFile = 'word/document.xml';
        
        if (true === $zip->open(Storage::path($filePath))) {
            if (($index = $zip->locateName($dataFile)) !== false) {
                $data = $zip->getFromIndex($index);
                $zip->close();
                
                // Use regular expression to extract text content
                preg_match_all('/<w:t [^>]*>(.*?)<\/w:t>/', $data, $matches);
                $text = implode(' ', $matches[1]);
                $text = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $text);
                $text = str_replace('</w:r></w:p>', "\r\n", $text);
                $text = str_replace("<br />", "\n", $text);
                $text = strip_tags($text);
                
                Storage::delete($filePath);
                
                return nl2br($text);
            }
        } else {
            Storage::delete($filePath);
            return 0;
        }
    }    

    protected function get_text_pdf($filePath)
    {
        $parser = new Parser();
        $parsedText = $parser->parseFile(Storage::path($filePath))->getText();
        return $parsedText;
    }

    protected function readTXTFile($filePath)
    {
        $text = file_get_contents(Storage::path($filePath));
        return $text;
    }
    // public function docx_to_txt($filePath)
    // {
    //     $zip = new ZipArchive;
    //     $dataFile = 'word/document.xml';
    //     if (true === $zip->open(Storage::path($filePath))) {
    //         if (($index = $zip->locateName($dataFile)) !== false) {
    //             $data = $zip->getFromIndex($index);
    //             $zip->close();
    //             $data = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $data);
    //             $data = str_replace('</w:r></w:p>', "\r\n", $data);
    //             $data = strip_tags($data);
    //             Storage::delete($filePath);
    //             return $data;
    //         }
    //     } else {
    //         Storage::delete($filePath);
    //         return 0;
    //     }
    // }
    
    // public function doc_to_text($path)
    // {
    //     $fileHandle = fopen(Storage::path($path), "r");
    //     $line = @fread($fileHandle, Storage::size($path));
    //     $lines = explode(chr(0x0D), $line);
    //     $outtext = "";
    //     foreach ($lines as $thisline) {
    //         $pos = strpos($thisline, chr(0x00));
    //         if (($pos !== false) || (strlen($thisline) == 0)) {
    //         } else {
    //             $outtext .= $thisline . " ";
    //         }
    //     }
    //     Storage::delete($path);
    //     return $outtext = preg_replace("/[^a-zA-Z0-9\s,.-\r\t@/_()]/", "", $outtext);
    // }
}
