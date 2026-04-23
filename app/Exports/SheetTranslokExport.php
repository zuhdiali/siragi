<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

// 2. Tambahkan WithTitle di sini
class SheetTranslokExport extends StringValueBinder implements FromCollection, WithHeadings, WithMapping, WithCustomValueBinder, WithTitle
{
    // ... (Semua kode constructor, collection, headings, map Anda tetap sama)
    // ... (Tidak ada yang perlu diubah di sini)
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

    public function collection()
    {
        return $this->kegiatan->mitra;
    }

    public function headings(): array
    {
        return ['NIP Lama', 'Tujuan ke-', 'Asal', 'Tujuan', 'Berangkat (yyyy-mm-dd)', 'Pulang (yyyy-mm-dd)'];
    }

    public function map($mitra): array
    {
        return [
            $mitra->nik,
            '1101' . $this->tujuan,
            '1101' . $mitra->kec_asal,
            '1101' . $this->tujuan,
            $this->tgl_mulai,
            $this->tgl_selesai,
        ];
    }

    /**
     * 3. TAMBAHKAN METHOD INI UNTUK MEMBERI NAMA SHEET
     */
    public function title(): string
    {
        return 'Data Translok Mitra';
    }
}
