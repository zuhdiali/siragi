<?php

namespace App\Exports;

use App\Models\Kegiatan; // <-- Penting: Import model Kegiatan
use App\Models\KegiatanLampiran;
use App\Models\KegiatanRincian;
use App\Models\Mitra;
use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

// Tiga library di bawah ini berguna untuk mengonversi NIK ke format teks, agar tidak menjadi seperti ini formatnya: 1,10907E+15
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder; // <-- 1. Import
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;   // <-- 2. Import

class ExportPJKegiatan extends StringValueBinder implements FromCollection, WithHeadings, WithMapping, WithCustomValueBinder
{
    // protected $kegiatan;

    // /**
    //  * Kita menggunakan constructor untuk menerima objek Kegiatan
    //  * dari controller.
    //  */
    // public function __construct(Kegiatan $kegiatan)
    // {
    //     $this->kegiatan = $kegiatan;
    // }

    /**
     * Method ini akan mengambil data relasi mitra dari
     * objek kegiatan yang sudah kita simpan.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Kegiatan::where('jenis_kak', 'honor-mitra')->get(); // Ini akan mengembalikan collection Mitra yang berelasi
    }

    /**
     * Method ini untuk mendefinisikan judul setiap kolom di Excel.
     */
    public function headings(): array
    {
        return [
            'NamaPJK',          // A
            'NamaKegiatan',     // B
            'MitraTerlibat',    // C
            'HonorPPL',         // D
            'HonorPML',         // F
            'SatuanHonor'       // G
        ];
    }

    /**
     * Method ini untuk memetakan/memformat data setiap baris.
     * Di sinilah kita akan mengakses data pivot.
     * @param mixed $mitra
     */
    public function map($kegiatan): array
    {
        $kegiatanLampiran = KegiatanLampiran::where('kegiatan_id', $kegiatan->id)->get();

        // Mengambil mitra terlibat dari lampiran kegiatan
        $id_mitra = [];
        $mitraTerlibat = "";
        foreach ($kegiatanLampiran as $lampiran) {
            if ($lampiran->tipe_personil == 'mitra' && !in_array($lampiran->peserta_id, $id_mitra)) {
                $id_mitra[] = $lampiran->peserta_id;
                $mitra = Mitra::find($lampiran->peserta_id);
                $mitraTerlibat .= $mitra ? $mitra->nama . ", " : '';
            }
        }
        $mitraTerlibat = rtrim($mitraTerlibat, ", ");

        // Mencari nama PJK berdasarkan id_pjk di tabel kegiatan
        $pjk = Pegawai::find($kegiatan->id_pjk);

        // Mengambil satuan honor berdasarkan akun
        $akunBelanja = KegiatanRincian::where('kegiatan_id', $kegiatan->id)->first();

        return [
            $pjk ? $pjk->nama : '', // Asumsi di model Pegawai ada kolom 'nama'
            $kegiatan->singkatan_resmi,
            $mitraTerlibat,
            $kegiatan->honor_pencacahan ? $kegiatan->honor_pencacahan : ($akunBelanja ? $akunBelanja->harga_satuan : 0), // Jika honor_pencacahan tidak null, gunakan itu. Jika null, gunakan harga_satuan dari rincian kegiatan.
            $kegiatan->honor_pengawasan ? $kegiatan->honor_pengawasan : 0, // Jika honor_pengawasan tidak null, gunakan itu. Jika null, gunakan harga_satuan dari rincian kegiatan.
            $kegiatan->satuan_honor_pencacahan ? $kegiatan->satuan_honor_pencacahan : ($akunBelanja ? $akunBelanja->satuan : ''), // Jika satuan_honor_pencacahan tidak null, gunakan itu. Jika null, gunakan satuan dari rincian kegiatan.
        ];
    }
}
