<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MyDataController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ResidenceAddressController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryRangeController;
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

//Employees
Route::controller(EmployeeController::class)->group(function () {
    Route::name('employees.')->group(function () {
        Route::get('employees/pick-user/', 'pickUser')->name('pick-user');
        Route::get('employees/create/{user}', 'create')->name('create');
        Route::post('employees/{employee}/leave', 'addLeave')->name('add-leave');
        Route::put('employees/{employee}/leave/', 'updateLeave')->name('update-leave');
        Route::get('employees/{employee}/leave/edit', 'editLeave')->name('edit-leave');
        Route::get('employees/{employee}/residence/create', 'createResidence')->name('create-residence');
        Route::post('employees/{employee}/residence', 'storeResidence')->name('store-residence');
        Route::put('employees/{employee}/residence', 'updateResidence')->name('update-residence');
        Route::get('employees/{employee}/residence/edit', 'editResidence')->name('edit-residence');
        Route::delete('employees/{employee}/residence', 'destroyResidence')->name('destroy-residence');
    });
});

Route::resource('employees', EmployeeController::class)->except('create');

//Residence Address
Route::controller(ResidenceAddressController::class)->group(function () {
    Route::name('residences.')->group(function () {
        //
    });
});

//Families
Route::controller(FamilyController::class)->group(function () {
    Route::name('families.')->group(function () {
        Route::get('employees/{employee}/families/create', 'create')->name('create');
        Route::post('employees/{employee}/families', 'store')->name('store');
        Route::get('employees/{employee}/families/{family}', 'show')->name('show');
        Route::get('employees/{employee}/families/{family}/edit', 'edit')->name('edit');
        Route::put('employees/{employee}/families/{family}', 'update')->name('update');
        Route::delete('employees/{employee}/families/{family}', 'destroy')->name('destroy');
    });
});

Route::resource('my-data', MyDataController::class)->except('show');

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'departments' => DepartmentController::class,
    'levels' => LevelController::class,
    'positions' => PositionController::class,
    'salary-ranges' => SalaryRangeController::class,
    'leave-requests' => LeaveRequestController::class,
]);
