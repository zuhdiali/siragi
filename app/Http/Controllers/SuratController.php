<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\Surat;
use App\Models\KamusSurat;
use App\Models\Pegawai;
use App\Models\Kegiatan;
use App\Models\KegiatanRincian;
use Illuminate\Support\Facades\Auth;
use App\Models\FotoSuratMasuk;
use App\Models\KegiatanLampiran;
use App\Models\KegiatanPegawai;
use App\Models\POK;
// use PhpOffice\PhpWord\Phpword;
use Carbon\Carbon;
// use PhpOffice\PhpWord\TemplateProcessor;

class SuratController extends Controller
{
    private function tambahInformasiSurat($surats)
    {
        foreach ($surats as $surat) {
            $surat->pembuat_surat = Pegawai::find($surat->id_pembuat_surat);
            $surat->kegiatan = Kegiatan::find($surat->id_kegiatan);
            if ($surat->spk_id) {
                $surat->spk = Surat::find($surat->spk_id);
            }
            if ($surat->mitra_spk) {
                $surat->mitra = Mitra::find($surat->mitra_spk);
            }
            if ($surat->id_kegiatan) {
                $surat->kegiatan = Kegiatan::find($surat->id_kegiatan);
            }
        }
        return $surats;
    }

    public function tugas()
    {
        $surats = Surat::where('jenis_surat', 'tugas')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        foreach ($surats as $surat) {
            if ($surat->pegawai_yang_bertugas) {
                $surat->pegawai = Pegawai::find($surat->pegawai_yang_bertugas);
            }
        }
        return view('surat.tugas', ['surats' => $surats]);
    }

    public function permintaan()
    {
        $surats = Surat::where('jenis_surat', 'permintaan')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        foreach ($surats as $surat) {
            if ($surat->surat_tugas_id) {
                $st = Surat::find($surat->surat_tugas_id);
                $surat->nomor_surat_tugas = $st->nomor_surat;
            }
            if ($surat->spd_id) {
                $spd = Surat::find($surat->spd_id);
                $surat->nomor_surat_spd = $spd->nomor_surat;
            }
            if ($surat->pegawai_yang_bertugas) {
                $surat->pegawai = Pegawai::find($surat->pegawai_yang_bertugas);
            }
        }
        return view('surat.permintaan', ['surats' => $surats]);
    }

    public function masuk()
    {
        $surats = Surat::where('jenis_surat', 'masuk')->where('flag', null)->orderBy('created_at', 'desc')->get();
        // $surats = $this->tambahInformasiSurat($surats);
        return view('surat.masuk', ['surats' => $surats]);
    }

    public function rincianSuratMasuk($id)
    {
        $surat = Surat::find($id);
        $file = './uploads/surat/' . $surat->file;
        // $surat->foto_surat_masuk = FotoSuratMasuk::where('id_surat', $id)->get();
        // return view('surat.rincian-surat-masuk', ['surat' => $surat]);
        return response()->file($file);
    }

    public function keluar()
    {
        $surats = Surat::where('jenis_surat', 'keluar')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        foreach ($surats as $surat) {
            $surat->pembuat_surat = Pegawai::find($surat->id_pembuat_surat);
        }
        return view('surat.keluar', ['surats' => $surats]);
    }

    public function spd()
    {
        $surats = Surat::where('jenis_surat', 'spd')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        foreach ($surats as $surat) {
            $surat->pegawai = Pegawai::find($surat->pegawai_yang_bertugas);
        }
        return view('surat.spd', ['surats' => $surats]);
    }

    public function sk()
    {
        $surats = Surat::where('jenis_surat', 'sk')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        return view('surat.sk', ['surats' => $surats]);
    }

    public function spk()
    {
        $surats = Surat::where('jenis_surat', 'spk')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        foreach ($surats as $surat) {
            $surat->mitra = Mitra::find($surat->mitra_spk);
            $surat->bulan = $this->convertDigitBulan($surat->bulan_spk);
        }
        return view('surat.spk', ['surats' => $surats]);
    }

    public function bast()
    {
        $surats = Surat::where('jenis_surat', 'bast')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        // dd($surats);
        return view('surat.bast', ['surats' => $surats]);
    }

    public function createLama($jenis)
    {
        $kegiatans = Kegiatan::where('tim', Auth::user()->tim)->orWhere('id_pjk', Auth::user()->id)->get();
        $kegiatan_pegawais = KegiatanPegawai::where('pegawai_id', Auth::user()->id)->get();
        foreach ($kegiatan_pegawais as $kegiatan_pegawai) {
            $kegiatan = Kegiatan::find($kegiatan_pegawai->kegiatan_id);
            if ($kegiatans->contains($kegiatan)) {
                continue;
            } else {
                $kegiatans->push($kegiatan);
            }
        }
        $noTerakhir = $this->getNoSuratTerakhir($jenis);
        $opsiSuratAwal = KamusSurat::where('tim', '11012')->get();
        $kamusSuratUmum = KamusSurat::where('tim', '11011')->get();
        $kamusSuratTeknis = KamusSurat::where('tim', '11012')->orderBy('kode_surat_gabungan', 'desc')->get();
        if ($jenis != 'spk') {
            $pegawais = Pegawai::where('flag', null)->get();
            return view('surat.create', compact('jenis', 'noTerakhir', 'kamusSuratUmum', 'kamusSuratTeknis', 'kegiatans', 'opsiSuratAwal', 'pegawais'));
        } else {
            $mitras = Mitra::where('flag', null)->where('nama', 'not like', '%bayangan%')->get();
            return view('surat.create', compact('jenis', 'noTerakhir', 'kamusSuratUmum', 'kamusSuratTeknis', 'kegiatans', 'opsiSuratAwal', 'mitras'));
        }
    }

    public function create($jenis, $tipe_bast = null)
    {
        $kegiatans = collect();
        if (Auth::user()->role == 'Admin') {
            $kegiatans = Kegiatan::where('is_approved', 1)->get();
        } else {
            $kegiatans = Kegiatan::where('tim', Auth::user()->tim)->orWhere('id_pjk', Auth::user()->id)->where('is_approved', 1)->get();
            $kegiatan_lampirans = KegiatanLampiran::where('peserta_id', Auth::user()->id)->where('tipe_personil', "pegawai")->get();
            // $kegiatan_pegawais = KegiatanPegawai::where('pegawai_id', Auth::user()->id)->get();
            foreach ($kegiatan_lampirans as $kegiatan_lampiran) {
                $kegiatan = Kegiatan::find($kegiatan_lampiran->kegiatan_id);
                if (!$kegiatan) {
                    continue;
                }
                if ($kegiatans->contains($kegiatan)) {
                    continue;
                } else {
                    if ($kegiatan->is_approved == 1) {
                        $kegiatans->push($kegiatan);
                    }
                }
            }
        }

        $noTerakhir = $this->getNoSuratTerakhir($jenis);
        $opsiSuratAwal = KamusSurat::where('tim', '11012')->get();
        $kamusSuratUmum = KamusSurat::where('tim', '11011')->get();
        $kamusSuratTeknis = KamusSurat::where('tim', '11012')->orderBy('kode_surat_gabungan', 'desc')->get();
        $spks = Surat::where('jenis_surat', 'spk')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
        foreach ($spks as $spk) {
            $spk->mitra = Mitra::find($spk->mitra_spk);
        }
        // dd($spks);
        if ($jenis != 'spk') {
            $pegawais = Pegawai::where('flag', null)->get();
            return view('surat.create', compact('jenis', 'noTerakhir', 'kamusSuratUmum', 'kamusSuratTeknis', 'kegiatans', 'opsiSuratAwal', 'pegawais', 'tipe_bast', 'spks'));
        } else {
            $mitras = Mitra::where('flag', null)->where('nama', 'not like', '%bayangan%')->get();
            return view('surat.create', compact('jenis', 'noTerakhir', 'kamusSuratUmum', 'kamusSuratTeknis', 'kegiatans', 'opsiSuratAwal', 'mitras'));
        }
    }

