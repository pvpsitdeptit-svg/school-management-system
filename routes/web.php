<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentPromotionController;

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

// Landing Page
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])
    ->name('login.store');

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->name('logout');

// Super Admin Routes
Route::prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('dashboard');
    
    Route::get('/schools', [SuperAdminController::class, 'schools'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('schools');
    
    Route::get('/schools/create', [SuperAdminController::class, 'createSchool'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('schools.create');
    
    Route::post('/schools', [SuperAdminController::class, 'storeSchool'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('schools.store');
    
    Route::get('/schools/{school}/edit', [SuperAdminController::class, 'editSchool'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('schools.edit');
    
    Route::put('/schools/{school}', [SuperAdminController::class, 'updateSchool'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('schools.update');
    
    Route::delete('/schools/{school}', [SuperAdminController::class, 'destroySchool'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('schools.destroy');
    
    Route::get('/schools/{school}/stats', [SuperAdminController::class, 'schoolStats'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('schools.stats');
    
    Route::get('/export', [SuperAdminController::class, 'export'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('export');
    
    Route::get('/settings', [SuperAdminController::class, 'settings'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('settings');
    
    Route::get('/export/schools', [SuperAdminController::class, 'exportSchools'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('export.schools');
    
    Route::get('/export/students', [SuperAdminController::class, 'exportStudents'])
        ->middleware(['auth', 'role:super_admin'])
        ->name('export.students');
});

// School Admin Routes (Protected Routes)
Route::middleware(['auth', 'role:school_admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    
    // Students Management
    Route::get('/students', [StudentController::class, 'index'])
        ->name('students.index');
    
    Route::get('/students/create', [StudentController::class, 'create'])
        ->name('students.create');
    
    Route::post('/students', [StudentController::class, 'store'])
        ->name('students.store');
    
    Route::get('/students/{student}', [StudentController::class, 'show'])
        ->name('students.show');
    
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
        ->name('students.edit');
    
    Route::put('/students/{student}', [StudentController::class, 'update'])
        ->name('students.update');
    
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])
        ->name('students.destroy');
    
    // Bulk Upload Routes
    Route::get('/students/bulk-upload', [StudentController::class, 'bulkUpload'])
        ->name('students.bulk-upload');
    
    Route::post('/students/bulk-upload', [StudentController::class, 'processBulkUpload'])
        ->name('students.process-bulk-upload');
    
    Route::get('/students/download-template', [StudentController::class, 'downloadTemplate'])
        ->name('students.download-template');
    
    Route::get('/students/export', [StudentController::class, 'exportStudents'])
        ->name('students.export');
    
    // Student Promotion Routes
    Route::get('/students/promotion', [StudentPromotionController::class, 'index'])
        ->name('students.promotion.index');
    
    Route::get('/students/promotion/get-students', [StudentPromotionController::class, 'getStudents'])
        ->name('students.promotion.get-students');
    
    Route::post('/students/promotion/promote', [StudentPromotionController::class, 'promote'])
        ->name('students.promotion.promote');
    
    Route::post('/students/promotion/bulk-promote', [StudentPromotionController::class, 'bulkPromote'])
        ->name('students.promotion.bulk-promote');
    
    Route::get('/students/promotion/history', [StudentPromotionController::class, 'history'])
        ->name('students.promotion.history');
    
    // Parent Management Routes
    Route::resource('parents', ParentController::class);
    
    Route::post('/parents/{parent}/link-students', [ParentController::class, 'linkStudents'])
        ->name('parents.link-students');
    
    // Placeholder routes for other modules
    Route::view('/faculty', 'faculty.index')->name('faculty.index');
    Route::view('/classes', 'classes.index')->name('classes.index');
    Route::view('/subjects', 'subjects.index')->name('subjects.index');
    Route::view('/attendance', 'attendance.index')->name('attendance.index');
    Route::view('/marks', 'marks.index')->name('marks.index');
    Route::view('/exams', 'exams.index')->name('exams.index');
    Route::view('/fees', 'fees.index')->name('fees.index');
    Route::view('/settings', 'settings.index')->name('settings.index');
});

// Test routes
Route::get('/test-auth', function () {
    return view('test_auth');
});

Route::get('/test-attendance', function () {
    return view('test_attendance');
});

Route::get('/test-exams', function () {
    return view('test_exams');
});

Route::get('/test-android', function () {
    return view('test_android');
});

Route::get('/test-admin', function () {
    return view('test_admin');
});

/*
|--------------------------------------------------------------------------
| API Routes - Firebase Authentication
|--------------------------------------------------------------------------
|
| API endpoints that follow the exact Firebase authentication flow:
| 1. Client sends token to Laravel
| 2. Laravel verifies token
| 3. Laravel finds user in DB
| 4. Laravel identifies role
|
*/

// API Authentication Routes
Route::prefix('api')->middleware(['api'])->group(function () {
    
    // Public authentication endpoint
    Route::post('/auth/login', [AuthController::class, 'login']);
    
    // Protected endpoints (require authentication)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });
});

// Default Laravel auth routes (commented out to avoid conflicts)
// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
