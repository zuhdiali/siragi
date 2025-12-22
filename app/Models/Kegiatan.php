<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\POK;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis_kak',
        'singkatan_resmi',
        'kak1_latar_belakang',
        'kak2_maksud',
        'kak2_tujuan',
        'kak3_target',
        'kak2_target2',
        'kak4_pjk',
        'tgl_mulai',
        'tgl_selesai',
        'kak6_pengadaan',
        'kak6_program',
        'kak6_aktivitas',
        'kak6_kro',
        'kak6_ro',
        'kak6_komponen',
        'kak6_sub_komponen',
        'kak6_pembiayaan',
        'kak6_total',
        'kak8_tgl',
        'kak8_pengaju',
        'is_approved',
        'satuan_honor_pengawasan',
        'honor_pengawasan',
        'satuan_honor_pencacahan',
        'honor_pencacahan',
        'flag_pembayaran',
        'id_pjk',
        'progress',
        'tim',
        'beban_anggaran'
    ];

    public function mitra(): BelongsToMany
    {
        return $this->belongsToMany(Mitra::class, 'kegiatan_mitras')->withPivot('jumlah', 'is_pml', 'honor', 'estimasi_honor');
    }

    public function pegawai(): BelongsToMany
    {
        return $this->belongsToMany(Pegawai::class, 'kegiatan_pegawais')->withPivot('translok');
    }

    public function kegiatanMitra()
    {
        return $this->hasMany(KegiatanMitra::class, 'kegiatan_id');
    }

    public function kegiatanLampiran()
    {
        return $this->hasMany(KegiatanLampiran::class, 'kegiatan_id');
    }

    public function kegiatanRincian()
    {
        return $this->hasMany(KegiatanRincian::class, 'kegiatan_id');
    }

    /**
     * Relasi untuk kak6_program ke tabel POK (berdasarkan ID)
     */
    public function pokProgram(): BelongsTo
    {
        // Parameter 2: Nama kolom FK di tabel kegiatan
        // Parameter 3: Nama kolom PK di tabel pok (id)
        return $this->belongsTo(POK::class, 'kak6_program', 'id');
    }

    /**
     * Relasi untuk kak6_aktivitas ke tabel POK
     */
    public function pokAktivitas(): BelongsTo
    {
        return $this->belongsTo(POK::class, 'kak6_aktivitas', 'id');
    }

    /**
     * Relasi untuk kak6_kro ke tabel POK
     */
    public function pokKro(): BelongsTo
    {
        return $this->belongsTo(POK::class, 'kak6_kro', 'id');
    }

    /**
     * Relasi untuk kak6_ro ke tabel POK
     */
    public function pokRo(): BelongsTo
    {
        return $this->belongsTo(POK::class, 'kak6_ro', 'id');
    }

    /**
     * Relasi untuk kak6_komponen ke tabel POK
     */
    public function pokKomponen(): BelongsTo
    {
        return $this->belongsTo(POK::class, 'kak6_komponen', 'id');
    }

    /**
     * Relasi untuk kak6_sub_komponen ke tabel POK
     */
    public function pokSubKomponen(): BelongsTo
    {
        return $this->belongsTo(POK::class, 'kak6_sub_komponen', 'id');
    }
}
