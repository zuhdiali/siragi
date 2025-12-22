<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POK extends Model
{
    use HasFactory;
    protected $table = 'pok';

    protected $fillable = [
        'id',
        'kode_program',
        'kode_aktivitas',
        'kode_klasifikasi_rincian_output',
        'kode_rincian_output',
        'kode_komponen',
        'kode_sub_komponen',
        'kode_akun',
        'uraian'
    ];
}