    public function storeLama(Request $request, $jenis)
    {
        if ($jenis != 'masuk') { //jika jenis surat selain masuk
            if ($jenis != 'spk') { //jika bukan mau generate SPK
                $request->validate([
                    'tim' => 'required',
                    'kode' => 'required',
                    'perihal' => 'required',
                ]);
                if ($jenis != 'keluar') {
                    $request->validate([
                        'id_kegiatan' => 'required',
                    ]);
                    $kegiatan = Kegiatan::find($request->id_kegiatan);
                    $mitraMelebihiHonor = KegiatanController::validasiHonorMitra($kegiatan->mitra, $kegiatan->tgl_mulai);
                    if (count($mitraMelebihiHonor) > 0) {
                        return redirect()->back()->with('error', 'Mitra (' . implode(",", $mitraMelebihiHonor) . ') melebihi batas honor yang diperbolehkan.');
                    }

                    if ($kegiatan->honor_pengawasan == null || $kegiatan->honor_pencacahan == null) {
                        return redirect()->back()->with('error', 'Kegiatan yang dipilih belum memiliki honor pengawasan atau pencacahan.');
                    } else {
                        if ($kegiatan->mitra->count() == 0) {
                            return redirect()->back()->with('error', 'Kegiatan yang dipilih belum memiliki mitra.');
                        } else {
                            foreach ($kegiatan->mitra as $mitra) {
                                if ($mitra->pivot->jumlah == null) {
                                    return redirect()->back()->with('error', 'Ada mitra yang belum memiliki estimasi honor dari kegiatan yang dipilih.');
                                }
                            }
                        }
                    }
                }
                if ($jenis == 'tugas') {
                    $request->validate([
                        'tgl_surat' => 'required',
                    ]);
                }
            } else {  //jika mau generate SPK
                $request->validate([
                    'mitra_spk' => 'required',
                    'bulan_spk' => 'required',
                    'tahun_spk' => 'required',
                ]);
            }
        } else {  //jika jenis surat masuk
            $request->validate([
                'dinas_surat_masuk' => 'required',
                'no_surat_masuk' => 'required',
                'file' => 'required|mimes:pdf',
                'perihal' => 'required',
                'tgl_surat' => 'required',
            ]);
            // $totalFoto = count($request->file('files'));
            // for ($i = 0; $i < $totalFoto; $i++) {
            //     $request->validate([
            //         'file.' . $i => 'mimes:png,jpg,jpeg,webp,svg',
            //     ]);
            // }
        }

        if ($jenis == 'spd') {
            $request->validate([
                'tgl_awal_kegiatan' => 'required|date',
                'tgl_akhir_kegiatan' => 'required|date',
                'pegawai_yang_bertugas' => 'required',
            ]);
        }

        $surat = new Surat();
        $surat->jenis_surat = $jenis;
        $surat->perihal = $request->perihal;

        if ($jenis == 'spd') {
            $surat->tgl_awal_kegiatan = $request->tgl_awal_kegiatan;
            $surat->tgl_akhir_kegiatan = $request->tgl_akhir_kegiatan;
            $surat->pegawai_yang_bertugas = $request->pegawai_yang_bertugas;
        }


        $surat->id_pembuat_surat = Auth::user()->id;

        if ($jenis != 'masuk') {
            if ($jenis != 'spk') {
                $noTerakhir = $this->getNoSuratTerakhir($jenis);
                $surat->no_terakhir = $noTerakhir + 1;
                if ($jenis != 'keluar') {
                    $surat->nomor_surat = $this->generateNomorSurat($request->tim, $request->kode, $jenis, $noTerakhir);
                    $surat->id_kegiatan = $request->id_kegiatan;
                } else {
                    $surat->nomor_surat = $this->generateNomorSurat("11010", $request->kode, $jenis, $noTerakhir);
                }
                $surat->tim = $request->tim;
                if ($jenis == 'tugas') {
                    $surat->tgl_surat = $request->tgl_surat;
                }
            } else {  //jika jenis surat spk
                $cekSurat = Surat::where('jenis_surat', 'spk')
                    ->where('mitra_spk', $request->mitra_spk)
                    ->where('bulan_spk', $request->bulan_spk)
                    ->where('tahun_spk', $request->tahun_spk)
                    ->first();
                if ($cekSurat) {
                    $mitra = Mitra::find($request->mitra_spk);
                    return redirect()->back()->with('error', 'SPK untuk mitra ' . $mitra->nama . ' pada bulan ' . $request->bulan_spk . ' dan tahun ' . $request->tahun_spk . ' sudah ada.');
                }
                $noSPK_terakhir = Surat::where('jenis_surat', 'spk')->where('tahun_spk', $request->tahun_spk)->orderBy('no_terakhir', 'desc')->first();
                if ($noSPK_terakhir == null) {
                    $noTerakhir = 0;
                } else {
                    $noTerakhir = $noSPK_terakhir->no_terakhir;
                }
                $surat->no_terakhir = $noTerakhir + 1;
                $surat->mitra_spk = $request->mitra_spk;
                $surat->bulan_spk = $request->bulan_spk;
                $surat->tahun_spk = $request->tahun_spk;
                // $surat->file = $this->generateSPK($request->mitra_spk, $request->bulan_spk, $request->tahun_spk);
            }
            $surat->save();
        } else { //jika jenis surat masuk
            $surat->dinas_surat_masuk = $request->dinas_surat_masuk;
            $surat->no_surat_masuk = $request->no_surat_masuk;
            $surat->tgl_surat = $request->tgl_surat;
            $surat->save();
            // if ($request->has('files')) {
            //     $i = 0;
            //     foreach ($request->file('files') as $file) {
            //         $extension = $file->getClientOriginalExtension();
            //         $filename = date('Y-m-d') . '_' . time() . '_' . $i . '.' . $extension;
            //         $path = 'uploads/surat/';
            //         $file->move($path, $filename);
            //         $fotoSuratMasuk = new FotoSuratMasuk();
            //         $fotoSuratMasuk->id_surat = $surat->id;
            //         $fotoSuratMasuk->filename = $filename;
            //         $fotoSuratMasuk->save();
            //         $i++;
            //     }
            // }
            if ($request->has('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $filename = date('Y-m-d') . '_' . time() . '.' . $extension;
                $path = 'uploads/surat/';
                $file->move($path, $filename);
                $surat->file = $filename;
                $surat->save();
            }
        }

        return redirect()->route('surat.' . $jenis)->with('success', 'Surat berhasil dibuat.');
    }

    public function storeAndValidate(Request $request, $jenis, $tipe_bast = null)
    {

        if ($jenis != 'masuk') { //jika jenis surat selain masuk
            if ($jenis != 'spk') { //jika bukan mau generate SPK
                if ($jenis != 'sk') {
                    $request->validate([
                        'tim' => 'required',
                        'kode' => 'required',
                        'perihal' => 'required',
                    ]);
                }

                if ($jenis != 'keluar') {  //jika surat form permintaan atau tugas atau spd
                    if ($jenis == 'sk' || $jenis == 'bast') {
                        $request->validate([
                            'tgl_surat' => 'required',
                        ]);
                    } else {
                        if (!$tipe_bast) {
                            $request->validate([
                                'id_kegiatan' => 'required',
                            ]);
                            $kegiatan = Kegiatan::find($request->id_kegiatan);
                            $mitraMelebihiHonor = KegiatanController::validasiHonorMitra($kegiatan->mitra, $kegiatan->tgl_mulai);
                            if (count($mitraMelebihiHonor) > 0) {
                                return redirect()->back()->with('error', 'Mitra (' . implode(",", $mitraMelebihiHonor) . ') melebihi batas honor yang diperbolehkan.');
                            }
                        }
                    }
                }

                if ($jenis == 'tugas') {
                    $request->validate([
                        'tgl_surat' => 'required',
                    ]);
                }
            } else {  //jika mau generate SPK
                $request->validate([
                    'mitra_spk' => 'required',
                    'bulan_spk' => 'required',
                    'tahun_spk' => 'required',
                ]);
            }
        } else {  //jika jenis surat masuk
            $request->validate([
                'dinas_surat_masuk' => 'required',
                'no_surat_masuk' => 'required',
                'file' => 'required|mimes:pdf',
                'perihal' => 'required',
                'tgl_surat' => 'required',
                'tgl_diterima' => 'required',
            ]);
            // $totalFoto = count($request->file('files'));
            // for ($i = 0; $i < $totalFoto; $i++) {
            //     $request->validate([
            //         'file.' . $i => 'mimes:png,jpg,jpeg,webp,svg',
            //     ]);
            // }
        }

        if ($jenis == 'spd') {
            $request->validate([
                'tgl_awal_kegiatan' => 'required|date',
                'tgl_akhir_kegiatan' => 'required|date',
                'pegawai_yang_bertugas' => 'required',
            ]);
        } else if ($jenis == 'sk') {
            $request->validate([
                'perihal' => 'required',
            ]);
        }

        // Buat BAST banyak
        if ($jenis == 'bast' && !$request->tipe_bast) {
            $kegiatan = Kegiatan::find($request->id_kegiatan);
            if ($kegiatan->jenis_kak != 'honor-mitra') {
                return redirect()->back()->with('error', 'BAST banyak hanya untuk kegiatan dengan jenis KAK Honor Mitra.');
            }
            foreach ($kegiatan->kegiatanLampiran as $lampiran) {
                $surat = new Surat();
                $surat->jenis_surat = $jenis;
                $surat->perihal = $request->perihal;
                $surat->id_kegiatan = $request->id_kegiatan;
                $spk = Surat::where('mitra_spk', $lampiran->peserta_id)
                    ->where('jenis_surat', 'spk')
                    ->where('bulan_spk', (int)Carbon::parse($kegiatan->tgl_mulai)->format('m'))
                    ->where('tahun_spk', date('Y'))
                    ->where('flag', null)
                    ->first();
                if (!$spk) {
                    $noTerakhirSPK = $this->getNoSuratTerakhir('spk');
                    $spk = new Surat();
                    $spk->jenis_surat = 'spk';
                    $spk->mitra_spk = $lampiran->peserta_id;
                    $spk->bulan_spk = (int)Carbon::parse($kegiatan->tgl_mulai)->format('m');
                    $spk->tahun_spk = date('Y');
                    $spk->no_terakhir = $noTerakhirSPK + 1;
                    $spk->id_pembuat_surat = Auth::user()->id;
                    $spk->save();
                }
                $surat->spk_id = $spk->id;
                $surat->mitra_spk = $lampiran->peserta_id;
                $surat->tim = $request->tim;
                $surat->tgl_surat = $request->tgl_surat;

                $noTerakhir = $this->getNoSuratTerakhir($jenis);
                $surat->no_terakhir = $noTerakhir + 1;
                $surat->nomor_surat = $this->generateNomorSurat($request->tim, $request->kode, $jenis, $noTerakhir);

                $surat->id_pembuat_surat = Auth::user()->id;
                $surat->save();

                // // Update kegiatan lampiran dengan id surat bast
                // $lampiran->bast_id = $surat->id;
                // $lampiran->save();
            }
        } else {
            // jika bukan buat spk
            $surat = new Surat();
            $surat->jenis_surat = $jenis;
            $surat->perihal = $request->perihal;

            if ($jenis == 'spd') {
                $surat->tgl_awal_kegiatan = $request->tgl_awal_kegiatan;
                $surat->tgl_akhir_kegiatan = $request->tgl_akhir_kegiatan;
                $surat->pegawai_yang_bertugas = $request->pegawai_yang_bertugas;
            }

            $surat->id_pembuat_surat = Auth::user()->id;
            if ($jenis != 'masuk') {
                if ($jenis != 'spk') {
                    $noTerakhir = $this->getNoSuratTerakhir($jenis);
                    $surat->no_terakhir = $noTerakhir + 1;
                    if ($jenis != 'keluar' && $jenis != 'sk') {
                        $surat->nomor_surat = $this->generateNomorSurat($request->tim, $request->kode, $jenis, $noTerakhir);
                        $surat->id_kegiatan = $request->id_kegiatan;
                        if ($jenis == 'bast') {
                            $surat->spk_id = $request->no_spk;
                        }
                        if ($request->pegawai_yang_bertugas) {
                            $surat->pegawai_yang_bertugas = $request->pegawai_yang_bertugas;
                        }
                    } else { // jika jenis surat keluar atau sk
                        if ($jenis == 'sk' || $jenis == 'bast') {
                            $surat->tim = $request->tim;
                            $surat->no_terakhir = $noTerakhir + 1;
                            $surat->tgl_surat = $request->tgl_surat;
                            if ($jenis == 'sk') {
                                $surat->id_kegiatan = $request->id_kegiatan;
                            }
                        } else {
                            $surat->nomor_surat = $this->generateNomorSurat("11010", $request->kode, $jenis, $noTerakhir);
                        }
                    }
                    $surat->tim = $request->tim;

                    if ($jenis == 'tugas' || $jenis == 'bast') {
                        $surat->tgl_surat = $request->tgl_surat;
                    }
                } else {  //jika jenis surat spk
                    $cekSurat = Surat::where('jenis_surat', 'spk')
                        ->where('mitra_spk', $request->mitra_spk)
                        ->where('bulan_spk', $request->bulan_spk)
                        ->where('tahun_spk', $request->tahun_spk)
                        ->first();
                    if ($cekSurat) {
                        $mitra = Mitra::find($request->mitra_spk);
                        return redirect()->back()->with('error', 'SPK untuk mitra ' . $mitra->nama . ' pada bulan ' . $request->bulan_spk . ' dan tahun ' . $request->tahun_spk . ' sudah ada.');
                    }
                    $noSPK_terakhir = Surat::where('jenis_surat', 'spk')->where('tahun_spk', $request->tahun_spk)->orderBy('no_terakhir', 'desc')->first();
                    if ($noSPK_terakhir == null) {
                        $noTerakhir = 0;
                    } else {
                        $noTerakhir = $noSPK_terakhir->no_terakhir;
                    }
                    $surat->no_terakhir = $noTerakhir + 1;
                    $surat->mitra_spk = $request->mitra_spk;
                    $surat->bulan_spk = $request->bulan_spk;
                    $surat->tahun_spk = $request->tahun_spk;
                    // $surat->file = $this->generateSPK($request->mitra_spk, $request->bulan_spk, $request->tahun_spk);
                }

                // dd($request->all(), $surat, $jenis);
                $surat->save();
            } else { //jika jenis surat masuk
                $surat->dinas_surat_masuk = $request->dinas_surat_masuk;
                $surat->no_surat_masuk = $request->no_surat_masuk;
                $surat->tgl_surat = $request->tgl_surat;
                $surat->tgl_diterima = $request->tgl_diterima;
                $surat->save();
                if ($request->has('file')) {
                    $file = $request->file('file');
                    $extension = $file->getClientOriginalExtension();
                    $filename = date('Y-m-d') . '_' . time() . '.' . $extension;
                    $path = 'uploads/surat/';
                    $file->move($path, $filename);
                    $surat->file = $filename;
                    $surat->save();
                }
            }
        }

        return $surat;
    }

    public function store(Request $request, $jenis, $tipe_bast = null)
    {

        $surat = $this->storeAndValidate($request, $jenis, $tipe_bast);
        // dd($request->all(), $jenis, $surat);
        // dd($surat);
        return redirect()->route('surat.' . $jenis)->with('success', 'Surat berhasil dibuat.');
    }

    public function edit($jenis, $id)
    {
        $surat = Surat::find($id);

        if ($jenis == 'masuk') {
            return view('surat.edit', [
                'surat' => $surat,
                'jenis' => $jenis,
            ]);
        } else if ($jenis == 'sk') {
            $kegiatan = Kegiatan::where('id', $surat->id_kegiatan)->first();

            return view('surat.edit', [
                'surat' => $surat,
                'jenis' => $jenis,
                'kegiatan' => $kegiatan,
            ]);
        } else {
            $kegiatan = Kegiatan::where('id', $surat->id_kegiatan)->first();
            $spks = $pecahanSurat = null;
            $kamusSuratUmum = KamusSurat::where('tim', '11011')->get();
            $pegawais = Pegawai::where('flag', null)->get();
            if ($jenis != 'bast') {
                $pecahanSurat = explode("/", $surat->nomor_surat);
                if (!$surat->tim) {
                    $surat->tim = $pecahanSurat[1];
                }
                $surat->kode_surat = $pecahanSurat[2];
                if ($jenis == 'spd') {
                    $id = $surat->pegawai_yang_bertugas;
                    $surat->pegawai_yang_bertugas = Pegawai::find($id);
                }
                if (count($pecahanSurat) == 5) {
                    $surat->bulan = [3];
                    $surat->tahun = $pecahanSurat[4];
                } else {
                    $surat->tahun = $pecahanSurat[3];
                }
            } else {
                $spks = Surat::where('jenis_surat', 'spk')->where('flag', null)->orderBy('no_terakhir', 'desc')->get();
                foreach ($spks as $spk) {
                    $spk->mitra = Mitra::find($spk->mitra_spk);
                }
            }
            $opsiSuratAwal = KamusSurat::where('tim', $surat->tim)->get();
            $kamusSuratTeknis = KamusSurat::where('tim', '11012')->orderBy('kode_surat_gabungan', 'desc')->get();
            $noTerakhir = $surat->no_terakhir;
            return view('surat.edit', [
                'surat' => $surat,
                'kamusSuratUmum' => $kamusSuratUmum,
                'kamusSuratTeknis' => $kamusSuratTeknis,
                'jenis' => $jenis,
                'pecahanSurat' => $pecahanSurat,
                'noTerakhir' => $noTerakhir,
                'opsiSuratAwal' => $opsiSuratAwal,
                'kegiatan' => $kegiatan,
                'pegawais' => $pegawais,
                'spks' => $spks,
            ]);
        }
    }

    public function update(Request $request, $jenis, $id)
    {
        $surat = Surat::find($id);

        if ($jenis == 'masuk') {
            $request->validate([
                'dinas_surat_masuk' => 'required',
                'no_surat_masuk' => 'required',
                'file' => 'nullable|mimes:pdf',
                'perihal' => 'required',
                'tgl_surat' => 'required',
                'tgl_diterima' => 'required',
            ]);
            $surat->dinas_surat_masuk = $request->dinas_surat_masuk;
            $surat->tgl_surat = $request->tgl_surat;
            $surat->tgl_diterima = $request->tgl_diterima;
            $surat->no_surat_masuk = $request->no_surat_masuk;
            if ($request->has('file')) {

                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();

                $filename = date('Y-m-d') . '_' . time() . '_' . '.' . $extension;

                $path = 'uploads/surat/';
                $file->move($path, $filename);
                $surat->file = $filename;
            }
        } else {  //jika jenis surat selain masuk
            if ($jenis != 'sk') {
                $request->validate([
                    'kode' => 'required',
                    'perihal' => 'required',
                ]);
            }
            if ($jenis == 'spd') {
                $request->validate([
                    'tgl_awal_kegiatan' => 'required|date',
                    'tgl_akhir_kegiatan' => 'required|date',
                    'pegawai_yang_bertugas' => 'required',
                ]);
                $surat->tgl_awal_kegiatan = $request->tgl_awal_kegiatan;
                $surat->tgl_akhir_kegiatan = $request->tgl_akhir_kegiatan;
                $surat->pegawai_yang_bertugas = $request->pegawai_yang_bertugas;
            }
            if ($request->id_kegiatan != null) {
                $surat->id_kegiatan = $request->id_kegiatan;
            }
            $noTerakhir = $surat->no_terakhir;
            if ($jenis != 'keluar' && $jenis != 'sk') {
                if ($surat->tim) {
                    $surat->nomor_surat = $this->generateNomorSurat($surat->tim, $request->kode, $jenis, $noTerakhir - 1);
                } else {
                    $kegiatan = Kegiatan::find($surat->id_kegiatan);
                    $surat->nomor_surat = $this->generateNomorSurat($kegiatan->tim, $request->kode, $jenis, $noTerakhir - 1);
                }
            }
            if ($jenis == 'keluar') { //jika surat keluar
                $surat->nomor_surat = $this->generateNomorSurat("11010", $request->kode, $jenis, $noTerakhir - 1);
            }

            if ($jenis == 'tugas' || $jenis == 'sk' || $jenis == 'bast') {
                $request->validate([
                    'tgl_surat' => 'required',
                ]);
                $surat->tgl_surat = $request->tgl_surat;
                if ($jenis == 'sk') {
                    $surat->tim = $request->tim;
                }
            }
            if ($request->pegawai_yang_bertugas) {
                $surat->pegawai_yang_bertugas = $request->pegawai_yang_bertugas;
            }
            if ($request->no_spk) {
                $surat->spk_id = $request->no_spk;
            }
        }
        $surat->perihal = $request->perihal;

        $surat->save();

        return redirect()->route('surat.' . $jenis)->with('success', 'Surat berhasil diubah.');
    }

    public function destroy($id)
    {
        $surat = Surat::find($id);
        // tambahkan validasi jik yang login bukan admin maka tidak bisa menghapus
        if ((now()->diffInDays($surat->created_at) > 7)) {
            if ((Auth::user()->role != 'Admin')) {
                return redirect()->back()->with('error', 'Silakan hubungi pegawai TU untuk menghapus nomor surat.');
            }
        }
        if ($surat->jenis_surat == 'spk') {
            $filePath = $surat->file;
            // unlink($filePath);
            $bast = Surat::where('spk_id', $surat->id)->get();
            foreach ($bast as $b) {
                $b->spk_id = null;
                $b->save();
            }
            $surat->delete();
        } else if ($surat->jenis_surat == 'sk' || $surat->jenis_surat == 'bast') {
            $surat->delete();
        } else {
            if ($surat->jenis_surat == 'tugas') {
                $formPermintaan = Surat::where('surat_tugas_id', $surat->id)->first();
                if ($formPermintaan) {
                    $formPermintaan->surat_tugas_id = null;
                    $formPermintaan->save();
                }
            } else if ($surat->jenis_surat == 'spd') {
                $formPermintaan = Surat::where('spd_id', $surat->id)->first();
                if ($formPermintaan) {
                    $formPermintaan->spd_id = null;
                    $formPermintaan->save();
                }
            }
            $surat->flag = 'Dihapus';
            $surat->save();
        }
        return redirect()->back()->with('success', 'Surat berhasil dihapus.');
    }

    public function getKegiatanApi(Request $request)
    {
        $request->validate([
            'tim' => 'required',
            'id_pegawai' => 'required',
        ]);
        $kegiatans = null;
        $jenis = $request->input('jenis', null);
        if (Auth::user()->role == 'Admin') {
            if ($jenis == 'sk') {
                $kegiatans = Kegiatan::where('is_approved', 1)->where('tim', $request->tim)->where('jenis_kak', 'honor-mitra')->get();
            } else {
                $kegiatans = Kegiatan::where('is_approved', 1)->where('tim', $request->tim)->get();
            }
        } else {
            if (Auth::user()->role == 'Ketua Tim') {
                $kegiatans = Kegiatan::where('tim', $request->tim)->where('is_approved', 1)->get();
            } else {
                $kegiatans = Kegiatan::where('tim', $request->tim)->where('id_pjk', $request->id_pegawai)->where('is_approved', 1)->get();
            }
            $kegiatan_lampirans = KegiatanLampiran::where('peserta_id', Auth::user()->id)->where('tipe_personil', "pegawai")->get();

            // mencari kegiatan yang melibatkan pegawai yang login sebagai peserta kegiatan lampiran, kemudian menambahkan kegiatan tersebut ke dalam list kegiatan jika belum ada di dalam list kegiatan
            foreach ($kegiatan_lampirans as $kegiatan_lampiran) {
                $kegiatan = Kegiatan::find($kegiatan_lampiran->kegiatan_id);
                if (!$kegiatan) {
                    continue;
                } else if ($kegiatans->contains($kegiatan)) {
                    continue;
                } else {
                    if ($kegiatan->tim) {
                        if ($kegiatan->tim == $request->tim && $kegiatan->is_approved == 1) {
                            $kegiatans->push($kegiatan);
                        }
                    } else {
                        if ($kegiatan->id_pjk == $request->id_pegawai && $kegiatan->is_approved == 1) {
                            $kegiatans->push($kegiatan);
                        }
                    }
                }
            }
        }
        foreach ($kegiatans as $kegiatan) {
            $nama_awal = $kegiatan->nama;
            $kegiatan->nama = $kegiatan->singkatan_resmi . " - " . $nama_awal;
        }
        return response()->json($kegiatans);
    }

    public function generateSuratTugas($id_kegiatan, $id_form_permintaan)
    {
        $kegiatan = Kegiatan::find($id_kegiatan);
        $perihal = null;
        if ($kegiatan->jenis_kak == 'translok-biasa' || $kegiatan->jenis_kak == 'translok-8jam') {
            $perihal = $kegiatan->kak2_maksud . ' ' . $kegiatan->singkatan_resmi;
        } else if ($kegiatan->jenis_kak == 'pemanggilan-konsultasi') {
            $perihal = $kegiatan->kak2_maksud . ' ' . $kegiatan->singkatan_resmi;
        } else if ($kegiatan->jenis_kak == 'pelatihan') {
            $perihal = 'Pelatihan ' . $kegiatan->singkatan_resmi;
        } else if ($kegiatan->jenis_kak == 'honor-inda') {
            $perihal = 'Instruktur Daerah ' . $kegiatan->singkatan_resmi;
        } else {
            $perihal = 'Pemutakhiran/Pendataan ' . $kegiatan->singkatan_resmi;
        }
        $form = Surat::find($id_form_permintaan);

        $explode = explode("/", $form->nomor_surat);
        $kode_surat = $explode[2];

        $request = new Request();
        $request->merge([
            'tim' => $kegiatan->tim,
            'kode' => $kode_surat,
            'perihal' => $perihal,
            'id_kegiatan' => $kegiatan->id,
            'tgl_surat' => date('Y-m-d'),
        ]);
        $surat = $this->storeAndValidate($request, 'tugas');
        $form = Surat::find($id_form_permintaan);
        $form->surat_tugas_id = $surat->id;
        $form->save();
        return redirect()->back()->with('success', 'Surat Tugas berhasil dibuat.');
    }

    public function generateSPD($id_kegiatan, $id_form_permintaan)
    {
        $kegiatan = Kegiatan::find($id_kegiatan);
        $perihal = null;
        if ($kegiatan->jenis_kak == 'translok-biasa' || $kegiatan->jenis_kak == 'translok-8jam') {
            $perihal = $kegiatan->kak2_maksud . ' ' . $kegiatan->singkatan_resmi;
        } else if ($kegiatan->jenis_kak == 'pemanggilan-konsultasi') {
            $perihal = $kegiatan->kak2_maksud . ' ' . $kegiatan->singkatan_resmi;
        } else if ($kegiatan->jenis_kak == 'pelatihan') {
            $perihal = 'Pelatihan ' . $kegiatan->singkatan_resmi;
        } else if ($kegiatan->jenis_kak == 'honor-inda') {
            $perihal = 'Instruktur Daerah ' . $kegiatan->singkatan_resmi;
        } else {
            $perihal = 'Pemutakhiran/Pendataan ' . $kegiatan->singkatan_resmi;
        }

        // cari form permintaan
        $form = Surat::find($id_form_permintaan);

        // copy kode surat dari form permintaan
        $explode = explode("/", $form->nomor_surat);
        $kode_surat = $explode[2];

        // cari pegawai yang bertugas dari kegiatan
        $pegawai_yang_bertugas = null;
        foreach ($kegiatan->kegiatanLampiran as $lampiran) {
            if ($lampiran->tipe_personil == "pegawai") {
                $pegawai_yang_bertugas = $lampiran->peserta_id;
                break;
            }
        }

        $request = new Request();
        $request->merge([
            'tim' => $kegiatan->tim,
            'kode' => $kode_surat,
            'perihal' => $perihal,
            'id_kegiatan' => $kegiatan->id,
            'tgl_surat' => date('Y-m-d'),
            'tgl_awal_kegiatan' => $kegiatan->tgl_mulai,
            'tgl_akhir_kegiatan' => $kegiatan->tgl_selesai,
            'pegawai_yang_bertugas' => $pegawai_yang_bertugas ?? Auth::user()->id,
        ]);
        // simpan surat spd
        $surat = $this->storeAndValidate($request, 'spd');
        // dd($surat);
        // linking surat spd ke form permintaan
        $form->spd_id = $surat->id;
        $form->save();
        return redirect()->back()->with('success', 'Surat Perintah Dinas berhasil dibuat.');
    }

    public function getKodeSurat($tim)
    {
        if ($tim == "11011") {
            $kodeSurat = KamusSurat::where('tim', $tim)->get();
            return response()->json($kodeSurat);
        } else {
            $kodeSurat = KamusSurat::where('tim', $tim)->get();
        }
        return response()->json($kodeSurat);
    }

    private function getNoSuratTerakhir($jenis)
    {
        $suratTerakhir = Surat::where('jenis_surat', $jenis)->orderBy('no_terakhir', 'desc')->first();
        if ($suratTerakhir == null) {
            $noTerakhir = 0;
        } else {
            $noTerakhir = $suratTerakhir->no_terakhir;
        }
        return $noTerakhir;
    }

    private function generateNomorSurat($tim, $kode, $jenis, $noTerakhir)
    {
        $noSurat = "";
        if ($jenis == "spd") {
            $noSurat =  str_pad($noTerakhir + 1, 4, "0", STR_PAD_LEFT) . "/" . $tim . "/" . $kode . "/" . date("Y");
        } else if ($jenis == "bast") {
            $noSurat = str_pad($noTerakhir + 1, 4, "0", STR_PAD_LEFT)  . "/" . $kode . "/" . date("Y");
        } else {
            $noSurat = "B-" . str_pad($noTerakhir + 1, 4, "0", STR_PAD_LEFT) . "/" . $tim . "/" . $kode . "/" . date("Y");
        }
        return $noSurat;
    }

    public function downloadSPK($id)
    {
        $surat = Surat::find($id);
        $bulan = str_pad($surat->bulan_spk, 2, '0', STR_PAD_LEFT);
        // $surat->file = $this->generateSPK($surat->mitra_spk, $bulan, $surat->tahun_spk);
        $id_mitra = $surat->mitra_spk;
        $tahun = $surat->tahun_spk;
        $mitra = Mitra::find($id_mitra);
        $kegiatan_lampiran = KegiatanLampiran::where('peserta_id', $id_mitra)->where('tipe_personil', 'mitra')->get();
        // dd($kegiatan_lampiran);
        $namaBulan = $this->convertDigitBulan($bulan);
        $tglAwal = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $tglAkhir = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        $namaHariAwal = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->locale('id')->translatedFormat('l');

        $suratTerakhir = Surat::where('jenis_surat', 'spk')->where('tahun_spk', $tahun)->orderBy('no_terakhir', 'desc')->first();
        // dd($suratTerakhir);
        if ($suratTerakhir == null) {
            $noTerakhir = 0;
        } else {
            $noTerakhir = $suratTerakhir->no_terakhir;
        }

        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor("SPK.docx");
        $phpWord->setValue('nomor', $noTerakhir + 1);
        $phpWord->setValue('hari', $namaHariAwal);
        $phpWord->setValue('tanggal', 1);
        $phpWord->setValue('bulan', strtolower($namaBulan));
        $phpWord->setValue('Bulan', $namaBulan);
        $phpWord->setValue('BULAN', strtoupper($namaBulan));
        $phpWord->setValue('nama_mitra', $mitra->nama);
        $phpWord->setValue('kec_asal', $this->konversiKodeKec($mitra->kec_asal));
        $phpWord->setValue('tgl_awal', $tglAwal->locale('id')->translatedFormat('d F'));
        $phpWord->setValue('tgl_akhir', $tglAkhir->locale('id')->translatedFormat('d F'));
        $jumlah_honor = 0;
        $count = 1;
        $values = [];
        foreach ($kegiatan_lampiran as $km) {
            $kegiatan = Kegiatan::find($km->kegiatan_id);

            if (!$kegiatan) {
                continue;
            }

            // butuh informasi kegiatan rincian untuk mengetahui satuan honor dan honor per satuan
            $kegiatan_rincian = KegiatanRincian::where('kegiatan_id', $kegiatan->id)->first();
            $total_honor = 0;
            try {
                if ($km->pcl_or_pml == 1) {
                    $kegiatan->honor_pengawasan == null ? $total_honor = 0 : $total_honor = $kegiatan->honor_pengawasan;
                } else {
                    $kegiatan->honor_pencacahan == null ? $total_honor = 0 : $total_honor = $kegiatan->honor_pencacahan;
                }

                if ($total_honor < 10) {

                    if ($kegiatan_rincian) {
                        $total_honor = $kegiatan_rincian->harga_satuan;
                    }
                }
            } catch (\Exception $e) {
                $total_honor = 0;
            }
            // dd($km, $kegiatan, $total_honor);
            // Jika satuan honor kurang dari 10 artinya kegiatan dummy
            // if ($total_honor < 10 || Carbon::parse($kegiatan->tgl_mulai)->format('m') != $bulan || $total_honor == null || $km->jumlah == null || Carbon::parse($kegiatan->tgl_mulai)->format('Y') != $tahun) {
            //     continue;
            // }

            // generate jangka waktu kegiatan
            $jkw = '';
            if (Carbon::parse($kegiatan->tgl_mulai)->format('m') == Carbon::parse($kegiatan->tgl_selesai)->format('m')) {
                $jkw = Carbon::parse($kegiatan->tgl_mulai)->format('d') . ' s.d. ' . Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d F Y');
            } else {
                $jkw = Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d F') . ' s.d. ' . Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d F');
            }

            // beban anggaran
            try {
                $POK = POK::where('id', $kegiatan->kak6_sub_komponen)->first();
                $beban_anggaran = $POK->kode_aktivitas . "." . $POK->kode_klasifikasi_rincian_output . "." . $POK->kode_rincian_output . "." . $POK->kode_komponen . "." . $POK->kode_sub_komponen;
                $POK_akun = POK::where('id', $kegiatan_rincian->pok_id)->first();
                if ($POK_akun) {
                    $beban_anggaran .= "." . $POK_akun->kode_akun;
                }
            } catch (\Exception $e) {
                $beban_anggaran = "{#beban_anggaran#}";
            }


            if ($kegiatan_rincian) {
                $satuan_honor = $kegiatan_rincian->satuan;
            } else {
                $satuan_honor = "{#satuan_honor#}";
            }

            $jumlah_honor += $km->transport_bayar;
            array_push($values, [
                'no_keg' => $count,
                'nama_keg' => $kegiatan->singkatan_resmi,
                'jkw' => $jkw,
                'vol_keg' => $km->jml_sampel_pcl,
                'sat_keg' => $satuan_honor,
                'harga_sat' => $total_honor,
                'honor' => $km->transport_bayar,
                'beban_ang' => $beban_anggaran,
            ]);
            $count++;
        }
        // $values = [
        //     ['no_keg' => 1, 'nama_keg' => 'SUSENAS Maret 2025', 'jkw' => '01 s.d. 28 Februari 2025', 'vol_keg' => 20, 'sat_keg' => 'Dokumen', 'harga_sat' => '37.000', 'honor' => '740.000'],
        //     ['no_keg' => 2, 'nama_keg' => 'SUSENAS April 2025', 'jkw' => '01 s.d. 30 April 2025', 'vol_keg' => 20, 'sat_keg' => 'Dokumen', 'harga_sat' => '37.000', 'honor' => '740.000'],
        // ];
        $honorTerbilang = $this->terbilang($jumlah_honor);
        $phpWord->cloneRowAndSetValues('no_keg', $values);
        $phpWord->setValue('total_honor', $jumlah_honor);
        $phpWord->setValue('total_honor_terbilang',  $honorTerbilang . " Rupiah");
        $phpWord->setValue('total_honor_terbilang_kecil',  strtolower($honorTerbilang) . " rupiah");

        // 1. Tentukan nama file yang akan dilihat user saat download
        $fileNameUser = 'SPK_' . str_replace(' ', '_', $mitra->nama) . '_' . $bulan . '_' . $tahun . '.docx';

        // 2. Buat file temporary (sementara) di sistem server
        $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
        $phpWord->saveAs($tempFile);
        // Parameter 1: Path file sementara
        // Parameter 2: Nama file yang akan didownload user
        return response()->download($tempFile, $fileNameUser)->deleteFileAfterSend(true);
    }

