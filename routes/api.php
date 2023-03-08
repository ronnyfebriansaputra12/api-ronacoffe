<?php

use App\Http\Controllers\API\ForgotPasswordController;
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
    'namespace' => 'App\Http\Controllers\API'
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


Route::group([
    'namespace' => 'App\Http\Controllers\API'
], function () {
    Route::post('/forgotpassword',[ForgotPasswordController::class,'forgotPassword'])->name('forgotPassword');
});


Route::group([
    'middleware' => ['auth.customer','owner.role'],
    'namespace' => 'App\Http\Controllers\API'
], function () {
    Route::get('/listUser', 'AuthController@getAllUser');
    Route::get('/profile', 'AuthController@profile');
    Route::put('/profile/{id}', 'AuthController@profedit');

    Route::get('/produk', 'ProdukController@index');
    Route::get('/produk/{id}', 'ProdukController@show');
    Route::post('/produk', 'ProdukController@store');
    Route::put('/produk/{id}', 'ProdukController@update');
    Route::delete('/produk/{id}', 'ProdukController@destroy');

    Route::get('/inventory', 'InventoryController@index');
    // Route::get('/inventory/filter-by-week', 'InventoryController@filterByWeek');
    Route::get('/inventory/{id}', 'InventoryController@show');
    Route::post('/inventory', 'InventoryController@store');
    Route::put('/inventory/{id}', 'InventoryController@update');
    Route::delete('/inventory/{id}', 'InventoryController@destroy');

    Route::get('/pemasukan', 'PemasukanController@index');
    Route::get('/pemasukan', 'PemasukanController@filterByWeek');
    Route::get('/pemasukan/{id}', 'PemasukanController@show');
    Route::post('/pemasukan', 'PemasukanController@store');
    Route::put('/pemasukan/{id}', 'PemasukanController@update');
    Route::delete('/pemasukan/{id}', 'PemasukanController@destroy');

    Route::get('/pengeluaran', 'PengeluaranController@index');
    Route::get('/pengeluaran/{id}', 'PengeluaranController@show');
    Route::post('/pengeluaran', 'PengeluaranController@store');
    Route::put('/pengeluaran/{id}', 'PengeluaranController@update');
    Route::delete('/pengeluaran/{id}', 'PengeluaranController@destroy');

    Route::get('/pengambilan', 'PengambilanBarangController@index');
    Route::get('/pengambilan/{id}', 'PengambilanBarangController@show');
    Route::post('/pengambilan', 'PengambilanBarangController@store');
    Route::put('/pengambilan/{id}', 'PengambilanBarangController@update');
    Route::delete('/pengambilan/{id}', 'PengambilanBarangController@destroy');

});


Route::group([
    'middleware' => ['auth.customer'],
    'namespace' => 'App\Http\Controllers\API'
], function () {
    Route::get('/listUser', 'AuthController@getAllUser');

});
