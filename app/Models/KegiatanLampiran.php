<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanLampiran extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_lampirans';

    protected $fillable = [
        'peserta_id',
        'kegiatan_id',
        'tipe_personil',
        'nip_nik',
        'kec_tujuan',
        'tgl_pelaksanaan',
        'pcl_diawasi',
        'jml_sampel_pcl',
        'jml_sampel_diawasi',
        'jml_ok',
        'transport_bayar',
        'created_at',
        'updated_at'
    ];

    public function peserta()
    {
        if ($this->tipe_personil == 'pegawai') {
            return $this->belongsTo(Pegawai::class, 'peserta_id');
        } else {
            return $this->belongsTo(Mitra::class, 'peserta_id');
        }
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}
