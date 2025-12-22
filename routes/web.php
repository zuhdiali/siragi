<?php

use App\Http\Controllers\KegiatanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SBKSController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\POKController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [MainController::class, 'login'])->name('login');
Route::post('/login', [MainController::class, 'loginPost'])->name('login.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [MainController::class, 'logout'])->name('logout');

    Route::get('/', [MainController::class, 'index'])->name('index');
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');
    Route::post('/dashboard-bulanan', [MainController::class, 'dashboardBulanan'])->name('dashboard-bulanan');
    Route::get('/dashboard/estimasi-honor/{id}', [MitraController::class, 'estimasiHonor'])->name('dashboard-estimasi-honor-mitra');
    Route::post('/dashboard/estimasi-honor-bulanan', [MitraController::class, 'estimasiHonorBulanan'])->name('dashboard-estimasi-honor-bulanan');

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [PegawaiController::class, 'user'])->name('index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('create');
        Route::post('/store', [PegawaiController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PegawaiController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [PegawaiController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [PegawaiController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('create');
        Route::post('/store', [PegawaiController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PegawaiController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [PegawaiController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [PegawaiController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('mitra')->name('mitra.')->group(function () {
        Route::get('/', [MitraController::class, 'index'])->name('index');
        Route::get('/create', [MitraController::class, 'create'])->name('create');
        Route::post('/store', [MitraController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MitraController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [MitraController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [MitraController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/honor', [PembayaranController::class, 'honor'])->name('honor');
        Route::get('/create/{objek}', [PembayaranController::class, 'create'])->name('create');
        Route::post('/store/{objek}', [PembayaranController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PembayaranController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [PembayaranController::class, 'update'])->name('update');
        Route::get('/destroy/{objek}/{id}', [PembayaranController::class, 'destroy'])->name('destroy');
        Route::get('/lihat-bukti/{id}', [PembayaranController::class, 'lihatBukti'])->name('lihat-bukti');

        Route::prefix('translok')->name('translok.')->group(function () {
            Route::get('/', [PembayaranController::class, 'translok'])->name('index');
            Route::get('/create', [PembayaranController::class, 'createTranslok'])->name('create');
            Route::post('/store', [PembayaranController::class, 'storeTranslok'])->name('store');
            Route::get('/edit/{id}', [PembayaranController::class, 'editTranslok'])->name('edit');
            Route::post('/update/{id}', [PembayaranController::class, 'updateTranslok'])->name('update');
            Route::get('/destroy/{id}', [PembayaranController::class, 'destroyTranslok'])->name('destroy');
        });
    });

    Route::prefix('kegiatan')->name('kegiatan.')->group(function () {
        Route::get('/', [KegiatanController::class, 'index'])->name('index');
        Route::get('/create', [KegiatanController::class, 'create'])->name('create');
        Route::post('/store', [KegiatanController::class, 'store'])->name('store');
        Route::get('/show/{id}', [KegiatanController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [KegiatanController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [KegiatanController::class, 'update'])->name('update');
        Route::get('/edit-terlibat/{id}', [KegiatanController::class, 'editTerlibat'])->name('edit.terlibat');
        Route::post('/update-terlibat/{id}', [KegiatanController::class, 'updateTerlibat'])->name('update.terlibat');
        Route::get('/destroy/{id}', [KegiatanController::class, 'destroy'])->name('destroy');

        Route::get('/estimasi-honor/{id}', [KegiatanController::class, 'estimasiHonor'])->name('estimasi-honor');
        Route::post('/estimasi-honor/{id}', [KegiatanController::class, 'estimasiHonorPost']);
        Route::get('/duplicate/{id}', [KegiatanController::class, 'duplicate'])->name('duplicate');

        Route::get('/mitra-belum-dibayar/{id}', [MainController::class, 'mitraKegiatanBelumDibayar']);
        Route::get('/pegawai-belum-dibayar/{id}', [MainController::class, 'pegawaiKegiatanBelumDibayar']);

        Route::post('/get-kegiatan-api', [SuratController::class, 'getKegiatanApi'])->name('get-kegiatan-api');

        Route::post('/approve/{id}', [KegiatanController::class, 'approveKegiatan'])->name('approve');
        Route::post('/reject/{id}', [KegiatanController::class, 'rejectKegiatan'])->name('reject');

        Route::post('/import-mitra-dan-honor/{id}', [KegiatanController::class, 'importMitraDanHonor'])->name('import-mitra-dan-honor');
        // Route::post('/honor-mitra', [MainController::class, 'jumlahHonorMitra'])->name('jumlah-honor-mitra');
        Route::get('/export-mitra-dan-honor/{id}', [KegiatanController::class, 'exportMitraDanHonor'])->name('export-mitra-dan-honor');
        Route::post('/export-translok/{id}', [KegiatanController::class, 'exportTranslok'])->name('export-translok');
        Route::get('/export-mitra-id', [KegiatanController::class, 'exportMitraId'])->name('export-mitra-id');

        Route::prefix('translok-biasa')->name('translok-biasa.')->group(function () {
            Route::get('/create', [KegiatanController::class, 'translokBiasaCreate'])->name('create');
            Route::get('/edit/{id}', [KegiatanController::class, 'translokBiasaEdit'])->name('edit');
            Route::get('/show/{id}', [KegiatanController::class, 'translokBiasaShow'])->name('show');
        });

        Route::prefix('translok-8jam')->name('translok-8jam.')->group(function () {
            Route::get('/create', [KegiatanController::class, 'translok8JamCreate'])->name('create');
            Route::get('/edit/{id}', [KegiatanController::class, 'translok8JamEdit'])->name('edit');
        });

        Route::prefix('pemanggilan-konsultasi')->name('pemanggilan-konsultasi.')->group(function () {
            Route::get('/create', [KegiatanController::class, 'pemanggilanKonsultasiCreate'])->name('create');
            Route::get('/edit/{id}', [KegiatanController::class, 'pemanggilanKonsultasiEdit'])->name('edit');
        });

        Route::prefix('honor-mitra')->name('honor-mitra.')->group(function () {
            Route::get('/create', [KegiatanController::class, 'honorMitraCreate'])->name('create');
            Route::get('/edit/{id}', [KegiatanController::class, 'honorMitraEdit'])->name('edit');
        });

        Route::prefix('honor-inda')->name('honor-inda.')->group(function () {
            Route::get('/create', [KegiatanController::class, 'honorIndaCreate'])->name('create');
            Route::get('/edit/{id}', [KegiatanController::class, 'honorIndaEdit'])->name('edit');
        });
    });

    Route::prefix('pok')->name('pok.')->group(function () {
        Route::post('/filter', [POKController::class, 'filterPOK'])->name('filter');
    });

    Route::prefix('surat')->name('surat.')->group(function () {
        Route::get('/tugas', [SuratController::class, 'tugas'])->name('tugas');
        Route::get('/spd', [SuratController::class, 'spd'])->name('spd');
        Route::get('/permintaan', [SuratController::class, 'permintaan'])->name('permintaan');
        Route::get('/masuk', [SuratController::class, 'masuk'])->name('masuk');
        Route::get('/detail/masuk/{id}', [SuratController::class, 'rincianSuratMasuk']);
        Route::get('/keluar', [SuratController::class, 'keluar'])->name('keluar');
        Route::get('/sk', [SuratController::class, 'sk'])->name('sk');
        Route::get('/spk', [SuratController::class, 'spk'])->name('spk');
        Route::get('/create/{jenis}', [SuratController::class, 'create'])->name('create');
        Route::post('/store/{jenis}', [SuratController::class, 'store'])->name('store');
        Route::post('/get-kode-surat/{tim}', [SuratController::class, 'getKodeSurat'])->name('get-kode-surat');
        Route::get('/show/{id}', [SuratController::class, 'show'])->name('show');
        Route::get('/edit/{jenis}/{id}', [SuratController::class, 'edit'])->name('edit');
        Route::post('/update/{jenis}/{id}', [SuratController::class, 'update'])->name('update');
        Route::get('/edit-terlibat/{id}', [SuratController::class, 'editTerlibat'])->name('edit.terlibat');
        Route::post('/update-terlibat/{id}', [SuratController::class, 'updateTerlibat'])->name('update.terlibat');
        Route::get('/destroy/{id}', [SuratController::class, 'destroy'])->name('destroy');

        Route::get('/generate-spk', [SuratController::class, 'generateSPK'])->name('generate-spk');
        Route::get('/download-spk/{id}', [SuratController::class, 'downloadSPK'])->name('download-spk');
    });

    Route::prefix('sbks')->name('sbks.')->group(function () {
        Route::get('/', [SBKSController::class, 'index'])->name('index');
        Route::get('/create', [SBKSController::class, 'create'])->name('create');
        Route::post('/store', [SBKSController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SBKSController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SBKSController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [SBKSController::class, 'destroy'])->name('destroy');

        Route::post('/get-honor', [SBKSController::class, 'getHonor'])->name('get-honor');
    });
});
