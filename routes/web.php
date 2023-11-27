<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Student\StudentAuthController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

if (env('APP_ENV') === 'production') {
    URL::forceSchema('https');
}

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/demo-pdf-download', [PlanController::class, 'generatePdf'])->name('generatePdf');

Route::group(['middleware' => ['auth', '\Spatie\Permission\Middleware\RoleMiddleware:superadmin']], function () {
    Route::resource('roles', RoleController::class);
});
Route::resource('users', UserController::class)->middleware('auth');



Route::middleware(['auth', 'verified'])->group(function () {
    // dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // User Profile
    Route::get('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'update_password'])->name('profile.update_password');


    // libraries Routes

    Route::get('/libraries', [LibraryController::class,  'index'])->name('libraries');
    Route::get('/view-library/{library?}', [LibraryController::class,  'show'])->name('library.show');

    Route::get('/add-library', [LibraryController::class, 'create'])->name('library.add');
    Route::post('/add-library', [LibraryController::class, 'store'])->name('library.store');

    Route::get('/edit-library/{library}', [LibraryController::class, 'edit'])->name('library.edit');
    Route::post('/edit-library/{library?}', [LibraryController::class, 'update'])->name('library.update');

    Route::get('/delete-library/{library?}', [LibraryController::class, 'destroy'])->name('library.destroy');

    // Route::get('/library-status-update/{id}', [LibraryController::class, 'libraryStatusUpdate'])->name('library.statusUpdate');

    Route::delete('/library-manager-remove/{manager_id?}/{lib_id?}', [LibraryController::class, 'libraryManagerRemove'])->name('library.managerRemove');

    Route::get('/library-student-view/{admin_id?}/{manager_id?}/{lib_id?}', [LibraryController::class, 'libraryStudentView'])->name('library.libraryStudentView');

    Route::get('/library-student-remove/{student_id?}', [LibraryController::class, 'libraryStudentRemove'])->name('library.studentRemove');

    Route::get('/library-statusUpdate/{id?}', [LibraryController::class, 'libraryUpdateStatus'])->name('library.libraryUpdateStatus');

    // student Routes

    Route::get('/students', [StudentController::class,  'index'])->name('students');
    Route::get('/view-students/{student?}', [StudentController::class,  'view'])->name('student.view');

    Route::get('/add-student', [StudentController::class, 'create'])->name('student.add');
    Route::post('/add-student', [StudentController::class, 'store'])->name('student.store');

    Route::get('/edit-student/{student}', [StudentController::class, 'edit'])->name('student.edit');
    Route::post('/edit-student/{student?}', [StudentController::class, 'update'])->name('student.update');

    Route::get('/delete-student/{student?}', [StudentController::class, 'destroy'])->name('student.delete');

    Route::get('/student-status-update/{id}', [StudentController::class, 'studentStatusUpdate'])->name('student.statusUpdate');


    // asign plan

    Route::get('/plans', [PlanController::class, 'index'])->name('plans');
    Route::get('/view-plans/{plan?}', [PlanController::class, 'view'])->name('plan.view');

    Route::get('/add-plan', [PlanController::class, 'create'])->name('plan.add');
    Route::post('/add-plan', [PlanController::class, 'store'])->name('plan.store');

    Route::get('/edit-plan/{plan?}', [PlanController::class, 'edit'])->name('plan.edit');
    Route::post('/edit-plan/{plan?}', [PlanController::class, 'update'])->name('plan.update');

    Route::get('/delete-plan/{plan?}', [PlanController::class, 'destroy'])->name('plan.delete');

    Route::get('/download-pdf/{id?}', [PlanController::class, 'downloadPdf'])->name('plan.downloadPdf');


    // Registration Request Route
    Route::get('userRegistration', [RegistrationRequestController::class, 'registrationRequestIndex'])->name('registrationRequest.index');

    Route::get('userRegistration-edit/{id?}', [RegistrationRequestController::class, 'registrationRequestEdit'])->name('registrationRequest.edit');

    Route::post('userRegistration-update/{id?}', [RegistrationRequestController::class, 'registrationRequestUpdate'])->name('registrationRequest.update');

    Route::get('userRegistration-delete/{id?}', [RegistrationRequestController::class, 'registrationRequestDestroy'])->name('registrationRequest.destroy');

    Route::get('/update-status/{id}', [RegistrationRequestController::class, 'updateStatus'])->name('registrationRequest.updateStatus');

    Route::post('asign-password-user', [RegistrationRequestController::class, 'asignPasswordUser'])->name('registrationRequest.asignPasswordUser');
});

// registrationRequest Route

Route::get('user-registration', [RegistrationRequestController::class, 'registrationRequest'])->name('user.registrationRequest');
Route::post('user-registration', [RegistrationRequestController::class, 'registrationRequestStore'])->name('user.registrationRequestStore');

// STUDENT AUTH ROUTES
Route::get('student-login', [StudentAuthController::class, 'studentLoginView'])->name('student.login');
Route::post('student-login', [StudentAuthController::class, 'studentLogin'])->name('student.loginPost');

Route::get('student-forgot-password', [StudentAuthController::class, 'forgotPassword'])
    ->name('student.passwordRequest');

Route::post('student-forgot-password', [StudentAuthController::class, 'forgotPasswordStore'])
    ->name('student.forgotPasswordStore');

Route::get('student-reset-password/{token?}', [StudentAuthController::class, 'resetPassword'])
    ->name('student.passwordReset');

Route::post('student-reset-password', [StudentAuthController::class, 'resetPasswordStore'])
    ->name('student.resetPasswordStore');

Route::get('student-logout', [StudentAuthController::class, 'studentLogout'])->name('student.logout');

// STUDENT PANEL ROUTES
Route::middleware(['student'])->group(function () {
    Route::get('student-dashboard', [StudentDashboardController::class, 'studentdashboard'])->name('student.dashboard');
    Route::get('student-profile', [StudentDashboardController::class, 'studentProfile'])->name('student.profile');

    Route::get('student-profile-edit', [StudentDashboardController::class, 'studentProfileEdit'])->name('student.studentProfileEdit');

    Route::post('student-profile-update', [StudentDashboardController::class, 'studentProfileUpdate'])->name('student.studentProfileUpdate');

    Route::get('plan-details', [StudentDashboardController::class, 'planDetails'])->name('student.planDetails');
});

Route::get('/email', function () {
    return view('emails.asignPasswordUser');
});

Route::get('/email-reset-pw', function () {
    return view('pdf.emailtesting');
});


// TESTING BLADE FILE
Route::get('/testing', function () {
   return view('pdf.testing');
});

require __DIR__ . '/auth.php';
