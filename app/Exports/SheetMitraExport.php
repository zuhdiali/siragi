<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder; // <-- 1. Import
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;   // <-- 2. Import


class SheetMitraExport extends StringValueBinder implements FromCollection, WithHeadings, WithTitle, WithMapping, WithCustomValueBinder
{
    protected $kegiatan;

    public function __construct(Kegiatan $kegiatan)
    {
        $this->kegiatan = $kegiatan;
    }

    public function collection()
    {
        // Mengambil data dari relasi 'pegawai' di model Kegiatan
        return $this->kegiatan->mitra;
    }

    public function headings(): array
    {
        return [
            'nik',
            'nama',
        ];
    }

    public function map($mitra): array
    {
        return [
            $mitra->nik,
            $mitra->nama,
        ];
    }

    public function title(): string
    {
        return 'M 11 01 ';
    }
}
