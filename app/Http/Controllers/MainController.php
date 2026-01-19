<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

use App\Models\KegiatanMitra;
use App\Models\KegiatanPegawai;
use App\Models\Pembayaran;
use App\Models\Mitra;
use App\Models\Pegawai;
use App\Models\SBKS;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        // $kegiatanMitra = KegiatanMitra::get();
        // foreach ($kegiatanMitra as $km) {
        //     $kegiatan = Kegiatan::find($km->kegiatan_id);
        //     $km->tgl_realisasi = $kegiatan->tgl_selesai;
        //     $km->save();
        // }

        // UPDATE KEGIATAN YANG BELUM ADA SINGKATAN RESMI
        // $nama_kegiatan = SBKS::select('nama_kegiatan')->groupBy('nama_kegiatan')->get();
        // // dd($sbks);
        // foreach ($nama_kegiatan as $item) {
        //     $semuaKegiatan = SBKS::where('nama_kegiatan', $item->nama_kegiatan)->get();
        //     $singkatanResmi = SBKS::where('nama_kegiatan', $item->nama_kegiatan)->where('singkatan_resmi', '<>', null)->first();
        //     if ($singkatanResmi) {
        //         foreach ($semuaKegiatan as $kegiatan) {
        //             if ($kegiatan->singkatan_resmi == null) {
        //                 $kegiatan->singkatan_resmi = $singkatanResmi->singkatan_resmi;
        //                 $kegiatan->save();
        //             }
        //         }
        //     }
        // }

        // $id_mitra =  227791;
        // //  id fitra = 229707
        // // id rahmad hidayat = 227791
        // $bulan = 1;
        // $tahun = 2025;
        // $idKegiatanPengecualian = 152;
        // $honorMitra = DB::table('mitras')
        //     ->select('mitras.id as mitra_id', 'mitras.nama as nama', 'mitras.kec_asal as kec_asal', DB::raw("COUNT('kegiatan_mitras.kegiatan_id') as total_kegiatan"), DB::raw("SUM(estimasi_honor) as total_estimasi_honor"), DB::raw("SUM(honor) as total_honor"))
        //     ->where('mitras.id', $id_mitra)
        //     ->leftJoin('kegiatan_mitras', 'mitras.id', '=', 'kegiatan_mitras.mitra_id')
        //     ->leftJoin('kegiatans', 'kegiatan_mitras.kegiatan_id', '=', 'kegiatans.id')
        //     ->whereRaw('MONTH(kegiatans.tgl_selesai) = ' . $bulan)
        //     ->whereRaw('YEAR(kegiatans.tgl_selesai) = ' . $tahun)
        //     ->when($idKegiatanPengecualian, function ($query) use ($idKegiatanPengecualian) {
        //         if ($idKegiatanPengecualian !== null) {
        //             return $query->where('kegiatans.id', '<>', $idKegiatanPengecualian);
        //         }
        //     })
        //     ->groupBy('mitras.id', 'mitras.nama', 'mitras.kec_asal')
        //     ->orderBy('mitras.nama', 'asc')
        //     ->first();
        // dd($honorMitra);

        // $query->whereMonth('tgl_selesai', date('m'))
        //         ->whereYear('tgl_selesai', date('Y'))
        //         ->orWhereMonth('tgl_mulai', date('m'))
        //         ->whereYear('tgl_mulai', date('Y'));

        $kegiatanBulanIni = Kegiatan::where(function ($query) {
            $query->whereMonth('tgl_mulai', date('m'))->orWhereMonth('tgl_selesai', date('m'))
                ->whereYear('tgl_mulai', date('Y'));
        })->where('tim', '<>', '11011')->orderBy('progress', 'desc')->get();
        return view('index', compact('kegiatanBulanIni'));
    }

    public function login()
    {
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');
        // dd($credentials);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('index');
        }

        return back()->withErrors([
            'username' => 'Username atau kata sandi salah.',
            'password' => 'Username atau kata sandi salah.',
        ])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }

    public function dashboard()
    {
        $tahun = date('Y');

        $mitraAdaHonor = DB::table('mitras')
            ->select('mitras.id as mitra_id', 'mitras.nama as nama', 'mitras.kec_asal as kec_asal', DB::raw("COUNT('kegiatan_lampirans.kegiatan_id') as total_kegiatan"), DB::raw("SUM(transport_bayar) as total_estimasi_honor"))
            ->leftJoin('kegiatan_lampirans', 'mitras.id', '=', 'kegiatan_lampirans.peserta_id')
            ->leftJoin('kegiatans', 'kegiatan_lampirans.kegiatan_id', '=', 'kegiatans.id')
            ->whereRaw('YEAR(kegiatans.tgl_mulai) = ' . $tahun)
            ->groupBy('mitras.id', 'mitras.nama', 'mitras.kec_asal')
            ->orderBy('mitras.nama', 'asc')
            ->get();

        $mitras = Mitra::where('flag', null)->get();
        $mitraAktif = $mitras->count();
        $mitraAktif--; // mengurangi 1 karena ada mitra bayangan


        foreach ($mitraAdaHonor as $m) {
            $m->kec_asal = $this->konversiKodeKec($m->kec_asal);
            if (str_contains($m->nama, 'bayangan')) {
                $mitraAdaHonor = $mitraAdaHonor->reject(function ($item) use ($m) {
                    return $item->mitra_id == $m->mitra_id;
                });
            }
        }
        foreach ($mitras as $mitra) {
            if (!in_array($mitra->nama, array_column($mitraAdaHonor->toArray(), 'nama')) && !str_contains($mitra->nama, 'bayangan')) {
                $mitra->total_kegiatan = 0;
                $mitra->total_estimasi_honor = 0;
                $mitra->total_honor = 0;
                $mitra->kec_asal = $this->konversiKodeKec($mitra->kec_asal);
                $mitraAdaHonor->push($mitra);
            }
        }
        // dd($mitraAdaHonor[17]);
        return view('dashboard.mitra', compact('mitraAktif', 'mitraAdaHonor'));
    }

    public function dashboardBulanan(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $mitraAdaHonor = DB::table('mitras')
            ->select('mitras.id as mitra_id', 'mitras.nama as nama', 'mitras.kec_asal as kec_asal', DB::raw("COUNT('kegiatan_lampirans.kegiatan_id') as total_kegiatan"), DB::raw("SUM(transport_bayar) as total_estimasi_honor"))
            ->leftJoin('kegiatan_lampirans', 'mitras.id', '=', 'kegiatan_lampirans.peserta_id')
            ->leftJoin('kegiatans', 'kegiatan_lampirans.kegiatan_id', '=', 'kegiatans.id')
            ->whereRaw('MONTH(kegiatans.tgl_mulai) = ' . $bulan)
            ->whereRaw('YEAR(kegiatans.tgl_mulai) = ' . $tahun)
            ->groupBy('mitras.id', 'mitras.nama', 'mitras.kec_asal')
            ->orderBy('mitras.nama', 'asc')
            ->get();
        // dd($mitraAdaHonor);
        foreach ($mitraAdaHonor as $m) {

            $m->kec_asal = $this->konversiKodeKec($m->kec_asal);
            $m->total_honor = $m->total_honor ?? 0;
            $m->total_estimasi_honor = $m->total_estimasi_honor ?? 0;
            $m->total_kegiatan = $m->total_kegiatan ?? 0;
            if (str_contains($m->nama, 'bayangan')) {
                $mitraAdaHonor = $mitraAdaHonor->reject(function ($item) use ($m) {
                    return $item->mitra_id == $m->mitra_id;
                });
            }
        }

        // ini menambahkan mitra sisanya
        $mitras = Mitra::where('flag', null)->get();
        foreach ($mitras as $mitra) {
            if (!in_array($mitra->nama, array_column($mitraAdaHonor->toArray(), 'nama')) && !str_contains($mitra->nama, 'bayangan')) {
                $mitra->total_kegiatan = 0;
                $mitra->total_estimasi_honor = 0;
                $mitra->total_honor = 0;
                $mitra->kec_asal = $this->konversiKodeKec($mitra->kec_asal);
                $mitraAdaHonor->push($mitra);
            }
        }

        // ini jika returnya object, bukan array
        if (is_object($mitras)) {
            $dummy = $mitraAdaHonor;
            $mitraAdaHonor = [];
            foreach ($dummy as $d) {
                array_push($mitraAdaHonor, $d);
            }
        }

        return response()->json($mitraAdaHonor);
    }



    public function mitraKegiatanBelumDibayar($id_kegiatan)
    {
        $mitra = KegiatanMitra::where('kegiatan_id', $id_kegiatan)->where('honor', null)->get();
        foreach ($mitra as $m) {
            $m->mitra = Mitra::find($m->mitra_id);
        }
        return response()->json($mitra);
    }

    public function pegawaiKegiatanBelumDibayar($id_kegiatan)
    {
        $pegawai = KegiatanPegawai::where('kegiatan_id', $id_kegiatan)->where('translok',  null)->get();
        foreach ($pegawai as $m) {
            $m->pegawai = Pegawai::find($m->pegawai_id);
        }
        return response()->json($pegawai);
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
}
