<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmdComponentController;
use App\Http\Controllers\EmdCustomFieldController;
use App\Http\Controllers\EmdCustomPageController;
use App\Http\Controllers\EmdEmailCampaignController;
use App\Http\Controllers\EmdEmailListController;
use App\Http\Controllers\EmdEmailSettingController;
use App\Http\Controllers\EmdEmailTemplateController;
use App\Http\Controllers\EmdFeedbackController;
use App\Http\Controllers\EmdMicrosoftClarityController;
use App\Http\Controllers\EmdPermissionController;
use App\Http\Controllers\EmdPlanZonePriceController;
use App\Http\Controllers\EmdPricingPlanAllowController;
use App\Http\Controllers\EmdPricingPlanController;
use App\Http\Controllers\EmdTransactionLogController;
use App\Http\Controllers\EmdUserPermissionController;
use App\Http\Controllers\EmdUserTransactionController;
use App\Http\Controllers\EmdWebUserController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('hash/{hash}', [AdminController::class, 'setCookie']);
Route::get('{login?}', [AdminController::class, 'index'])->middleware(['admin_access', 'guest'])->where('login', 'login')->name('login');
Route::post('login', [UserController::class, 'login'])->middleware(['admin_access', 'guest'])->where('login', 'login')->name('admin_login');
Route::middleware(['admin_access', 'auth:admin_sess'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('admin.dashboard');
        // SETTING ROUTES
        Route::get('settings', 'settings')->name('dashboard.settings');
        Route::post('settings', 'update_settings')->name('dashboard.update.settings');
        Route::get('settings/delete', 'settings_delete')->name('setting.delete');
    });
    //Blog Routes
    Route::controller(BlogController::class)->prefix('blog')->group(function () {
        Route::get('trash', 'trash_list')->name('blog.trash_list');
        Route::get('permanent_destroy/{id}', 'blog_permanent_destroy')->name('blog.permanent_destroy');
        Route::get('restore/{id}', 'blog_restore')->name('blog.restore');
        Route::post('content_translate', 'content_translate')->name('blog.content_translate');
    });
    Route::resource('blog', BlogController::class);
    //Tool Routes
    Route::controller(ToolController::class)->prefix('tool')->group(function () {
        Route::get('trash', 'trash_list')->name('tool.trash_list');
        Route::get('permanent_destroy/{id}', 'tool_permanent_destroy')->name('tool.permanent_destroy');
        Route::get('restore/{id}', 'tool_restore')->name('tool.restore');
        Route::get('audit/{id}', 'tool_audit')->name('tool.audit');
        // GET AND SET CONTENT
        Route::get('download/content/{tool}', 'download')->name('content.download');
        Route::post('upload/content/{tool}', 'upload_tool_content')->name('content.upload');
        // translate content
        Route::get('add-tool-key/{id}', 'add_tool_key')->name('tool.add_tool_key');
        Route::post('create-tool-key/{id}/{type}', 'create_tool_key')->name('tool.create_tool_key');
        Route::post('key_translate', 'key_translate')->name('tool.key_translate');
        Route::post('all_key_translate', 'all_key_translate')->name('tool.all_key_translate');
    });
    Route::resource('tool', ToolController::class);
    //Media Routes
    Route::controller(MediaController::class)->prefix('media')->group(function () {
        Route::get('trash', 'trash_list')->name('media.trash_list');
        Route::get('permanent_destroy/{id}', 'media_permanent_destroy')->name('media.permanent_destroy');
        Route::get('restore/{id}', 'media_restore')->name('media.restore');
    });
    Route::resource('media', MediaController::class)->only(['create', 'store', 'destroy']);
    // User List Routes
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('trash', 'trash_list')->name('admin.trash_list');
        Route::get('permanent_destroy/{id}', 'user_permanent_destroy')->name('admin.user.permanent_destroy');
        Route::get('restore/{id}', 'user_restore')->name('admin.user.restore');
    });
    Route::resource('user', UserController::class);
    // CONTACT ROUTES
    Route::controller(ContactController::class)->prefix('contact')->group(function () {
        Route::get('trash', 'trash')->name('dashboard.contacts.trash');
        Route::get('restore/{id}', 'restore')->name('dashboard.contacts.restore');
        Route::get('permanent_destroy/{id}', 'contact_permanent_destroy')->name('dashboard.contacts.destroy');
        Route::get('date-filter/{start_date}/{end_date}', 'emd_contact_date_filter_page');
    });
    Route::resource('contact', ContactController::class);
    // EMD Pricing Plans
    Route::prefix('emd-pricing-plan')->controller(EmdPricingPlanController::class)->group(function () {
        Route::get('view', 'view_pricing_plan')->name('emd_pricing_plan_view');
        Route::get('add', 'add_pricing_plan')->name('emd_pricing_plan_add');
        Route::post('create', 'create_pricing_plan')->name('emd_pricing_plan_create');
        Route::get('edit/{id}', 'edit_pricing_plan')->name('emd_pricing_plan_edit');
        Route::post('update/{id}', 'update_pricing_plan')->name('emd_pricing_plan_update');
        Route::get('trash', 'trash_pricing_plan')->name('emd_pricing_plan_trash');
        Route::delete('destroy{id}', 'destroy_pricing_plan')->name('emd_pricing_plan_destroy');
        Route::get('restore/{id}', 'restore_pricing_plan')->name('emd_pricing_plan_restore');
        Route::delete('permanent_delete/{id}', 'permanent_delete_pricing_plan')->name('emd_pricing_plan_permanent_delete');
        Route::post('ordering-no', 'ordering_no_pricing_plan')->name('emd_pricing_plan_ordering_no');
        Route::get('active/{id}/{is_active}', 'emd_pricing_plan_show_hide');

    });
    Route::prefix('emd-zone-pricing/set-price')->controller(EmdPlanZonePriceController::class)->group(function () {
        Route::get('view/{plan_id}', 'view_and_add_zone_pricing')->name('emd_view_and_add_zone_pricing');
        Route::post('add/{plan_id}', 'create_zone_pricing')->name('emd_create_zone_pricing');
        Route::post('edit/{id}', 'update_zone_pricing')->name('emd_update_zone_pricing');
        Route::delete('destroy/{id}', 'destroy_zone_pricing')->name('emd_destroy_zone_pricing');
    });
    // EMD Pricing Plan Query Set
    Route::prefix('emd-pricing-plan/set-query')->controller(EmdPricingPlanAllowController::class)->group(function () {
        Route::get('view/{plan_id}', 'view_and_add_pricing_plan_allow')->name('emd_view_and_add_pricing_plan_allow');
        Route::post('add/{plan_id}', 'create_pricing_plan_allow')->name('emd_create_pricing_plan_allow');
        Route::get('edit/{plan_id}/{id}', 'edit_pricing_plan_allow')->name('emd_edit_pricing_plan_allow');
        Route::post('edit/{plan_id}/{id}', 'update_pricing_plan_allow')->name('emd_update_pricing_plan_allow');
        Route::delete('destroy/{plan_id}/{id}', 'destroy_pricing_plan_allow')->name('emd_destroy_pricing_plan_allow');
    });
    // EMD web users
    Route::prefix('emd-web-user')->controller(EmdWebUserController::class)->group(function () {
        Route::get('view', 'view_web_users')->name('emd_view_web_users');
        Route::post('view', 'emd_user_search_by_email')->name('emd_user_search_by_email');
        Route::get('trash', 'view_web_users_trash')->name('emd_view_web_users_trash');
        Route::get('detail/{id}', 'view_web_user_detail')->name('emd_view_web_user_detail');
        Route::post('detail/{user_id}', 'emd_update_user_info')->name('emd_update_user_info');
        Route::post('add-more-query/{user_id}', 'emd_add_more_user_query')->name('emd_add_more_user_query');
        Route::post('de-active-user/{user_id}', 'emd_deactive_user_account')->name('emd_deactive_user_account');
        Route::get('query-availability/{transaction_id}', 'emd_query_availability')->name('emd_query_availability');
        Route::get('login/{id}', 'user_login_by_admin')->name('user_login_by_admin');
        Route::post('change-password/{user_id}', 'emd_change_user_password')->name('emd_change_user_password');
        Route::get('date-filter/{start_date}/{end_date}', 'emd_web_user_date_filter_page');
        Route::get('export', 'web_users_export_page')->name('web_users_export_page');
        Route::post('export', 'web_users_export_req')->name('web_users_export_req');
    });
    // EMD transaction log start
    Route::prefix('emd-transaction')->controller(EmdTransactionLogController::class)->group(function () {
        Route::get('trans-logs/{order_no?}', 'view_trans_log')->name('emd_transaction_logs_page');
        Route::post('trans-logs', 'view_trans_log_search')->name('view_trans_log_search');
        Route::get('trans-log-detail/{id}', 'view_trans_log_detail')->name('view_trans_log_detail');
        Route::get('download-log-json/{id}', 'download_log_json')->name('download_log_json');
    });
    // EMD user Transaction List
    Route::prefix('emd-transaction')->controller(EmdUserTransactionController::class)->group(function () {
        Route::get('detail/{id}', 'view_single_transaction')->name('emd_single_transaction');
        Route::post('custom-premium/{user_id}', 'emd_custom_premium')->name('emd_custom_premium');
        Route::post('change-plan/{user_id}', 'emd_user_plan_change')->name('emd_user_plan_change');
        Route::get('date-filter/{start_date}/{end_date}', 'emd_transaction_date_filter_page');
        Route::get('search', 'emd_transaction_search_page')->name('emd_transaction_search_page');
        Route::post('search', 'emd_transaction_search_req')->name('emd_transaction_search_req');
        Route::get('{type?}', 'view_all_transaction')->name('emd_all_transaction');
    });
    // EMD get tool data from live website to staging

    // EMD transaction log end
    Route::prefix('emd-live-tool')->controller(ToolController::class)->group(function () {
        Route::get('view', 'emd_tool_get_page')->name('emd_tool_get_page');
        Route::post('single-api', 'emd_get_single_tool_api')->name('emd_get_single_tool_api');
    });
    // EMD Email Settings
    Route::prefix('emd-email-setting')->controller(EmdEmailSettingController::class)->group(function () {
        Route::get('view', 'emd_email_setting_page')->name('emd_email_setting_page');
        Route::get('view/{type}', 'emd_email_setting_type_page')->name('emd_email_setting_type_page');
        Route::post('{type}', 'emd_email_setting_req')->name('emd_email_setting_req');
    });
    Route::prefix('emd-user-permission')->controller(EmdUserPermissionController::class)->group(function () {
        Route::post('allow/{user_id}', 'allow_team_permision_req')->name('allow_team_permision_req');
    });
    //EMD Permission
    Route::prefix('emd-permission')->controller(EmdPermissionController::class)->group(function () {
        Route::get('view', 'view_all_permission_page')->name('view_all_permission_page');
    });
    //EMD Chat On Off
    Route::prefix('emd-chat')->controller(SettingController::class)->group(function () {
        Route::get('view', 'emd_chat_page')->name('emd_chat_page');
        Route::post('view', 'emd_chat_req')->name('emd_chat_req');
    });
    //EMD Laravel Log File
    Route::prefix('emd-laravel-log')->controller(SettingController::class)->group(function () {
        Route::get('view', 'emd_laravel_log_page')->name('emd_laravel_log_page');
        Route::delete('view', 'emd_laravel_log_delete')->name('emd_laravel_log_delete');
        Route::post('view', 'emd_laravel_log_download')->name('emd_laravel_log_download');
        Route::get('read', 'emd_laravel_log_read')->name('emd_laravel_log_read');
    });
    //EMD Feedbacks
    Route::prefix('emd-feedback')->controller(EmdFeedbackController::class)->group(function () {
        Route::get('view', 'emd_feedback_page')->name('emd_feedback_page');
        Route::get('delete/{id}', 'emd_delete_feedback')->name('emd_delete_feedback');
        Route::get('restore/{id}', 'emd_restore_feedback')->name('emd_restore_feedback');
        Route::get('trash', 'emd_trash_feedback_page')->name('emd_trash_feedback_page');
        Route::get('date-filter/{start_date}/{end_date}', 'emd_feedback_date_filter_page');
    });
    // USERS FEEDBACK ROUTES END

    //EMD Email Campaign start route
    Route::prefix('emd-email-campaign')->controller(EmdEmailCampaignController::class)->group(function () {
        Route::get('users', 'view_users_page')->name('campaign.view_users_page');
        Route::get('add', 'add_page')->name('campaign.add_page');
        Route::post('add', 'create_page')->name('campaign.create_page');
        Route::get('change-status/{id}/{status}', 'change_status')->name('campaign.change_status');
        Route::post('send-testing-email', 'send_test_email')->name('campaign.send_test_email');
    });
    Route::prefix('emd-email-campaign')->controller(EmdEmailListController::class)->group(function () {
        Route::get('emails', 'emd_email_list_page')->name('campaign.emd_email_list_page');
        Route::post('emails', 'emd_email_list_create')->name('campaign.emd_email_list_create');
        Route::get('email-delete/{id}', 'emd_email_list_delete')->name('campaign.emd_email_list_delete');
    });
    //EMD Email Campaign end route

    //EMD Email Template start route
    Route::prefix('emd-email-template')->controller(EmdEmailTemplateController::class)->group(function () {
        Route::get('view', 'view_page')->name('template.view_page');
        Route::get('add', 'add_page')->name('template.add_page');
        Route::post('add', 'create_page')->name('template.create_page');
    });
    //EMD Email Template end route

    //EMD Email Template start route
    Route::prefix('emd-custom-page')->controller(EmdCustomPageController::class)->group(function () {
        Route::get('view', 'view_page')->name('custom_page.view_page');
        Route::get('trash', 'trash_page')->name('custom_page.trash_page');
        Route::get('destroy/{id}', 'destroy')->name('custom_page.destroy');
        Route::get('restore/{id}', 'restore')->name('custom_page.restore');
        Route::get('add', 'add_page')->name('custom_page.add_page');
        Route::post('add', 'create_page')->name('custom_page.create_page');
        Route::get('edit/{id}', 'edit_page')->name('custom_page.edit_page');
        Route::post('edit/{id}', 'update_page')->name('custom_page.update_page');
        Route::get('download/content/{id}', 'download_content')->name('custom_page.download_content');
        Route::post('upload/content/{id}', 'upload_content')->name('custom_page.upload_content');
    });
    //EMD Email Template end route

    // EMD Custom Field start
    Route::prefix('emd-custom-field')->controller(EmdCustomFieldController::class)->group(function () {
        Route::get('view', 'view_page')->name('custom_field.view_page');
        Route::get('add', 'add_page')->name('custom_field.add_page');
        Route::post('add', 'add_req')->name('custom_field.add_req');
        Route::get('edit/{id}', 'edit_page')->name('custom_field.edit_page');
        Route::post('edit/{id}', 'edit_req')->name('custom_field.edit_req');
        Route::get('trash', 'trash_view_page')->name('custom_field.trash_view_page');
        Route::get('delete/{id}', 'delete_link')->name('custom_field.delete_link');
        Route::get('restore/{id}', 'restore_link')->name('custom_field.restore_link');
        Route::post('get-key-tool-filter', 'get_key_tool_filter')->name('custom_field.get_key_tool_filter');
    });
    // EMD Custom Field end

    // EMD Component start
    Route::prefix('emd-component')->controller(EmdComponentController::class)->group(function () {
        Route::get('view', 'view_page')->name('component.view_page');
        Route::get('add', 'add_page')->name('component.add_page');
        Route::post('add', 'add_req')->name('component.add_req');
        Route::get('edit/{id}', 'edit_page')->name('component.edit_page');
        Route::post('edit/{id}', 'edit_req')->name('component.edit_req');
        Route::get('trash', 'trash_view_page')->name('component.trash_view_page');
        Route::get('delete/{id}', 'delete_link')->name('component.delete_link');
        Route::get('restore/{id}', 'restore_link')->name('component.restore_link');
        Route::get('child/{id}', 'child_page')->name('component.child_page');
    });
    // EMD Component end

    // EMD Microsoft Clarity start
    Route::prefix('emd-microsoft-clarity')->controller(EmdMicrosoftClarityController::class)->group(function () {
        Route::get('view-add', 'view_page')->name('clarity.view_page');
        Route::post('add', 'add_req')->name('clarity.add_req');
        Route::get('delete/{id}', 'delete_link')->name('clarity.delete_link');
    });
    // EMD Microsoft Clarity end

    Route::get('modals', [AdminController::class, 'modals'])->name('dashboard.components');
    Route::get('logout', [UserController::class, 'logout'])->name('logout');

});
