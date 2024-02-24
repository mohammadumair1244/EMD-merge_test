<?php
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::middleware('emd_tool_api_key')->group(function () {
    Route::post('get-tools-list', [ToolController::class, 'get_tools_list_for_api']);
});

Route::post('emd-version', function () {
    return response()->json([
        'version' => config('constants.version') ?? "",
        'commit_no' => config('constants.commit_no') ?? 0,
        'app_name' => config('app.name') ?? "",
    ]);
})->middleware('emd_version_api_key');
