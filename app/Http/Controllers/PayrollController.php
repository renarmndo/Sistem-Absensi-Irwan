<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Services\PayrollCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Payroll::with('user');

        if ($user->role?->name === 'karyawan') {
            $query->where('user_id', $user->id);
        }

        $payrolls = $query->latest()->paginate(20);

        return view('payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        Gate::authorize('hrd');

        $periods = Payroll::selectRaw('period_year, period_month')
            ->distinct()
            ->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->get();

        $lastPeriod = $periods->first();
        $suggestedMonth = $lastPeriod ? ($lastPeriod->period_month % 12) + 1 : now()->month;
        $suggestedYear = $lastPeriod
            ? ($lastPeriod->period_month === 12 ? $lastPeriod->period_year + 1 : $lastPeriod->period_year)
            : now()->year;

        return view('payrolls.generate', compact('suggestedMonth', 'suggestedYear'));
    }

    public function generate(Request $request)
    {
        Gate::authorize('hrd');

        $request->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2020', 'max:2099'],
        ]);

        $calculator = app(PayrollCalculator::class);
        $calculator->generatePayroll((int) $request->month, (int) $request->year);

        return redirect()
            ->route('payrolls.index')
            ->with('success', 'Payroll periode '.
                PayrollCalculator::monthName((int) $request->month).' '.$request->year.
                ' berhasil digenerate.');
    }

    public function show(Payroll $payroll)
    {
        $user = request()->user();

        if ($user->role?->name === 'karyawan' && $payroll->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $payroll->load('user');

        return view('payrolls.show', compact('payroll'));
    }

    public function pdf(Payroll $payroll)
    {
        $user = request()->user();

        if ($user->role?->name === 'karyawan' && $payroll->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        if ($user->role?->name === 'karyawan' && $payroll->approval_status !== 'approved') {
            abort(403, 'Slip gaji belum disetujui oleh pimpinan.');
        }

        $payroll->load('user');

        $pdf = Pdf::loadView('payrolls.pdf', compact('payroll'));

        $filename = 'slip-gaji-'.$payroll->user->employee_id.'-'
            .PayrollCalculator::monthName($payroll->period_month).'-'
            .$payroll->period_year.'.pdf';

        return $pdf->download($filename);
    }
}
