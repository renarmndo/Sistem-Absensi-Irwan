<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\OfficeSetting;
use App\Services\GeofenceService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly GeofenceService $geofenceService
    ) {}

    public function index(): View
    {
        $user = Auth::user();
        $today = Carbon::today();

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $office = OfficeSetting::first();

        $histories = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return view('attendance.index', compact('todayAttendance', 'office', 'histories'));
    }

    public function history(Request $request): View
    {
        $user = Auth::user();

        $query = Attendance::where('user_id', $user->id);

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(15);

        $summary = (object) [
            'total' => Attendance::where('user_id', $user->id)->count(),
            'hadir' => Attendance::where('user_id', $user->id)->where('status', 'Hadir')->count(),
            'terlambat' => Attendance::where('user_id', $user->id)->where('status', 'Terlambat')->count(),
        ];

        return view('attendance.history', compact('attendances', 'summary'));
    }

    public function store(AttendanceRequest $request): JsonResponse|RedirectResponse
    {
        $office = OfficeSetting::first();

        if (! $office) {
            return $this->error('Konfigurasi kantor belum diatur. Hubungi HRD.');
        }

        $distance = $this->geofenceService->getDistance(
            (float) $request->latitude,
            (float) $request->longitude,
            $office,
        );

        if ($distance > $office->radius_meter) {
            return $this->error(
                'Anda berada di luar radius kantor. Jarak Anda: '
                .number_format($distance, 0, ',', '.')
                .' meter (maksimal '.$office->radius_meter.' meter).'
            );
        }

        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();

        if ($request->type === 'in') {
            return DB::transaction(function () use ($request, $user, $today, $now, $office, $distance) {
                $existing = Attendance::where('user_id', $user->id)
                    ->whereDate('date', $today)
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    return $this->error('Anda sudah melakukan absen masuk hari ini.');
                }

                $selfiePath = $this->saveSelfie($request->selfie, 'in');

                $status = GeofenceService::status($now->format('H:i:s'), $office->work_start_time);

                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'clock_in' => $now,
                    'lat_in' => $request->latitude,
                    'long_in' => $request->longitude,
                    'distance_in' => $distance,
                    'selfie_in' => $selfiePath,
                    'status' => $status,
                ]);

                $message = $status === 'Hadir'
                    ? 'Absen masuk berhasil. Anda hadir tepat waktu.'
                    : 'Absen masuk berhasil. Anda tercatat terlambat.';

                return $this->success($message);
            });
        }

        if ($request->type === 'out') {
            return DB::transaction(function () use ($request, $user, $today, $now, $distance) {
                $attendance = Attendance::where('user_id', $user->id)
                    ->whereDate('date', $today)
                    ->lockForUpdate()
                    ->first();

                if (! $attendance) {
                    return $this->error('Anda belum melakukan absen masuk hari ini.');
                }

                if ($attendance->clock_out) {
                    return $this->error('Anda sudah melakukan absen pulang hari ini.');
                }

                $selfiePath = $this->saveSelfie($request->selfie, 'out');

                $attendance->update([
                    'clock_out' => $now,
                    'lat_out' => $request->latitude,
                    'long_out' => $request->longitude,
                    'distance_out' => $distance,
                    'selfie_out' => $selfiePath,
                ]);

                return $this->success('Absen pulang berhasil.');
            });
        }

        return $this->error('Tipe absensi tidak valid.');
    }

    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    private function saveSelfie(string $base64, string $type): string
    {
        preg_match('/^data:image\/(\w+);base64,/', $base64, $matches);
        $extension = strtolower($matches[1] ?? 'jpg');

        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            $extension = 'jpg';
        }

        $imageData = substr($base64, strpos($base64, ',') + 1);
        $imageData = base64_decode($imageData);

        if ($imageData === false) {
            throw new \RuntimeException('Data selfie tidak valid.');
        }

        $filename = sprintf(
            'selfies/%s/%s_%s_%s.%s',
            Auth::id(),
            $type,
            now()->format('YmdHis'),
            Str::random(4),
            $extension === 'jpeg' ? 'jpg' : $extension,
        );

        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }

    private function success(string $message): JsonResponse|RedirectResponse
    {
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('attendance.index')->with('success', $message);
    }

    private function error(string $message): JsonResponse|RedirectResponse
    {
        if (request()->expectsJson()) {
            return response()->json(['success' => false, 'error' => $message], 422);
        }

        return back()->withErrors(['error' => $message]);
    }
}
