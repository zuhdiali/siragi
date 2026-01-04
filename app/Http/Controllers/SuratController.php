<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\Surat;
use App\Models\KamusSurat;
use App\Models\Pegawai;
use App\Models\Kegiatan;
use App\Models\KegiatanMitra;
use Illuminate\Support\Facades\Auth;
use App\Models\FotoSuratMasuk;
use App\Models\KegiatanLampiran;
use App\Models\KegiatanPegawai;
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
        }
        return $surats;
    }

    public function tugas()
    {
        $surats = Surat::where('jenis_surat', 'tugas')->where('flag', null)->orderBy('created_at', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        return view('surat.tugas', ['surats' => $surats]);
    }

    public function permintaan()
    {
        $surats = Surat::where('jenis_surat', 'permintaan')->where('flag', null)->orderBy('created_at', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
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
        $surats = Surat::where('jenis_surat', 'keluar')->where('flag', null)->orderBy('created_at', 'desc')->get();
        foreach ($surats as $surat) {
            $surat->pembuat_surat = Pegawai::find($surat->id_pembuat_surat);
        }
        return view('surat.keluar', ['surats' => $surats]);
    }

    public function spd()
    {
        $surats = Surat::where('jenis_surat', 'spd')->where('flag', null)->orderBy('created_at', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        foreach ($surats as $surat) {
            $surat->pegawai = Pegawai::find($surat->pegawai_yang_bertugas);
        }
        return view('surat.spd', ['surats' => $surats]);
    }

    public function sk()
    {
        $surats = Surat::where('jenis_surat', 'sk')->where('flag', null)->orderBy('created_at', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        return view('surat.sk', ['surats' => $surats]);
    }

    public function spk()
    {
        $surats = Surat::where('jenis_surat', 'spk')->where('flag', null)->orderBy('created_at', 'desc')->get();
        $surats = $this->tambahInformasiSurat($surats);
        foreach ($surats as $surat) {
            $surat->mitra = Mitra::find($surat->mitra_spk);
            $surat->bulan = $this->convertDigitBulan($surat->bulan_spk);
        }
        return view('surat.spk', ['surats' => $surats]);
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

    public function create($jenis)
    {
        $kegiatans = Kegiatan::where('tim', Auth::user()->tim)->orWhere('id_pjk', Auth::user()->id)->get();
        $kegiatan_lampirans = KegiatanLampiran::where('peserta_id', Auth::user()->id)->where('tipe_personil', "pegawai")->get();

        // $kegiatan_pegawais = KegiatanPegawai::where('pegawai_id', Auth::user()->id)->get();
        foreach ($kegiatan_lampirans as $kegiatan_lampiran) {
            $kegiatan = Kegiatan::find($kegiatan_lampiran->kegiatan_id);
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
                $surat->file = $this->generateSPK($request->mitra_spk, $request->bulan_spk, $request->tahun_spk);
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

    public function store(Request $request, $jenis)
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
                $surat->file = $this->generateSPK($request->mitra_spk, $request->bulan_spk, $request->tahun_spk);
            }
            $surat->save();
        } else { //jika jenis surat masuk
            $surat->dinas_surat_masuk = $request->dinas_surat_masuk;
            $surat->no_surat_masuk = $request->no_surat_masuk;
            $surat->tgl_surat = $request->tgl_surat;
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
        } else {
            $kegiatan = Kegiatan::where('id', $surat->id_kegiatan)->first();

            $kamusSuratUmum = KamusSurat::where('tim', '11011')->get();
            $pegawais = Pegawai::where('flag', null)->get();

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
            ]);
            $surat->dinas_surat_masuk = $request->dinas_surat_masuk;
            $surat->tgl_surat = $request->tgl_surat;
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
            $request->validate([
                'kode' => 'required',
                'perihal' => 'required',
            ]);
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
            if ($jenis != 'keluar') {
                if ($surat->tim) {
                    $surat->nomor_surat = $this->generateNomorSurat($surat->tim, $request->kode, $jenis, $noTerakhir - 1);
                } else {
                    $kegiatan = Kegiatan::find($surat->id_kegiatan);
                    $surat->nomor_surat = $this->generateNomorSurat($kegiatan->tim, $request->kode, $jenis, $noTerakhir - 1);
                }
            } else { //jika bukan surat keluar
                $surat->nomor_surat = $this->generateNomorSurat("11010", $request->kode, $jenis, $noTerakhir - 1);
            }

            if ($jenis == 'tugas') {
                $request->validate([
                    'tgl_surat' => 'required',
                ]);
                $surat->tgl_surat = $request->tgl_surat;
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
            unlink($filePath);
            $surat->delete();
        } else {
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
        if (Auth::user()->role == 'Ketua Tim') {
            $kegiatans = Kegiatan::where('tim', $request->tim)->get();
        } else {
            $kegiatans = Kegiatan::where('tim', $request->tim)->where('id_pjk', $request->id_pegawai)->get();
        }
        $kegiatan_lampirans = KegiatanLampiran::where('peserta_id', Auth::user()->id)->where('tipe_personil', "pegawai")->get();

        // $kegiatan_pegawais = KegiatanPegawai::where('pegawai_id', Auth::user()->id)->get();
        foreach ($kegiatan_lampirans as $kegiatan_lampiran) {
            $kegiatan = Kegiatan::find($kegiatan_lampiran->kegiatan_id);
            if ($kegiatans->contains($kegiatan)) {
                continue;
            } else {
                if ($kegiatan->tim == $request->tim) {
                    $kegiatans->push($kegiatan);
                }
            }
        }
        foreach ($kegiatans as $kegiatan) {
            $nama_awal = $kegiatan->nama;
            $kegiatan->nama = $kegiatan->singkatan_resmi . " - " . $nama_awal;
        }
        return response()->json($kegiatans);
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
        } else {
            $noSurat = "B-" . str_pad($noTerakhir + 1, 4, "0", STR_PAD_LEFT) . "/" . $tim . "/" . $kode . "/" . date("Y");
        }
        return $noSurat;
    }

    public function downloadSPK($id)
    {
        $surat = Surat::find($id);
        $bulan = str_pad($surat->bulan_spk, 2, '0', STR_PAD_LEFT);
        $surat->file = $this->generateSPK($surat->mitra_spk, $bulan, $surat->tahun_spk);
        return response()->download($surat->file);
    }

    public  function generateSPK($id_mitra, $bulan, $tahun)
    {
        $mitra = Mitra::find($id_mitra);
        $kegiatan_mitra = KegiatanMitra::where('mitra_id', $id_mitra)->get();

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
        foreach ($kegiatan_mitra as $km) {
            $beban_anggaran = "{#beban_anggaran#}";
            $kegiatan = Kegiatan::find($km->kegiatan_id);

            $satuan_honor = ($km->is_pml == 1 ? $kegiatan->honor_pengawasan : $kegiatan->honor_pencacahan);
            // Jika satuan honor kurang dari 10 artinya kegiatan dummy
            if ($satuan_honor < 10 || Carbon::parse($kegiatan->tgl_mulai)->format('m') != $bulan || $satuan_honor == null || $km->jumlah == null || Carbon::parse($kegiatan->tgl_mulai)->format('Y') != $tahun) {
                continue;
            }
            $jkw = '';
            if (Carbon::parse($kegiatan->tgl_mulai)->format('m') == Carbon::parse($kegiatan->tgl_selesai)->format('m')) {
                $jkw = Carbon::parse($kegiatan->tgl_mulai)->format('d') . ' s.d. ' . Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d F Y');
            } else {
                $jkw = Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d F') . ' s.d. ' . Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d F');
            }
            if ($kegiatan->beban_anggaran) {
                $beban_anggaran = $kegiatan->beban_anggaran;
            }
            $jumlah_honor += $km->estimasi_honor;
            array_push($values, [
                'no_keg' => $count,
                'nama_keg' => $kegiatan->nama,
                'jkw' => $jkw,
                'vol_keg' => $km->jumlah,
                'sat_keg' => ($km->is_pml == 1 ? $kegiatan->satuan_honor_pengawasan : $kegiatan->satuan_honor_pencacahan),
                'harga_sat' => $satuan_honor,
                'honor' => $km->estimasi_honor,
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

        $filePath = 'SPK/' . $mitra->nama . '_' . $bulan . '_' . $tahun . '.docx';
        $phpWord->saveAs($filePath);
        return $filePath;
        // return response()->download($filePath);
    }

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
