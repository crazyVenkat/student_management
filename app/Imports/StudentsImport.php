<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Student([
            'name'          => $row['name'],
            'email'         => $row['email'],
            'department_id' => $row['department_id'],
            'programme_id'  => $row['programme_id'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name'          => 'required|string|max:255',
            '*.email'         => 'required|email|unique:students,email',
            '*.department_id' => 'required|exists:departments,id',
            '*.programme_id'  => 'required|exists:programmes,id',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.email.unique' => 'Email already exists in system.',
        ];
    }
}
