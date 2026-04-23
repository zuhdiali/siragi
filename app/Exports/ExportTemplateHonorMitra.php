<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportTemplateHonorMitra implements WithMultipleSheets
{

    /**
     * Method ini akan membuat array berisi class-class
     * yang akan menjadi sheet di file Excel.
     */
    public function sheets(): array
    {
        return [
            // Sheet 1
            new SheetTemplateHonorExport(),

            // Sheet 2
            new SheetAllMitraExport(),

            // Sheet 3
            new SheetPegawai(),

            // Sheet 4
            new SheetKeteranganKolomHonorMitraExport(),

            // Sheet 5
            new SheetKodeKecamatan(),
        ];
    }
}
