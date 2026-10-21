<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalaryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role?->name === 'hrd';
    }

    public function rules(): array
    {
        $salaryId = $this->route('salary')?->id;

        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('salaries', 'user_id')->ignore($salaryId),
            ],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'allowance' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Pilih karyawan.',
            'user_id.exists' => 'Karyawan tidak ditemukan.',
            'user_id.unique' => 'Gaji untuk karyawan ini sudah ada.',
            'basic_salary.required' => 'Gaji pokok harus diisi.',
            'basic_salary.min' => 'Gaji pokok tidak boleh negatif.',
            'allowance.required' => 'Tunjangan harus diisi.',
            'allowance.min' => 'Tunjangan tidak boleh negatif.',
        ];
    }
}
