<?php

use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffCertificateController;
use App\Http\Controllers\StaffUnitController;
use App\Http\Controllers\PenyeliaController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/register', [StaffAuthController::class, 'showRegister'])->name('register.staff');
Route::post('/register', [StaffAuthController::class, 'register'])->name('register.staff.submit');

Route::get('/login', [StaffAuthController::class, 'showLogin'])->name('login.staff');
Route::post('/login', [StaffAuthController::class, 'login'])->name('login.staff.submit');
Route::post('/logout', [StaffAuthController::class, 'logout'])->name('logout.staff');

Route::get('/staff/dashboard', [StaffAuthController::class, 'dashboard'])->name('staff.dashboard');
Route::get('/staff/completion-status', [StaffAuthController::class, 'completionStatus'])->name('staff.completion-status');
Route::get('/staff/certificate', [StaffCertificateController::class, 'show'])->name('staff.certificate');
Route::get('/staff/unit/{unitSlug}', [StaffUnitController::class, 'view'])->name('staff.unit.view');
Route::get('/staff/unit-progress', [StaffUnitController::class, 'getProgress'])->name('staff.unit.progress.get');
Route::post('/staff/unit-progress', [StaffUnitController::class, 'saveProgress'])->name('staff.unit.progress.save');
Route::get('/staff/unit-completion', [StaffUnitController::class, 'checkCompletion'])->name('staff.unit.check');
Route::post('/staff/completion-save', [StaffUnitController::class, 'saveCompletion'])->name('staff.unit.completion.save');

Route::get('/register-penyelia', [PenyeliaController::class, 'showRegister'])->name('register.penyelia');
Route::post('/register-penyelia', [PenyeliaController::class, 'register'])->name('register.penyelia.submit');
Route::get('/login-penyelia', [PenyeliaController::class, 'showLogin']);
Route::post('/login-penyelia', [PenyeliaController::class, 'login']);
Route::get('/penyelia/login', [PenyeliaController::class, 'showLogin'])->name('login.penyelia');
Route::post('/penyelia/login', [PenyeliaController::class, 'login'])->name('login.penyelia.submit');
Route::get('/penyelia/dashboard', [PenyeliaController::class, 'dashboard'])->name('penyelia.dashboard');
Route::post('/penyelia/verify', [PenyeliaController::class, 'verify'])->name('penyelia.verify');
Route::post('/penyelia/logout', [PenyeliaController::class, 'logout'])->name('penyelia.logout');

Route::get('/login-admin', [AdminController::class, 'showLogin']);
Route::post('/login-admin', [AdminController::class, 'login']);
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('login.admin');
Route::post('/admin/login', [AdminController::class, 'login'])->name('login.admin.submit');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/slide-content', [AdminController::class, 'slideContent'])->name('admin.slide-content');
Route::post('/admin/slide-content/upload', [AdminController::class, 'slideContentUpload'])->name('admin.slide-content.upload');
Route::post('/admin/slide-content/delete', [AdminController::class, 'slideContentDelete'])->name('admin.slide-content.delete');
Route::get('/admin/staff-progress', [AdminController::class, 'staffProgress'])->name('admin.staff-progress');
Route::get('/admin/reset-units', [AdminController::class, 'resetUnits'])->name('admin.reset-units');
Route::post('/admin/reset-units/action', [AdminController::class, 'resetUnitsAction'])->name('admin.reset-units.action');
Route::get('/admin/complete-units', [AdminController::class, 'completeUnits'])->name('admin.complete-units');
Route::post('/admin/complete-units/action', [AdminController::class, 'completeUnitsAction'])->name('admin.complete-units.action');
Route::get('/admin/pengurusan-staff', [AdminController::class, 'pengurusanStaff'])->name('admin.pengurusan-staff');
Route::get('/admin/pengurusan-staff/export-csv', [AdminController::class, 'pengurusanStaffExportCsv'])->name('admin.pengurusan-staff.export-csv');
Route::post('/admin/pengurusan-staff/edit-user', [AdminController::class, 'pengurusanStaffEditUser'])->name('admin.pengurusan-staff.edit-user');
Route::post('/admin/pengurusan-staff/delete-user', [AdminController::class, 'pengurusanStaffDeleteUser'])->name('admin.pengurusan-staff.delete-user');
Route::get('/admin/pengurusan-penyelia', [AdminController::class, 'pengurusanPenyelia'])->name('admin.pengurusan-penyelia');
Route::post('/admin/pengurusan-penyelia/edit-user', [AdminController::class, 'pengurusanPenyeliaEditUser'])->name('admin.pengurusan-penyelia.edit-user');
Route::post('/admin/pengurusan-penyelia/delete-user', [AdminController::class, 'pengurusanPenyeliaDeleteUser'])->name('admin.pengurusan-penyelia.delete-user');

Route::get('/admin/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
Route::post('/admin/laporan/edit-user', [AdminController::class, 'pengurusanStaffEditUser'])->name('admin.laporan.edit-user');
Route::post('/admin/laporan/delete-user', [AdminController::class, 'pengurusanStaffDeleteUser'])->name('admin.laporan.delete-user');
Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
Route::post('/admin/profile/info', [AdminController::class, 'profileUpdateInfo'])->name('admin.profile.info');
Route::post('/admin/profile/password', [AdminController::class, 'profileUpdatePassword'])->name('admin.profile.password');
Route::post('/admin/profile/photo', [AdminController::class, 'profileUploadPhoto'])->name('admin.profile.photo');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::get('/logout-legacy', function (Request $request) {
    $request->session()->flush();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->name('logout.legacy');
