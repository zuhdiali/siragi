@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('select2/css/select2-bootstrap-5-theme.min.css') }}" />
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('kegiatan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jenis_kak" value="translok_biasa" />
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">SBKS (Standard Biaya Kegiatan Statistik) </div>
                            </div>
                            <div class="card-body">
                                <div class="row ">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SBKS wajib diisi</label>
                                            <select name="filter_sbks" id="filter_sbks" class="form-control">
                                                <option value=""></option>
                                                @foreach ($sbks as $item)
                                                    <option value="{{ $item->singkatan_resmi }}"
                                                        {{ old('filter_sbks') == $item->nama_kegiatan ? 'selected' : '' }}>
                                                        {{ $item->nama_kegiatan_dan_singkatan }}</option>
                                                @endforeach
                                                <option value="LAINNYA"
                                                    {{ old('filter_sbks') == 'LAINNYA' ? 'selected' : '' }}>
                                                    Lainnya</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Kegiatan</label><br />
                                            <div class="d-flex">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="jenis_kegiatan"
                                                        id="updating" value="updating"
                                                        {{ old('jenis_kegiatan') == 'updating' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="updating">
                                                        Updating
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="jenis_kegiatan"
                                                        id="pendataan" value="pendataan"
                                                        {{ old('jenis_kegiatan') == 'pendataan' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="pendataan">
                                                        Pendataan
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="jenis_kegiatan"
                                                        id="pengolahan" value="pengolahan"
                                                        {{ old('jenis_kegiatan') == 'pengolahan' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="pengolahan">
                                                        Pengolahan
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Tambah KAK Translok Biasa</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p>NAMA SINGKATAN RESMI DARI SURVEI YANG DIPILIH</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" name="singkatan_resmi" id="singkatan_resmi"
                                                        class="form-control" />
                                                    @if ($errors->has('singkatan_resmi'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('singkatan_resmi') }}</small>
                                                    @else
                                                        <small class="form-text text-muted">
                                                            contoh pengisian: SUSENAS Maret, SUPAS, KSA Padi Triwulan I,
                                                            VHTS Januari
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p>1. LATAR BELAKANG</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <textarea name="kak1_latar_belakang" id="kak1_latar_belakang" rows="10" class="form-control"
                                                        placeholder="Masukkan latar belakang di sini"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <p>2. MAKSUD DAN TUJUAN</p>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="kak2_maksud">Maksud dari pengadaan ini adalah
                                                        untuk pembayaran transport lokal </label>

                                                    <select name="kak2_maksud" id="kak2_maksud" class="form-control">
                                                        <option value="">( Pilih salah satu )</option>
                                                        <option value="pengawasan">Pengawasan</option>
                                                        <option value="supervisi">Supervisi</option>
                                                        <option value="pendataan">Pendataan</option>
                                                    </select>
                                                    @if ($errors->has('kak2_maksud'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('kak2_maksud') }}</small>
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    <label for="kak2_tujuan">Tujuan pengadaan ini adalah untuk pembayaran
                                                        transport lokal
                                                    </label>
                                                    <select name="kak2_tujuan" id="kak2_tujuan" class="form-control">
                                                        <option value="">( Pilih salah satu )</option>
                                                        <option value="mitra">Mitra</option>
                                                        <option value="organik">Organik</option>
                                                    </select>
                                                    @if ($errors->has('kak2_tujuan'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('kak2_tujuan') }}</small>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <p>3. TARGET/SASARAN</p>
                                            <div class="row" id="kak3_target_wrap">
                                                <div class="col">
                                                    <label for="kak3_target" id="label_kak3_target">Mitra yang diawasi
                                                        ada sejumlah
                                                    </label>
                                                    <input type="number" name="kak3_target" id="kak3_target"
                                                        class="form-control" />
                                                    @if ($errors->has('kak3_target'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('kak3_target') }}</small>
                                                    @else
                                                        <small class="form-text text-muted">
                                                            petunjuk: isi dengan angka saja
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <p>4. PELAKSANA PENGADAAN BARANG/JASA</p>
                                            <div class="row">
                                                <div class="col">

                                                    <div class="row">
                                                        <div class="col">
                                                            <label for="tgl_mulai">Kegiatan dimulai dari tanggal</label>
                                                            <input type="date" class="form-control" id="tgl_mulai"
                                                                name="tgl_mulai" value="{{ old('tgl_mulai') }}" />
                                                            @if ($errors->has('tgl_mulai'))
                                                                <small
                                                                    class="form-text text-muted">{{ $errors->first('tgl_mulai') }}</small>
                                                            @else
                                                                <small class="form-text text-muted">
                                                                    Format: bulan/tanggal/tahun
                                                                </small>
                                                            @endif
                                                        </div>
                                                        <div class="col">
                                                            <label for="tgl_selesai">sampai tanggal</label>
                                                            <input type="date" class="form-control" id="tgl_selesai"
                                                                name="tgl_selesai" value="{{ old('tgl_selesai') }}" />
                                                            @if ($errors->has('tgl_selesai'))
                                                                <small
                                                                    class="form-text text-muted">{{ $errors->first('tgl_selesai') }}</small>
                                                            @else
                                                                <small class="form-text text-muted">
                                                                    Format: bulan/tanggal/tahun
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <label for="kak4_pjk">dengan penanggung jawab kegiatan </label>
                                                    <select class="form-select" id="kak4_pjk" name="kak4_pjk">
                                                        <option value="">(Pilih salah satu)</option>
                                                        <option value="11011"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11011' ? 'selected' : '') : (Auth::user()->tim == '11011' ? 'selected' : '') }}>
                                                            Umum</option>
                                                        <option value="11012"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11012' ? 'selected' : '') : (Auth::user()->tim == '11012' ? 'selected' : '') }}>
                                                            Statistik Sosial</option>
                                                        <option value="11013"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11013' ? 'selected' : '') : (Auth::user()->tim == '11013' ? 'selected' : '') }}>
                                                            Statistik Ekonomi Produksi</option>
                                                        <option value="11014"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11014' ? 'selected' : '') : (Auth::user()->tim == '11014' ? 'selected' : '') }}>
                                                            Statistik Ekonomi Distribusi</option>
                                                        <option value="11015"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11015' ? 'selected' : '') : (Auth::user()->tim == '11015' ? 'selected' : '') }}>
                                                            Neraca dan Analisis Statistik</option>
                                                        <option value="11016"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11016' ? 'selected' : '') : (Auth::user()->tim == '11016' ? 'selected' : '') }}>
                                                            TI dan Pengolahan</option>
                                                        <option value="11017"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11017' ? 'selected' : '') : (Auth::user()->tim == '11017' ? 'selected' : '') }}>
                                                            Diseminasi, Publisitas, dan Humas</option>
                                                        <option value="11018"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11018' ? 'selected' : '') : (Auth::user()->tim == '11018' ? 'selected' : '') }}>
                                                            Pembinaan Statistik Sektoral</option>
                                                    </select>
                                                    @if ($errors->has('kak4_pjk'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('kak4_pjk') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <p>5. SPESIFIKASI PENGADAAN BARANG/JASA</p>
                                            <p>Transport lokal sesuai SK Kepala BPS Kabupaten Simeulue nomor XX Tahun 2025
                                                tanggal DD-MM-YYYY tentang Penetapan Rate Biaya Transport dari Kabupaten
                                                Simeulue Ke Kecamatan Tahun 2026</p>
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <p>6. SUMBER DANA DAN PERKIRAAN BIAYA</p>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label for="kak6_program">Program </label>
                                                        <select name="kak6_program" id="kak6_program"
                                                            class="form-control">
                                                            <option value="">(Pilih Program)</option>
                                                            @foreach ($pok_awals as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->kode_program }} -
                                                                    {{ $item->uraian }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label for="kak6_aktivitas">Aktivitas </label>
                                                        <select name="kak6_aktivitas" id="kak6_aktivitas"
                                                            class="form-control">
                                                            <option value="">(Pilih Aktivitas)</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label for="kak6_kro">Klasifikasi Rincian Output </label>
                                                        <select name="kak6_kro" id="kak6_kro" class="form-control">
                                                            <option value="">(Pilih Klasifikasi Rincian Output)
                                                            </option>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label for="kak6_ro"> Rincian Output </label>
                                                        <select name="kak6_ro" id="kak6_ro" class="form-control">
                                                            <option value="">(Pilih Rincian Output)
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label for="kak6_komponen">Klasifikasi Komponen </label>
                                                        <select name="kak6_komponen" id="kak6_komponen"
                                                            class="form-control">
                                                            <option value="">(Pilih Klasifikasi Komponen)
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label for="kak6_sub_komponen">Klasifikasi Sub Komponen </label>
                                                        <select name="kak6_sub_komponen" id="kak6_sub_komponen"
                                                            class="form-control">
                                                            <option value="">(Pilih Klasifikasi Sub Komponen)
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Letakkan ini di bawah div mb-2 kak6_sub_komponen --}}
                                            <div class="row mt-4">
                                                <div class="col-12">
                                                    <p>Rincian Akun Belanja</p>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped"
                                                            id="tabel_rincian_akun">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th width="200px">Akun</th>
                                                                    <th width="250px">Rincian / Detail</th>
                                                                    <th width="80px">Volume</th>
                                                                    <th width="200px">Satuan</th>
                                                                    <th width="200px">Harga Satuan</th>
                                                                    <th width="200px">Total</th>
                                                                    <th width="50px">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="body_rincian_akun">
                                                                {{-- Baris akan muncul di sini via Jquery --}}
                                                            </tbody>
                                                            <tfoot>
                                                                {{-- Baris Kontrol untuk Memilih Akun --}}
                                                                <tr class="table-light">
                                                                    <td colspan="2">
                                                                        <select id="pilih_akun" class="form-control">
                                                                            <option value="">-- Pilih Sub Komponen
                                                                                Dahulu --</option>
                                                                        </select>
                                                                    </td>
                                                                    <td colspan="5">
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-sm"
                                                                            id="btn_tambah_akun">
                                                                            <i class="fa fa-plus"></i> Tambah Baris Akun
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                {{-- Baris Grand Total --}}
                                                                <tr>
                                                                    <td colspan="5" class="text-end fw-bold">Grand
                                                                        Total</td>
                                                                    <td colspan="2">
                                                                        <input type="text" id="grand_total"
                                                                            class="form-control fw-bold" readonly
                                                                            value="0">
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="row mt-2">
                                                <div class="col">
                                                    <label for="kak6_akun">Akun</label>
                                                    <select id="kak6_akun" name="kak6_akun[]" multiple="multiple">

                                                    </select>
                                                </div>
                                            </div> --}}

                                            <div class="row">
                                                <div class="col">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="row">
                                    <div class="col-12">
                                        <p>Rincian Biaya Transport & Obyek Pengawasan</p>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover align-middle"
                                                id="tabel_transport" style="min-width: 1500px;">
                                                <thead class="table-dark text-center">
                                                    <tr>
                                                        <th style="width: 250px;">Nama Pelaksana</th>
                                                        <th style="width: 150px;">NIP / NIK</th>
                                                        <th style="width: 150px;">Kecamatan Tujuan</th>
                                                        <th style="width: 120px;">Tanggal Pelaksanaan</th>
                                                        <th style="width: 200px;">Nama PCL yang Diawasi</th>
                                                        <th style="width: 80px;">Jml Sampel PCL</th>
                                                        <th style="width: 80px;">Jml Sampel Diawasi</th>
                                                        <th style="width: 100px;">Jml OK</th>
                                                        <th style="width: 150px;">Perkiraan Transport</th>
                                                        <th style="width: 50px;">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="body_transport">
                                                    {{-- Baris akan ditambahkan via JavaScript --}}
                                                </tbody>
                                                <tfoot class="table-light fw-bold">
                                                    <tr>
                                                        <td colspan="8" class="text-end">TOTAL ESTIMASI BIAYA
                                                            TRANSPORT:</td>
                                                        <td>
                                                            <input type="text" id="grand_total_transport"
                                                                class="form-control fw-bold text-end" readonly
                                                                value="0">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                            <div class="mt-2">
                                                <button type="button" class="btn btn-info btn-sm"
                                                    id="btn_tambah_pegawai">
                                                    <i class="fa fa-user-plus"></i> Tambah Pegawai
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    id="btn_tambah_mitra">
                                                    <i class="fa fa-users"></i> Tambah Mitra
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Template Opsi (Hidden) dengan Data NIP --}}
                                <div class="d-none">
                                    {{-- Simpan NIP/ID di atribut data-nip agar bisa ditarik otomatis --}}
                                    <select id="template_opsi_pegawai">
                                        <option value="">-- Pilih Pegawai --</option>
                                        @foreach ($pegawais as $p)
                                            <option value="{{ $p->id }}" data-nip="{{ $p->nip ?? '-' }}">
                                                {{ $p->nama }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select id="template_opsi_mitra">
                                        <option value="">-- Pilih Mitra --</option>
                                        @foreach ($mitras as $m)
                                            <option value="{{ $m->id }}" data-nip="{{ $m->nik ?? '-' }}">
                                                {{ $m->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <hr />

                                <div class="row">
                                    <div class="col-md-4">
                                        <div
                                            class="form-group  {{ $errors->has('kak6_pembiayaan') ? 'has-error has-feedback' : '' }}">
                                            <label for="kak6_pembiayaan">Pembiayaan digunakan untuk transport ...</label>
                                            <input type="text" class="form-control" name="kak6_pembiayaan"
                                                id="kak6_pembiayaan" value="{{ old('kak6_pembiayaan') }}" />
                                            @if ($errors->has('kak6_pembiayaan'))
                                                <small
                                                    class="form-text text-muted">{{ $errors->first('kak6_pembiayaan') }}</small>
                                            @else
                                                <small class="form-text text-muted">
                                                    contoh pengisian: pengawasan, pendataan, responden roleplaying, peserta
                                                    pembinaan
                                                </small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div
                                            class="form-group  {{ $errors->has('kak8_pengaju') ? 'has-error has-feedback' : '' }}">
                                            <label for="kak8_pengaju">Yang mengajukan </strong></label>
                                            <input type="text" class="form-control" name="kak8_pengaju"
                                                id="kak8_pengaju" value="{{ old('kak8_pengaju') }}" />
                                            @if ($errors->has('kak8_pengaju'))
                                                <small
                                                    class="form-text text-muted">{{ $errors->first('kak8_pengaju') }}</small>
                                            @else
                                                <small class="form-text text-muted">
                                                    contoh pengisian: PJK Supas, PJK KSA Padi, PJK VHTS
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div
                                            class="form-group  {{ $errors->has('kak8_tgl') ? 'has-error has-feedback' : '' }}">
                                            <label for="kak8_tgl">Tanggal Pengajuan KAK</strong></label>
                                            <input type="date" class="form-control" name="kak8_tgl" id="kak8_tgl"
                                                value="{{ old('kak8_tgl') ?? now()->format('Y-m-d') }}" />
                                            @if ($errors->has('kak8_tgl'))
                                                <small
                                                    class="form-text text-muted">{{ $errors->first('kak8_tgl') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="row">
                                    <div class="col">
                                        <div
                                            class="form-group  {{ $errors->has('id_pjk') ? 'has-error has-feedback' : '' }}">
                                            <label for="id_pjk">Nama Pegawai Penanggung Jawab Kegiatan</strong></label>
                                            <select class="form-select" id="id_pjk" name="id_pjk">
                                                <option value="">(Pilih salah satu)</option>
                                                @foreach ($pegawais as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('id_pjk') ? (old('id_pjk') == $item->id ? 'selected' : '') : (Auth::user()->id == $item->id ? 'selected' : '') }}>
                                                        {{ $item->nama }}</option>
                                                @endforeach

                                            </select>
                                            @if ($errors->has('id_pjk'))
                                                <small class="form-text text-muted">{{ $errors->first('id_pjk') }}</small>
                                            @else
                                                <small class="form-text text-muted">
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <p>JUDUL KERANGKA ACUAN KERJA (KAK)</p>
                                            <div class="row">
                                                <div class="col">
                                                    {{-- <label for="judul_kak" id="judul_kak">Mitra yang diawasi
                                                        ada sejumlah
                                                    </label> --}}
                                                    <input type="text" name="judul_kak" id="judul_kak"
                                                        class="form-control" />
                                                    @if ($errors->has('judul_kak'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('judul_kak') }}</small>
                                                    @else
                                                        <small class="form-text text-muted">
                                                            petunjuk pengisian: TRANSPORT LOKAL DIBAWAH 8 JAM
                                                            [ORGANIK/MITRA] [SUPERVISI/PENGAWASAN/PENDATAAN]
                                                            [UPDATING/PENDATAAN] [nama_kegiatan] TAHUN 2026 BPS KABUPATEN
                                                            SIMEULUE TAHUN ANGGARAN 2026
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr />

                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Tambah KAK</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#pegawai').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                closeOnSelect: false,
            });

            $('#mitra').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                closeOnSelect: false,
            });

            $('#filter_sbks,#kak6_program, #kak6_aktivitas, #kak6_kro, #kak6_ro, #kak6_komponen, #kak6_sub_komponen, #kak6_akun, #kak2_maksud, #kak2_tujuan')
                .select2({
                    theme: "bootstrap-5",
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                        'style',
                    placeholder: $(this).data('placeholder'),
                    closeOnSelect: true,
                });

            $('#kak2_maksud').on('change',
                function() {
                    var kode_maksud = $('#kak2_maksud').val();
                    if (kode_maksud == 'pengawasan' || kode_maksud == 'supervisi') {
                        $('#label_kak3_target').html('Mitra yang diawasi ada sejumlah');
                    } else {
                        $('#label_kak3_target').html('Sampel yang didata ada sejumlah');
                    }
                    $('#kak6_pembiayaan').val(kode_maksud);
                });

            $('#kak6_program').on('change',
                function() {
                    var kode_program = $('#kak6_program').val();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('pok.filter') }}',
                        type: 'POST',
                        data: {
                            kak6_program: kode_program,
                        },
                        success: function(data) {

                            $('#kak6_aktivitas').empty();
                            $('#kak6_aktivitas').innerHTML =
                                '<option value="">(Pilih Aktivitas)</option>';
                            data.map(function(item) {
                                $('#kak6_aktivitas').append(
                                    `<option value="${item.id}">${item.kode_aktivitas} - ${item.uraian}</option>`
                                );
                            });
                            if ($('#kak6_aktivitas').val()) {
                                $('#kak6_aktivitas').trigger('change');
                            }
                            if ($('#kak6_kro').val()) {
                                $('#kak6_kro').empty();
                            }
                            if ($('#kak6_ro').val()) {
                                $('#kak6_ro').empty();
                            }
                            if ($('#kak6_komponen').val()) {
                                $('#kak6_komponen').empty();
                            }
                            if ($('#kak6_sub_komponen').val()) {
                                $('#kak6_sub_komponen').empty();
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                });

            $('#kak6_aktivitas').on('change',
                function() {
                    var kode_program = $('#kak6_program').val();
                    var kode_aktivitas = $('#kak6_aktivitas').val();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('pok.filter') }}',
                        type: 'POST',
                        data: {
                            kak6_program: kode_program,
                            kak6_aktivitas: kode_aktivitas
                        },
                        success: function(data) {
                            $('#kak6_kro').empty();
                            $('#kak6_kro').innerHTML =
                                '<option value="">(Pilih Klasifikasi Rincian Output)</option>';
                            data.map(function(item) {
                                $('#kak6_kro').append(
                                    `<option value="${item.id}">${item.kode_klasifikasi_rincian_output} - ${item.uraian}</option>`
                                );
                            });
                            if ($('#kak6_kro').val()) {
                                $('#kak6_kro').trigger('change');
                            }
                            if ($('#kak6_ro').val()) {
                                $('#kak6_ro').empty();
                            }
                            if ($('#kak6_komponen').val()) {
                                $('#kak6_komponen').empty();
                            }
                            if ($('#kak6_sub_komponen').val()) {
                                $('#kak6_sub_komponen').empty();
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                });

            $('#kak6_kro').on('change',
                function() {
                    var kode_program = $('#kak6_program').val();
                    var kode_aktivitas = $('#kak6_aktivitas').val();
                    var kode_kro = $('#kak6_kro').val();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('pok.filter') }}',
                        type: 'POST',
                        data: {
                            kak6_program: kode_program,
                            kak6_aktivitas: kode_aktivitas,
                            kak6_kro: kode_kro
                        },
                        success: function(data) {
                            $('#kak6_ro').empty();
                            $('#kak6_ro').innerHTML =
                                '<option value="">(Pilih Rincian Output)</option>';
                            data.map(function(item) {
                                $('#kak6_ro').append(
                                    `<option value="${item.id}">${item.kode_rincian_output} - ${item.uraian}</option>`
                                );
                            });
                            if ($('#kak6_ro').val()) {
                                $('#kak6_ro').trigger('change');
                            }
                            if ($('#kak6_komponen').val()) {
                                $('#kak6_komponen').empty();
                            }
                            if ($('#kak6_sub_komponen').val()) {
                                $('#kak6_sub_komponen').empty();
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                });

            $('#kak6_ro').on('change',
                function() {
                    var kode_program = $('#kak6_program').val();
                    var kode_aktivitas = $('#kak6_aktivitas').val();
                    var kode_kro = $('#kak6_kro').val();
                    var kode_ro = $('#kak6_ro').val();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('pok.filter') }}',
                        type: 'POST',
                        data: {
                            kak6_program: kode_program,
                            kak6_aktivitas: kode_aktivitas,
                            kak6_kro: kode_kro,
                            kak6_ro: kode_ro
                        },
                        success: function(data) {
                            $('#kak6_komponen').empty();
                            $('#kak6_komponen').innerHTML =
                                '<option value="">(Pilih Komponen)</option>';
                            data.map(function(item) {
                                $('#kak6_komponen').append(
                                    `<option value="${item.id}">${item.kode_komponen} - ${item.uraian}</option>`
                                );
                            });
                            if ($('#kak6_komponen').val()) {
                                $('#kak6_komponen').trigger('change');
                            }
                            if ($('#kak6_sub_komponen').val()) {
                                $('#kak6_sub_komponen').empty();
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                });

            $('#kak6_komponen').on('change',
                function() {
                    var kode_program = $('#kak6_program').val();
                    var kode_aktivitas = $('#kak6_aktivitas').val();
                    var kode_kro = $('#kak6_kro').val();
                    var kode_ro = $('#kak6_ro').val();
                    var kode_komponen = $('#kak6_komponen').val();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('pok.filter') }}',
                        type: 'POST',
                        data: {
                            kak6_program: kode_program,
                            kak6_aktivitas: kode_aktivitas,
                            kak6_kro: kode_kro,
                            kak6_ro: kode_ro,
                            kak6_komponen: kode_komponen
                        },
                        success: function(data) {
                            $('#kak6_sub_komponen').empty();
                            $('#kak6_sub_komponen').innerHTML =
                                '<option value="">(Pilih Sub Komponen)</option>';
                            data.map(function(item) {
                                $('#kak6_sub_komponen').append(
                                    `<option value="${item.id}">${item.kode_sub_komponen} - ${item.uraian}</option>`
                                );
                            });
                            if ($('#kak6_sub_komponen').val()) {
                                $('#kak6_sub_komponen').trigger('change');
                            }
                            if ($('#kak6_akun').val()) {
                                $('#kak6_akun').empty();
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                });

            // --- 1. Inisialisasi Select2 untuk Pilih Akun ---
            $('#pilih_akun').select2({
                theme: "bootstrap-5",
                placeholder: "Pilih Akun Belanja",
                width: '100%'
            });

            // --- 2. AJAX: Ambil Daftar Akun saat Sub Komponen berubah ---
            $('#kak6_sub_komponen').on('change', function() {
                // Kumpulkan semua ID parent
                var semua_kode = {
                    kak6_program: $('#kak6_program').val(),
                    kak6_aktivitas: $('#kak6_aktivitas').val(),
                    kak6_kro: $('#kak6_kro').val(),
                    kak6_ro: $('#kak6_ro').val(),
                    kak6_komponen: $('#kak6_komponen').val(),
                    kak6_sub_komponen: $(this).val()
                };

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // Pastikan controller Anda menangani request ini untuk mengembalikan daftar AKUN
                    url: '{{ route('pok.filter') }}',
                    type: 'POST',
                    data: semua_kode,
                    success: function(data) {
                        $('#pilih_akun').empty().append(
                            '<option value="">(Pilih Akun)</option>');

                        // Asumsi data yang dikembalikan controller memiliki field: kode_akun dan uraian
                        data.map(function(item) {
                            // Simpan nama akun di atribut data-nama agar mudah diambil nanti
                            $('#pilih_akun').append(
                                `<option value="${item.id}" data-nama="${item.uraian}">
                        ${item.kode_akun} - ${item.uraian}
                    </option>`
                            );
                        });
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });

            // --- 3. LOGIC: Tambah Baris ke Tabel ---
            $('#btn_tambah_akun').click(function() {
                var akunId = $('#pilih_akun').val();
                // Ambil text dari option yang dipilih
                var akunText = $('#pilih_akun option:selected').text();

                if (!akunId) {
                    alert("Silakan pilih akun terlebih dahulu!");
                    return;
                }

                // Template Baris Baru
                // Perhatikan name="...[]" agar bisa dikirim sebagai array ke controller
                // <span class="badge bg-info text-dark">${akunText}</span>
                var barisBaru = `
                    <tr>
                        <td>
                            <input type="hidden" name="akun_kode[]" value="${akunId}">
                            <small>${akunText}</small>
                        </td>
                        <td>
                            <textarea name="rincian_detail[]" class="form-control form-control-sm" placeholder="Contoh: Pengawasan kegiatan lapangan SUPAS2025 dari BPS kab/kota ke kecamatan " rows="4" required></textarea>
                        </td>
                        <td>
                            <input type="number" name="rincian_volume[]" class="form-control form-control-sm hitung-total input-volume" min="0" value="0" required>
                        </td>
                        <td>
                            <select class="form-select form-control-sm" name="rincian_satuan[]">
                                                            <option value="">(Pilih salah satu)</option>
                                                            <option value="Dokumen">
                                                                Dokumen</option>
                                                            <option value="SLS">
                                                                SLS (Satuan Lingkungan Setempat)</option>
                                                            <option value="BS">
                                                                BS (Blok Sensus)</option>
                                                            <option value="Ruta">
                                                                Ruta (Rumah Tangga)</option>
                                                            <option value="OK">
                                                                OK (Orang Kegiatan) </option>
                                                            <option value="OH">
                                                                OH (Orang Harian)</option>
                                                            <option value="OB">
                                                                OB (Orang Bulan) </option>
                                                            <option value="OP">
                                                                OP (Orang Perjalanan)</option>
                                                            <option value="Segmen">
                                                                Segmen</option>
                                                            <option value="EA">
                                                                EA (Enumeration Area) </option>
                                                            <option value="Responden">
                                                                Responden</option>
                                                            <option value="Pasar">
                                                                Pasar</option>
                                                        </select>
                        </td>
                        <td>
                            <input type="number" name="rincian_harga[]" class="form-control form-control-sm hitung-total input-harga" min="0" value="0" required>
                        </td>
                        <td>
                            <input type="number" name="rincian_total[]" class="form-control form-control-sm input-subtotal" readonly value="0">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm hapus-baris">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#body_rincian_akun').append(barisBaru);
            });

            // --- 4. LOGIC: Hapus Baris ---
            $(document).on('click', '.hapus-baris', function() {
                $(this).closest('tr').remove();
                hitungGrandTotal(); // Hitung ulang total
            });

            // --- 5. LOGIC: Hitung Otomatis (Volume x Harga) ---
            $(document).on('keyup change', '.hitung-total', function() {
                var baris = $(this).closest('tr');
                var vol = parseFloat(baris.find('.input-volume').val()) || 0;
                var hrg = parseFloat(baris.find('.input-harga').val()) || 0;
                var subtotal = vol * hrg;

                baris.find('.input-subtotal').val(subtotal);
                hitungGrandTotal();
            });

            function hitungGrandTotal() {
                var grandTotal = 0;
                $('.input-subtotal').each(function() {
                    grandTotal += parseFloat($(this).val()) || 0;
                });
                $('#grand_total').val(grandTotal); // Anda bisa format currency di sini jika perlu
            }

            function generateJudulKAK() {
                var jenis_kegiatan = $('input[name="jenis_kegiatan"]:checked').val();
                var nama_kegiatan = $('#filter_sbks').val();
                var maksud = $('#kak2_maksud').val();
                var tujuan = $('#kak2_tujuan').val();

                if (jenis_kegiatan && nama_kegiatan && maksud && tujuan) {

                    if (maksud == 'pendataan') {
                        return 'TRANSPORT LOKAL DI BAWAH 8 JAM ' + tujuan.toUpperCase() + ' ' +
                            maksud.toUpperCase() + ' ' + nama_kegiatan.toUpperCase() +
                            ' TAHUN 2026 BPS KABUPATEN SIMEULUE TAHUN ANGGARAN 2026 ';
                    } else {
                        return 'TRANSPORT LOKAL DI BAWAH 8 JAM ' + tujuan.toUpperCase() + ' ' +
                            maksud.toUpperCase() + ' ' +
                            jenis_kegiatan.toUpperCase() + ' ' + nama_kegiatan.toUpperCase() +
                            ' TAHUN 2026 BPS KABUPATEN SIMEULUE TAHUN ANGGARAN 2026 ';
                    }
                };
                return '';
            }

            $('#filter_sbks').on('change',
                function() {
                    $('#kak8_pengaju').val('PJK ' + $('#filter_sbks').val());
                    $('#singkatan_resmi').val($('#filter_sbks').val() +
                        ' ' + new Date().toLocaleDateString('id-ID', {
                            month: 'long',
                        }));
                });

            $('#filter_sbks, input[name="jenis_kegiatan"], #kak2_maksud, #kak2_tujuan').on('change',
                function() {
                    var judulKAK = generateJudulKAK();
                    $('#judul_kak').val(judulKAK);
                });
        });


        // --- Script Khusus Tabel Lampiran--- //
        var rowIndex = 0;

        const standarBiaya = {
            "010": {
                nama: "Teupah Selatan",
                biaya: 140000
            },
            "020": {
                nama: "Simeulue Timur",
                biaya: 100000
            },
            "021": {
                nama: "Teupah Barat",
                biaya: 140000
            },
            "022": {
                nama: "Teupah Tengah",
                biaya: 120000
            },
            "030": {
                nama: "Simeulue Tengah",
                biaya: 160000
            },
            "031": {
                nama: "Teluk Dalam",
                biaya: 160000
            },
            "032": {
                nama: "Simeulue Cut",
                biaya: 170000
            },
            "040": {
                nama: "Salang",
                biaya: 180000
            },
            "050": {
                nama: "Simeulue Barat",
                biaya: 190000
            },
            "051": {
                nama: "Alafan",
                biaya: 200000
            },
        };

        // Helper untuk membuat opsi dropdown
        function getOptionsKecamatan() {
            let options = '<option value="">- Pilih Kecamatan -</option>';

            // Loop melalui setiap kode di standarBiaya
            for (const [kode, data] of Object.entries(standarBiaya)) {
                // Kita simpan biaya di 'data-biaya'
                // Kita simpan nama kecamatan di 'data-nama' (opsional, jika butuh nanti)
                // Tampilan di dropdown: "010 - Teupah Selatan"
                options += `<option value="${kode}" data-biaya="${data.biaya}" data-nama="${data.nama}">
                            [${kode}] ${data.nama}
                        </option>`;
            }
            return options;
        }

        // --- 1. Fungsi Tambah Baris (Updated: Satu Array) ---
        function tambahBarisTransport(tipe) {
            rowIndex++;

            var optionsHtml, badge;

            if (tipe === 'pegawai') {
                optionsHtml = $('#template_opsi_pegawai').html();
                badge = '<span class="badge bg-info text-dark mb-1">PNS</span>';
            } else {
                optionsHtml = $('#template_opsi_mitra').html();
                badge = '<span class="badge bg-warning text-dark mb-1">Mitra</span>';
            }

            var templateOpsiMitra = $('#template_opsi_mitra').html();

            // Ambil opsi kecamatan
            var optionsKecamatan = getOptionsKecamatan();

            var tr = `
                    <tr>
                        <td>
                            ${badge}
                            <select name="peserta_id[]" class="form-select select2-dinamis input-nama" id="row_${rowIndex}" required>
                                ${optionsHtml}
                            </select>
                            
                            <input type="hidden" name="tipe_peserta[]" value="${tipe}">
                        </td>
                        
                        <td>
                            <input type="text" name="nip[]" class="form-control form-control-sm text-center input-nip" readonly placeholder="-">
                        </td>
                        <td>
                            <select name="kecamatan_tujuan[]" class="form-select form-select-sm input-kecamatan" required>
                                ${optionsKecamatan}
                            </select>
                        </td>
                        <td>
                            <input type="date" name="tanggal_pelaksanaan[]" class="form-control form-control-sm">
                        </td>
                        <td>
                            <select name="pcl_diawasi[]" class="form-select form-select-sm  select2-dinamis form-select-mitra">
                                ${templateOpsiMitra}
                            </select>
                        </td>
                        <td>
                            <input type="number" name="jml_sampel_pcl[]" class="form-control form-control-sm text-center" min="0" value="0">
                        </td>
                        <td>
                            <input type="number" name="jml_sampel_diawasi[]" class="form-control form-control-sm text-center" min="0" value="0">
                        </td>
                        <td>
                            <input type="number" name="jml_ok[]" class="form-control form-control-sm text-center" min="1" value="1">
                        </td>
                        <td>
                            <input type="text" name="transport_bayar[]" class="form-control form-control-sm text-end input-transport input-mask-rupiah" placeholder="0">
                        </td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-danger btn-sm hapus-baris">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

            $('#body_transport').append(tr);

            $('#row_' + rowIndex).select2({
                theme: "bootstrap-5",
                width: '100%',
                dropdownParent: $('#tabel_transport')
            });
        }

        // --- 2. Event Listener Tombol Tambah ---
        $('#btn_tambah_pegawai').click(function() {
            tambahBarisTransport('pegawai');
        });
        $('#btn_tambah_mitra').click(function() {
            tambahBarisTransport('mitra');
        });

        // --- 3. Event Listener Hapus ---
        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
            hitungTotalTransport();
        });

        // --- 4. Logic Otomatis: Isi NIP saat Nama Dipilih ---
        $(document).on('change', '.input-nama', function() {
            var optionSelected = $(this).find(':selected');
            var row = $(this).closest('tr');
            var nip = optionSelected.data('nip'); // Ambil dari atribut data-nip

            row.find('.input-nip').val(nip ? nip : '-');
        });

        // --- 5. Logic Kalkulasi Total (Saat input transport berubah) ---
        $(document).on('input change', '.input-transport', function() {
            hitungTotalTransport();
        });

        function hitungTotalTransport() {
            var total = 0;
            $('.input-transport').each(function() {
                total += parseFloat($(this).val()) || 0;
            });

            // Format angka ke format Rupiah standar (Opsional, di sini hanya tampil angka)
            $('#grand_total_transport').val(total);
        }

        // --- 6. Logic Otomatis: Isi Transport saat Kecamatan Dipilih ---
        $(document).on('change', '.input-kecamatan', function() {
            var optionSelected = $(this).find(':selected');
            var row = $(this).closest('tr');

            // Ambil biaya dari atribut data-biaya yang sudah kita set di langkah 1
            var biaya = optionSelected.data('biaya');

            // Isi ke input transport
            row.find('.input-transport').val(biaya ? biaya : 0);

            // Panggil fungsi hitung total agar Grand Total langsung update
            hitungTotalTransport();
        });
    </script>
@endsection
