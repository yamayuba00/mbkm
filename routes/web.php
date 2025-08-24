<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\StudentsController;
use App\Http\Controllers\Students\LogBookController;
use App\Http\Controllers\Students\DashboardController;
use App\Http\Controllers\Admin\ProgramCampusController;
use App\Http\Controllers\Admin\SubmissionTypeController;
use App\Http\Controllers\Students\MbkmProgramController;
use App\Http\Controllers\Admin\SubmissionPeriodController;
use App\Http\Controllers\Admin\InformationProgramController;
use App\Http\Controllers\Admin\VerificationProgramMbkmController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Kaprodi\DashboardController as KaprodiDashboardController;
use App\Http\Controllers\Lecturer\ActivitiesController as LecturerActivitiesController;
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Http\Controllers\Lecturer\LogBookController as LecturerLogBookController;
use App\Http\Controllers\Lecturer\MbkmProgramController as LecturerMbkmProgramController;
use App\Http\Controllers\Lecturer\StudentsController as LecturerStudentsController;
use App\Http\Controllers\Students\ActivitiesController;
use App\Http\Controllers\Students\InformationProgramController as StudentsInformationProgramController;

Route::middleware(['guest'])->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/forgot-password', [AuthController::class, 'viewForgotPassword'])->name('forgot-password');
    Route::post('/reset-password/verify', [AuthController::class, 'verifyReset'])->name('reset.verify');
    Route::post('/reset-password/update', [AuthController::class, 'updatePassword'])->name('reset.update');

    Route::get('/register', [AuthController::class, 'viewRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/lecturers', [AuthController::class, 'getLecturer'])->name('lecturers.get');
    Route::get('/faculties', [AuthController::class, 'getFaculty'])->name('faculties.get');
    Route::get('/prodi/{id}', [AuthController::class, 'getProdi'])->name('prodi.get');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    Route::prefix('admin')->name('admin.')->middleware('role:1')->group(function () {
        Route::get('/dashboard/data', [AdminDashboardController::class, 'getDashboardData'])->name('dashboard.data');
        Route::get('/dashboard', [AdminDashboardController::class, 'viewDashboard'])->name('dashboard');

        Route::get('/students/data', [StudentsController::class, 'data'])->name('students.data');
        Route::post('/students/toggle-status', [StudentsController::class, 'toggleStatus']);
        Route::get('/students/get-faculties', [StudentsController::class, 'getFaculty'])->name('students.getFaculty');
        Route::get('/students/get-prodi/{id}', [StudentsController::class, 'getProdi'])->name('students.getProdi');
        Route::resource('/students', StudentsController::class);


        Route::get('/program-campus/get-faculties', [ProgramCampusController::class, 'getDataFaculty'])->name('program-campus.getDataFaculty');
        Route::get('/program-campus/get-prodi', [ProgramCampusController::class, 'getDataProdi'])->name('program-campus.getDataProdi');
        Route::resource('/program-campus', ProgramCampusController::class);

        Route::get('/program-campus/prodi/{id}/edit', [ProgramCampusController::class, 'editProdi'])->name('program-campus.editProdi');
        Route::post('/program-campus/{faculty_id}/store-prodi', [ProgramCampusController::class, 'storeProdi'])->name('program-campus.storeProdi');
        Route::put('/program-campus/{faculty_id}/update-prodi/{prodi_id}', [ProgramCampusController::class, 'updateProdi'])->name('program-campus.updateProdi');
        Route::delete('/program-campus/prodi/{id}/delete', [ProgramCampusController::class, 'destroyProdi'])->name('program-campus.destroyProdi');


        Route::get('/lecturers/data', [LecturerController::class, 'data'])->name('lecturers.data');
        Route::resource('/lecturers', LecturerController::class);
        Route::post('/lecturer/toggle-status', [LecturerController::class, 'toggleStatus']);

        Route::get('/submission-type/data', [SubmissionTypeController::class, 'data'])->name('submission-type.data');
        Route::resource('/submission-type', SubmissionTypeController::class);
        Route::post('/submission-type/toggle-status', [SubmissionTypeController::class, 'toggleStatus']);

        Route::get('/submission-periode/data', [SubmissionPeriodController::class, 'data'])->name('submission-periode.data');
        Route::resource('/submission-periode', SubmissionPeriodController::class);

        Route::get('/verification-program-mbkm/data', [VerificationProgramMbkmController::class, 'data'])->name('verification-program-mbkm.data');
        Route::get('/list-program-mbkm/list', [VerificationProgramMbkmController::class, 'list'])->name('verification-program-mbkm.list');
        Route::get('/list-program-mbkm', [VerificationProgramMbkmController::class, 'getViewlistProgram'])->name('verification-program-mbkm.view');
        Route::resource('/verification-program-mbkm', VerificationProgramMbkmController::class);
        Route::post('/verification-program-mbkm/{id}/status', [VerificationProgramMbkmController::class, 'changeStatus'])->name('verification-program-mbkm.status');

        Route::get('/information-program/data', [InformationProgramController::class, 'data'])->name('information-program.data');
        Route::resource('/information-program', InformationProgramController::class);
    });

    Route::prefix('kaprodi')->middleware('role:2')->name('kaprodi.')->group(function () {
        Route::get('/dashboard/data', [KaprodiDashboardController::class, 'getDashboardData'])->name('dashboard.data');
        Route::get('/dashboard', [KaprodiDashboardController::class, 'viewDashboard'])->name('dashboard');
    });
    Route::prefix('lecturer')->name('lecturer.')->middleware('role:3')->group(function () {
        Route::get('/dashboard/data', [LecturerDashboardController::class, 'getDashboardData'])->name('dashboard.data');
        Route::get('/dashboard', [LecturerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/verification-program-mbkm/data', [LecturerMbkmProgramController::class, 'data'])->name('verification-program-mbkm.data');
        Route::get('/program-mbkm', [LecturerMbkmProgramController::class, 'index'])->name('verification-program-mbkm.index');
        Route::post('/verification-program-mbkm/validate', [LecturerMbkmProgramController::class, 'validateProgramWithLecturer'])->name('verification-program-mbkm.validateProgramWithLecturer');
        Route::get('/verification-program-mbkm/{id}/edit', [LecturerMbkmProgramController::class, 'edit'])->name('verification-program-mbkm.edit');
        Route::put('/verification-program-mbkm/{id}/update', [LecturerMbkmProgramController::class, 'update'])->name('verification-program-mbkm.update');

        Route::get('/logbook/data', [LecturerLogBookController::class, 'data'])->name('logbook.data');
        Route::post('/logbook/{id}/validate', [LecturerLogBookController::class, 'updateStatus'])->name('logbook.validate');
        Route::resource('logbook', LecturerLogBookController::class);

        Route::get('/activities/data', [LecturerActivitiesController::class, 'data'])->name('activities.data');
        Route::post('/activities/{id}/validate', [LecturerActivitiesController::class, 'updateStatus'])->name('activities.validate');
        Route::resource('activities', LecturerActivitiesController::class);


        Route::get('/students/data', [LecturerStudentsController::class, 'data'])->name('students.data');
        Route::post('/students/toggle-status', [LecturerStudentsController::class, 'toggleStatus']);
        Route::post('students/{id}/reset-password', [LecturerStudentsController::class, 'resetPassword'])->name('students.resetPassword');
        Route::resource('/students', LecturerStudentsController::class);
    });

    Route::prefix('student')->name('student.')->middleware('role:4')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');

        Route::get('/get-submission-type', [MbkmProgramController::class, 'getSubmissionTypes'])->name('mbkm.program.getSubmissionTypes');
        Route::get('/get-submission-period', [MbkmProgramController::class, 'getSubmissionPeriods'])->name('mbkm.program.getSubmissionPeriods');

        Route::get('/mbkm-program/list', [MbkmProgramController::class, 'list'])->name('mbkm.program.list');
        Route::get('/mbkm-program/data', [MbkmProgramController::class, 'data'])->name('mbkm.program.data');
        Route::resource('/mbkm-program', MbkmProgramController::class);

        Route::get('/logbook/data', [LogBookController::class, 'data'])->name('logbook.data');
        Route::get('/logbook/get-mbkm', [LogBookController::class, 'getYourMbkmProgramsAccepted'])->name('logbook.mbkm');
        Route::resource('logbook', LogBookController::class);

        Route::get('/activities/data', [ActivitiesController::class, 'data'])->name('activities.data');
        Route::get('/activities/get-mbkm', [ActivitiesController::class, 'getYourMbkmProgramsAccepted'])->name('logbook.mbkm');
        Route::resource('activities', ActivitiesController::class);

        Route::get('/information-program/data', [StudentsInformationProgramController::class, 'data'])->name('information-program.data');
        Route::resource('/information-program', StudentsInformationProgramController::class);
    });
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('layouts.admin.dashboard.index');
//     })->name('dashboard');

//     // Other routes for authenticated users can be added here
// });
