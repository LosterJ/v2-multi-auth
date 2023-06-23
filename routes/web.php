<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm']);
Route::get('/login/writer', [LoginController::class, 'showWriterLoginForm']);
Route::get('/register/admin', [RegisterController::class, 'showAdminRegisterForm']);
Route::get('/register/writer', [RegisterController::class, 'showWriterRegisterForm']);

Route::post('/login/admin', [LoginController::class, 'adminLogin']);
Route::post('/login/writer', [LoginController::class, 'writerLogin']);
Route::post('/register/admin', [RegisterController::class, 'createAdmin']);
Route::post('/register/writer', [RegisterController::class, 'createWriter']);

Route::view('/home', 'home')->middleware('auth');
Route::group(['middleware' => 'auth:admin'], function () {
    Route::view('/admin', 'admin');
});

Route::group(['middleware' => 'auth:writer'], function () {
    Route::view('/writer', 'writer');
});