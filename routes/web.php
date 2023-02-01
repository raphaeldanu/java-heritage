<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\MyDataController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\SalaryRangeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveApprovalController;
use App\Http\Controllers\ResidenceAddressController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingMenuController;
use App\Http\Controllers\TrainingSubjectController;

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
    
    Route::name('my-data.')->group(function () {
        Route::get('my-data/families/create', 'createForUser')->name('create-family');
        Route::post('my-data/families', 'storeForUser')->name('store-family');
        Route::get('my-data/families/{family}', 'showForUser')->name('show-family');
        Route::get('my-data/families/{family}/edit', 'editForUser')->name('edit-family');
        Route::put('my-data/families/{family}', 'updateForUser')->name('update-family');
        Route::delete('my-data/families/{family}', 'destroyForUser')->name('destroy-family');
    });
});

Route::controller(MyDataController::class)->group(function () {
    Route::name('my-data.')->group(function () {
        Route::get('my-data/residence/create', 'createResidence')->name('create-residence');
        Route::post('my-data/residence', 'storeResidence')->name('store-residence');
        Route::put('my-data/residence', 'updateResidence')->name('update-residence');
        Route::get('my-data/residence/edit', 'editResidence')->name('edit-residence');
        Route::delete('my-data/residence', 'destroyResidence')->name('destroy-residence');
    });
});

Route::controller(MyDataController::class)->group(function () {
    Route::name('my-data.')->group(function () {
        Route::get('my-data', 'index')->name('index');
        Route::get('my-data/create', 'create')->name('create');
        Route::post('my-data', 'store')->name('store');
        Route::put('my-data', 'update')->name('update');
        Route::get('my-data/edit', 'edit')->name('edit');
        Route::delete('my-data', 'destroy')->name('destroy');
    });
});

Route::resource('approve-leave-requests', LeaveApprovalController::class)->parameters(['approve-leave-requests' => 'leave_request'])->except(['create', 'store']);


Route::controller(TrainingController::class)->group(function () {
    Route::name('trainings.')->group(function () {
        Route::get('trainings/{training}/attendant', 'addAttendant')->name('add-attendants');
        Route::get('trainings/{training}/edit/attendant', 'addAttendant')->name('edit-attendants');
        Route::post('trainings/{training}/attendant/{employee}', 'storeAttendant')->name('store-attendants');
        Route::put('trainings/{training}/attendant/{employee}', 'removeAttendant')->name('remove-attendants');
        Route::post('trainings/{training}/edit/attendant/{employee}', 'storeAttendant')->name('store-edit-attendants');
        Route::put('trainings/{training}/edit/attendant/{employee}', 'removeAttendant')->name('remove-edit-attendants');
    });
    Route::name('my-trainings.')->group(function () {
        Route::get('my-trainings', 'myIndex')->name('index');
        Route::get('my-trainings/{training}', 'myShow')->name('show');
    });
});

Route::controller(AttendanceController::class)->group(function () {
    Route::name('attendances.')->group(function () {
        Route::get('attendances/import', 'create')->name('import');
        Route::post('attendances/import', 'store')->name('store');
        Route::get('attendances/{employee}', 'showByEmployee')->name('show-by-employee');
    });
    Route::name('my-attendances.')->group(function () {
        Route::get('my-attendances', 'myIndex')->name('index');
    });
});

Route::resource('attendances', AttendanceController::class)->only(['index']);

Route::controller(EmployeeScheduleController::class)->group(function () {
    Route::name('schedules.')->group(function () {
        Route::get('schedules/of/{employee}', 'showByEmployee')->name('show-by-employee');
        Route::get('schedules/of/{employee}/create', 'create')->name('create');
    });
    Route::name('my-schedules.')->group(function () {
        Route::get('my-schedules', 'myIndex')->name('index');
    });
});

Route::resource('schedules', EmployeeScheduleController::class)->parameters([
    'schedules' => 'employee_schedule'
])->except(['create']);

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'departments' => DepartmentController::class,
    'levels' => LevelController::class,
    'positions' => PositionController::class,
    'salary-ranges' => SalaryRangeController::class,
    'leave-requests' => LeaveRequestController::class,
    'training-subjects' => TrainingSubjectController::class,
    'training-menus' => TrainingMenuController::class,
    'trainings' => TrainingController::class,
]);
