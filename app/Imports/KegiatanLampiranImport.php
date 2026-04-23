<?php

namespace App\Imports;

use App\Models\Mitra;
use App\Models\Kegiatan;
use App\Models\Pegawai;
use App\Models\KegiatanLampiran;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KegiatanLampiranImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2; // Mulai dari baris kedua (abaikan header)
    }

    public function model(array $row)
    {
        if (!isset($row[0]) || !isset($row[2]) || !isset($row[3]) || !isset($row[4]) || !isset($row[5]) || !isset($row[6]) || !isset($row[7]) || !isset($row[8]) || !isset($row[9]) || !isset($row[10]) || !isset($row[11])) {
            return null; // Abaikan baris yang tidak lengkap
        }

        $mitra = null;
        $pegawai = null;
        $kegiatan = null;
        if ($row[3] == 'mitra') {
            $mitra = Mitra::find($row[0]);
        } else if ($row[3] == 'pegawai') {
            $pegawai = Pegawai::find($row[0]);
        }
        $kegiatan = Kegiatan::find($row[2]);
        return new KegiatanLampiran([
            'peserta_id' => $row[0],
            'kegiatan_id' => $row[2],
            'tipe_personil' => $row[3],
            'pcl_or_pml' => $row[4],
            'kec_tujuan' => $row[5],
            'nama_sls' => $row[6],
            'lampiran_tgl_mulai' =>  is_numeric($row[7]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7])->format('Y-m-d') : $row[7],
            'lampiran_tgl_selesai' =>  is_numeric($row[8]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[8])->format('Y-m-d') : $row[8],
            'jml_sampel_pcl' => $row[9],
            'tipe_pengawas' => $row[10],
            'pengawas_id' => $row[11],
            'nip_nik' => $mitra ? $mitra->nik : ($pegawai ? $pegawai->nip : null),
            'transport_bayar' => $row[4] == 1 ? ($kegiatan->honor_pengawasan ?? 0) : ($kegiatan->honor_pencacahan ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