    public function downloadBASTPCL($id)
    {
        $surat = Surat::find($id);
        $kegiatan = Kegiatan::find($surat->id_kegiatan);
        $mitra = Mitra::find($surat->mitra_spk);
        $spk = Surat::find($surat->spk_id);
        if (!$kegiatan || !$spk) {
            return redirect()->back()->with('error', 'Tidak dapat mengunduh BAST PCL karena kegiatan tidak ditemukan.');
        }
        if (!$mitra) {
            return redirect()->back()->with('error', 'Tidak dapat mengunduh BAST PCL karena mitra tidak ditemukan.');
        }
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor("bast-pcl-ke-ppk.docx");
        $phpWord->setValue('singkatan_resmi_uppercase', strtoupper($kegiatan->singkatan_resmi));
        $phpWord->setValue('singkatan_resmi', ($kegiatan->singkatan_resmi));
        $phpWord->setValue('no_bast', $surat->nomor_surat);
        $phpWord->setValue('hari_surat', \Carbon\Carbon::parse($surat->tgl_surat)->locale('id')->translatedFormat('l'));
        $tgl_surat = \Carbon\Carbon::parse($surat->tgl_surat)->locale('id');
        $phpWord->setValue('tanggal_surat', trim($this->terbilang($tgl_surat->day)));
        $phpWord->setValue('bulan_surat', $tgl_surat->translatedFormat('F'));
        $phpWord->setValue('tahun_surat', trim($this->terbilang($tgl_surat->year)));
        $phpWord->setValue('tanggal_lengkap', $tgl_surat->translatedFormat('d/m/Y'));
        $phpWord->setValue('nama_mitra', $mitra->nama);
        $phpWord->setValue('nik_mitra', $mitra->nik);
        $phpWord->setValue('kec_asal_mitra', $this->konversiKodeKec($mitra->kec_asal));
        $phpWord->setValue('no_spk', $spk->no_terakhir);
        $phpWord->setValue('tahun_spk', $spk->tahun_spk);
        $values = [];
        $count = 1;
        $total_honor = 0;
        foreach ($kegiatan->kegiatanLampiran as $kl) {
            $mitra2 = null;
            if ($kl->tipe_personil != 'mitra') {
                continue;
            } else {
                $mitra2 = Mitra::find($kl->peserta_id);
            }
            array_push($values, [
                'lamp_no' => $count++,
                'lamp_nama' => $mitra2->nama,
                'lamp_kec_asal' => $this->konversiKodeKec($kl->kec_tujuan), //aslinya bukan kec asal, tapi kec yang dicacah. Malas mengubah semua template
                'lamp_nama_sls' => $kl->nama_sls,
                'lamp_jml' => $kl->jml_sampel_pcl
            ]);
        }
        $phpWord->cloneRowAndSetValues('lamp_no', $values);
        $phpWord->setValue('tgl_bast', \Carbon\Carbon::parse($surat->tgl_surat)->locale('id')->translatedFormat('d F Y'));
        $phpWord->setValue('kak8_pengaju', $kegiatan->kak8_pengaju);
        $pengaju = Pegawai::find($kegiatan->id_pjk);
        $phpWord->setValue('nama_pengaju', $pengaju->nama);
        $phpWord->setValue('nip_pengaju', $pengaju->nip);

        // 1. Tentukan nama file yang akan dilihat user saat download
        $fileNameUser = 'BAST_PCL_' . str_replace(' ', '_', $mitra->nama) . '_' . date('Ymd_His') . '.docx';

        // 2. Buat file temporary (sementara) di sistem server
        $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
        $phpWord->saveAs($tempFile);
        // Parameter 1: Path file sementara
        // Parameter 2: Nama file yang akan didownload user
        return response()->download($tempFile, $fileNameUser)->deleteFileAfterSend(true);
    }

