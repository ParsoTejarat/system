<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Panel\RoleController;
use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('test/{id}',function ($id){
    return \auth()->loginUsingId($id);
});

Route::middleware('auth')->prefix('/panel')->group(function (){
    Route::get('/', [PanelController::class, 'index'])->name('panel');

    // Users
    Route::resource('users',UserController::class)->except('show');

    // Roles
    Route::resource('roles', RoleController::class)->except('show');
});

Auth::routes(['register' => false, 'reset' => false, 'confirm' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::fallback(function (){
    abort(404);
});
