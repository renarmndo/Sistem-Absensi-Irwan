<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaryStoreRequest;
use App\Models\Salary;
use App\Models\User;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = Salary::with('user')
            ->latest('effective_at')
            ->paginate(20);

        return view('salaries.index', compact('salaries'));
    }

    public function create()
    {
        $employeeIdsWithSalary = Salary::pluck('user_id');

        $employees = User::whereHas('role', fn ($q) => $q->where('name', 'karyawan'))
            ->whereNotIn('id', $employeeIdsWithSalary)
            ->orderBy('name')
            ->get();

        return view('salaries.create', compact('employees'));
    }

    public function store(SalaryStoreRequest $request)
    {
        Salary::create([...$request->validated(), 'effective_at' => now()]);

        return redirect()
            ->route('salaries.index')
            ->with('success', 'Data gaji berhasil ditambahkan.');
    }

    public function edit(Salary $salary)
    {
        $employees = User::whereHas('role', fn ($q) => $q->where('name', 'karyawan'))
            ->orderBy('name')
            ->get();

        return view('salaries.edit', compact('salary', 'employees'));
    }

    public function update(SalaryStoreRequest $request, Salary $salary)
    {
        $salary->update([...$request->validated(), 'effective_at' => now()]);

        return redirect()
            ->route('salaries.index')
            ->with('success', 'Data gaji berhasil diperbarui.');
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();

        return redirect()
            ->route('salaries.index')
            ->with('success', 'Data gaji berhasil dihapus.');
    }
}
