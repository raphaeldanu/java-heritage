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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalaryRangeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveApprovalController;
use App\Http\Controllers\PtkpController;
use App\Http\Controllers\ResidenceAddressController;
use App\Http\Controllers\SalaryController;
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

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('account/change-password', [HomeController::class, 'editPassword'])->name('profile.edit-password');
Route::put('account/change-password', [HomeController::class, 'updatePassword'])->name('profile.update-password');

// Users Routes
Route::put('users/{user}/change-status', [UserController::class, 'changeStatus'])->name('users.change-status');
Route::put('users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');

//Employees
Route::controller(EmployeeController::class)->prefix('employees')->group(function () {
    Route::get('/pick-user', 'pickUser')->name('employees.pick-user');
    Route::get('/export-turn-over', 'exportTurnOver')->name('employees.export-turn-over');
    Route::get('/export-ratio', 'exportRatio')->name('employees.export-ratio');
    Route::post('/export-turn-over', 'export')->name('employees.export');
    Route::get('/create/{user}', 'create')->name('employees.create');
    Route::post('/{employee}/leave', 'addLeave')->name('employees.add-leave');
    Route::put('/{employee}/leave/', 'updateLeave')->name('employees.update-leave');
    Route::get('/{employee}/leave/edit', 'editLeave')->name('employees.edit-leave');
    Route::get('/{employee}/residence/create', 'createResidence')->name('employees.create-residence');
    Route::post('/{employee}/residence', 'storeResidence')->name('employees.store-residence');
    Route::put('/{employee}/residence', 'updateResidence')->name('employees.update-residence');
    Route::get('/{employee}/residence/edit', 'editResidence')->name('employees.edit-residence');
    Route::delete('/{employee}/residence', 'destroyResidence')->name('employees.destroy-residence');
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
    Route::prefix('employees')->group(function () {
        Route::get('/{employee}/families/create', 'create')->name('families.create');
        Route::post('/{employee}/families', 'store')->name('families.store');
        Route::get('/{employee}/families/{family}', 'show')->name('families.show');
        Route::get('/{employee}/families/{family}/edit', 'edit')->name('families.edit');
        Route::put('/{employee}/families/{family}', 'update')->name('families.update');
        Route::delete('/{employee}/families/{family}', 'destroy')->name('families.destroy');
    });

    Route::prefix('my-data/families')->group(function () {
        Route::get('/create', 'createForUser')->name('my-data.create-family');
        Route::post('/', 'storeForUser')->name('my-data.store-family');
        Route::get('/{family}', 'showForUser')->name('my-data.show-family');
        Route::get('/{family}/edit', 'editForUser')->name('my-data.edit-family');
        Route::put('/{family}', 'updateForUser')->name('my-data.update-family');
        Route::delete('/{family}', 'destroyForUser')->name('my-data.destroy-family');
    });
});

Route::controller(MyDataController::class)->prefix('my-data/residence')->group(function () {
    Route::get('/create', 'createResidence')->name('my-data.create-residence');
    Route::post('/', 'storeResidence')->name('my-data.store-residence');
    Route::put('/', 'updateResidence')->name('my-data.update-residence');
    Route::get('/edit', 'editResidence')->name('my-data.edit-residence');
    Route::delete('/', 'destroyResidence')->name('my-data.destroy-residence');
});

Route::controller(MyDataController::class)->prefix('my-data')->group(function () {
    Route::get('/', 'index')->name('my-data.index');
    Route::get('/create', 'create')->name('my-data.create');
    Route::post('/', 'store')->name('my-data.store');
    Route::put('/', 'update')->name('my-data.update');
    Route::get('/edit', 'edit')->name('my-data.edit');
    Route::delete('/', 'destroy')->name('my-data.destroy');
});

Route::resource('approve-leave-requests', LeaveApprovalController::class)
    ->parameters(['approve-leave-requests' => 'leave_request'])
    ->except(['create', 'store']);


Route::controller(TrainingController::class)->group(function () {
    Route::prefix('trainings')->group(function () {
        Route::get('/{training}/attendant', 'addAttendant')->name('trainings.add-attendants');
        Route::get('/{training}/edit/attendant', 'addAttendant')->name('trainings.edit-attendants');
        Route::post('/{training}/attendant/{employee}', 'storeAttendant')->name('trainings.store-attendants');
        Route::put('/{training}/attendant/{employee}', 'removeAttendant')->name('trainings.remove-attendants');
        Route::post('/{training}/edit/attendant/{employee}', 'storeAttendant')->name('trainings.store-edit-attendants');
        Route::put('/{training}/edit/attendant/{employee}', 'removeAttendant')->name('trainings.remove-edit-attendants');
    });
    Route::prefix('my-trainings')->group(function () {
        Route::get('/', 'myIndex')->name('my-trainings.index');
        Route::get('/{training}', 'myShow')->name('my-trainings.show');
    });
});

Route::controller(AttendanceController::class)->group(function () {
    Route::prefix('attendances')->group(function () {
        Route::get('/import', 'create')->name('attendances.import');
        Route::post('/import', 'store')->name('attendances.store');
        Route::get('/{employee}', 'showByEmployee')->name('attendances.show-by-employee');
    });

    Route::get('my-attendances', 'myIndex')->name('my-attendances.index');
});

Route::resource('attendances', AttendanceController::class)->only(['index']);

Route::controller(EmployeeScheduleController::class)->group(function () {
    Route::prefix('schedules/of')->group(function () {
        Route::get('/{employee}', 'showByEmployee')->name('schedules.show-by-employee');
        Route::get('/{employee}/create', 'create')->name('schedules.create');
    });

    Route::get('my-schedules', 'myIndex')->name('my-schedules.index');
});

Route::controller(SalaryController::class)->prefix('salaries')->group(function () {
    Route::get('/of/{employee}', 'showByEmployee')->name('salaries.show-by-employee');
    Route::get('/of/{employee}/create', 'createByEmployee')->name('salaries.create-by-employee');
    Route::get('/check-workdays', 'checkWorkdays')->name('salaries.check-workdays');
    Route::get('/{salary}/export', 'export')->name('salaries.export');
});

Route::resource('schedules', EmployeeScheduleController::class)->parameters([
    'schedules' => 'employee_schedule'
])->except(['create']);

Route::resource('salaries', SalaryController::class)->except(['update', 'edit']);

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
    'ptkps' => PtkpController::class,
]);
