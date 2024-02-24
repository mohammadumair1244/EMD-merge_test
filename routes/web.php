<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmdComponentController;
use App\Http\Controllers\EmdFeedbackController;
use App\Http\Controllers\EmdUserTransactionController;
use App\Http\Controllers\EmdWebUserController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Artisan;
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

Route::middleware(['emd_is_user_premium', 'emd_allow_post_method'])->group(function () {
    require __DIR__ . '/custom_pages.php';
    Route::prefix('emd')->group(function () {
        Route::controller(EmdWebUserController::class)->group(function () {
            Route::prefix('login')->group(function () {
                Route::post('web', 'emd_login_with_web')->name('emd_login_with_web'); // for login website user use this route & don't change route name
                Route::get('google', 'emd_login_with_google')->name('emd_login_with_google');
                Route::get('google/callback', 'emd_callback_from_google');
            });
            Route::prefix('register')->group(function () {
                Route::post('web', 'emd_register_with_web')->name('emd_register_with_web'); // for registration new user use this route & don't change route name
            });
            Route::post('update-user-password', 'emd_update_user_password')->middleware('auth:web_user_sess')->name("emd_update_user_password"); // when need to update user password then use this route & don't change route name
            Route::post('user-account-delete', 'emd_user_account_delete')->middleware('auth:web_user_sess')->name("emd_user_account_delete"); // when need to delete user account use this route & don't change route name
            Route::post('cancel-plan-membership', 'emd_cancel_plan_membership')->middleware('auth:web_user_sess')->name("emd_cancel_plan_membership"); // when need to cancel type plna use this route & don't change route name
            Route::post('forgot-password', 'emd_forgot_password')->name("emd_forgot_password"); // when user need to forget password use this route
            Route::post('reset-password/{token?}', 'emd_reset_password')->name("emd_reset_password"); // when user reset password after forgot email use this route
            Route::get('logout', 'emd_web_user_logout')->name("emd_web_user_logout");
        });
        Route::post('feedback-send', [EmdFeedbackController::class, 'emd_feedback_req'])->name("emd_feedback_req");
    });
    Route::get('emd-verify-account/{token}', [EmdWebUserController::class, 'emd_verify_user_account']); // you can change route slug
    Route::post('emd-paypro-callback', [EmdUserTransactionController::class, 'emd_paypro_callback']); // you can change route slug if already used paypro callback
    Route::post('emd-get-component', [EmdComponentController::class, 'get_component'])->name('emd_get_component'); // you can change route slug and don't change route name

    Route::get('artisan', function () {
        Artisan::call('optimize:clear');
    });
    require __DIR__ . '/redirection.php';
    require __DIR__ . '/custom.php';
    require __DIR__ . '/custom_pages.php';

    Route::post('contact', [ContactController::class, 'store'])->middleware('throttle:2,1440')->name('store_contact');
    Route::controller(FrontendController::class)->group(function () {
        Route::get('sitemap.xml', 'sitemap');
        Route::get('blog', 'blog')->name('page.blog');
        Route::get('blog/{slug}', 'single_blog')->name('page.single_blog');
        Route::get('{lang}/blog/{slug}', 'single_blog_other_language')
            ->where(['lang' => '[a-z]{2}'])
            ->name('single_blog_other_language');
        Route::get('{slug}', 'native_language_tool')->name('native_language_tool');
        Route::get('{lang}/{slug}', 'other_language_tool')
            ->where(['lang' => '[a-z]{2}'])
            ->name('other_language_tool');
        Route::get('/', 'index')->name('home');
    });
});
