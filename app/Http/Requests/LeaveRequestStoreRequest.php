<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role?->name === 'karyawan';
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:Izin,Sakit,Cuti'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Pilih jenis pengajuan.',
            'type.in' => 'Jenis pengajuan tidak valid.',
            'start_date.required' => 'Tanggal mulai harus diisi.',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'end_date.required' => 'Tanggal selesai harus diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'reason.required' => 'Alasan pengajuan harus diisi.',
            'reason.max' => 'Alasan maksimal 2000 karakter.',
            'attachment.mimes' => 'Lampiran harus berupa file: jpg, png, pdf, doc, docx.',
            'attachment.max' => 'Ukuran lampiran maksimal 2MB.',
        ];
    }
}
