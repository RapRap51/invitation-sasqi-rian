<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GuestTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Nama',
            'No HP',
            'Jumlah Undangan',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Budi Santoso',
                '08123456789',
                2,
            ],
            [
                'Andi Pratama',
                '081298765432',
                1,
            ],
        ];
    }
}