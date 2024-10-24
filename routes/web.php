<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\SubscriptionController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [GoogleLoginController::class, 'signup'])->name('signup');

Route::post('make-account', [GoogleLoginController::class, 'make_account'])->name('make.account');

Route::get('/subscriptions', [GoogleLoginController::class, 'subscriptions'])->name('subscriptions');

Route::get('/login', [GoogleLoginController::class, 'login'])->name('login');

Route::get('/faqs', [GoogleLoginController::class, 'faqs'])->name('faqs');

Route::post('make-login', [GoogleLoginController::class, 'make_login'])->name('make.login');

Route::get('/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');

Route::get('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('account/verify/{token}', [GoogleLoginController::class, 'verifyAccount'])->name('account/verify');

/*Password Management*/
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');

Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');

Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');

Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
/* Password Management Ends */


#AfterLogin
Route::group(['middleware' => 'disable_back_btn'], function () {
Route::group(['middleware' => ['auth']], function()
{ 
#Route::get('/dashboard', [GoogleLoginController::class, 'dashboard'])->name('dashboard');    
Route::get('/account-settings', [GoogleLoginController::class, 'accountsettings'])->name('account-settings');

Route::get('/addwebsite', [GoogleLoginController::class, 'addwebsite'])->name('addwebsite');

Route::get('/create-outlinks', [GoogleLoginController::class, 'createoutlinks'])->name('create-outlinks');

Route::get('/create-backlinks', [GoogleLoginController::class, 'createbacklinks'])->name('create-backlinks');

Route::get('/backlinks/{forwhich_user_url}', [GoogleLoginController::class, 'backlinks'])->name('backlinks');

Route::get('/outlinks/{forwhich_user_url}', [GoogleLoginController::class, 'outlinks'])->name('outlinks');

Route::post('push-website', [GoogleLoginController::class, 'push_website'])->name('push-website');

Route::post('submitlinkdetails', [GoogleLoginController::class, 'submitlinkdetails'])->name('submitlinkdetails');

Route::get('deletewebsite/{id}', [GoogleLoginController::class, 'deletewebsite'])->name('deletewebsite');

Route::get('chat/{id}', [GoogleLoginController::class, 'chat'])->name('chat');

Route::get('/chat', function () {return redirect()->route('addwebsite')->with('error', 'Invalid chat request.');});

Route::get('acceptedby-to-outlink-connection/{id}/{forwhich_user_url}/{website_url}', [GoogleLoginController::class, 'acceptedby_to_outlink_connection'])->name('acceptedby-to-outlink-connection');

Route::get('acceptedby-from-backlink-connection/{id}/{forwhich_user_url}/{website_url}', [GoogleLoginController::class, 'acceptedby_from_backlink_connection'])->name('acceptedby-from-backlink-connection');

Route::get('reject/{forwhich_user_url}/{website_url}', [GoogleLoginController::class, 'rejectPair'])->name('reject');

Route::get('/backlinks-submission-details', [GoogleLoginController::class, 'backlinks_submission_details'])->name('backlinks-submission-details');

Route::get('/outlinks-submission-details', [GoogleLoginController::class, 'outlinks_submission_details'])->name('outlinks-submission-details');

Route::get('/deleteconnection/{myuniqueid}', [GoogleLoginController::class, 'deleteconnection'])->name('deleteconnection');

Route::get('/seen-notification/{id}', [GoogleLoginController::class, 'seen_notification'])->name('seen-notification');

Route::get('/seen-message/{id}', [GoogleLoginController::class, 'seen_message'])->name('seen-message');

Route::get('/show-edit-form/{website_id}', [GoogleLoginController::class, 'show_edit_form'])->name('show-edit-form');

Route::post('/edit-website/{website_id}', [GoogleLoginController::class, 'edit_website'])->name('edit-website');

Route::post('/stripe/webhook', [GoogleLoginController::class, 'handleWebhook']);

Route::get('show-subscription/{subscription_id}', [GoogleLoginController::class, 'showSubscription'])->name('show-subscription');

// Route to cancel subscription
Route::post('/subscription/{subscription_id}/cancel', [GoogleLoginController::class, 'cancelSubscription'])->name('subscription.cancel');

// Route to resume subscription
Route::post('/subscription/{subscription_id}/resume', [GoogleLoginController::class, 'resumeSubscription'])->name('subscription.resume');

Route::get('/weekly-update', [GoogleLoginController::class, 'weeklyUpdate']);

Route::get('signout', [GoogleLoginController::class, 'signout'])->name('signout');
});
});

#admin
Route::get('/admin', [AdminAuthController::class, 'adminlogin'])->name('admin');

Route::post('/make-admin-login', [AdminAuthController::class, 'make_admin_login'])->name('make-admin-login');

Route::group(['middleware' => 'disable_back_btn'], function () {    
Route::group(['middleware' => 'adminauth'], function () {

Route::get('/admin-dashboard', [AdminAuthController::class, 'admindashboard'])->name('admin-dashboard');

Route::get('/all-users', [AdminAuthController::class, 'all_users'])->name('all-users');

Route::get('/all-websites', [AdminAuthController::class, 'all_websites'])->name('all-websites');

Route::get('/connections', [AdminAuthController::class, 'connections'])->name('connections');

Route::get('/delete-connection/{chat_id}', [AdminAuthController::class, 'delete_connection'])->name('delete-connection');

#Route::get('/plans', [AdminAuthController::class, 'plans'])->name('plans');

#Route::post('/add-plans', [AdminAuthController::class, 'add_plans'])->name('add-plans');

#Route::get('/show-plans', [AdminAuthController::class, 'show_plans'])->name('show-plans');

Route::get('/delete-user/{userid}', [AdminAuthController::class, 'delete_user'])->name('delete-user');

Route::get('/delete-website/{id}', [AdminAuthController::class, 'delete_website'])->name('delete-website');

#Route::get('/delete-plan/{id}', [AdminAuthController::class, 'delete_plan'])->name('delete-plan');

Route::get('/block-user/{userid}', [AdminAuthController::class, 'block_user'])->name('block-user');

Route::get('/unblock-user/{userid}', [AdminAuthController::class, 'unblock_user'])->name('unblock-user');

Route::get('/verify-email/{userid}', [AdminAuthController::class, 'verify_email'])->name('verify-email');

#Route::get('/turn-off-plan/{planid}', [AdminAuthController::class, 'turn_off_plan'])->name('turn-off-plan');

#Route::get('/turn-on-plan/{planid}', [AdminAuthController::class, 'turn_on_plan'])->name('turn-on-plan');

#Route::get('/promocode', [AdminAuthController::class, 'promocode'])->name('promocode');

#Route::post('create-promocode', [AdminAuthController::class, 'createpromocode'])->name('create-promocode');

#Route::get('/show-promocode', [AdminAuthController::class, 'show_promocode'])->name('show-promocode');

#Route::get('/delete-promocode/{promocodeid}', [AdminAuthController::class, 'delete_promocode'])->name('delete-promocode');

#Route::get('/turn-off-promocode/{promocodeid}', [AdminAuthController::class, 'turn_off_promocode'])->name('turn-off-promocode');

#Route::get('/turn-on-promocode/{promocodeid}', [AdminAuthController::class, 'turn_on_promocode'])->name('turn-on-promocode');

Route::get('admin-signout', [AdminAuthController::class, 'adminsignout'])->name('admin-signout');
});
});
