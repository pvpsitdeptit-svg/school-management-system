<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StudentsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        // Return sample data to show the format
        return [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@school.com',
                'phone' => '+1234567890',
                'date_of_birth' => '2005-05-15',
                'gender' => 'male',
                'address' => '123 Main Street, City',
                'password' => 'student123',
                'status' => 'active',
                'admission_no' => 'STU20240001'
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@school.com',
                'phone' => '+1234567891',
                'date_of_birth' => '2005-08-22',
                'gender' => 'female',
                'address' => '456 Oak Avenue, City',
                'password' => 'student123',
                'status' => 'active',
                'admission_no' => 'STU20240002'
            ]
        ];
    }
    
    public function headings(): array
    {
        return [
            'first_name',
            'last_name',
            'email',
            'phone',
            'date_of_birth',
            'gender',
            'address',
            'password',
            'status',
            'admission_no'
        ];
    }
    
    public function title(): string
    {
        return 'Students Template';
    }
}
