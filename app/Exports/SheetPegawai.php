<?php

namespace App\Exports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;


class SheetPegawai implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return Pegawai::where('flag', null)->get();
    }

    public function headings(): array
    {
        return [
            'id_pegawai',
            'nama',
        ];
    }

    public function map($pegawai): array
    {
        return [
            $pegawai->id,
            $pegawai->nama,
        ];
    }

    public function title(): string
    {
        return 'Data Pegawai';
    }
}
