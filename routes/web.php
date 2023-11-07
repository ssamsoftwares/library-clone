<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
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

Route::get('/demo-pdf', function () {
    return view('pdf.testPdf');
});

Route::get('/demo-pdf-download',[PlanController::class,'generatePdf'])->name('generatePdf');


Route::group(['middleware' => ['auth','\Spatie\Permission\Middleware\RoleMiddleware:superadmin']], function () {
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





    // student Routes

    Route::get('/students', [StudentController::class,  'index'])->name('students');
    Route::get('/view-students/{student?}', [StudentController::class,  'view'])->name('student.view');

    Route::get('/add-student', [StudentController::class, 'create'])->name('student.add');
    Route::post('/add-student', [StudentController::class, 'store'])->name('student.store');

    Route::get('/edit-student/{student}', [StudentController::class, 'edit'])->name('student.edit');
    Route::post('/edit-student/{student?}', [StudentController::class, 'update'])->name('student.update');

    Route::get('/delete-student/{student?}',[StudentController::class, 'destroy'])->name('student.delete');

    Route::get('/student-status-update/{id}',[StudentController::class,'studentStatusUpdate'])->name('student.statusUpdate');


    // asign plan

    Route::get('/plans',[PlanController::class,'index'])->name('plans');
    Route::get('/view-plans/{plan?}',[PlanController::class,'view'])->name('plan.view');

    Route::get('/add-plan',[PlanController::class,'create'])->name('plan.add');
    Route::post('/add-plan',[PlanController::class,'store'])->name('plan.store');

    Route::get('/edit-plan/{plan?}',[PlanController::class,'edit'])->name('plan.edit');
    Route::post('/edit-plan/{plan?}',[PlanController::class,'update'])->name('plan.update');

    Route::get('/delete-plan/{plan?}',[PlanController::class, 'destroy'])->name('plan.delete');

    Route::get('/download-pdf/{id?}',[PlanController::class,'downloadPdf'])->name('plan.downloadPdf');

});



// Admin Routes
// Route::middleware(['auth', 'role:admin'])->group(function () {
// });



Route::get('/terms', function(){
    return view('terms');
})->name('terms');

Route::get('/privacy', function(){
    return view('privacy');
})->name('privacy');

require __DIR__ . '/auth.php';