    public function donwloadBASTPJK($id)
    {
        $surat = Surat::find($id);
        return response()->download($surat->file);
    }

    public function generateSK($id)
    {
        $surat = Surat::find($id);
        $kegiatan = Kegiatan::find($surat->id_kegiatan);
        if (!$kegiatan) {
            return redirect()->back()->with('error', 'Tidak dapat generate SK karena kegiatan belum dipilih.');
        }
        $kegiatan_lampiran = KegiatanLampiran::where('kegiatan_id', $kegiatan->id)->get();
        $tglAwal = \Carbon\Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d F Y');
        $tglAkhir = \Carbon\Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d F Y');
        $tglSK = \Carbon\Carbon::parse($surat->tgl_surat)->locale('id')->translatedFormat('d F Y');
        $singkatanResmiUpper = strtoupper($kegiatan->singkatan_resmi);
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor("SK-untuk-2026.docx");
        $phpWord->setValue('no', $surat->no_terakhir);
        $phpWord->setValue('perihal', strtoupper($surat->perihal));
        $phpWord->setValue('singkatan_resmi', $kegiatan->singkatan_resmi);
        $phpWord->setValue('singkatan_resmi_upper', $singkatanResmiUpper);
        $phpWord->setValue('tgl_mulai', $tglAwal);
        $phpWord->setValue('tgl_akhir', $tglAkhir);
        $phpWord->setValue('tgl_sk', $tglSK);
        $array_id_organik = [];
        $array_id_mitra = [];
        $count = 0;
        $values = [];
        foreach ($kegiatan_lampiran as $kl) {
            $nama = "";
            $jabatan = "";
            $tugas = "";
            $honor = 0;

            if ($kl->tipe_personil == 'mitra') {
                if (!in_array($kl->peserta_id, $array_id_mitra)) {
                    // Alternative approach (using push method)
                    array_push($array_id_mitra, $kl->peserta_id);
                    $mitra = Mitra::find($kl->peserta_id);
                    $nama = $mitra->nama;
                    $jabatan = "Mitra";
                    if ($kl->pcl_or_pml == 1) {
                        $tugas = "PML";
                        $honor = $kegiatan->honor_pengawasan ?? 0;
                    } else {
                        $tugas = "PCL";
                        if ($kegiatan->honor_pencacahan == null || $kegiatan->honor_pencacahan < 10) {
                            $kegiatan_rincian = KegiatanRincian::where('kegiatan_id', $kegiatan->id)->first();
                            if ($kegiatan_rincian) {
                                $honor = $kegiatan_rincian->harga_satuan ?? 0;
                            } else {
                                $honor = 0;
                            }
                        } else {
                            $honor = $kegiatan->honor_pencacahan;
                        }
                    }
                } else {
                    continue;
                }
            } else {
                if (!in_array($kl->peserta_id, $array_id_organik)) {
                    // Alternative approach (using push method)
                    array_push($array_id_organik, $kl->peserta_id);
                    $pegawai = Pegawai::find($kl->peserta_id);
                    $nama = $pegawai->nama;
                    $jabatan = "Organik";
                    $tugas = $kl->pcl_or_pml == 1 ? "PML" : "PCL";
                } else {
                    continue;
                }
            }

            array_push($values, [
                'lamp_no' => ++$count,
                'lamp_nama' => $nama,
                'lamp_jabatan' => $jabatan,
                'lamp_tugas' => $tugas,
                'lamp_honor' => $honor == null ? "{#honor#}" : number_format($honor, 0, ',', '.'),
            ]);

            $nama = "";
            $jabatan = "";
            $tugas = "";
            $honor = 0;

            if ($kl->tipe_pengawas == "organik") {
                if (!in_array($kl->pengawas_id, $array_id_organik)) {
                    // Alternative approach (using push method)
                    array_push($array_id_organik, $kl->pengawas_id);
                    $pegawai = Pegawai::find($kl->pengawas_id);
                    $nama = $pegawai->nama;
                    $jabatan = "Organik";
                    $tugas = "PML";
                    $honor = 0;
                    array_push($values, [
                        'lamp_no' => ++$count,
                        'lamp_nama' => $nama,
                        'lamp_jabatan' => $jabatan,
                        'lamp_tugas' => $tugas,
                        'lamp_honor' => number_format(0, 0, ',', '.'),
                    ]);
                }
            }
        }
        $phpWord->cloneRowAndSetValues('lamp_no', $values);

        // 1. Tentukan nama file yang akan dilihat user saat download
        $fileNameUser = 'SK_' . str_replace(' ', '_', $surat->perihal) . '_' . date('Ymd_His') . '.docx';

        // 2. Buat file temporary (sementara) di sistem server
        $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
        $phpWord->saveAs($tempFile);
        // Parameter 1: Path file sementara
        // Parameter 2: Nama file yang akan didownload user
        return response()->download($tempFile, $fileNameUser)->deleteFileAfterSend(true);
    }


