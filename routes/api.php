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
    'middleware' => ['auth.customer'],
    'namespace' => 'App\Http\Controllers\API'
], function () {
    // Route::get('/listUser', 'AuthController@getAllUser');
    Route::get('/profile', 'AuthController@profile');
    Route::post('/profile/update/{id}', 'AuthController@profedit');


    Route::get('/inventory', 'InventoryController@index');
    // Route::get('/inventory/filter-by-week', 'InventoryController@filterByWeek');
    Route::get('/inventory/{id}', 'InventoryController@show');
    // Route::post('/inventory', 'InventoryController@store');
    // Route::put('/inventory/{id}', 'InventoryController@update');
    // Route::delete('/inventory/{id}', 'InventoryController@destroy');

    Route::get('/pengeluaran', 'PengeluaranController@index');
    Route::get('/pengeluaran/{id}', 'PengeluaranController@show');
    Route::post('/pengeluaran', 'PengeluaranController@store');
    Route::post('/pengeluaran/update/{id}', 'PengeluaranController@update');
    Route::post('/pengeluaran/delete/{id}', 'PengeluaranController@destroy');

    Route::get('/pengambilan', 'PengambilanBarangController@index');
    Route::get('/pengambilan/{id}', 'PengambilanBarangController@show');
    Route::post('/pengambilan', 'PengambilanBarangController@store');
    Route::post('/pengambilan/update/{id}', 'PengambilanBarangController@update');
    Route::post('/pengambilan/delete/{id}', 'PengambilanBarangController@destroy');

});

// Route::group([
//     'middleware' => ['auth.customer'],
//     'namespace' => 'App\Http\Controllers\API'
// ], function () {
//     Route::get('/profile', 'AuthController@profile');
//     Route::put('/profile/{id}', 'AuthController@profedit');

// });


// Route::group([
//     'middleware' => ['auth.customer','owner.role'],
//     'namespace' => 'App\Http\Controllers\API'
// ], function () {
//     Route::get('/listUser', 'AuthController@getAllUser');

// });


// Route::group([
//     'middleware' => ['auth.customer'],
//     'namespace' => 'App\Http\Controllers\API'
// ], function () {
//     // Route::get('/absensi', 'AbsensiController@index');
//     Route::post('/absensi', 'AbsensiController@store');
//     Route::put('/absensi/{id}', 'AbsensiController@update');
// });
