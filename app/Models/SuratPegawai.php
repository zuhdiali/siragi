<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPegawai extends Model
{
    use HasFactory;

    protected $table = 'surat_pegawais';

    protected $fillable = [
        'pegawai_id',
        'surat_id',
        'nominal',
        'bukti_pembayaran_id',
    ];
}