    public function uploadSK(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:pdf',
        ]);

        $surat = Surat::find($id);
        if ($request->has('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            $filename = date('Y-m-d') . '_' . time() . '_' . '.' . $extension;

            $path = 'uploads/surat/';
            $file->move($path, $filename);
            $surat->file = $filename;
            $surat->save();
        }

        return redirect()->back()->with('success', 'File SK berhasil diunggah.');
    }

    public function downloadSK($id)
    {
        $surat = Surat::find($id);
        return response()->download('uploads/surat/' . $surat->file);
    }

    public  function generateSPK($id_mitra, $bulan, $tahun) {}

    public function convertDigitBulan($digit)
    {
        $bulan = "";
        switch ($digit) {
            case 1:
                $bulan = "Januari";
                break;
            case 2:
                $bulan = "Februari";
                break;
            case 3:
                $bulan = "Maret";
                break;
            case 4:
                $bulan = "April";
                break;
            case 5:
                $bulan = "Mei";
                break;
            case 6:
                $bulan = "Juni";
                break;
            case 7:
                $bulan = "Juli";
                break;
            case 8:
                $bulan = "Agustus";
                break;
            case 9:
                $bulan = "September";
                break;
            case 10:
                $bulan = "Oktober";
                break;
            case 11:
                $bulan = "November";
                break;
            case 12:
                $bulan = "Desember";
                break;
        }
        return $bulan;
    }

    private function konversiKodeKec($id)
    {
        $kec = "";
        switch ($id) {
            case '010':
                $kec = "Teupah Selatan";
                break;
            case '020':
                $kec = "Simeulue Timur";
                break;
            case '021':
                $kec = "Teupah Barat";
                break;
            case '022':
                $kec = "Teupah Tengah";
                break;
            case '030':
                $kec = "Simeulue Tengah";
                break;
            case '031':
                $kec = "Teluk Dalam";
                break;
            case '032':
                $kec = "Simeulue Cut";
                break;
            case '040':
                $kec = "Salang";
                break;
            case '050':
                $kec = "Simeulue Barat";
                break;
            case '051':
                $kec = "Alafan";
                break;
            default:
                $kec = "";
                break;
        }
        return $kec;
    }

    private function terbilang($x)
    {
        $angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

        if ($x < 12)
            return " " . $angka[$x];
        elseif ($x < 20)
            return $this->terbilang($x - 10) . " Belas";
        elseif ($x < 100)
            return $this->terbilang($x / 10) . " Puluh" . $this->terbilang($x % 10);
        elseif ($x < 200)
            return " Seratus" . $this->terbilang($x - 100);
        elseif ($x < 1000)
            return $this->terbilang($x / 100) . " Ratus" . $this->terbilang($x % 100);
        elseif ($x < 2000)
            return " Seribu" . $this->terbilang($x - 1000);
        elseif ($x < 1000000)
            return $this->terbilang($x / 1000) . " Ribu" . $this->terbilang($x % 1000);
        elseif ($x < 1000000000)
            return $this->terbilang($x / 1000000) . " Juta" . $this->terbilang($x % 1000000);
    }
}
