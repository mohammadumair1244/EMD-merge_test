<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }

    public function emd_chat_page()
    {
        $this->authorize('view_chat');
        $setting = Setting::where('key', 'emd_chat_status')->first();
        if ($setting == null) {
            $chat_status = 0;
        } else {
            $chat_status = $setting->value;
        }
        return view('admin.emd-chat-on-off')->with("chat_status", $chat_status);
    }

    public function emd_chat_req(Request $request)
    {
        $this->authorize('on_off_chat');
        Setting::where('key', 'emd_chat_status')->update(['value' => $request->chat_status]);
        return back();
    }

    public function emd_laravel_log_page()
    {
        $logFilePath = storage_path('logs/laravel.log');
        $available = 0;
        if (File::exists($logFilePath)) {
            $available = 1;
        }
        return view('admin.emd-laravel-log')->with("available", $available);
    }

    public function emd_laravel_log_delete(Request $request)
    {
        $logFilePath = storage_path('logs/laravel.log');
        if (File::exists($logFilePath)) {
            File::delete($logFilePath);
        }
        return back();
    }
    public function emd_laravel_log_download(Request $request)
    {
        $logFilePath = storage_path('logs/laravel.log');
        if (File::exists($logFilePath)) {
            return response()->download($logFilePath, 'laravel.log');
        }
        return back();
    }
    public function emd_laravel_log_read(){
        $logFilePath = storage_path('logs/laravel.log');
        if (File::exists($logFilePath)) {
            $logFilePath = File::get($logFilePath);
                return view('admin.read-log-file')->with([
                    'log_content' => $logFilePath,
                ]);
        }
        return back();
    }
}
