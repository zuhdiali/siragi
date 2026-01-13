<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Pegawai;
use App\Models\Mitra;
use App\Models\Surat;
use App\Models\SBKS;
use App\Models\POK;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Imports\KegiatanMitraImport;
use App\Exports\ExportHonorKegiatan;
use App\Exports\ExportTranslok;
use App\Exports\ExportMitra;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatanTahunIni = Kegiatan::whereRaw(DB::raw('YEAR(tgl_selesai) = ' . date('Y')))->count();
        $kegiatans = Kegiatan::orderBy('tgl_selesai', 'asc')->get();
        foreach ($kegiatans as $kegiatan) {
            if ($kegiatan->honor_pencacahan == null) {
                $kegiatan->honor_pencacahan = 0;
            }
            if ($kegiatan->honor_pengawasan == null) {
                $kegiatan->honor_pengolahan = 0;
            }
            $kegiatan->pjk = Pegawai::find($kegiatan->id_pjk);
            $kegiatan->namaTim = $this->konversiTim($kegiatan->kak4_pjk);
        }
        return view('kegiatan.index', ['kegiatans' => $kegiatans, 'kegiatanTahunIni' => $kegiatanTahunIni]);
    }

    private function loadCreateEditView($viewPath, $jenis_kak, $id = null)
    {
        $data = [
            'pegawais' => Pegawai::where('flag', null)->where('nama', 'not like', '%Admin%')->where('nama', 'not like', '%Dummy%')->orderBy('nama', 'asc')->get(),
            'mitras' => Mitra::where('flag', null)->orderBy('nama', 'asc')->get(),
            'pok_awals' => POK::where('kode_aktivitas', null)->get(),
            'sbks' => SBKS::select(['nama_kegiatan', 'singkatan_resmi'])->where('ada_di_simeulue', 1)->distinct('nama_kegiatan')->orderBy('nama_kegiatan', 'asc')->get(),
            'skSurats' => Surat::where('jenis_surat', 'sk')->orderBy('nomor_surat', 'asc')->get(),
        ];
        foreach ($data['sbks'] as $item) {
            if ($item->singkatan_resmi) {
                $item->nama_kegiatan_dan_singkatan = $item->nama_kegiatan . ' (' . $item->singkatan_resmi . ')';
            } else {
                $item->nama_kegiatan_dan_singkatan = $item->nama_kegiatan;
            }
        }
        // untuk edit
        if ($id) {
            $data['kegiatan'] = Kegiatan::with([
                'kegiatanRincian.pok', // Agar nama akun (uraian) muncul
                'kegiatanLampiran',    // Agar data transport muncul
                'pokProgram',
                'pokAktivitas',
                'pokKro',
                'pokRo',
                'pokKomponen',
                'pokSubKomponen',
            ])->find($id);
        }
        $data['jenis_kak'] = $jenis_kak;
        // dd($data);
        return view($viewPath, $data);
    }

    public function translokBiasaCreate()
    {
        return $this->loadCreateEditView('kegiatan.create', 'translok-biasa');
    }

    public function translokBiasaEdit($id)
    {
        return $this->loadCreateEditView('kegiatan.edit', 'translok-biasa', $id);
    }

    public function translokBiasaShow($id)
    {
        return $this->loadCreateEditView('kegiatan.show', 'translok-biasa', $id);
    }

    public function translok8JamCreate()
    {
        return $this->loadCreateEditView('kegiatan.create', 'translok-8jam');
    }

    public function translok8JamEdit($id)
    {
        return $this->loadCreateEditView('kegiatan.edit', 'translok-8jam', $id);
    }

    public function translok8JamShow($id)
    {
        return $this->loadCreateEditView('kegiatan.show', 'translok-8jam', $id);
    }

    public function lainnyaCreate()
    {
        return $this->loadCreateEditView('kegiatan.create', 'lainnya');
    }

    public function lainnyaEdit($id)
    {
        return $this->loadCreateEditView('kegiatan.edit', 'lainnya', $id);
    }

    public function lainnyaShow($id)
    {
        return $this->loadCreateEditView('kegiatan.show', 'lainnya', $id);
    }

    public function pemanggilanKonsultasiCreate()
    {
        return $this->loadCreateEditView('kegiatan.create', 'pemanggilan-konsultasi');
    }

    public function pemanggilanKonsultasiEdit($id)
    {
        return $this->loadCreateEditView('kegiatan.edit', 'pemanggilan-konsultasi', $id);
    }

    public function pemanggilanKonsultasiShow($id)
    {
        return $this->loadCreateEditView('kegiatan.show', 'pemanggilan-konsultasi', $id);
    }

    public function honorMitraCreate()
    {
        return $this->loadCreateEditView('kegiatan.create', 'honor-mitra');
    }

    public function honorMitraEdit($id)
    {
        return $this->loadCreateEditView('kegiatan.edit', 'honor-mitra', $id);
    }

    public function honorMitraShow($id)
    {
        return $this->loadCreateEditView('kegiatan.show', 'honor-mitra', $id);
    }

    public function honorIndaCreate()
    {
        return $this->loadCreateEditView('kegiatan.create', 'honor-inda');
    }

    public function honorIndaEdit($id)
    {
        return $this->loadCreateEditView('kegiatan.edit', 'honor-inda', $id);
    }

    public function honorIndaShow($id)
    {
        return $this->loadCreateEditView('kegiatan.show', 'honor-inda', $id);
    }

    public function unduhKAK($id)
    {
        $kegiatan = Kegiatan::find($id);

        switch ($kegiatan->jenis_kak) {
            case 'translok-biasa':
                $phpWord = new \PhpOffice\PhpWord\TemplateProcessor("kak-translok-biasa.docx");
                $phpWord->setValue('nama', $kegiatan->nama);
                $phpWord->setValue('kak1_latar_belakang', $kegiatan->kak1_latar_belakang);
                $phpWord->setValue('singkatan_resmi', $kegiatan->singkatan_resmi);
                $phpWord->setValue('kak2_maksud', $kegiatan->kak2_maksud);
                $phpWord->setValue('kak2_tujuan', $kegiatan->kak2_tujuan);
                $phpWord->setValue('kak3_target', $kegiatan->kak3_target);
                $kak4_tgl_mulai = Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d F Y');
                $kak4_tgl_selesai = Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d F Y');
                $phpWord->setValue('kak4_tgl_mulai', $kak4_tgl_mulai);
                $phpWord->setValue('kak4_tgl_selesai', $kak4_tgl_selesai);
                $phpWord->setValue('pj', $this->konversiTim($kegiatan->tim));
                $phpWord = $this->findDetailRincianPOK($phpWord, $kegiatan->id);
                $phpWord->setValue('kak6_pembiayaan', $kegiatan->kak6_pembiayaan);
                $phpWord->setValue('tgl_kak', Carbon::parse($kegiatan->kak8_tgl)->locale('id')->translatedFormat('d F Y'));
                $phpWord->setValue('kak8_pengaju', $kegiatan->kak8_pengaju);
                $pengaju = Pegawai::find($kegiatan->id_pjk);
                if ($pengaju) {
                    $phpWord->setValue('nama_pengaju', $pengaju->nama);
                    $phpWord->setValue('nip_pengaju', $pengaju->nip);
                } else {
                    $phpWord->setValue('nama_pengaju', '-');
                    $phpWord->setValue('nip_pengaju', '-');
                }
                $values = [];
                $no = 1;
                foreach ($kegiatan->kegiatanLampiran as $index => $lampiran) {
                    $petugas = null;
                    if ($lampiran->tipe_personil == 'mitra') {
                        $petugas = Mitra::find($lampiran->peserta_id);
                    } else {
                        $petugas = Pegawai::find($lampiran->peserta_id);
                    }
                    $pcl_diawasi = Mitra::find($lampiran->pcl_diawasi);
                    array_push($values, [
                        'lamp_no' => $no++,
                        'lamp_nama' => $petugas ? $petugas->nama : '-',
                        'lamp_nip_nik' => $petugas->nip ? $petugas->nip : $petugas->nik,
                        'lamp_tujuan' => $this->konversiKodeKec($lampiran->kec_tujuan),
                        'lamp_tgl_pelaksanaan' => Carbon::parse($lampiran->tgl_pelaksanaan)->locale('id')->translatedFormat('d F Y'),
                        'lamp_pcl' => $pcl_diawasi ? $pcl_diawasi->nama : '-',
                        'lamp_jml_sampel' => $lampiran->jml_sampel_pcl,
                        'lamp_sampel_diawasi' => $lampiran->jml_sampel_diawasi,
                        'lamp_vol' => $lampiran->jml_ok,
                        'lamp_transport_bayar' => number_format($lampiran->transport_bayar, 0, ',', '.'),
                    ]);
                }
                $phpWord->cloneRowAndSetValues('lamp_no', $values);
                // 1. Tentukan nama file yang akan dilihat user saat download
                $fileNameUser = 'KAK_Translok_Biasa_' . str_replace(' ', '_', $kegiatan->singkatan_resmi) . '_' . time() . '.docx';

                // 2. Buat file temporary (sementara) di sistem server
                $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
                $phpWord->saveAs($tempFile);
                // Parameter 1: Path file sementara
                // Parameter 2: Nama file yang akan didownload user
                return response()->download($tempFile, $fileNameUser)->deleteFileAfterSend(true);
                break;
            case 'translok-8jam':
                $phpWord = new \PhpOffice\PhpWord\TemplateProcessor("kak-translok-8jam.docx");
                $phpWord->setValue('nama', $kegiatan->nama);
                $phpWord->setValue('kak1_latar_belakang', $kegiatan->kak1_latar_belakang);
                $phpWord->setValue('singkatan_resmi', $kegiatan->singkatan_resmi);
                $phpWord->setValue('kak2_maksud', $kegiatan->kak2_maksud);
                $phpWord->setValue('kak2_tujuan', $kegiatan->kak2_tujuan);
                $phpWord->setValue('kak3_target', $kegiatan->kak3_target);
                $kak4_tgl_mulai = Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d F Y');
                $kak4_tgl_selesai = Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d F Y');
                $phpWord->setValue('kak4_tgl_mulai', $kak4_tgl_mulai);
                $phpWord->setValue('kak4_tgl_selesai', $kak4_tgl_selesai);
                $phpWord->setValue('pj', $this->konversiTim($kegiatan->tim));
                $phpWord = $this->findDetailRincianPOK($phpWord, $kegiatan->id);
                $phpWord->setValue('kak6_pembiayaan', $kegiatan->kak6_pembiayaan);
                $phpWord->setValue('tgl_kak', Carbon::parse($kegiatan->kak8_tgl)->locale('id')->translatedFormat('d F Y'));
                $phpWord->setValue('kak8_pengaju', $kegiatan->kak8_pengaju);
                $pengaju = Pegawai::find($kegiatan->id_pjk);
                if ($pengaju) {
                    $phpWord->setValue('nama_pengaju', $pengaju->nama);
                    $phpWord->setValue('nip_pengaju', $pengaju->nip);
                } else {
                    $phpWord->setValue('nama_pengaju', '-');
                    $phpWord->setValue('nip_pengaju', '-');
                }
                $values = [];
                $no = 1;
                foreach ($kegiatan->kegiatanLampiran as $index => $lampiran) {
                    $petugas = null;
                    if ($lampiran->tipe_personil == 'mitra') {
                        $petugas = Mitra::find($lampiran->peserta_id);
                    } else {
                        $petugas = Pegawai::find($lampiran->peserta_id);
                    }
                    $pcl_diawasi = Mitra::find($lampiran->pcl_diawasi);
                    array_push($values, [
                        'lamp_no' => $no++,
                        'lamp_nama' => $petugas ? $petugas->nama : '-',
                        'lamp_nip_nik' => $petugas->nip ? $petugas->nip : $petugas->nik,
                        'lamp_tujuan' => $this->konversiKodeKec($lampiran->kec_tujuan),
                        'lamp_tgl_pelaksanaan' => Carbon::parse($lampiran->tgl_pelaksanaan)->locale('id')->translatedFormat('d F Y'),
                        'lamp_pcl' => $pcl_diawasi ? $pcl_diawasi->nama : '-',
                        'lamp_jml_sampel' => $lampiran->jml_sampel_pcl,
                        'lamp_sampel_diawasi' => $lampiran->jml_sampel_diawasi,
                        'lamp_vol' => $lampiran->jml_ok,
                        'lamp_transport_bayar' => number_format($lampiran->transport_bayar, 0, ',', '.'),
                    ]);
                }
                $phpWord->cloneRowAndSetValues('lamp_no', $values);
                // 1. Tentukan nama file yang akan dilihat user saat download
                $fileNameUser = 'KAK_Translok_DI_ATAS_8_JAM_' . str_replace(' ', '_', $kegiatan->singkatan_resmi) . '_' . time() . '.docx';

                // 2. Buat file temporary (sementara) di sistem server
                $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
                $phpWord->saveAs($tempFile);
                // Parameter 1: Path file sementara
                // Parameter 2: Nama file yang akan didownload user
                return response()->download($tempFile, $fileNameUser)->deleteFileAfterSend(true);
                break;
            case 'pemanggilan-konsultasi':
                $phpWord = new \PhpOffice\PhpWord\TemplateProcessor("kak-pemanggilan-konsultasi.docx");
                $phpWord->setValue('nama', $kegiatan->nama);
                $phpWord->setValue('kak1_latar_belakang', $kegiatan->kak1_latar_belakang);
                $phpWord->setValue('singkatan_resmi', $kegiatan->singkatan_resmi);
                $phpWord->setValue('kak2_maksud', $kegiatan->kak2_maksud);
                $phpWord->setValue('kak2_tujuan', $kegiatan->kak2_tujuan);
                $phpWord->setValue('kak3_target', $kegiatan->kak3_target);
                $kak4_tgl_mulai = Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d F Y');
                $kak4_tgl_selesai = Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d F Y');
                $phpWord->setValue('kak4_tgl_mulai', $kak4_tgl_mulai);
                $phpWord->setValue('kak4_tgl_selesai', $kak4_tgl_selesai);
                $phpWord->setValue('pj', $this->konversiTim($kegiatan->tim));
                $phpWord = $this->findDetailRincianPOK($phpWord, $kegiatan->id);
                $phpWord->setValue('tgl_kak', Carbon::parse($kegiatan->kak8_tgl)->locale('id')->translatedFormat('d F Y'));
                $phpWord->setValue('kak8_pengaju', $kegiatan->kak8_pengaju);
                $pengaju = Pegawai::find($kegiatan->id_pjk);
                if ($pengaju) {
                    $phpWord->setValue('nama_pengaju', $pengaju->nama);
                    $phpWord->setValue('nip_pengaju', $pengaju->nip);
                } else {
                    $phpWord->setValue('nama_pengaju', '-');
                    $phpWord->setValue('nip_pengaju', '-');
                }
                $values = [];
                $no = 1;
                foreach ($kegiatan->kegiatanLampiran as $index => $lampiran) {
                    $peserta = Pegawai::find($lampiran->peserta_id);
                    array_push($values, [
                        'lamp_no' => $no++,
                        'lamp_nama' => $peserta ? $peserta->nama : '-',
                        'lamp_nip' => $peserta ? $peserta->nip : '-',
                        'lamp_tujuan' => $kegiatan->kak2_tujuan,
                        'lamp_tgl_mulai' => Carbon::parse($lampiran->tgl_mulai)->locale('id')->translatedFormat('d F Y'),
                        'lamp_tgl_selesai' => Carbon::parse($lampiran->tgl_selesai)->locale('id')->translatedFormat('d F Y'),
                        'lamp_biaya' => number_format($lampiran->transport_bayar, 0, ',', '.'),
                    ]);
                }
                $phpWord->cloneRowAndSetValues('lamp_no', $values);

                // 1. Tentukan nama file yang akan dilihat user saat download
                $fileNameUser = 'KAK_Pemanggilan_Konsul_' . str_replace(' ', '_', $kegiatan->singkatan_resmi) . '_' . time() . '.docx';

                // 2. Buat file temporary (sementara) di sistem server
                $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
                $phpWord->saveAs($tempFile);

                // Parameter 1: Path file sementara
                // Parameter 2: Nama file yang akan didownload user
                return response()->download($tempFile, $fileNameUser)->deleteFileAfterSend(true);
                break;
            case 'honor-mitra':
                $phpWord = new \PhpOffice\PhpWord\TemplateProcessor("kak-honor-mitra.docx");
                $phpWord->setValue('nama', $kegiatan->nama);
                $phpWord->setValue('kak1_latar_belakang', $kegiatan->kak1_latar_belakang);
                $phpWord->setValue('singkatan_resmi', $kegiatan->singkatan_resmi);
                $kak4_tgl_mulai = Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d F Y');
                $kak4_tgl_selesai = Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d F Y');
                $phpWord->setValue('kak4_tgl_mulai', $kak4_tgl_mulai);
                $phpWord->setValue('kak4_tgl_selesai', $kak4_tgl_selesai);
                $phpWord->setValue('pj', $this->konversiTim($kegiatan->tim));
                $sk = Surat::find($kegiatan->kak5_sk);
                if ($sk) {
                    $phpWord->setValue('no_sk', $sk->no_terakhir);
                    $phpWord->setValue('tgl_sk', Carbon::parse($sk->tgl_surat)->locale('id')->translatedFormat('d F Y'));
                    $phpWord->setValue('perihal_sk', $sk->perihal);
                } else {
                    $phpWord->setValue('no_sk', '-');
                    $phpWord->setValue('tgl_sk', '-');
                    $phpWord->setValue('perihal_sk', '-');
                }
                $phpWord = $this->findDetailRincianPOK($phpWord, $kegiatan->id);
                $phpWord->setValue('tgl_kak', Carbon::parse($kegiatan->kak8_tgl)->locale('id')->translatedFormat('d F Y'));
                $phpWord->setValue('kak8_pengaju', $kegiatan->kak8_pengaju);
                $pengaju = Pegawai::find($kegiatan->id_pjk);
                if ($pengaju) {
                    $phpWord->setValue('nama_pengaju', $pengaju->nama);
                    $phpWord->setValue('nip_pengaju', $pengaju->nip);
                } else {
                    $phpWord->setValue('nama_pengaju', '-');
                    $phpWord->setValue('nip_pengaju', '-');
                }
                $values = [];
                $no = 1;
                foreach ($kegiatan->kegiatanLampiran as $index => $lampiran) {
                    $petugas = Mitra::find($lampiran->peserta_id);
                    $pengawas = null;
                    if ($lampiran->tipe_pengawas == 'organik') {
                        $pengawas = Pegawai::find($lampiran->pengawas_id);
                    } else if ($lampiran->tipe_pengawas == 'non-organik') {
                        $pengawas = Mitra::find($lampiran->pengawas_id);
                    }
                    array_push($values, [
                        'lamp_no' => $no++,
                        'lamp_nama' => $petugas ? $petugas->nama : '-',
                        'lamp_nip_nik' => $petugas ? $petugas->nik : '-',
                        'lamp_tugas' => $lampiran->pcl_or_pml == 1 ? 'PML' : 'PCL',
                        'lamp_tujuan' => $this->konversiKodeKec($lampiran->kec_tujuan),
                        'lamp_sls' => $lampiran->nama_sls,
                        'lamp_sampel' => $lampiran->jml_sampel_pcl,
                        'lamp_pengawas' => $pengawas ? $pengawas->nama : '-',
                        'lamp_tgl_mulai' => Carbon::parse($lampiran->lampiran_tgl_mulai)->locale('id')->translatedFormat('d F Y'),
                        'lamp_tgl_selesai' => Carbon::parse($lampiran->lampiran_tgl_selesai)->locale('id')->translatedFormat('d F Y'),
                        'lamp_honor' => $lampiran->pcl_or_pml == 1 ? number_format($kegiatan->honor_pengawasan, 0, ',', '.') : number_format($kegiatan->honor_pencacahan, 0, ',', '.'),
                    ]);
                }
                $phpWord->cloneRowAndSetValues('lamp_no', $values);

                // 1. Tentukan nama file yang akan dilihat user saat download
                $fileNameUser = 'KAK_Honor_Mitra_' . str_replace(' ', '_', $kegiatan->singkatan_resmi) . '_' . time() . '.docx';

                // 2. Buat file temporary (sementara) di sistem server
                $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
                $phpWord->saveAs($tempFile);

                // Parameter 1: Path file sementara
                // Parameter 2: Nama file yang akan didownload user
                return response()->download($tempFile, $fileNameUser)->deleteFileAfterSend(true);
                break;
            case 'honor-inda':
                $phpWord = new \PhpOffice\PhpWord\TemplateProcessor("kak-honor-inda.docx");
                $phpWord->setValue('nama', $kegiatan->nama);
                $phpWord->setValue('kak1_latar_belakang', $kegiatan->kak1_latar_belakang);
                $phpWord->setValue('singkatan_resmi', $kegiatan->singkatan_resmi);
                $kak4_tgl_mulai = Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d F Y');
                $kak4_tgl_selesai = Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d F Y');
                $phpWord->setValue('kak4_tgl_mulai', $kak4_tgl_mulai);
                $phpWord->setValue('kak4_tgl_selesai', $kak4_tgl_selesai);
                $phpWord->setValue('pj', $this->konversiTim($kegiatan->tim));
                $sk = Surat::find($kegiatan->kak5_sk);
                if ($sk) {
                    $phpWord->setValue('no_sk', $sk->no_terakhir);
                    $phpWord->setValue('tgl_sk', Carbon::parse($sk->tgl_surat)->locale('id')->translatedFormat('d F Y'));
                    $phpWord->setValue('perihal_sk', $sk->perihal);
                } else {
                    $phpWord->setValue('no_sk', '-');
                    $phpWord->setValue('tgl_sk', '-');
                    $phpWord->setValue('perihal_sk', '-');
                }
                $phpWord = $this->findDetailRincianPOK($phpWord, $kegiatan->id);
                $phpWord->setValue('tgl_kak', Carbon::parse($kegiatan->kak8_tgl)->locale('id')->translatedFormat('d F Y'));
                $phpWord->setValue('kak8_pengaju', $kegiatan->kak8_pengaju);
                $pengaju = Pegawai::find($kegiatan->id_pjk);
                if ($pengaju) {
                    $phpWord->setValue('nama_pengaju', $pengaju->nama);
                    $phpWord->setValue('nip_pengaju', $pengaju->nip);
                } else {
                    $phpWord->setValue('nama_pengaju', '-');
                    $phpWord->setValue('nip_pengaju', '-');
                }
                $values = [];
                $no = 1;
                foreach ($kegiatan->kegiatanLampiran as $index => $lampiran) {
                    $inda = Pegawai::find($lampiran->peserta_id);
                    array_push($values, [
                        'lamp_no' => $no++,
                        'lamp_nama' => $inda ? $inda->nama : '-',
                        'lamp_nik' => $inda ? $inda->nip : '-',
                        'lamp_honor' => number_format($lampiran->transport_bayar, 0, ',', '.'),
                    ]);
                }
                $phpWord->cloneRowAndSetValues('lamp_no', $values);

                // 1. Tentukan nama file yang akan dilihat user saat download
                $fileNameUser = 'KAK_Honor_Inda_' . str_replace(' ', '_', $kegiatan->singkatan_resmi) . '_' . time() . '.docx';

                // 2. Buat file temporary (sementara) di sistem server
                $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
                $phpWord->saveAs($tempFile);

                // Parameter 1: Path file sementara
                // Parameter 2: Nama file yang akan didownload user
                return response()->download($tempFile, $fileNameUser)->deleteFileAfterSend(true);
                break;
            default:
                return redirect()->back()->with('error', 'Jenis KAK tidak dikenali.');
                break;
        }
    }

    public function findDetailRincianPOK($phpWord, $id)
    {
        $kegiatan = Kegiatan::find($id);
        if ($kegiatan) {
            $pok_program = POK::find($kegiatan->kak6_program);
            $pok_aktivitas = POK::find($kegiatan->kak6_aktivitas);
            $pok_kro = POK::find($kegiatan->kak6_kro);
            $pok_ro = POK::find($kegiatan->kak6_ro);
            $pok_komponen = POK::find($kegiatan->kak6_komponen);
            $pok_sub_komponen = POK::find($kegiatan->kak6_sub_komponen);

            if ($pok_program) {
                $phpWord->setValue('program', $pok_program->uraian . ' (' . $pok_program->kode_program . ')');
            } else {
                $phpWord->setValue('program', '-');
            }
            if ($pok_aktivitas) {
                $phpWord->setValue('aktivitas', $pok_aktivitas->uraian . ' (' . $pok_aktivitas->kode_aktivitas . ')');
            } else {
                $phpWord->setValue('aktivitas', '-');
            }

            if ($pok_kro) {
                $phpWord->setValue('kro', $pok_kro->uraian . ' (' . $pok_kro->kode_klasifikasi_rincian_output . ')');
            } else {
                $phpWord->setValue('kro', '-');
            }

            if ($pok_ro) {
                $phpWord->setValue('ro', $pok_ro->uraian . ' (' . $pok_ro->kode_rincian_output . ')');
            } else {
                $phpWord->setValue('ro', '-');
            }

            if ($pok_komponen) {
                $phpWord->setValue('komponen', $pok_komponen->uraian . ' (' . $pok_komponen->kode_komponen . ')');
            } else {
                $phpWord->setValue('komponen', '-');
            }

            if ($pok_sub_komponen) {
                $phpWord->setValue('sub_komponen', $pok_sub_komponen->uraian . ' (' . $pok_sub_komponen->kode_sub_komponen . ')');
            } else {
                $phpWord->setValue('sub_komponen', '-');
            }
            $values = [];
            $total_biaya = 0;
            foreach ($kegiatan->kegiatanRincian as $index => $rincian) {
                $pok_akun = POK::find($rincian->pok_id);
                array_push($values, [
                    'akun' => $pok_akun ?  $pok_akun->kode_akun : '-',
                    'rincian_akun' => $rincian->rincian,
                    'vol' => $rincian->vol,
                    'satuan' => $rincian->satuan,
                    'harga' => number_format($rincian->harga_satuan, 0, ',', '.'),
                    'jml' => number_format($rincian->jumlah, 0, ',', '.'),
                ]);
                $total_biaya += $rincian->jumlah;
            }
            $phpWord->setValue('total_biaya', number_format($total_biaya, 0, ',', '.'));
            $phpWord->setValue('total_biaya_terbilang', $this->terbilang($total_biaya));
            $phpWord->cloneRowAndSetValues('akun', $values);
        }
        return $phpWord;
    }
    public function create()
    {
        $mitras = Mitra::where('flag', null)->orderBy('nama', 'asc')->get();
        $pegawais = Pegawai::where('flag', null)->orderBy('nama', 'asc')->get();
        $sbks = SBKS::select(['nama_kegiatan', 'singkatan_resmi'])->where('ada_di_simeulue', 1)->distinct('nama_kegiatan')->orderBy('nama_kegiatan', 'asc')->get();
        $semuaSK = Surat::where('jenis_surat', 'sk')->orderBy('nomor_surat', 'asc')->get();
        foreach ($sbks as $item) {
            if ($item->singkatan_resmi) {
                $item->nama_kegiatan_dan_singkatan = $item->nama_kegiatan . ' (' . $item->singkatan_resmi . ')';
            } else {
                $item->nama_kegiatan_dan_singkatan = $item->nama_kegiatan;
            }
        }
        // dd($sbks);
        // return view('kegiatan.create', ['kegiatans' => $kegiatans, 'mitras' => $mitras]);
        return view('kegiatan.create', ['pegawais' => $pegawais, 'mitras' => $mitras, 'sbks' => $sbks, 'skSurats' => $semuaSK]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the request...
        $request->validate([
            // 'nama' => 'required|max:254',
            // 'tgl_mulai' => 'required|date',
            // 'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            // 'honor_pengawasan' => 'nullable|numeric',
            // 'honor_pencacahan' => 'nullable|numeric',
            // 'satuan_honor_pengawasan' => 'required',
            // 'satuan_honor_pencacahan' => 'required',
            // 'id_pjk' => 'required',
            // 'tim' => 'required',
        ]);

        //untuk menandai apakah ada mitra yang melebihi batas honor
        // $mitraMelebihiHonor = $this->validasiHonorMitra($request->mitra_id, $request->tgl_mulai);
        // if (count($mitraMelebihiHonor) > 0) {
        //     return redirect()->route('kegiatan.create')->with('error', 'Mitra ' . implode(",", $mitraMelebihiHonor) . ' yang melebihi batas honor.')->withInput();
        // }

        $kegiatan = Kegiatan::create([
            'nama' => $request->judul_kak,
            'jenis_kak' => $request->jenis_kak,
            'singkatan_resmi' => $request->singkatan_resmi,
            'kak1_latar_belakang' => $request->kak1_latar_belakang,
            'kak2_maksud' => $request->kak2_maksud,
            'kak2_tujuan' => $request->kak2_tujuan,
            'kak3_target' => $request->kak3_target,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'kak5_sk' => $request->kak5_sk,
            'kak4_pjk' => $request->kak4_pjk,
            'kak6_program' => $request->kak6_program,
            'kak6_aktivitas' => $request->kak6_aktivitas,
            'kak6_kro' => $request->kak6_kro,
            'kak6_ro' => $request->kak6_ro,
            'kak6_komponen' => $request->kak6_komponen,
            'kak6_sub_komponen' => $request->kak6_sub_komponen,
            'kak6_pembiayaan' => $request->kak6_pembiayaan,
            'kak6_total' => $request->kak6_total,
            'kak8_pengaju' => $request->kak8_pengaju,
            'kak8_tgl' => $request->kak8_tgl,
            'id_pjk' => $request->id_pjk,
            'tim' => $request->kak4_pjk,
            'honor_pengawasan' => $request->honor_pengawasan,
            'honor_pencacahan' => $request->honor_pencacahan,
        ]);
        $jumlah = 0;
        if ($request->has('akun_kode')) {
            foreach ($request->akun_kode as $index => $akun_kode) {
                $rincian_total = 0;
                if (str_contains($request->rincian_total[$index], '.')) {
                    $rincian_total = str_replace('.', '', $request->rincian_total[$index]);
                } else {
                    $rincian_total = $request->rincian_total[$index];
                }
                $kegiatan->kegiatanRincian()->create([
                    'kegiatan_id' => $kegiatan->id,
                    'pok_id' => $request->akun_kode[$index],
                    'rincian' => $request->rincian_detail[$index],
                    'vol' => $request->rincian_volume[$index],
                    'satuan' => $request->rincian_satuan[$index],
                    'harga_satuan' => $request->rincian_harga[$index],
                    'jumlah' => $rincian_total,
                ]);
                $jumlah += $rincian_total;
            }
            $kegiatan->kak6_total = $jumlah;
        }
        if ($request->jenis_kak == 'lainnya' && !$request->has('tim')) {
            $kegiatan->kak4_pjk = '11011';
            $kegiatan->tim = '11011';
        }
        $kegiatan->save();

        if ($request->jenis_kak == 'translok-biasa' || $request->jenis_kak == 'translok-8jam') {
            foreach ($request->tipe_peserta as $index => $tipe_personil) {
                $kegiatan->kegiatanLampiran()->create([
                    'peserta_id' => $request->peserta_id[$index],
                    'tipe_personil' => $request->tipe_peserta[$index],
                    'nip_nik' => $request->nip[$index],
                    'kec_tujuan' => $request->kec_tujuan[$index],
                    'tgl_pelaksanaan' => $request->tanggal_pelaksanaan[$index],
                    'pcl_diawasi' => $request->pcl_diawasi[$index],
                    'jml_sampel_pcl' => $request->jml_sampel_pcl[$index],
                    'jml_sampel_diawasi' => $request->jml_sampel_diawasi[$index],
                    'jml_ok' => $request->jml_ok[$index],
                    'transport_bayar' => $request->transport_bayar[$index],
                ]);
            }
        } else if ($request->jenis_kak == 'pemanggilan-konsultasi') {
            foreach ($request->tipe_peserta as $index => $tipe_personil) {
                $kegiatan->kegiatanLampiran()->create([
                    'peserta_id' => $request->peserta_id[$index],
                    'tipe_personil' => $request->tipe_peserta[$index],
                    'nip_nik' => $request->nip[$index],
                    'nama_sls' => $request->nama_sls[$index],
                    'lampiran_tgl_mulai' => $request->lampiran_tgl_mulai[$index],
                    'lampiran_tgl_selesai' => $request->lampiran_tgl_selesai[$index],
                    'transport_bayar' => $request->transport_bayar[$index],
                ]);
            }
        } else if ($request->jenis_kak == 'honor-inda') {
            foreach ($request->tipe_peserta as $index => $tipe_personil) {
                $kegiatan->kegiatanLampiran()->create([
                    'peserta_id' => $request->peserta_id[$index],
                    'tipe_personil' => $request->tipe_peserta[$index],
                    'nip_nik' => $request->nip[$index],
                    'transport_bayar' => $request->transport_bayar[$index],
                ]);
            }
        } else if ($request->jenis_kak == 'honor-mitra') {
            foreach ($request->peserta_id as $index => $peserta_id) {
                $kegiatan->kegiatanLampiran()->create([
                    'peserta_id' => $request->peserta_id[$index],
                    'tipe_personil' => $request->tipe_peserta[$index],
                    'nip_nik' => $request->nip[$index],
                    'pcl_or_pml' => $request->pcl_or_pml[$index],
                    'kec_tujuan' => $request->kec_tujuan[$index],
                    'nama_sls' => $request->nama_sls[$index],
                    'jml_sampel_pcl' => $request->jml_sampel_pcl[$index],
                    'tipe_pengawas' => $request->tipe_pengawas[$index],
                    'pengawas_id' => $request->pengawas_id[$index],
                    'lampiran_tgl_mulai' => $request->lampiran_tgl_mulai[$index],
                    'lampiran_tgl_selesai' => $request->lampiran_tgl_selesai[$index],
                    'transport_bayar' => $this->hitungTransportBayar($request, $index, $kegiatan),
                ]);
            }
        }
        // $kegiatan = new Kegiatan;
        // $kegiatan->nama = $request->nama;
        // $kegiatan->tgl_mulai = $request->tgl_mulai;
        // $kegiatan->tgl_selesai = $request->tgl_selesai;
        // $kegiatan->satuan_honor_pengawasan = $request->satuan_honor_pengawasan;
        // $kegiatan->honor_pengawasan = $request->honor_pengawasan;
        // $kegiatan->satuan_honor_pencacahan = $request->satuan_honor_pencacahan;
        // $kegiatan->honor_pencacahan = $request->honor_pencacahan;
        // $kegiatan->id_pjk = $request->id_pjk;
        // $kegiatan->tim = $request->tim;
        // $kegiatan->progress = $request->progress;
        // if ($request->filter_sbks) {
        //     $sbks = SBKS::where('nama_kegiatan', $request->filter_sbks)->where('beban_anggaran', '!=', null)->first();
        //     if ($sbks) {
        //         $kegiatan->beban_anggaran = $sbks->beban_anggaran;
        //     }
        // } else {
        //     $kegiatan->beban_anggaran = $request->beban_anggaran ?? '{#beban_anggaran#}';
        // }
        // $kegiatan->save();
        // if ($request->pegawai != null) {
        //     $kegiatan->pegawai()->attach($request->pegawai);
        // }
        // if ($request->mitra != null) {
        //     $kegiatan->mitra()->attach($request->mitra);
        // }


        if (!$kegiatan->wasRecentlyCreated) {
            return redirect()->route('kegiatan.create')->with('error', 'Gagal.');
        }

        return redirect()->route('kegiatan.' . str_replace('_', '-', $request->jenis_kak) . '.show', ['id' => $kegiatan->id])->with('success', 'KAK berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::find($id);
        $mitras = Mitra::where('flag', null)->orderBy('nama', 'asc')->get();
        $pegawais = Pegawai::where('flag', null)->orderBy('nama', 'asc')->get();
        $skSurats = Surat::where('jenis_surat', 'sk')->orderBy('nomor_surat', 'asc')->get();
        return view('kegiatan.edit', ['kegiatan' => $kegiatan, 'pegawais' => $pegawais, 'mitras' => $mitras, 'skSurats' => $skSurats]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // 1. Validasi (Sesuaikan jika perlu)
        // $request->validate([
        //     'nama' => 'required',
        //     'tgl_mulai' => 'required|date',
        //     // ... validasi lainnya ...
        // ]);

        // 2. Ambil Data Kegiatan Lama
        $kegiatan = Kegiatan::findOrFail($id);

        // 3. Gabungkan Target 1 dan Target 2 (jika di form dipisah)
        $gabungan_target = $request->kak3_target;

        // 4. Update Data Utama
        $kegiatan->update([
            'nama' => $request->judul_kak, // Pastikan name di form edit adalah 'nama'
            'jenis_kak' => $request->jenis_kak,
            'singkatan_resmi' => $request->singkatan_resmi,
            'kak1_latar_belakang' => $request->kak1_latar_belakang,
            'kak2_maksud' => $request->kak2_maksud,
            'kak2_tujuan' => $request->kak2_tujuan,
            'kak3_target' => $gabungan_target,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'kak4_pjk' => $request->kak4_pjk,
            'kak5_sk' => $request->kak5_sk,
            'kak6_program' => $request->kak6_program,
            'kak6_aktivitas' => $request->kak6_aktivitas,
            'kak6_kro' => $request->kak6_kro,
            'kak6_ro' => $request->kak6_ro,
            'kak6_komponen' => $request->kak6_komponen,
            'kak6_sub_komponen' => $request->kak6_sub_komponen,
            'kak6_pembiayaan' => $request->kak6_pembiayaan,
            // 'kak6_total' => dihitung ulang di bawah
            'tim' => $request->kak4_pjk,
            'kak8_pengaju' => $request->kak8_pengaju,
            'kak8_tgl' => $request->kak8_tgl,
            'honor_pengawasan' => $request->honor_pengawasan,
            'honor_pencacahan' => $request->honor_pencacahan,
            'id_pjk' => $request->id_pjk,
        ]);

        // 5. Update Rincian Akun (Hapus Lama -> Buat Baru)
        // Hapus semua rincian lama
        $kegiatan->kegiatanRincian()->delete();

        $jumlah_total = 0;
        if ($request->has('akun_kode')) {
            foreach ($request->akun_kode as $index => $akun_kode) {
                $kegiatan->kegiatanRincian()->create([
                    'kegiatan_id' => $kegiatan->id,
                    'pok_id' => $request->akun_kode[$index],
                    'rincian' => $request->rincian_detail[$index],
                    'vol' => $request->rincian_volume[$index],
                    'satuan' => $request->rincian_satuan[$index],
                    'harga_satuan' => $request->rincian_harga[$index],
                    'jumlah' => $request->rincian_total[$index],
                ]);
                $jumlah_total += $request->rincian_total[$index];
            }
            // Simpan total baru ke tabel utama
            $kegiatan->kak6_total = $jumlah_total;
        }

        $kegiatan->save();

        // 6. Update Lampiran/Personil (Hapus Lama -> Buat Baru)
        // Hapus semua lampiran lama
        $kegiatan->kegiatanLampiran()->delete();

        if ($request->has('peserta_id')) {
            if ($request->jenis_kak == 'translok-biasa' || $request->jenis_kak == 'translok-8jam') {
                foreach ($request->peserta_id as $index => $peserta_id) {
                    $kegiatan->kegiatanLampiran()->create([
                        'kegiatan_id' => $kegiatan->id, // Penting! Jangan lupa ini
                        'peserta_id' => $request->peserta_id[$index],
                        'tipe_personil' => $request->tipe_peserta[$index],
                        'nip_nik' => $request->nip[$index],
                        'kec_tujuan' => $request->kec_tujuan[$index],
                        'tgl_pelaksanaan' => $request->tanggal_pelaksanaan[$index],
                        'pcl_diawasi' => $request->pcl_diawasi[$index],
                        'jml_sampel_pcl' => $request->jml_sampel_pcl[$index],
                        'jml_sampel_diawasi' => $request->jml_sampel_diawasi[$index],
                        'jml_ok' => $request->jml_ok[$index],
                        'transport_bayar' => $request->transport_bayar[$index],
                    ]);
                }
            } else if ($request->jenis_kak == 'pemanggilan-konsultasi') {
                foreach ($request->peserta_id as $index => $peserta_id) {
                    $kegiatan->kegiatanLampiran()->create([
                        'kegiatan_id' => $kegiatan->id, // Penting! Jangan lupa ini
                        'peserta_id' => $request->peserta_id[$index],
                        'tipe_personil' => $request->tipe_peserta[$index],
                        'nip_nik' => $request->nip[$index],
                        'nama_sls' => $request->nama_sls[$index],
                        'lampiran_tgl_mulai' => $request->lampiran_tgl_mulai[$index],
                        'lampiran_tgl_selesai' => $request->lampiran_tgl_selesai[$index],
                        'transport_bayar' => $request->transport_bayar[$index],
                    ]);
                }
            } else if ($request->jenis_kak == 'honor-inda') {
                foreach ($request->tipe_peserta as $index => $tipe_personil) {
                    $kegiatan->kegiatanLampiran()->create([
                        'peserta_id' => $request->peserta_id[$index],
                        'tipe_personil' => $request->tipe_peserta[$index],
                        'nip_nik' => $request->nip[$index],
                        'transport_bayar' => $request->transport_bayar[$index],
                    ]);
                }
            } else if ($request->jenis_kak == 'honor-mitra') {
                foreach ($request->peserta_id as $index => $peserta_id) {
                    $kegiatan->kegiatanLampiran()->create([
                        'peserta_id' => $request->peserta_id[$index],
                        'tipe_personil' => $request->tipe_peserta[$index],
                        'nip_nik' => $request->nip[$index],
                        'pcl_or_pml' => $request->pcl_or_pml[$index],
                        'kec_tujuan' => $request->kec_tujuan[$index],
                        'nama_sls' => $request->nama_sls[$index],
                        'jml_sampel_pcl' => $request->jml_sampel_pcl[$index],
                        'tipe_pengawas' => $request->tipe_pengawas[$index],
                        'pengawas_id' => $request->pengawas_id[$index],
                        'lampiran_tgl_mulai' => $request->lampiran_tgl_mulai[$index],
                        'lampiran_tgl_selesai' => $request->lampiran_tgl_selesai[$index],
                        'transport_bayar' => $this->hitungTransportBayar($request, $index, $kegiatan),
                    ]);
                }
            }
        }

        return redirect()->route('kegiatan.' . str_replace('_', '-', $request->jenis_kak) . '.show', ['id' => $kegiatan->id])->with('success', 'KAK berhasil diperbarui.');
    }

    // public function update(Request $request, $id)
    // {
    //     // dd($request->all());
    //     // Validate the request...
    //     // $request->validate([
    //     //     'nama' => 'required|max:254',
    //     //     'tgl_mulai' => 'required|date',
    //     //     'tgl_selesai' => 'required|date',
    //     //     'honor_pengawasan' => 'nullable|numeric',
    //     //     'honor_pencacahan' => 'nullable|numeric',
    //     //     'satuan_honor_pengawasan' => 'required',
    //     //     'satuan_honor_pencacahan' => 'required',
    //     //     'id_pjk' => 'required',
    //     //     'tim' => 'required',
    //     // ]);


    //     try {
    //         $kegiatan = Kegiatan::find($id);
    //         $tgl_mulai_sebelumnya = $kegiatan->tgl_mulai;
    //         $kegiatan->tgl_mulai = $request->tgl_mulai;
    //         $kegiatan->save();
    //         // validasi honor mitra
    //         $mitraMelebihiHonor = $this->validasiHonorMitra($kegiatan->mitra, $request->tgl_mulai);
    //         // dd($mitraMelebihiHonor);
    //         if ($mitraMelebihiHonor) {
    //             $kegiatan->tgl_mulai = $tgl_mulai_sebelumnya;
    //             $kegiatan->save();
    //             return redirect()->route('kegiatan.edit', ['id' => $id])->with('error', 'Mitra ' . implode(", ", $mitraMelebihiHonor) . ' melebihi batas honor jika kegiatan diubah ke tanggal ' . $request->tgl_mulai . '.');
    //         }
    //         $kegiatan->nama = $request->nama;
    //         $kegiatan->tgl_mulai = $request->tgl_mulai;
    //         $kegiatan->tgl_selesai = $request->tgl_selesai;
    //         $honor_pengawasan_sebelumnya = $kegiatan->honor_pengawasan;
    //         $kegiatan->satuan_honor_pengawasan = $request->satuan_honor_pengawasan;
    //         $kegiatan->honor_pengawasan = $request->honor_pengawasan;
    //         $honor_pencacahan_sebelumnya = $kegiatan->honor_pencacahan;
    //         $kegiatan->satuan_honor_pencacahan = $request->satuan_honor_pencacahan;
    //         $kegiatan->honor_pencacahan = $request->honor_pencacahan;
    //         $kegiatan->id_pjk = $request->id_pjk;
    //         $kegiatan->tim = $request->tim;
    //         $kegiatan->progress = $request->progress;
    //         $kegiatan->beban_anggaran = $request->beban_anggaran;
    //         $kegiatan->pegawai()->sync($request->pegawai);
    //         $kegiatan->mitra()->sync($request->mitra);
    //         $kegiatan->save();

    //         // Flag apakah ada perubahan honor
    //         $flag = false;
    //         if ($honor_pencacahan_sebelumnya != $request->honor_pencacahan || $honor_pengawasan_sebelumnya != $request->honor_pengawasan) {
    //             $flag = true;
    //         }
    //         foreach ($kegiatan->mitra as $mitra) {
    //             // Isi default tanggal realisasi jika belum diisi
    //             // if ($mitra->pivot->tgl_realisasi == null) {
    //             //     $kegiatan->mitra()->updateExistingPivot($mitra->id, ['tgl_realisasi' => $kegiatan->tgl_selesai]);
    //             // }

    //             // Hitung estimasi honor
    //             if ($flag) {
    //                 $estimasi_honor = 0;
    //                 $is_pml = 0;
    //                 if ($mitra->pivot->is_pml == 1) {
    //                     $estimasi_honor = $request->honor_pengawasan * $mitra->pivot->jumlah;
    //                     $is_pml = 1;
    //                 } else {
    //                     $estimasi_honor = $request->honor_pencacahan * $mitra->pivot->jumlah;
    //                 }
    //                 $kegiatan->mitra()->updateExistingPivot($mitra->id, ['estimasi_honor' => $estimasi_honor, 'is_pml' => $is_pml]);
    //             }
    //         }
    //     } catch (\Throwable $th) {
    //         return redirect()->route('kegiatan.edit', ['id' => $id])->with('error', 'Gagal.');
    //     }

    //     return redirect()->route('kegiatan.show', ['id' => $kegiatan->id])->with('success', 'Kegiatan berhasil diubah.');
    // }

    public function show($id)
    {
        $kegiatan = Kegiatan::find($id);
        if ($kegiatan->honor_pencacahan == null) {
            $kegiatan->honor_pencacahan = 0;
        }
        if ($kegiatan->honor_pengawasan == null) {
            $kegiatan->honor_pengolahan = 0;
        }
        // foreach ($kegiatan->mitra as $mitra) {
        //     $mitra->pjk = Pegawai::find($mitra->id_pjk);
        // }


        $currentMonth = Carbon::parse($kegiatan->tgl_selesai)->month;
        $currentYear = Carbon::parse($kegiatan->tgl_selesai)->year;

        // Total estimasi honor dari kegiatan lain untuk setiap mitra, dengan filter bulan dan tahun ini
        $mitraEstimasiHonors = [];
        foreach ($kegiatan->mitra as $mitra) {
            $estimasiDariLainnya = $mitra->kegiatan()
                ->join('kegiatans as k2', 'k2.id', '=', 'kegiatan_mitras.kegiatan_id') // Alias tabel kegiatans ke 'k2'
                ->where('kegiatan_mitras.kegiatan_id', '<>', $id) // Filter kegiatan lain
                ->whereMonth('k2.tgl_selesai', $currentMonth) // Filter bulan sekarang (kolom 'tgl_selesai' dari alias 'k2')
                ->whereYear('k2.tgl_selesai', $currentYear) // Filter tahun sekarang
                ->selectRaw('SUM(CASE 
                                WHEN kegiatan_mitras.honor IS NOT NULL THEN kegiatan_mitras.honor 
                                ELSE kegiatan_mitras.estimasi_honor 
                            END) as total_honor') // Pilih honor jika ada, estimasi_honor jika tidak ada
                ->value('total_honor'); // Ambil nilai total honor

            $mitraEstimasiHonors[] = [
                'id' => $mitra->id,
                'nama' => $mitra->nama,
                'estimasi_honor_kegiatan_ini' => $mitra->pivot->honor ?? $mitra->pivot->estimasi_honor,
                'estimasi_total_honor' => ($mitra->pivot->honor ?? $mitra->pivot->estimasi_honor) + $estimasiDariLainnya,
            ];
        }
        $kegiatan->pjk = Pegawai::find($kegiatan->id_pjk);
        return view('kegiatan.show', ['kegiatan' => $kegiatan]);
    }


    public function destroy($id)
    {
        $kegiatan = Kegiatan::find($id);
        if ($kegiatan->pegawai->count() > 0) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak bisa dihapus karena masih ada pegawai terlibat.');
        }
        if ($kegiatan->mitra->count() > 0) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak bisa dihapus karena masih ada mitra terlibat.');
        }
        $surats = Surat::where('id_kegiatan', $id)->get();
        if ($surats->count() > 0) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak bisa dihapus karena ada nomor surat yang berkaitan.');
        }
        $nama = $kegiatan->nama;
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $nama . ' berhasil dihapus.');
    }

    public function approveKegiatan($id)
    {
        $kegiatan = Kegiatan::find($id);
        if (Auth::user()->nama != env('NAMA_PPK')) {
            return redirect()->route('kegiatan.' . str_replace('_', '-', $kegiatan->jenis_kak) . '.edit', ['id' => $id])->with('error', 'Hanya PPK yang dapat menyetujui KAK.');
        }
        $kegiatan->is_approved = 1;
        $kegiatan->save();
        return redirect()->route('kegiatan.index', ['id' => $id])->with('success', 'KAK berhasil disetujui.');
    }

    public function rejectKegiatan($id)
    {
        $kegiatan = Kegiatan::find($id);
        if (Auth::user()->nama != env('NAMA_PPK')) {
            return redirect()->route('kegiatan.' . str_replace('_', '-', $kegiatan->jenis_kak) . '.edit', ['id' => $id])->with('error', 'Hanya PPK yang dapat membatalkan persetujuan KAK.');
        }
        $kegiatan->is_approved = 0;
        $kegiatan->save();
        return redirect()->route('kegiatan.index', ['id' => $id])->with('success', 'Persetujuan KAK berhasil dibatalkan.');
    }

    public function editTerlibat($id)
    {
        $kegiatan = Kegiatan::find($id);
        $pegawais = Pegawai::orderBy('nama', 'asc')->get();
        $mitras = Mitra::orderBy('nama', 'asc')->get();
        return view('kegiatan.edit-terlibat', ['kegiatan' => $kegiatan, 'pegawais' => $pegawais, 'mitras' => $mitras]);
    }

    // INI TIDAK DIPAKAI
    public function updateTerlibat(Request $request, $id)
    {
        // dd($request->all());
        $kegiatan = Kegiatan::find($id);
        $kegiatan->pegawai()->sync($request->pegawai);
        $kegiatan->mitra()->sync($request->mitra);
        // foreach ($kegiatan->mitra as $mitra) {
        //     if ($mitra->pivot->tgl_realisasi == null) {
        //         $kegiatan->mitra()->updateExistingPivot($mitra->id, ['tgl_realisasi' => $kegiatan->tgl_selesai]);
        //     }
        // }
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan terlibat berhasil diubah.');
    }

    public function estimasiHonor($id)
    {
        $kegiatan = Kegiatan::find($id);
        return view('kegiatan.estimasi-honor', ['kegiatan' => $kegiatan]);
    }

    // Fungsi untuk update estimasi honor mitra pada kegiatan
    private function updateEstimasiHonorMitra($kegiatan, $mitra_id, $jumlah, $is_pml, $idKegiatanPengecualian = null)
    {
        $estimasi_honor = $is_pml ? $jumlah * $kegiatan->honor_pengawasan : $jumlah * $kegiatan->honor_pencacahan;

        if ($estimasi_honor > 3600100) {
            return ['error' => 'Ada mitra yang melebihi batas honor.'];
        }

        $bulan = Carbon::parse($kegiatan->tgl_selesai)->month;
        $tahun = Carbon::parse($kegiatan->tgl_selesai)->year;

        $honorMitraBulanIni = self::jumlahHonorMitra($mitra_id, $bulan, $tahun, $idKegiatanPengecualian);
        $honorMitraDenganKegiatanIni = self::jumlahHonorMitra($mitra_id, $bulan, $tahun);

        if ($honorMitraBulanIni != null) {
            $honorMitraSetelahPerubahan = $honorMitraBulanIni->total_estimasi_honor + $estimasi_honor;
            if ($honorMitraSetelahPerubahan > 3600100) {
                if ($honorMitraDenganKegiatanIni->total_estimasi_honor > $honorMitraSetelahPerubahan) {
                    // Tetap update pivot meskipun warning
                    $kegiatan->mitra()->updateExistingPivot($mitra_id, [
                        'jumlah' => $jumlah,
                        'estimasi_honor' => $estimasi_honor,
                        'is_pml' => $is_pml,
                    ]);
                    return ['warning' => $honorMitraBulanIni->nama];
                } else {
                    return ['error' => 'Mitra ' . $honorMitraBulanIni->nama . ' akan melebihi batas honor jika mendata sebanyak ' . $jumlah . '.'];
                }
            }
        }

        $kegiatan->mitra()->updateExistingPivot($mitra_id, [
            'jumlah' => $jumlah,
            'estimasi_honor' => $estimasi_honor,
            'is_pml' => $is_pml,
        ]);

        return [];
    }

    public function estimasiHonorPost(Request $request, $id)
    {
        $kegiatan = Kegiatan::find($id);
        if (!$request->has('is_pml')) {
            $request->merge(['is_pml' => []]);
        }
        $mitraYangPerluWarning = [];
        foreach ($request->jumlah as $mitra_id => $jumlah) {
            $is_pml = in_array($mitra_id, array_keys($request->is_pml)) ? 1 : 0;
            $result = $this->updateEstimasiHonorMitra($kegiatan, $mitra_id, $jumlah, $is_pml, $id);
            if (isset($result['error'])) {
                return redirect()->route('kegiatan.estimasi-honor', ['id' => $id])->with('error', $result['error']);
            }
            if (isset($result['warning'])) {
                $mitraYangPerluWarning[] = $result['warning'];
            }
        }
        if (count($mitraYangPerluWarning) > 0) {
            return redirect()->route('kegiatan.show', ['id' => $id])->with('warning', 'Pembaruan estimasi honor berhasil, namun mitra (' . implode(", ", $mitraYangPerluWarning) . ') masih melebihi batas honor. Sebaiknya segera kurangi honor yang diterimanya.');
        }
        return redirect()->route('kegiatan.show', ['id' => $id])->with('success', 'Estimasi honor berhasil diperbarui.');
    }

    public function importMitraDanHonor(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls',
        ]);
        $import = new KegiatanMitraImport();
        Excel::import($import, $request->file('file'));
        $data = $import->getData('Template upload');

        $mitraYangPerluWarning = [];

        foreach ($data as $row) {
            if (isset($row['mitra_id']) && isset($row['jumlah'])) {
                $kegiatan = Kegiatan::find($id);
                $kegiatan->mitra()->syncWithoutDetaching($row['mitra_id']);
                $is_pml = isset($row['is_pml']) ? (int)$row['is_pml'] : 0;
                $result = $this->updateEstimasiHonorMitra($kegiatan, $row['mitra_id'], $row['jumlah'], $is_pml, $id);
                if (isset($result['error'])) {
                    return redirect()->route('kegiatan.estimasi-honor', ['id' => $id])->with('error', $result['error']);
                }
                if (isset($result['warning'])) {
                    $mitraYangPerluWarning[] = $result['warning'];
                }
            } else {
                return redirect()->route('kegiatan.show', ['id' => $id])->with('error', 'Format file yang diunggah tidak sesuai.');
            }
        }
        if (count($mitraYangPerluWarning) > 0) {
            return redirect()->route('kegiatan.show', ['id' => $id])->with('warning', 'Pembaruan estimasi honor berhasil, namun mitra (' . implode(", ", $mitraYangPerluWarning) . ') masih melebihi batas honor. Sebaiknya segera kurangi honor yang diterimanya.');
        }
        return redirect()->route('kegiatan.show', ['id' => $id])->with('success', 'Data mitra dan estimasi honor berhasil diimpor.');
    }

    public function exportMitraDanHonor($id)
    {
        // 1. Cari kegiatan spesifik berdasarkan ID
        $kegiatan = Kegiatan::findOrFail($id);

        // 2. Buat nama file yang dinamis
        $fileName = 'honor-' . $kegiatan->nama . '-' . now()->format('Y-m-d') . '.xlsx';

        // 3. Panggil Excel::download dan lemparkan objek $kegiatan
        //    ke dalam constructor ExportHonorKegiatan
        return Excel::download(new ExportHonorKegiatan($kegiatan), $fileName);
    }

    public function exportTranslok(Request $request, $id)
    {

        $kegiatan = Kegiatan::find($id);
        $tgl_mulai = $request->tgl_mulai ? Carbon::parse($request->tgl_mulai)->format('Y-m-d') : Carbon::parse($kegiatan->tgl_mulai)->format('Y-m-d');
        $tgl_selesai = $request->tgl_selesai ? Carbon::parse($request->tgl_selesai)->format('Y-m-d') : Carbon::parse($kegiatan->tgl_selesai)->format('Y-m-d');
        $tujuan = $request->tujuan ? $request->tujuan : '020';

        $fileName = 'translok-' . $kegiatan->nama . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new ExportTranslok($kegiatan, $tgl_mulai, $tgl_selesai, $tujuan), $fileName);
    }

    public function exportMitraId()
    {
        $fileName = 'all-mitra-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new ExportMitra(), $fileName);
    }

    public function duplicate($id)
    {
        // 1. Cari Data Lama beserta Relasinya (PENTING: pakai 'with')
        $kegiatanLama = Kegiatan::with(['kegiatanRincian', 'kegiatanLampiran'])->findOrFail($id);

        // 2. Replicate Data Induk (Kegiatan)
        $kegiatanBaru = $kegiatanLama->replicate();

        // 3. Modifikasi data unik (Opsional)
        // Agar tidak bingung, kita tambahkan kata "(Copy)" di nama kegiatan baru
        // dan reset tanggal agar tidak dianggap kegiatan lama
        $kegiatanBaru->nama = '(Duplikat) ' . $kegiatanLama->nama;
        $kegiatanBaru->created_at = now();
        $kegiatanBaru->updated_at = now();
        $kegiatanBaru->is_approved = 0; // Set ulang approval
        $kegiatanBaru->save(); // Simpan dulu agar dapat ID baru

        // 4. Duplikat Data Anak: Rincian Akun (kegiatanRincian)
        foreach ($kegiatanLama->kegiatanRincian as $rincianLama) {
            $rincianBaru = $rincianLama->replicate();
            $rincianBaru->kegiatan_id = $kegiatanBaru->id; // Tautkan ke ID Baru
            $rincianBaru->created_at = now();
            $rincianBaru->updated_at = now();
            $rincianBaru->save();
        }

        // 5. Duplikat Data Anak: Lampiran/Personil (kegiatanLampiran)
        foreach ($kegiatanLama->kegiatanLampiran as $lampiranLama) {
            $lampiranBaru = $lampiranLama->replicate();
            $lampiranBaru->kegiatan_id = $kegiatanBaru->id; // Tautkan ke ID Baru
            $lampiranBaru->created_at = now();
            $lampiranBaru->updated_at = now();
            $lampiranBaru->save();
        }

        // 6. Redirect (Bisa ke halaman Edit kegiatan baru, atau ke Index)
        // Saya sarankan redirect ke halaman EDIT agar user bisa langsung ubah tanggalnya
        return redirect()->route('kegiatan.index', $kegiatanBaru->id)
            ->with('success', 'Kegiatan berhasil diduplikat! Silakan sesuaikan tanggalnya.');
    }

    // public function duplicate($id)
    // {
    //     $kegiatan = Kegiatan::find($id);
    //     $kegiatanBaru = $kegiatan->replicate();
    //     $mitraMelebihiHonor = $this->validasiHonorMitra($kegiatan->mitra, $kegiatanBaru->tgl_mulai);
    //     if (count($mitraMelebihiHonor) > 0) {
    //         return redirect()->route('kegiatan.index')->with('error', 'Mitra (' . implode(",", $mitraMelebihiHonor) . ')  melebihi batas honor.')->withInput();
    //     }
    //     $kegiatanBaru->nama = '(Duplikat) ' . $kegiatan->nama;
    //     $kegiatanBaru->save();
    //     $kegiatanBaru->pegawai()->attach($kegiatan->pegawai);
    //     $kegiatanBaru->mitra()->attach($kegiatan->mitra);
    //     return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diduplikasi.');
    // }


    private function konversiTim($kodeTim)
    {
        $tim = "";
        switch ($kodeTim) {
            case '11011':
                $tim = "Umum";
                break;
            case '11012':
                $tim = "Statistik Sosial";
                break;
            case '11013':
                $tim = "Statistik Ekonomi Produksi";
                break;
            case '11014':
                $tim = "Statistik Ekonomi Distribusi";
                break;
            case '11015':
                $tim = "Neraca dan Analisis Statistik";
                break;
            case '11016':
                $tim = "TI dan Pengolahan";
                break;
            case '11017':
                $tim = 'Diseminasi, Publisitas, dan Humas';
                break;
            case '11018':
                $tim = 'Pembinaan Statistik Sektoral';
                break;
            default:
                # code...
                break;
        }
        return $tim;
    }

    public static function validasiHonorMitra($arrayMitra, $tgl_selesai_atau_mulai)
    {
        $mitraMelebihiHonor = [];
        $id_mitra = "";
        if ($arrayMitra == null) {
            return $mitraMelebihiHonor;
        }
        foreach ($arrayMitra as $mitra) {
            if (gettype($mitra) == 'string') {
                $id_mitra = $mitra;
            } else {
                $id_mitra = $mitra->id;
            }
            $honorMitra = self::jumlahHonorMitra($id_mitra, Carbon::parse($tgl_selesai_atau_mulai)->month, Carbon::parse($tgl_selesai_atau_mulai)->year);
            if ($honorMitra == null) {
                continue;
            }
            if (intval($honorMitra->total_estimasi_honor) > 3600100) {
                array_push($mitraMelebihiHonor, $honorMitra->nama);
            }
        }
        return $mitraMelebihiHonor;
    }

    private function hitungTransportBayar($request, $index, $kegiatan)
    {
        if ($request->tipe_peserta[$index] == 'pegawai') {
            return 0;
        }

        if ($request->pcl_or_pml[$index] == 1) {
            return $request->jml_sampel_pcl[$index] * $kegiatan->honor_pengawasan;
        }

        return $request->jml_sampel_pcl[$index] * $kegiatan->honor_pencacahan;
    }

    public static function jumlahHonorMitra($id_mitra, $bulan, $tahun, $idKegiatanPengecualian = null)
    {
        // $request->validate([
        //     'id_mitra' => 'required',
        //     'bulan' => 'required',
        //     'tahun' => 'required',
        // ]);

        $honorMitra = DB::table('mitras')
            ->select('mitras.id as mitra_id', 'mitras.nama as nama', 'mitras.kec_asal as kec_asal', DB::raw("COUNT('kegiatan_mitras.kegiatan_id') as total_kegiatan"), DB::raw("SUM(estimasi_honor) as total_estimasi_honor"), DB::raw("SUM(honor) as total_honor"))
            ->where('mitras.id', $id_mitra)
            ->leftJoin('kegiatan_mitras', 'mitras.id', '=', 'kegiatan_mitras.mitra_id')
            ->leftJoin('kegiatans', 'kegiatan_mitras.kegiatan_id', '=', 'kegiatans.id')
            ->whereRaw('MONTH(kegiatans.tgl_mulai) = ' . $bulan)
            ->whereRaw('YEAR(kegiatans.tgl_mulai) = ' . $tahun)
            ->when($idKegiatanPengecualian, function ($query) use ($idKegiatanPengecualian) {
                if ($idKegiatanPengecualian !== null) {
                    return $query->where('kegiatans.id', '<>', $idKegiatanPengecualian);
                }
            })
            ->groupBy('mitras.id', 'mitras.nama', 'mitras.kec_asal')
            ->orderBy('mitras.nama', 'asc')
            ->first();
        // dd($honorMitra);
        return $honorMitra;
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
