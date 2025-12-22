<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMitra extends Model
{
    use HasFactory;

    protected $table = 'surat_mitras';

    protected $fillable = [
        'surat_id',
        'mitra_id',
        'is_pml',
        'jumlah',
        'honor',
        'estimasi_honor',
        'bukti_pembayaran_id',
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }
}
