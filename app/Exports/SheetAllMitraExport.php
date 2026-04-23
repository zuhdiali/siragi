<?php

namespace App\Exports;

use App\Models\Mitra;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder; // <-- 1. Import
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;   // <-- 2. Import


class SheetAllMitraExport extends StringValueBinder implements FromCollection, WithHeadings, WithTitle, WithMapping, WithCustomValueBinder
{
    public function collection()
    {
        // Mengambil data dari relasi 'pegawai' di model Kegiatan
        return Mitra::where('flag', null)->get();
    }

    public function headings(): array
    {
        return [
            'mitra_id',
            'nama',
        ];
    }

    public function map($mitra): array
    {
        return [
            $mitra->id,
            $mitra->nama,
        ];
    }

    public function title(): string
    {
        return 'M 11 01 ';
    }
}
