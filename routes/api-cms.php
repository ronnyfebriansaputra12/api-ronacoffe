<?php

use App\Http\Controllers\CMS\Manage\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return app()->version();
});

Route::group([
    'middleware' => 'auth.global',
    'namespace' => 'App\Http\Controllers'
], function () {
    Route::post('/file-uploader', 'FileUploadController@FileUploader');
});

Route::group([
    'middleware' => 'guest',
    'namespace' => 'App\Http\Controllers'
], function () {
    Route::post('/token', 'CredentialController@AuthSystem');
});

Route::group([
    'middleware' => 'token',
    'namespace' => 'App\Http\Controllers\CMS\Manage'
], function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::post('/verify', 'ForgotPasswordController@verifyOtp');
    Route::post('/resend', 'ForgotPasswordController@resendOtp');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/forgot', 'ForgotPasswordController@forgot');
    Route::get('/verifyOtp/{id}', 'ForgotPasswordController@verifyOtp');
    Route::post('/resendOtp', 'ForgotPasswordController@resendOtp');
    Route::post('reset/{id}', 'ForgotPasswordController@reset');

});


// Route::group([
//     'namespace' => 'App\Http\Controllers\CMS\Manage'
// ], function () {
//     Route::post('/forgotpassword',[ForgotPasswordController::class,'forgotPassword'])->name('forgotPassword');
// });


Route::group([
    'middleware' => ['auth.backoffice','owner.role'],
    'namespace' => 'App\Http\Controllers\CMS\Manage'
], function () {

    Route::get('/inventory', 'InventoryController@index');
    Route::get('/inventory/{id}', 'InventoryController@show');
    Route::post('/inventory', 'InventoryController@store');
    Route::post('/inventory/update/{id}', 'InventoryController@update');
    Route::post('/inventory/delete/{id}', 'InventoryController@destroy');

    Route::get('/pengeluaran', 'PengeluaranController@index');
    Route::get('/create-pdf', 'PengeluaranController@createPdf');
    Route::get('/pengeluaran/{id}', 'PengeluaranController@show');
    Route::post('/pengeluaran', 'PengeluaranController@store');
    Route::post('/pengeluaran/update/{id}', 'PengeluaranController@update');
    Route::post('/pengeluaran/delete/{id}', 'PengeluaranController@destroy');

    Route::get('/pengambilan', 'PengambilanBarangController@index');
    Route::get('/pengambilan/{id}', 'PengambilanBarangController@show');
    Route::post('/pengambilan', 'PengambilanBarangController@store');
    Route::post('/pengambilan/update/{id}', 'PengambilanBarangController@update');
    Route::post('/pengambilan/delete/{id}', 'PengambilanBarangController@destroy');

    Route::get('/cekAbsensi', 'DashboardController@cekAbsensi');


});

Route::group([
    'middleware' => ['auth.backoffice','owner.role'],
    'namespace' => 'App\Http\Controllers\CMS\Manage'
], function () {
    Route::get('/listUser', 'AuthController@getAllUser');
    Route::get('/profile', 'AuthController@profile');
    Route::post('/profile/update/{id}', 'AuthController@profedit');
    Route::post('/user/update/{id}', 'AuthController@userUpdate');
    Route::get('/user/{id}', 'AuthController@showUser');
    Route::post('/user/delete/{id}', 'AuthController@destroy');



});

Route::group([
    'middleware' => ['auth.backoffice','owner.role'],
    'namespace' => 'App\Http\Controllers\CMS\Manage'
], function () {
    Route::get('/absensi', 'AbsensiController@index');
    Route::post('/absensi', 'AbsensiController@store');
    Route::post('/absensi/update/{id}', 'AbsensiController@update');
    Route::post('/absensi/delete/{id}', 'AbsensiController@destroy');

});



