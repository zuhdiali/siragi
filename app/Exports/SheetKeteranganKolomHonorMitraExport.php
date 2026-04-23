<?php

namespace App\Exports;

use App\Models\Mitra;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class SheetKeteranganKolomHonorMitraExport extends StringValueBinder implements WithHeadings, WithTitle, FromCollection
{

    public function headings(): array
    {
        return [
            'kolom',
            'keterangan',
        ];
    }

    public function collection()
    {
        return collect([
            ['kolom' => 'peserta_id', 'keterangan' => 'ID mitra sesuai dengan Sheet M 11 01'],
            ['kolom' => 'kegiatan_id', 'keterangan' => 'ID kegiatan (bisa dilihat di menu kegiatan).'],
            ['kolom' => 'tipe_personil', 'keterangan' => 'Jika mitra, isi dengan "mitra", jika pegawai, isi dengan "pegawai".'],
            ['kolom' => 'pcl_or_pml', 'keterangan' => 'Jika PCL, isi dengan 0, jika PML, isi dengan 1.'],
            ['kolom' => 'kec_tujuan', 'keterangan' => 'Kode kecamatan.'],
            ['kolom' => 'nama_sls', 'keterangan' => 'Nama SLS sampel. Jika tidak ada, isi dengan "-".'],
            ['kolom' => 'lampiran_tgl_mulai', 'keterangan' => 'Tanggal mulai kegiatan (format: YYYY-MM-DD).'],
            ['kolom' => 'lampiran_tgl_selesai', 'keterangan' => 'Tanggal selesai kegiatan (format: YYYY-MM-DD).'],
            ['kolom' => 'jml_sampel_pcl', 'keterangan' => 'Jumlah sampel yang ditangani oleh PCL.'],
            ['kolom' => 'tipe_pengawas', 'keterangan' => 'Tipe PML. Jika mitra, isi dengan "mitra". Jika pegawai, isi dengan "organik".'],
            ['kolom' => 'pengawas_id', 'keterangan' => 'ID PML, isi dengan ID mitra atau pegawai yang menjadi PML.'],
        ]);
    }

    public function title(): string
    {
        return 'Keterangan Kolom Honor Mitra';
    }
}
