<?php

namespace App\Http\Controllers;

use App\Models\OfficeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfficeSettingController extends Controller
{
    public function edit(): \Illuminate\View\View
    {
        $office = OfficeSetting::firstOrCreate([], [
            'office_name' => 'Kantor Pusat',
            'latitude' => '-6.2088',
            'longitude' => '106.8456',
            'radius_meter' => 50,
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'effective_work_days' => 20,
        ]);

        return view('office-settings.edit', compact('office'));
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'office_name' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'string', 'max:20'],
            'longitude' => ['required', 'string', 'max:20'],
            'radius_meter' => ['required', 'integer', 'min:10', 'max:500'],
            'work_start_time' => ['required'],
            'work_end_time' => ['required'],
            'effective_work_days' => ['required', 'integer', 'min:1', 'max:31'],
        ]);

        $data['work_start_time'] = str_pad($data['work_start_time'], 8, ':00');
        $data['work_end_time'] = str_pad($data['work_end_time'], 8, ':00');

        if ($data['work_end_time'] <= $data['work_start_time']) {
            return back()->withErrors(['work_end_time' => 'Jam pulang harus setelah jam masuk.']);
        }

        DB::transaction(function () use ($data) {
            $office = OfficeSetting::first();

            if ($office) {
                $office->update($data);
            } else {
                OfficeSetting::create($data);
            }
        });

        return redirect()->route('office-settings.edit')
            ->with('success', 'Pengaturan kantor berhasil diperbarui.');
    }
}
