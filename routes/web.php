<?php

use App\Http\Controllers\DepartmentController;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

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
    return redirect()->route('login');
});

Auth::routes([
    'login' => true,
    'logout' => true,
    'register' => false,
    'reset' => false,
    'confirm' => true,
    'verify' => false,
]);

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');

// Users Routes 
Route::put('users/{user}/change-status', [UserController::class, 'changeStatus'])->name('users.change-status');
Route::put('users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'departments' => DepartmentController::class,
    
]);
