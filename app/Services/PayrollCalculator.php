<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\OfficeSetting;
use App\Models\Payroll;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PayrollCalculator
{
    public const MONTHS_INDONESIAN = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    public function calculateForUser(User $user, int $month, int $year): array
    {
        $salary = Salary::where('user_id', $user->id)->first();

        $office = OfficeSetting::first();
        $effectiveWorkDays = $office?->effective_work_days ?? 20;

        $basicSalary = $salary?->basic_salary ?? 0;
        $allowance = $salary?->allowance ?? 0;

        $totalAttendance = $this->countAttendance($user->id, $month, $year);
        $totalAlfa = $this->countAlfa($user->id, $month, $year, $effectiveWorkDays);

        $dailyRate = $effectiveWorkDays > 0 ? $basicSalary / $effectiveWorkDays : 0;
        $cuts = round($dailyRate * $totalAlfa, 2);
        $takeHomePay = max(0, $basicSalary + $allowance - $cuts);

        return [
            'user_id' => $user->id,
            'period_month' => $month,
            'period_year' => $year,
            'total_attendance' => $totalAttendance,
            'total_absence_alfa' => $totalAlfa,
            'basic_salary' => $basicSalary,
            'allowance' => $allowance,
            'cuts' => $cuts,
            'take_home_pay' => $takeHomePay,
            'approval_status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
        ];
    }

    public function countAttendance(int $userId, int $month, int $year): int
    {
        return Attendance::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereNotNull('clock_in')
            ->count();
    }

    public function countAlfa(int $userId, int $month, int $year, int $effectiveWorkDays): int
    {
        $firstDay = "{$year}-{$month}-01";
        $lastDay = date('Y-m-t', strtotime($firstDay));

        $holidayCount = Holiday::whereBetween('holiday_date', [$firstDay, $lastDay])->count();

        $approvedLeaves = LeaveRequest::where('user_id', $userId)
            ->where('status', 'Approved')
            ->where(function ($q) use ($userId, $firstDay, $lastDay) {
                $q->whereBetween('start_date', [$firstDay, $lastDay])
                    ->orWhereBetween('end_date', [$firstDay, $lastDay])
                    ->orWhere(function ($q2) use ($firstDay, $lastDay) {
                        $q2->where('start_date', '<=', $firstDay)
                            ->where('end_date', '>=', $lastDay);
                    });
            })
            ->get()
            ->sum(function ($leave) use ($firstDay, $lastDay) {
                $start = max($leave->start_date->format('Y-m-d'), $firstDay);
                $end = min($leave->end_date->format('Y-m-d'), $lastDay);

                return (int) (strtotime($end) - strtotime($start)) / 86400 + 1;
            });

        $totalAttendance = $this->countAttendance($userId, $month, $year);

        $alfa = $effectiveWorkDays - $totalAttendance - $holidayCount - $approvedLeaves;

        return max(0, $alfa);
    }

    public function generatePayroll(int $month, int $year): Collection
    {
        return DB::transaction(function () use ($month, $year) {
            $employees = User::whereHas('role', function ($q) {
                $q->where('name', 'karyawan');
            })->get();

            $results = collect();

            foreach ($employees as $employee) {
                $data = $this->calculateForUser($employee, $month, $year);

                $payroll = Payroll::updateOrCreate(
                    [
                        'user_id' => $employee->id,
                        'period_month' => $month,
                        'period_year' => $year,
                    ],
                    $data
                );

                $results->push($payroll);
            }

            return $results;
        });
    }

    public static function monthName(int $month): string
    {
        return self::MONTHS_INDONESIAN[$month] ?? 'Unknown';
    }
}
