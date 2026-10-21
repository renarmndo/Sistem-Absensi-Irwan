<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role?->name === 'karyawan';
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:in,out'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'selfie' => ['required', 'string', 'regex:/^data:image\/(jpeg|png|jpg);base64,[a-zA-Z0-9+\/=]+$/'],
            'accuracy' => ['nullable', 'numeric', 'min:0', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Tipe absensi harus diisi.',
            'type.in' => 'Tipe absensi tidak valid.',
            'latitude.required' => 'Lokasi GPS tidak ditemukan. Aktifkan GPS Anda.',
            'latitude.between' => 'Koordinat latitude tidak valid.',
            'longitude.required' => 'Lokasi GPS tidak ditemukan. Aktifkan GPS Anda.',
            'longitude.between' => 'Koordinat longitude tidak valid.',
            'selfie.required' => 'Foto selfie wajib diambil.',
        ];
    }
}
