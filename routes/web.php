<?php

use App\Http\Controllers\InviteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\THLReportController;
use App\Http\Controllers\THLController;


/*
|--------------------------------------------------------------------------
| ROUTES TANPA AUTH (login / register)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/wel', function () {
    return view('welcome');
});



/*
|--------------------------------------------------------------------------
| ROUTES DENGAN AUTH
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /* ===================== Dashboard (All roles) ====================== */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* ===================== Layout ====================== */
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    /* ===================== THL (Admin & Staff only) ====================== */
    Route::get('/thl', [THLController::class, 'index'])->name('THL.index')->middleware('role:admin,staff');
    Route::post('/thl/import', [THLController::class, 'import'])->name('THL.import')->middleware('role:admin');


    /* ===================== Rekap THL (All roles) ====================== */
    Route::get('/Athl/report', [THLReportController::class, 'index'])->name('Athl.report');

    // EXPORT CHECK TOKEN
    Route::get('/check-download', function (Illuminate\Http\Request $request) {

        $token = $request->query('token');

        if (!$token) {
            return response()->json(['finished' => false]);
        }

        $key = "download_{$token}_finished";
        $finished = session()->pull($key, false);

        return response()->json([
            'finished' => (bool) $finished
        ]);
    });


    /* ===================== Monthly Report ====================== */
    Route::get('/monthly-report', [MonthlyReportController::class, 'index'])->name('monthly.report');
    Route::post('/monthly-report/import', [MonthlyReportController::class, 'import'])->name('reportmonthly.import')->middleware('role:admin');
    Route::post('/update-keterangan/{id}', [MonthlyReportController::class, 'updateKeterangan'])->middleware('role:admin,staff');
    Route::get('/monthly-report/export', [MonthlyReportController::class, 'export'])->name('reportmonthly.export');
    Route::get('/monthly-report/fetch', [MonthlyReportController::class, 'fetchData'])->name('monthly.report.fetch');


    /* ===================== Invite (Admin only) ====================== */
    Route::middleware('role:admin')->group(function () {
        Route::get('/invites', [InviteController::class, 'index'])->name('invites.index');
        Route::get('/invites/create', [InviteController::class, 'invite'])->name('invites.invite');
        Route::post('/invites', [InviteController::class, 'store'])->name('invites.store');
        Route::get('/invites/{id}/edit', [InviteController::class, 'edit'])->name('invites.edit');
        Route::put('/invites/{id}', [InviteController::class, 'update'])->name('invites.update');
        Route::delete('/invites/bulk-delete', [InviteController::class, 'bulkDelete'])->name('invites.bulkDelete');
        Route::delete('/invites/{id}', [InviteController::class, 'destroy'])->name('invites.destroy');
    });


    /* ===================== Settings (Semua user bisa ubah password sendiri) ====================== */
    Route::prefix('settings')->group(function () {
        Route::get('/setting', [SettingsController::class, 'index'])->name('setting');
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/update-username', [SettingsController::class, 'updateUsername'])->name('settings.updateUsername');
        Route::post('/update-password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
    });

});

