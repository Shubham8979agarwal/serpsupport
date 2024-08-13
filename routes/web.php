<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\ForgotPasswordController;

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

Route::get('/login', [GoogleLoginController::class, 'login'])->name('login');
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
Route::get('/account-settings', [GoogleLoginController::class, 'accountsettings'])->name('account-settings');
Route::get('/addwebsite', [GoogleLoginController::class, 'addwebsite'])->name('addwebsite');

Route::get('/create-outlinks', [GoogleLoginController::class, 'createoutlinks'])->name('create-outlinks');

Route::get('/create-backlinks', [GoogleLoginController::class, 'createbacklinks'])->name('create-backlinks');

Route::get('/backlinks/{forwhich_user_url}', [GoogleLoginController::class, 'backlinks'])->name('backlinks');

//Route::get('/backlinks', [GoogleLoginController::class, 'backlinks'])->name('backlinks');

Route::get('/outlinks/{forwhich_user_url}', [GoogleLoginController::class, 'outlinks'])->name('outlinks');

#Route::get('/seen/{forwhich_user_url}', [GoogleLoginController::class, 'seen'])->name('seen');

//Route::get('/outlinks', [GoogleLoginController::class, 'outlinks'])->name('outlinks');

Route::post('push-website', [GoogleLoginController::class, 'push_website'])->name('push-website');

Route::get('delete-website/{id}', [GoogleLoginController::class, 'deletewebsite'])->name('delete-website');

Route::get('acceptedby-to-outlink-connection/{id}', [GoogleLoginController::class, 'acceptedby_to_outlink_connection'])->name('acceptedby-to-outlink-connection');

Route::get('acceptedby-from-outlink-connection/{id}', [GoogleLoginController::class, 'acceptedby_from_outlink_connection'])->name('acceptedby-from-outlink-connection');

Route::get('acceptedby-to-backlink-connection/{id}', [GoogleLoginController::class, 'acceptedby_to_backlink_connection'])->name('acceptedby-to-backlink-connection');

Route::get('acceptedby-from-backlink-connection/{id}', [GoogleLoginController::class, 'acceptedby_from_backlink_connection'])->name('acceptedby-from-backlink-connection');

Route::get('reject/{forwhich_user_url}/{website_url}', [GoogleLoginController::class, 'rejectPair'])->name('reject');

Route::get('/weekly-update', [WebsiteController::class, 'weeklyUpdate']);

Route::get('signout', [GoogleLoginController::class, 'signout'])->name('signout');
});
});
