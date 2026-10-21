<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequestStoreRequest;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (Gate::allows('hrd')) {
            $leaveRequests = LeaveRequest::with('user')
                ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 WHEN status = 'Approved' THEN 1 WHEN status = 'Rejected' THEN 2 ELSE 3 END")
                ->latest()
                ->paginate(15);
        } else {
            $leaveRequests = LeaveRequest::with('approver')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        Gate::authorize('karyawan');

        return view('leave-requests.create');
    }

    public function store(LeaveRequestStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')
                ->store('leave-attachments', 'public');
        }

        LeaveRequest::create($data);

        return redirect()
            ->route('leave-requests.index')
            ->with('success', 'Pengajuan berhasil dikirim dan menunggu persetujuan.');
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        Gate::authorize('hrd');

        if ($leaveRequest->status !== 'Pending') {
            return redirect()
                ->route('leave-requests.index')
                ->with('error', 'Pengajuan ini sudah tidak dalam status pending.');
        }

        DB::transaction(function () use ($leaveRequest) {
            $leaveRequest->update([
                'status' => 'Approved',
                'approved_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        });

        return redirect()
            ->route('leave-requests.index')
            ->with('success', 'Pengajuan izin telah disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        Gate::authorize('hrd');

        if ($leaveRequest->status !== 'Pending') {
            return redirect()
                ->route('leave-requests.index')
                ->with('error', 'Pengajuan ini sudah tidak dalam status pending.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($leaveRequest, $validated) {
            $leaveRequest->update([
                'status' => 'Rejected',
                'approved_by' => auth()->id(),
                'reviewed_at' => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);
        });

        return redirect()
            ->route('leave-requests.index')
            ->with('success', 'Pengajuan izin telah ditolak.');
    }
}
