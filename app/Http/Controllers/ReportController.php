<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\OfficeSetting;
use App\Models\Payroll;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function dashboard()
    {
        Gate::authorize('pengelola');

        $today = now()->format('Y-m-d');

        $todayStats = [
            'hadir' => Attendance::whereDate('date', $today)
                ->where('status', 'Hadir')->count(),
            'terlambat' => Attendance::whereDate('date', $today)
                ->where('status', 'Terlambat')->count(),
            'izin' => LeaveRequest::whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->where('status', 'Approved')
                ->count(),
        ];
        $todayStats['total'] = User::whereHas('role', fn ($q) => $q->where('name', 'karyawan'))->count();
        $todayStats['alfa'] = $todayStats['total'] - $todayStats['hadir'] - $todayStats['terlambat'] - $todayStats['izin'];

        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');

        $attendanceByDate = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select('date', 'status', DB::raw('count(*) as total'))
            ->groupBy('date', 'status')
            ->get()
            ->groupBy(fn ($item) => $item->date->format('d'));

        $monthlyStats = [];
        for ($day = 1; $day <= now()->daysInMonth; $day++) {
            $dayStr = str_pad((string) $day, 2, '0', STR_PAD_LEFT);
            $dayData = $attendanceByDate->get($dayStr, collect());
            $monthlyStats[] = [
                'date' => $day,
                'hadir' => $dayData->where('status', 'Hadir')->sum('total'),
                'terlambat' => $dayData->where('status', 'Terlambat')->sum('total'),
            ];
        }

        $latestPayrolls = Payroll::with('user')
            ->where('approval_status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        $officeName = optional(OfficeSetting::first())->office_name ?? 'Kantor';

        return view('reports.dashboard', compact(
            'todayStats', 'monthlyStats', 'latestPayrolls', 'officeName'
        ));
    }

    public function index(Request $request)
    {
        Gate::authorize('pengelola');

        $query = Attendance::with('user')
            ->select('attendances.*')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'karyawan');

        if ($request->filled('user_id')) {
            $request->validate(['user_id' => ['exists:users,id']]);
            $query->where('attendances.user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $request->validate(['date_from' => ['date']]);
            $query->whereDate('attendances.date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $request->validate(['date_to' => ['date']]);
            $query->whereDate('attendances.date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $request->validate(['status' => ['in:Hadir,Terlambat']]);
            $query->where('attendances.status', $request->status);
        }

        $attendances = $query->orderByDesc('attendances.date')->paginate(20);

        $employees = User::whereHas('role', fn ($q) => $q->where('name', 'karyawan'))
            ->orderBy('name')
            ->get();

        return view('reports.index', compact('attendances', 'employees'));
    }

    public function exportPdf(Request $request)
    {
        Gate::authorize('pengelola');

        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'status' => ['nullable', 'in:Hadir,Terlambat'],
        ]);

        $query = Attendance::with('user')
            ->select('attendances.*')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'karyawan');

        if ($request->filled('user_id')) {
            $query->where('attendances.user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('attendances.date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('attendances.date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('attendances.status', $request->status);
        }

        $attendances = $query->orderByDesc('attendances.date')->get();

        $params = $request->only(['user_id', 'date_from', 'date_to', 'status']);

        $pdf = Pdf::loadView('reports.pdf', compact('attendances', 'params'));

        return $pdf->download('laporan-absensi-'.now()->format('Ymd').'.pdf');
    }

    public function exportExcel(Request $request)
    {
        Gate::authorize('pengelola');

        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'status' => ['nullable', 'in:Hadir,Terlambat'],
        ]);

        $query = Attendance::with('user')
            ->select('attendances.*')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'karyawan');

        if ($request->filled('user_id')) {
            $query->where('attendances.user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('attendances.date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('attendances.date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('attendances.status', $request->status);
        }

        $attendances = $query->orderByDesc('attendances.date')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="laporan-absensi-'.now()->format('Ymd').'.csv"',
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['No', 'NIK', 'Nama', 'Tanggal', 'Masuk', 'Pulang', 'Status']);

            foreach ($attendances as $i => $a) {
                fputcsv($file, [
                    $i + 1,
                    $a->user->employee_id,
                    $a->user->name,
                    $a->date instanceof Carbon ? $a->date->format('d/m/Y') : $a->date,
                    $a->clock_in ? ($a->clock_in instanceof Carbon ? $a->clock_in->format('H:i') : $a->clock_in) : '-',
                    $a->clock_out ? ($a->clock_out instanceof Carbon ? $a->clock_out->format('H:i') : $a->clock_out) : '-',
                    $a->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function approvePayroll(Payroll $payroll)
    {
        Gate::authorize('pimpinan');

        $payroll->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('reports.dashboard')
            ->with('success', 'Payroll telah disetujui.');
    }

    public function rejectPayroll(Payroll $payroll)
    {
        Gate::authorize('pimpinan');

        $payroll->update([
            'approval_status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('reports.dashboard')
            ->with('success', 'Payroll telah ditolak.');
    }
}
