<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surats';

    protected $fillable = [
        'nomor_surat',
        'jenis_surat',
        'tipe_surat',
        'tim',
        'no_terakhir',
        'tgl_awal_kegiatan',
        'tgl_akhir_kegiatan',
        'tgl_surat',
        'perihal',
        'no_surat_masuk',
        'dinas_surat_masuk',
        'id_pembuat_surat',
        'id_kegiatan',
        'file',
        'pegawai_yang_bertugas',
        'mitra_spk',
        'bulan_spk',
        'tahun_spk',
        'surat_tugas_id',
        'spd_id',
        'flag',
    ];
}
