<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportTranslok implements WithMultipleSheets
{
    protected $kegiatan;
    protected $tgl_mulai;
    protected $tgl_selesai;
    protected $tujuan;

    public function __construct(Kegiatan $kegiatan, $tgl_mulai, $tgl_selesai, $tujuan)
    {
        $this->kegiatan = $kegiatan;
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_selesai = $tgl_selesai;
        $this->tujuan = $tujuan;
    }

    /**
     * Method ini akan membuat array berisi class-class
     * yang akan menjadi sheet di file Excel.
     */
    public function sheets(): array
    {
        return [
            // Sheet 1
            new SheetTranslokExport($this->kegiatan, $this->tgl_mulai, $this->tgl_selesai, $this->tujuan),

            // Sheet 2
            new SheetMitraExport($this->kegiatan),
        ];
    }
}
