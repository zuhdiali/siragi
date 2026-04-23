<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\FromCollection;

class SheetKodeKecamatan extends StringValueBinder implements WithHeadings, WithTitle, FromCollection
{
    public function headings(): array
    {
        return [
            'kode_kecamatan',
            'nama_kecamatan',
        ];
    }

    public function collection()
    {
        return collect([
            ['010', 'Teupah Selatan'],
            ['020', 'Simeulue Timur'],
            ['021', 'Teupah Barat'],
            ['022', 'Teupah Tengah'],
            ['030', 'Simeulue Tengah'],
            ['031', 'Teluk Dalam '],
            ['032', 'Simeulue Cut'],
            ['040', 'Salang'],
            ['050', 'Simeulue Barat'],
            ['051', 'Alafan'],

        ]);
    }

    public function title(): string
    {
        return 'Kode Kecamatan';
    }
}
