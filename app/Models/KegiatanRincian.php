<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanRincian extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_rincians';

    protected $fillable = [
        'kegiatan_id',
        'pok_id',
        'rincian',
        'vol',
        'satuan',
        'harga_satuan',
        'jumlah',
        'created_at',
        'updated_at'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function pok()
    {
        return $this->belongsTo(POK::class, 'pok_id');
    }
}
