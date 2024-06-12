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

Route::get('account/change-password', [HomeController::class, 'editPassword']);
Route::put('account/change-password', [HomeController::class, 'updatePassword'])->name('profile.update-password');

// Users Routes
Route::put('users/{user}/change-status', [UserController::class, 'changeStatus'])->name('users.change-status');
Route::put('users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');

//Employees
Route::controller(EmployeeController::class)->group(function () {
    Route::name('employees.')->prefix('employees')->group(function () {
        Route::get('/pick-user/', 'pickUser')->name('pick-user');
        Route::get('/export-turn-over', 'exportTurnOver')->name('export-turn-over');
        Route::get('/export-ratio', 'exportRatio')->name('export-ratio');
        Route::post('/export-turn-over', 'export')->name('export');
        Route::get('/create/{user}', 'create')->name('create');
        Route::post('/{employee}/leave', 'addLeave')->name('add-leave');
        Route::put('/{employee}/leave/', 'updateLeave')->name('update-leave');
        Route::get('/{employee}/leave/edit', 'editLeave')->name('edit-leave');
        Route::get('/{employee}/residence/create', 'createResidence')->name('create-residence');
        Route::post('/{employee}/residence', 'storeResidence')->name('store-residence');
        Route::put('/{employee}/residence', 'updateResidence')->name('update-residence');
        Route::get('/{employee}/residence/edit', 'editResidence')->name('edit-residence');
        Route::delete('/{employee}/residence', 'destroyResidence')->name('destroy-residence');
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
    Route::name('families.')->prefix('employees')->group(function () {
        Route::get('/{employee}/families/create', 'create')->name('create');
        Route::post('/{employee}/families', 'store')->name('store');
        Route::get('/{employee}/families/{family}', 'show')->name('show');
        Route::get('/{employee}/families/{family}/edit', 'edit')->name('edit');
        Route::put('/{employee}/families/{family}', 'update')->name('update');
        Route::delete('/{employee}/families/{family}', 'destroy')->name('destroy');
    });

    Route::name('my-data.')->prefix('my-data/families')->group(function () {
        Route::get('/create', 'createForUser')->name('create-family');
        Route::post('/', 'storeForUser')->name('store-family');
        Route::get('/{family}', 'showForUser')->name('show-family');
        Route::get('/{family}/edit', 'editForUser')->name('edit-family');
        Route::put('/{family}', 'updateForUser')->name('update-family');
        Route::delete('/{family}', 'destroyForUser')->name('destroy-family');
    });
});

Route::controller(MyDataController::class)->group(function () {
    Route::name('my-data.')->prefix('my-data/residence')->group(function () {
        Route::get('/create', 'createResidence')->name('create-residence');
        Route::post('/', 'storeResidence')->name('store-residence');
        Route::put('/', 'updateResidence')->name('update-residence');
        Route::get('/edit', 'editResidence')->name('edit-residence');
        Route::delete('/', 'destroyResidence')->name('destroy-residence');
    });
});

Route::controller(MyDataController::class)->group(function () {
    Route::name('my-data.')->prefix('my-data')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::put('/', 'update')->name('update');
        Route::get('/edit', 'edit')->name('edit');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

Route::resource('approve-leave-requests', LeaveApprovalController::class)
    ->parameters(['approve-leave-requests' => 'leave_request'])
    ->except(['create', 'store']);


Route::controller(TrainingController::class)->group(function () {
    Route::name('trainings.')->prefix('trainings')->group(function () {
        Route::get('/{training}/attendant', 'addAttendant')->name('add-attendants');
        Route::get('/{training}/edit/attendant', 'addAttendant')->name('edit-attendants');
        Route::post('/{training}/attendant/{employee}', 'storeAttendant')->name('store-attendants');
        Route::put('/{training}/attendant/{employee}', 'removeAttendant')->name('remove-attendants');
        Route::post('/{training}/edit/attendant/{employee}', 'storeAttendant')->name('store-edit-attendants');
        Route::put('/{training}/edit/attendant/{employee}', 'removeAttendant')->name('remove-edit-attendants');
    });
    Route::name('my-trainings.')->prefix('my-trainings')->group(function () {
        Route::get('/', 'myIndex')->name('index');
        Route::get('/{training}', 'myShow')->name('show');
    });
});

Route::controller(AttendanceController::class)->group(function () {
    Route::name('attendances.')->prefix('attendances')->group(function () {
        Route::get('/import', 'create')->name('import');
        Route::post('/import', 'store')->name('store');
        Route::get('/{employee}', 'showByEmployee')->name('show-by-employee');
    });
    Route::name('my-attendances.')->group(function () {
        Route::get('my-attendances', 'myIndex')->name('index');
    });
});

Route::resource('attendances', AttendanceController::class)->only(['index']);

Route::controller(EmployeeScheduleController::class)->group(function () {
    Route::name('schedules.')->prefix('schedules/of')->group(function () {
        Route::get('/{employee}', 'showByEmployee')->name('show-by-employee');
        Route::get('/{employee}/create', 'create')->name('create');
    });
    Route::name('my-schedules.')->group(function () {
        Route::get('my-schedules', 'myIndex')->name('index');
    });
});

Route::controller(SalaryController::class)->group(function () {
    Route::name('salaries.')->prefix('salaries')->group(function () {
        Route::get('/of/{employee}', 'showByEmployee')->name('show-by-employee');
        Route::get('/of/{employee}/create', 'createByEmployee')->name('create-by-employee');
        Route::get('/check-workdays', 'checkWorkdays')->name('check-workdays');
        Route::get('/{salary}/export', 'export')->name('export');
    });
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
