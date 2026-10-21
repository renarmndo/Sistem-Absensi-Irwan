<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\OfficeSettingController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\UserController;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\OfficeSetting;
use App\Models\Payroll;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $today = now()->format('Y-m-d');
    $office = OfficeSetting::first();

    if ($user->isHrd()) {
        $totalKaryawan = \App\Models\User::whereHas('role', fn ($q) => $q->where('name', 'karyawan'))->count();

        $todayAttendances = Attendance::with('user')
            ->whereDate('date', $today)
            ->get();

        $pendingLeaves = LeaveRequest::with('user')
            ->where('status', 'Pending')
            ->latest()
            ->limit(5)
            ->get();

        $pendingCount = LeaveRequest::where('status', 'Pending')->count();
        $approvedToday = LeaveRequest::where('status', 'Approved')->whereDate('updated_at', $today)->count();
        $rejectedToday = LeaveRequest::where('status', 'Rejected')->whereDate('updated_at', $today)->count();

        return view('dashboard-hrd', compact(
            'user', 'office', 'totalKaryawan', 'todayAttendances',
            'pendingLeaves', 'pendingCount', 'approvedToday', 'rejectedToday'
        ));
    }

    $todayAttendance = Attendance::where('user_id', $user->id)
        ->whereDate('date', $today)
        ->first();

    $monthStart = now()->startOfMonth()->format('Y-m-d');
    $monthSummary = [
        'hadir' => Attendance::where('user_id', $user->id)->whereDate('date', '>=', $monthStart)->where('status', 'Hadir')->count(),
        'terlambat' => Attendance::where('user_id', $user->id)->whereDate('date', '>=', $monthStart)->where('status', 'Terlambat')->count(),
        'izin' => LeaveRequest::where('user_id', $user->id)->where('start_date', '<=', $today)->where('end_date', '>=', $today)->where('status', 'Approved')->count(),
    ];
    $monthSummary['alfa'] = max(0, now()->day - $monthSummary['hadir'] - $monthSummary['terlambat'] - $monthSummary['izin'] - now()->copy()->endOfMonth()->diffInDays(now()));

    $recentHistory = Attendance::where('user_id', $user->id)
        ->whereDate('date', '>=', now()->subDays(7)->format('Y-m-d'))
        ->orderByDesc('date')
        ->get();

    $recentLeave = LeaveRequest::where('user_id', $user->id)
        ->latest()
        ->first();

    $latestPayroll = Payroll::where('user_id', $user->id)
        ->latest()
        ->first();

    return view('dashboard', compact(
        'user', 'todayAttendance', 'monthSummary', 'recentHistory', 'recentLeave', 'latestPayroll', 'office'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('presensi')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::post('/', [AttendanceController::class, 'store'])->name('store');
        Route::get('/riwayat', [AttendanceController::class, 'history'])->name('history');
    });

    Route::prefix('pengajuan')->name('leave-requests.')->group(function () {
        Route::get('/', [LeaveRequestController::class, 'index'])->name('index');
        Route::get('/buat', [LeaveRequestController::class, 'create'])->name('create');
        Route::post('/', [LeaveRequestController::class, 'store'])->name('store');
        Route::post('/{leaveRequest}/setuju', [LeaveRequestController::class, 'approve'])->name('approve');
        Route::post('/{leaveRequest}/tolak', [LeaveRequestController::class, 'reject'])->name('reject');
    });

    Route::prefix('karyawan')->name('users.')->middleware('can:hrd')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/buat', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('gaji')->name('salaries.')->middleware('can:hrd')->group(function () {
        Route::get('/', [SalaryController::class, 'index'])->name('index');
        Route::get('/buat', [SalaryController::class, 'create'])->name('create');
        Route::post('/', [SalaryController::class, 'store'])->name('store');
        Route::get('/{salary}/edit', [SalaryController::class, 'edit'])->name('edit');
        Route::put('/{salary}', [SalaryController::class, 'update'])->name('update');
        Route::delete('/{salary}', [SalaryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pengaturan-kantor')->name('office-settings.')->middleware('can:pengelola')->group(function () {
        Route::get('/', [OfficeSettingController::class, 'edit'])->name('edit');
        Route::put('/', [OfficeSettingController::class, 'update'])->name('update');
    });

    Route::prefix('payroll')->name('payrolls.')->group(function () {
        Route::get('/', [PayrollController::class, 'index'])->name('index');
        Route::get('/buat', [PayrollController::class, 'create'])->name('create');
        Route::post('/', [PayrollController::class, 'generate'])->name('store');
        Route::get('/{payroll}', [PayrollController::class, 'show'])->name('show');
        Route::get('/{payroll}/pdf', [PayrollController::class, 'pdf'])->name('pdf');
    });

    Route::prefix('laporan')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
        Route::get('/pdf', [ReportController::class, 'exportPdf'])->name('pdf');
        Route::get('/excel', [ReportController::class, 'exportExcel'])->name('excel');
        Route::post('/payroll/{payroll}/setujui', [ReportController::class, 'approvePayroll'])->name('approve-payroll');
        Route::post('/payroll/{payroll}/tolak', [ReportController::class, 'rejectPayroll'])->name('reject-payroll');
    });
});

require __DIR__.'/auth.php';
