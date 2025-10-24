<?php

use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\SiswaRecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CalonSiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

// Custom Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/kurikulum/sync-siswa', [KurikulumController::class, 'syncSiswaByNomor'])
     ->name('kurikulum.syncSiswa')->middleware('auth');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pelajaran', MataPelajaranController::class)->except(['show'] );
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
     Route::get('/kurikulum', [KurikulumController::class, 'index'])
      ->name('kurikulum.index'); // daftar angkatan
  Route::get('/kurikulum/{angkatan}', [KurikulumController::class, 'show'])
      ->name('kurikulum.show');  // detail per angkatan


    // Calon Siswa routes
    Route::get('/calon-siswa/create', [DashboardController::class, 'create'])->name('calon-siswa.create');
    Route::post('/calon-siswa', [DashboardController::class, 'store'])->name('calon-siswa.store');
    Route::get('/calon-siswa/{id}/edit', [DashboardController::class, 'edit'])->name('calon-siswa.edit');
    Route::put('/calon-siswa/{id}', [DashboardController::class, 'update'])->name('calon-siswa.update');

    // Admin only routes
    Route::middleware(['auth','verified','isAdmin'])->group(function () {
        Route::get('/admin/create-user',  [AuthController::class, 'showCreateUserForm'])
            ->name('admin.createUser.form');
        Route::post('/admin/create-user', [AuthController::class, 'createAdmin'])
            ->name('admin.createUser');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/calon-siswa/{calonSiswa}/records', [SiswaRecordController::class, 'manage'])
->name('records.manage');

Route::post('/calon-siswa/{calonSiswa}/records/save', [SiswaRecordController::class, 'save'])
->name('records.save');

Route::get('/calon-siswas/{id}/summary', [CalonSiswaController::class, 'summary'])
    ->name('calon-siswa.summary');
Route::delete('/calon-siswa/{id}', [CalonSiswaController::class, 'destroy'])
    ->name('calon-siswa.destroy');






Route::resource('kurikulum', KurikulumController::class)->only(['index','show']);
Route::post('/kurikulum/{angkatan}/mapel', [KurikulumController::class,'attachMapel'])
     ->name('kurikulum.attachMapel');
Route::patch('/kurikulum/{angkatan}/mapel/{mapel}', [KurikulumController::class,'updateMapel'])
     ->name('kurikulum.updateMapel');
Route::delete('/kurikulum/{angkatan}/mapel/{mapel}', [KurikulumController::class,'detachMapel'])
     ->name('kurikulum.detachMapel');
Route::patch('/kurikulum/{angkatan}/periode', [KurikulumController::class, 'updatePeriode'])
    ->name('kurikulum.updatePeriode');
Route::post('/kurikulum/{angkatan}/upload', [KurikulumController::class, 'uploadFile'])
    ->name('kurikulum.uploadFile');
Route::delete('/kurikulum/{angkatan}/file/{file}', [KurikulumController::class, 'deleteFile'])
    ->name('kurikulum.deleteFile');




require __DIR__.'/auth.php';
