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
                    <div class="card">
                        <form action="{{ url('surat/store', $jenis) }}" method="POST" enctype="multipart/form-data">
                            @csrf <!-- {{ csrf_field() }} -->
                            <div class="card-header">
                                <div class="card-title">Tambah Surat {{ $jenis }}</div>
                            </div>
                            <div class="card-body">
                                @if ($jenis != 'masuk')
                                    @if ($jenis == 'spk')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group {{ $errors->has('mitra_spk') ? 'has-error has-feedback' : '' }}">
                                                    <label for="mitra_spk">Mitra</label>
                                                    <select class="form-select" id="single-select-field" name="mitra_spk"
                                                        value="{{ old('mitra_spk') }}"
                                                        data-placeholder="Pilih salah satu mitra">
                                                        <option value=""></option>
                                                        @foreach ($mitras as $m)
                                                            <option value="{{ $m->id }}"
                                                                {{ old('mitra_spk') == $m->id ? 'selected' : '' }}>
                                                                {{ $m->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('mitra_spk'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('mitra_spk') }}</small>
                                                    @else
                                                        <small class="form-text text-muted">
                                                        </small>
                                                    @endif
                                                </div>
                                                <div
                                                    class="form-group  {{ $errors->has('bulan_spk') ? 'has-error has-feedback' : '' }}">
                                                    <label for="bulan_spk">Bulan SPK</label>
                                                    <select class="form-select" id="bulan_spk" name="bulan_spk">
                                                        <option value="">---- Pilih Salah Satu ----</option>
                                                        <option value="01">Januari</option>
                                                        <option value="02">Februari</option>
                                                        <option value="03">Maret</option>
                                                        <option value="04">April</option>
                                                        <option value="05">Mei</option>
                                                        <option value="06">Juni</option>
                                                        <option value="07">Juli</option>
                                                        <option value="08">Agustus</option>
                                                        <option value="09">September</option>
                                                        <option value="10">Oktober</option>
                                                        <option value="11">November</option>
                                                        <option value="12">Desember</option>
                                                    </select>
                                                    @if ($errors->has('bulan_spk'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('bulan_spk') }}</small>
                                                    @endif
                                                </div>

                                                <div
                                                    class="form-group  {{ $errors->has('tahun_spk') ? 'has-error has-feedback' : '' }}">
                                                    <label for="tahun_spk">Tahun SPK</label>
                                                    <select class="form-select" id="tahun_spk" name="tahun_spk">
                                                        <option value="">---- Pilih Salah Satu ----</option>
                                                        @for ($i = date('Y'); $i >= 2024; $i--)
                                                            <option value="{{ $i }}"
                                                                {{ old('tahun_spk', date('Y')) == $i ? 'selected' : '' }}>
                                                                {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                    @if ($errors->has('tahun_spk'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('tahun_spk') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- jenis surat bukan SPK --}}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group  {{ $errors->has('tim') ? 'has-error has-feedback' : '' }}">
                                                    <label for="tim">Tim</label>
                                                    <select class="form-select" id="tim" name="tim">
                                                        <option value="">(Pilih salah satu)</option>
                                                        <option value="11011"
                                                            {{ old('tim') ? (old('tim') == '11011' ? 'selected' : '') : (Auth::user()->tim == '11011' ? 'selected' : '') }}>
                                                            Umum</option>
                                                        <option value="11012"
                                                            {{ old('tim') ? (old('tim') == '11012' ? 'selected' : '') : (Auth::user()->tim == '11012' ? 'selected' : '') }}>
                                                            Statistik Sosial</option>
                                                        <option value="11013"
                                                            {{ old('tim') ? (old('tim') == '11013' ? 'selected' : '') : (Auth::user()->tim == '11013' ? 'selected' : '') }}>
                                                            Statistik Ekonomi Produksi</option>
                                                        <option value="11015"
                                                            {{ old('tim') ? (old('tim') == '11015' ? 'selected' : '') : (Auth::user()->tim == '11015' ? 'selected' : '') }}>
                                                            Neraca dan Analisis Statistik</option>
                                                        <option value="11014"
                                                            {{ old('tim') ? (old('tim') == '11014' ? 'selected' : '') : (Auth::user()->tim == '11014' ? 'selected' : '') }}>
                                                            Statistik Ekonomi Distribusi</option>
                                                        <option value="11016"
                                                            {{ old('tim') ? (old('tim') == '11016' ? 'selected' : '') : (Auth::user()->tim == '11016' ? 'selected' : '') }}>
                                                            TI dan Pengolahan</option>
                                                        <option value="11017"
                                                            {{ old('tim') ? (old('tim') == '11017' ? 'selected' : '') : (Auth::user()->tim == '11017' ? 'selected' : '') }}>
                                                            Diseminasi, Publisitas, dan Humas</option>
                                                        <option value="11018"
                                                            {{ old('tim') ? (old('tim') == '11018' ? 'selected' : '') : (Auth::user()->tim == '11018' ? 'selected' : '') }}>
                                                            Pembinaan Statistik Sektoral</option>
                                                    </select>
                                                    @if ($errors->has('tim'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('tim') }}</small>
                                                    @endif
                                                </div>
                                                {{-- <div class="form-group  {{$errors->has('tipe') ? 'has-error has-feedback' : ''}}">
                                                            <label for="tipe"
                                                            >Tipe</label
                                                            >
                                                            <select
                                                            class="form-select"
                                                            id="tipe"
                                                            name="tipe"
                                                            >
                                                            <option value="">(Pilih salah satu)</option>
                                                            <option value="SS" {{ old('tipe') == 'SS' ? 'selected' : ''}}>Sensus</option>
                                                            <option value="VS" {{old('tipe') == 'VS' ? 'selected' : ''}}>Survei</option>
                                                            </select>
                                                            @if ($errors->has('tipe'))
                                                            <small class="form-text text-muted">{{ $errors->first('tipe') }}</small>
                                                            @endif
                                                        </div> --}}
                                                @if ($jenis != 'sk')
                                                    <div
                                                        class="form-group  {{ $errors->has('kode') ? 'has-error has-feedback' : '' }}">
                                                        <label for="kode">Kode Surat</label>
                                                        <select class="form-select" id="kode" name="kode">

                                                        </select>
                                                        @if ($errors->has('kode'))
                                                            <small
                                                                class="form-text text-muted">{{ $errors->first('kode') }}</small>
                                                        @else
                                                            <small class="form-text text-muted">
                                                                Jika pilihan kode surat tidak muncul, pilih tim terlebih
                                                                dahulu.
                                                            </small>
                                                        @endif
                                                    </div>
                                                @endif

                                                {{-- Pegawai yang berkegiatan di SPD ada di bawah, tidak pakai ini --}}
                                                @if ($jenis != 'spd')
                                                    <div
                                                        class="form-group {{ $errors->has('pegawai_yang_bertugas') ? 'has-error has-feedback' : '' }}">
                                                        <label for="pegawai_yang_bertugas">Pegawai Yang Berkegiatan</label>
                                                        <select class="form-select" id="single-select-field-2"
                                                            name="pegawai_yang_bertugas"
                                                            value="{{ old('pegawai_yang_bertugas') }}"
                                                            data-placeholder="Pilih salah satu">
                                                            <option value="">(Pilih salah satu)</option>
                                                            @foreach ($pegawais as $p)
                                                                <option value="{{ $p->id }}"
                                                                    {{ old('pegawai_yang_bertugas') == $p->id ? 'selected' : '' }}>
                                                                    {{ $p->nama }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('pegawai_yang_bertugas'))
                                                            <small
                                                                class="form-text text-muted">{{ $errors->first('pegawai_yang_bertugas') }}</small>
                                                        @else
                                                            <small class="form-text text-muted">
                                                            </small>
                                                        @endif
                                                    </div>
                                                @endif



                                            </div>
                                            @if ($tipe_bast != null)
                                                <input type="hidden" name="tipe_bast" id="tipe_bast" value="lainnya">
                                            @endif
                                            <div class="col-md-6">
                                                @if ($jenis != 'keluar' && $tipe_bast == null)
                                                    <div
                                                        class="form-group  {{ $errors->has('id_kegiatan') ? 'has-error has-feedback' : '' }}">
                                                        <label for="id_kegiatan">Kegiatan</label>
                                                        <select class="form-select" id="single-select-field"
                                                            name="id_kegiatan" data-placeholder="Pilih salah satu">
                                                            <option value="">(Pilih salah satu)</option>
                                                            @foreach ($kegiatans as $k)
                                                                <option value="{{ $k->id }}"
                                                                    {{ old('id_kegiatan') == $k->id ? 'selected' : '' }}>
                                                                    {{ $k->nama }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('id_kegiatan'))
                                                            <small
                                                                class="form-text text-muted">{{ $errors->first('id_kegiatan') }}</small>
                                                        @endif
                                                    </div>
                                                @endif
                                                @if ($jenis == 'tugas' || $jenis == 'sk' || $jenis == 'bast')
                                                    <div
                                                        class="form-group {{ $errors->has('tgl_surat') ? 'has-error has-feedback' : '' }}">
                                                        <label for="tgl_surat">Tanggal
                                                            {{ $jenis == 'tugas' ? 'Surat Tugas' : ($jenis == 'sk' ? 'SK' : 'BAST') }}</label>
                                                        <input type="date" class="form-control" id="tgl_surat"
                                                            name="tgl_surat"
                                                            value="{{ old('tgl_surat') ? old('tgl_surat') : date('Y-m-d') }}" />
                                                        @if ($errors->has('tgl_surat'))
                                                            <small
                                                                class="form-text text-muted">{{ $errors->first('tgl_surat') }}</small>
                                                        @else
                                                            <small class="form-text text-muted">
                                                                Format: bulan/tanggal/tahun
                                                            </small>
                                                        @endif
                                                    </div>
                                                @endif

                                                {{-- @if ($jenis == 'bast' && $tipe_bast == null)
                                                    <div
                                                        class="form-group {{ $errors->has('bulan_spk') ? 'has-error has-feedback' : '' }}">
                                                        <label for="bulan_spk">Pilih Bulan</label>
                                                        <select name="bulan_spk" id="bulan_spk" class="form-select">
                                                            <option value="">(Pilih salah satu)</option>
                                                            <option value="01"
                                                                {{ old('bulan_spk') == '01' ? 'selected' : '' }}>01.
                                                                Januari
                                                            </option>
                                                            <option value="02"
                                                                {{ old('bulan_spk') == '02' ? 'selected' : '' }}>02.
                                                                Februari
                                                            </option>
                                                            <option value="03"
                                                                {{ old('bulan_spk') == '03' ? 'selected' : '' }}>03. Maret
                                                            </option>
                                                            <option value="04"
                                                                {{ old('bulan_spk') == '04' ? 'selected' : '' }}>04. April
                                                            </option>
                                                            <option value="05"
                                                                {{ old('bulan_spk') == '05' ? 'selected' : '' }}>05. Mei
                                                            </option>
                                                            <option value="06"
                                                                {{ old('bulan_spk') == '06' ? 'selected' : '' }}>06. Juni
                                                            </option>
                                                            <option value="07"
                                                                {{ old('bulan_spk') == '07' ? 'selected' : '' }}>07. Juli
                                                            </option>
                                                            <option value="08"
                                                                {{ old('bulan_spk') == '08' ? 'selected' : '' }}>08.
                                                                Agustus
                                                            </option>
                                                            <option value="09"
                                                                {{ old('bulan_spk') == '09' ? 'selected' : '' }}>09.
                                                                September
                                                            </option>
                                                            <option value="10"
                                                                {{ old('bulan_spk') == '10' ? 'selected' : '' }}>10.
                                                                Oktober
                                                            </option>
                                                            <option value="11"
                                                                {{ old('bulan_spk') == '11' ? 'selected' : '' }}>11.
                                                                November
                                                            </option>
                                                            <option value="12"
                                                                {{ old('bulan_spk') == '12' ? 'selected' : '' }}>12.
                                                                Desember
                                                            </option>
                                                        </select>
                                                        @if ($errors->has('bulan_spk'))
                                                            <small
                                                                class="form-text text-muted">{{ $errors->first('bulan_spk') }}</small>
                                                        @endif
                                                    </div>
                                                @endif --}}

                                                <div
                                                    class="form-group {{ $errors->has('perihal') ? 'has-error has-feedback' : '' }}">
                                                    <label for="perihal">Perihal</label>
                                                    <input type="text" class="form-control" id="perihal"
                                                        name="perihal" placeholder="Perihal surat wajib diisi"
                                                        value="{{ old('perihal') }}" />
                                                    @if ($errors->has('perihal'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('perihal') }}</small>
                                                    @else
                                                        <small class="form-text text-muted">
                                                            @if ($jenis == 'sk')
                                                                Misal: PENETAPAN PETUGAS SURVEI ANGATAN KERJA NASIONAL
                                                                (SAKERNAS) AGUSTUS TAHUN 2025
                                                            @else
                                                                Misal: Pengawasan SERUTI Triwulan I 2024, Pelatihan
                                                                Instruktur
                                                                SAKERNAS, dll.
                                                            @endif
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    {{-- jenis == masuk --}}
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div
                                                class="form-group {{ $errors->has('dinas_surat_masuk') ? 'has-error has-feedback' : '' }}">
                                                <label for="dinas_surat_masuk">Dinas Pemberi Surat</label>
                                                <input type="text" class="form-control" id="dinas_surat_masuk"
                                                    name="dinas_surat_masuk"
                                                    placeholder="Masukkan Dinas/Kementerian/Lembaga Yang Memberi Surat"
                                                    value="{{ old('dinas_surat_masuk') }}" />
                                                @if ($errors->has('dinas_surat_masuk'))
                                                    <small
                                                        class="form-text text-muted">{{ $errors->first('dinas_surat_masuk') }}</small>
                                                @else
                                                    <small class="form-text text-muted">
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                            <div
                                                class="form-group {{ $errors->has('no_surat_masuk') ? 'has-error has-feedback' : '' }}">
                                                <label for="no_surat_masuk">No Surat Masuk</label>
                                                <input type="text" class="form-control" id="no_surat_masuk"
                                                    name="no_surat_masuk" placeholder="Nomor Surat Masuk Yang Diterima"
                                                    value="{{ old('no_surat_masuk') }}" />
                                                @if ($errors->has('no_surat_masuk'))
                                                    <small
                                                        class="form-text text-muted">{{ $errors->first('no_surat_masuk') }}</small>
                                                @else
                                                    <small class="form-text text-muted">
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="form-group {{ $errors->has('tgl_surat') ? 'has-error has-feedback' : '' }}">
                                                <label for="tgl_surat">Tanggal Surat</label>
                                                <input type="date" class="form-control" id="tgl_surat"
                                                    name="tgl_surat"
                                                    value="{{ old('tgl_surat') ? old('tgl_surat') : date('Y-m-d') }}" />
                                                @if ($errors->has('tgl_surat'))
                                                    <small
                                                        class="form-text text-muted">{{ $errors->first('tgl_surat') }}</small>
                                                @else
                                                    <small class="form-text text-muted">
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="form-group {{ $errors->has('tgl_diterima') ? 'has-error has-feedback' : '' }}">
                                                <label for="tgl_diterima">Tanggal Diterima</label>
                                                <input type="date" class="form-control" id="tgl_diterima"
                                                    name="tgl_diterima"
                                                    value="{{ old('tgl_diterima') ? old('tgl_diterima') : date('Y-m-d') }}" />
                                                @if ($errors->has('tgl_diterima'))
                                                    <small
                                                        class="form-text text-muted">{{ $errors->first('tgl_diterima') }}</small>
                                                @else
                                                    <small class="form-text text-muted">
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="form-group {{ $errors->has('file') ? 'has-error has-feedback' : '' }}">
                                                <label for="file">File PDF Surat Masuk</label>
                                                <br>
                                                <input type="file" class="form-control" id="file"
                                                    name="file" />
                                                @if ($errors->has('file'))
                                                    <small
                                                        class="form-text text-muted">{{ $errors->first('file') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {{-- WARNING!! INI PERIHAL DI SURAT MASUK AJA --}}
                                            <div
                                                class="form-group {{ $errors->has('perihal') ? 'has-error has-feedback' : '' }}">
                                                <label for="perihal">Perihal</label>
                                                <input type="text" class="form-control" id="perihal" name="perihal"
                                                    placeholder="Perihal surat wajib diisi"
                                                    value="{{ old('perihal') }}" />
                                                @if ($errors->has('perihal'))
                                                    <small
                                                        class="form-text text-muted">{{ $errors->first('perihal') }}</small>
                                                @else
                                                    <small class="form-text text-muted">
                                                        Misal: Pengawasan SERUTI Triwulan I 2024, Pelatihan Instruktur
                                                        SAKERNAS, dll.
                                                    </small>
                                                @endif
                                            </div>
                                            {{-- WARNING!! INI PERIHAL DI SURAT MASUK AJA --}}
                                        </div>

                                    </div>
                                @endif

                                @if ($jenis == 'spd')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <hr />
                                            <div
                                                class="form-group {{ $errors->has('tgl_awal_kegiatan') ? 'has-error has-feedback' : '' }}">
                                                <label for="tgl_awal_kegiatan">Tanggal Kegiatan Dimulai</label>
                                                <input type="date" class="form-control" id="tgl_awal_kegiatan"
                                                    name="tgl_awal_kegiatan" value="{{ old('tgl_awal_kegiatan') }}" />
                                                @if ($errors->has('tgl_awal_kegiatan'))
                                                    <small
                                                        class="form-text text-muted">{{ $errors->first('tgl_awal_kegiatan') }}</small>
                                                @else
                                                    <small class="form-text text-muted">
                                                    </small>
                                                @endif
                                            </div>
                                            <div
                                                class="form-group {{ $errors->has('tgl_akhir_kegiatan') ? 'has-error has-feedback' : '' }}">
                                                <label for="tgl_akhir_kegiatan">Tanggal Kegiatan Berakhir</label>
                                                <input type="date" class="form-control" id="tgl_akhir_kegiatan"
                                                    name="tgl_akhir_kegiatan" value="{{ old('tgl_akhir_kegiatan') }}" />
                                                @if ($errors->has('tgl_akhir_kegiatan'))
                                                    <small
                                                        class="form-text text-muted">{{ $errors->first('tgl_akhir_kegiatan') }}</small>
                                                @else
                                                    <small class="form-text text-muted">
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <hr />
                                            <div
                                                class="form-group {{ $errors->has('pegawai_yang_bertugas') ? 'has-error has-feedback' : '' }}">
                                                <label for="pegawai_yang_bertugas">Pegawai Yang Berkegiatan</label>
                                                <select class="form-select" id="single-select-field-2"
                                                    name="pegawai_yang_bertugas"
                                                    value="{{ old('pegawai_yang_bertugas') }}"
                                                    data-placeholder="Pilih salah satu">
                                                    <option value="">(Pilih salah satu)</option>
                                                    @foreach ($pegawais as $p)
                                                        <option value="{{ $p->id }}"
                                                            {{ old('pegawai_yang_bertugas') == $p->id ? 'selected' : '' }}>
                                                            {{ $p->nama }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('pegawai_yang_bertugas'))
                                                    <small
                                                        class="form-text text-muted">{{ $errors->first('pegawai_yang_bertugas') }}</small>
                                                @else
                                                    <small class="form-text text-muted">
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($jenis != 'masuk' && $jenis != 'spk' && $jenis != 'sk' && $jenis != 'bast')
                                    <div class="row">
                                        <div class="col">
                                            <hr />
                                            <h5>
                                                Kemungkinan nomor surat:
                                                <span class="kemungkinan_no_surat">
                                                    @if (!str_contains(Request::path(), 'spd'))
                                                        B-
                                                    @endif
                                                    <span
                                                        id="no-surat">{{ str_pad($noTerakhir + 1, 4, '0', STR_PAD_LEFT) }}</span>/<span
                                                        id="kode-tim">{{ old('tim') }}</span>/<span
                                                        id="kode-surat">{{ old('kode') }}</span>/<span>{{ date('Y') }}</span>
                                                </span>
                                            </h5>
                                            {{-- <input type="hidden" name="nomor_surat" id="nomor_surat" > --}}
                                            <hr />
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Buat Surat</button>
                            </div>
                        </form>
                    </div>

                    @if ($jenis != 'masuk' && $jenis != 'spk' && $jenis != 'sk')
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Kamus Nomor Surat</div>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                            href="#pills-home" role="tab" aria-controls="pills-home"
                                            aria-selected="true">Teknis</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                            href="#pills-profile" role="tab" aria-controls="pills-profile"
                                            aria-selected="false">Umum</a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                        aria-labelledby="pills-home-tab">
                                        <div class="table-responsive">
                                            <table id="basic-datatables" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Kode Surat</th>
                                                        <th>Klasifikasi</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Kode Surat</th>
                                                        <th>Klasifikasi</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    @foreach ($kamusSuratTeknis as $kode)
                                                        <tr>
                                                            <td>
                                                                {{ $kode->kode_surat_gabungan }} (@if (str_contains($kode->kode_surat_gabungan, 'SS'))
                                                                    Sensus
                                                                @else
                                                                    Survei
                                                                @endif)
                                                            </td>
                                                            <td>{{ $kode->klasifikasi }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                        aria-labelledby="pills-profile-tab">
                                        <div class="table-responsive">
                                            <table id="multi-filter-select"
                                                class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Kode Surat</th>
                                                        <th>Klasifikasi</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Kode Surat</th>
                                                        <th>Klasifikasi</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    @foreach ($kamusSuratUmum as $kode)
                                                        <tr>
                                                            <td>{{ $kode->kode_surat_gabungan }}</td>
                                                            <td>{{ $kode->klasifikasi }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('select2/js/select2.full.min.js') }}"></script>
    <script>
        const jenis = '{{ $jenis }}';


        function gantiTimDanKegiatan() {
            var tim = $('#tim').val();
            if (jenis != "keluar") {
                $('#kode-tim').text(tim);
            } else {
                $('#kode-tim').text('11010');
            }
            $('#nomor_surat').val($('.kemungkinan_no_surat').text());
            $("#kode").empty();
            if (tim == '11011') {
                $("#kode").append(opsiUmum);
            } else if (tim == '11016') {
                $("#kode").append(opsiIPDS);
            } else {
                $("#kode").append(opsiTeknis);
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('kegiatan.get-kegiatan-api') }}",
                data: {
                    tim: tim,
                    id_pegawai: {{ Auth::user()->id }},
                    jenis: jenis
                },

                success: function(msg) {
                    // console.log(msg);
                    $('#single-select-field').empty();
                    $.each(msg, function(key, value) {
                        $('#single-select-field').append('<option value="' + value.id + '">' + value
                            .singkatan_resmi + ' - ' + value.nama + '</option>');

                    });

                },
                error: function(msg) {
                    console.log(msg);
                }

            });
        }

        $(document).ready(function() {
            if (jenis != 'spk') {
                gantiTimDanKegiatan();
                $('#tim').change(function() {
                    var tim = $(this).val();
                    gantiTimDanKegiatan();


                });

                // $('#tipe').change(function() {
                // var tipe = $(this).val();
                // $('#tipe-surat').text(tipe);
                // $('#nomor_surat').val($('.kemungkinan_no_surat').text());
                // });

                $('#kode').change(function() {
                    var kode = $(this).val();
                    $('#kode-surat').text(kode);
                    $('#nomor_surat').val($('.kemungkinan_no_surat').text());
                });
            }

            $('#single-select-field, #no_spk, #single-select-field-2').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
            });


            $('#kode').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
            });


        });
        var opsiTeknis =
            '<option value="">(Pilih salah satu)</option><option value="VS.210">VS.210 - Survei - Pelatihan Instruktur</option><option value="VS.220">VS.220 - Survei - Pelatihan Petugas</option><option value="VS.230">VS.230 - Survei - Pelatihan Petugas Pengolahan</option><option value="VS.310">VS.310 - Survei - Listing</option><option value="VS.330">VS.330 - Survei - Pengumpulan Data</option><option value="VS.340">VS.340 - Survei - Pemeriksaan Data</option><option value="VS.350">VS.350 - Survei - Pengawasan Lapangan</option><option value="VS.360">VS.360 - Survei - Monitoring Kualitas</option><option value="SS.210">SS.210 - Sensus - Pelatihan Instruktur</option><option value="SS.220">SS.220 - Sensus - Pelatihan Petugas</option><option value="SS.230">SS.230 - Sensus - Pelatihan Petugas Pengolahan</option><option value="SS.310">SS.310 - Sensus - Listing</option><option value="SS.320">SS.320 - Sensus - Pemilihan Sampel</option><option value="SS.330">SS.330 - Sensus - Pengumpulan Data</option><option value="SS.340">SS.340 - Sensus - Pemeriksaan Data</option><option value="SS.350">SS.350 - Sensus - Pengawasan Lapangan</option><option value="SS.360">SS.360 - Sensus - Monitoring Kualitas</option><option value="ES">ES - Evaluasi Dan Pelaporan Sensus, Survei, dan Konsolidasi Data</option>';

        var opsiIPDS =
            '<option value="">(Pilih salah satu)</option><option value="VS.210">VS.210 - Survei - Pelatihan Instruktur</option><option value="VS.220">VS.220 - Survei - Pelatihan Petugas</option><option value="VS.230">VS.230 - Survei - Pelatihan Petugas Pengolahan</option><option value="VS.310">VS.310 - Survei - Listing</option><option value="VS.330">VS.330 - Survei - Pengumpulan Data</option><option value="VS.340">VS.340 - Survei - Pemeriksaan Data</option><option value="VS.350">VS.350 - Survei - Pengawasan Lapangan</option><option value="VS.360">VS.360 - Survei - Monitoring Kualitas</option><option value="VS.400">VS.400 - Survei - PENGOLAHAN</option><option value="VS.410">VS.410 - Survei - Pengelolaan Dokumen (penerimaan/pengiriman, pengelompokan/batching)</option><option value="VS.420">VS.420 - Survei - Pemeriksaan Dokumen dan Pengkodean(editing/coding)</option><option value="VS.430">VS.430 - Survei - Perekam Data (entri/scanner)</option><option value="VS.440">VS.440 - Survei - Tabulasi Data</option><option value="VS.450">VS.450 - Survei - Pemeriksaan Tabulasi</option><option value="VS.460">VS.460 - Survei - Laporan Konsistensi Tabulasi</option><option value="SS.210">SS.210 - Sensus - Pelatihan Instruktur</option><option value="SS.220">SS.220 - Sensus - Pelatihan Petugas</option><option value="SS.230">SS.230 - Sensus - Pelatihan Petugas Pengolahan</option><option value="SS.310">SS.310 - Sensus - Listing</option><option value="SS.320">SS.320 - Sensus - Pemilihan Sampel</option><option value="SS.330">SS.330 - Sensus - Pengumpulan Data</option><option value="SS.340">SS.340 - Sensus - Pemeriksaan Data</option><option value="SS.350">SS.350 - Sensus - Pengawasan Lapangan</option><option value="SS.360">SS.360 - Sensus - Monitoring Kualitas</option><option value="SS.400">SS.400 - Sensus - PENGOLAHAN</option><option value="SS.410">SS.410 - Sensus - Pengelolaan Dokumen (penerimaan/pengiriman, pengelompokan/batching)</option><option value="SS.420">SS.420 - Sensus - Pemeriksaan Dokumen dan Pengkodean(editing/coding)</option><option value="SS.430">SS.430 - Sensus - Perekam Data (entri/scanner)</option><option value="SS.440">SS.440 - Sensus - Tabulasi Data</option><option value="SS.450">SS.450 - Sensus - Pemeriksaan Tabulasi</option><option value="SS.460">SS.460 - Sensus - Laporan Konsistensi Tabulasi</option><option value="ES">ES - Evaluasi Dan Pelaporan Sensus, Survei, dan Konsolidasi Data</option>';

        var opsiUmum =
            '<option value="">(Pilih salah satu)</option><option value="KU.000">KU.000 - PELAKSANAAN ANGGARAN</option><option value="KU.010">KU.010 - Ketentuan/Peraturan Menteri Keuangan Menyangkut Pelaksanaan dan Penatausahaan</option><option value="KU.100">KU.100 - REALISASI PENDAPATAN/PENERIMAAN NEGARA</option><option value="KU.110">KU.110 - Surat Setoran Pajak (SSP)</option><option value="KU.120">KU.120 - Surat Setoran Bukan Pajak (SSBP)</option><option value="KU.130">KU.130 - Bukti Penerimaan Bukan Pajak (PNBP)</option><option value="KU.140">KU.140 - Dana Bagi Hasil yang bersumber dari Pajak :</option><option value="KU.141">KU.141 - Pajak Bumi Bangunan</option><option value="KU.142">KU.142 - Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)</option><option value="KU.143">KU.143 - Pajak Penghasilan (Pph) Pasal 21, 25 dan 29</option><option value="KU.150">KU.150 - Bukti Setor Sisa Anggaran Lebih atau Bukti Setor Pengembalian Belanja (SSPB)</option><option value="KU.160">KU.160 - Bunga dan atau Jasa Giro pada Bank</option><option value="KU.170">KU.170 - Piutang Negara</option><option value="KU.180">KU.180 - Pengelolaan Investasi dan Penyertaan Modal</option><option value="KU.200">KU.200 - PENGELOLAAN PERBENDAHARAAN</option><option value="KU.210">KU.210 - Pejabat Penguji dan Penandatanganan SPM</option><option value="KU.220">KU.220 - Bendahara Penerimaan</option><option value="KU.230">KU.230 - Bendahara Pengeluaran</option><option value="KU.240">KU.240 - Kartu Pengawasan Pembayaran Penghasilan Pegawai (PK4)</option><option value="KU.250">KU.250 - Pengembalian Belanja</option><option value="KU.260">KU.260 - Pembukuan Anggaran :</option><option value="KU.261">KU.261 - Buku Kas Umum (BKU)</option><option value="KU.262">KU.262 - Buku Kas Pembantu</option><option value="KU.263">KU.263 - Kartu Realisasi Anggaran dan Pengawasan Realisasi Anggaran</option><option value="KU.270">KU.270 - Berita Acara Pemeriksaan Kas</option><option value="KU.280">KU.280 - Datar Gaji/Kartu Gaji</option><option value="KU.300">KU.300 - PENGELUARAN ANGGARAN Naskah - naskah yang berkaitan dengan pelaksanaan anggaran pengeluaran mulai dari SPP-GU, SPP-LS, SPP-UP, SPP-UP, SPP-TUP, SPM, SP2D, Juklak mekanisme pengelolaan APB serta bahan nota keuangan</option><option value="KU.310">KU.310 - Belanja Bahan</option><option value="KU.320">KU.320 - Belanja Barang</option><option value="KU.330">KU.330 - Belanja Jasa (Konsultasi, Profesi)</option><option value="KU.340">KU.340 - Belanja Perjalanan</option><option value="KU.350">KU.350 - Belanja Pegawai</option><option value="KU.360">KU.360 - Belanja Paket Meeting Dalam Kota</option><option value="KU.370">KU.370 - Belanja Paket Meeting Luar Kota</option><option value="KU.380">KU.380 - Belanja Akun Kombinasi</option><option value="KU.400">KU.400 - VERIFIKASI ANGGARAN</option><option value="KU.410">KU.410 - Surat Permintaan Pembayaran (SPP) beserta lampirannya</option><option value="KU.420">KU.420 - Surat Perintah Membayar (SPM), Surat perintah Pencairan dana (SP2D)</option><option value="KU.500">KU.500 - PELAPORAN</option><option value="KU.510">KU.510 - Akuntansi Keuangan:</option><option value="KU.511">KU.511 - Berita Acara Pemeriksaan Kas</option><option value="KU.512">KU.512 - Kas/Registrasi Penutupan Kas</option><option value="KU.513">KU.513 - Laporan Pendapatan Negara</option><option value="KU.514">KU.514 - Arsip Data Komputer (ADK)</option><option value="KU.520">KU.520 - Pengumpulan, Pemantauan, Evaluasi dan laporan Keuangan:</option><option value="KU.521">KU.521 - Keadaan Kredit Anggaran (LKKA) Bulanan/ Triwulan/Semesteran</option><option value="KU.521">KU.521 - a. Laporan Realisasi Anggaran (RKA);</option><option value="KU.521">KU.521 - b. Neraca</option><option value="KU.521">KU.521 - c. Laporan Arus Kas</option><option value="KU.521">KU.521 - d. Catatan Atas Laporan Keuangan (CALK)</option><option value="KU.530">KU.530 - Rekonsiliasi Data Laporan Keuangan</option><option value="KU.600">KU.600 - BANTUAN PINJAMAN LUAR NEGERI</option><option value="KU.610">KU.610 - Permohonan Pinjaman Luar Negeri (Blue Book)</option><option value="KU.620">KU.620 - Dokumen Kesanggupan negara donor (Gray Book)</option><option value="KU.630">KU.630 - Memorandum of Understand (MOU) dan dokumen sejenisnya</option><option value="KU.640">KU.640 - Loan Agreement Pinjaman/Hibah Luar Negeri (PHLN), legal opinion, surat-menyuratnya dengan lender, konsultan.</option><option value="KU.650">KU.650 - Alokasi dan Relokasi Penggunaan Dana Pinjaman/Hibah Luar Negeri</option><option value="KU.660">KU.660 - Penarikan Dana Bantuan Luar Negeri (BLN)</option><option value="KU.661">KU.661 - Aplikasi Penarikan Dana Bantuan Luar Negeri (BLN) berikut lampirannya</option><option value="KU.661">KU.661 - a. Reimbursment;</option><option value="KU.661">KU.661 - b. Direct [payment/ Transfer Procedure;</option><option value="KU.661">KU.661 - c. Special Comitment/ L/C Opening;</option><option value="KU.661">KU.661 - d. Special Account/Imprest Fund.</option><option value="KU.662">KU.662 - Otorisasi Penarikan Dana (Payment Advice)</option><option value="KU.663">KU.663 - Replenisment (permintaan penarikan dana dari negara donor) meliputi:</option><option value="KU.663">KU.663 - a. No Objection Letter (NOL)</option><option value="KU.663">KU.663 - b. Notification of Contract;</option><option value="KU.663">KU.663 - c. Withdrawal Authorization (WA)</option><option value="KU.663">KU.663 - d. Statement of Expenditur (SE).</option><option value="KU.670">KU.670 - Realisasi Pencairan Dana Bantuan Luar Negeri:</option><option value="KU.670">KU.670 - a. Surat Perintah Pencairan Dana (SP2D)</option><option value="KU.670">KU.670 - b. SPM beserta lampirannya: SPP, Kontrak, BA dan data pendukung lainnya</option><option value="KU.680">KU.680 - Ketentuan/Peraturan yang Menyangkut Bantuan/Pinjaman Luar Negeri</option><option value="KU.690">KU.690 - Laporan-laporan Pelaksanaan Bantuan/Pinjaman Luar Negeri.</option><option value="KU.691">KU.691 - Staff Appraisal Report.</option><option value="KU.692">KU.692 - Report/Laporan yang terdiri dari:</option><option value="KU.692">KU.692 - a. Progress Report</option><option value="KU.692">KU.692 - b. Monthly Report</option><option value="KU.692">KU.692 - c. Quartely Report</option><option value="KU.693">KU.693 - Laporan Hutang Negara:</option><option value="KU.693">KU.693 - a. Laporan Pembayaran Hutang Negara;</option><option value="KU.693">KU.693 - b. Laporan Posisi Hutang Negara.</option><option value="KU.694">KU.694 - Completion Report/Annual Report</option><option value="KU.700">KU.700 - PENGELOLA APBN/DANA PINJAMAN/HIBAH LUAR NEGERI (PHLN)</option><option value="KU.710">KU.710 - Keputusan Kepala BPS tentang Penetapan:</option><option value="KU.711">KU.711 - Kuasa Pengguna Anggaran (KPA), pejabat Pembuat Komitmen (PKK);</option><option value="KU.712">KU.712 - Pejabat Pembuatan Daftar Gaji;</option><option value="KU.713">KU.713 - Penandatangan SPM;</option><option value="KU.714">KU.714 - Bendahara Penerimaan/Pengeluaran, Pengelola Barang.</option><option value="KU.800">KU.800 - SISTEM AKUNTANSI INSTANSI (SAI)</option><option value="KU.810">KU.810 - Manual Implementasi Sistem Akuntansi Instansi (SAI)</option><option value="KU.820">KU.820 - Arsip Data Komputer dan Berita Acara Rekonsiliasi</option><option value="KU.830">KU.830 - a. Daftar Transaksi (DT), Pengeluaran (PK), Penerimaan (PN).</option><option value="KU.830">KU.830 - b. Dokumen Sumber (DS), Bukti Jurnal (BJ) surat Tanda Setor (STS)</option><option value="KU.830">KU.830 - c. Surat Setor Bukan Pajak (SSBP), Surat Perintah Pencairan Dana (SP2D)</option><option value="KU.830">KU.830 - d. SPM dalam Daftar Ringkasan Pengembalian dan Potongan dari Pengeluaran (SPRD)</option><option value="KU.840">KU.840 - Listing (Daftar Rekaman Penerimaan) Buku Temuan dan Tindakan Lain (SAI)</option><option value="KU.850">KU.850 - Laporan Realisasi Bulanan SAI</option><option value="KU.860">KU.860 - Laporan Realisasi Triwulanan SAI dari Unit Akuntansi Wilayah (UAW) dan Gabungan semua UAW/Unit Akuntansi Kantor Pusat Instansi (UAKPI)</option><option value="KU.900">KU.900 - PERTANGGUNGJAWABAN KEUANGAN NEGARA</option><option value="KU.910">KU.910 - Laporan Hasil Pemeriksaan atas Laporan Keuangan oleh BPK RI.</option><option value="KU.920">KU.920 - Hasil Pengawasan dan Pemeriksaan Internal.</option><option value="KU.930">KU.930 - Laporan Aparat Pemeriksa Fungsional</option><option value="KU.931">KU.931 - Laporan Hasil Pemeriksaan (LHP).</option><option value="KU.932">KU.932 - Memorandum Hasil Pemeriksaan (MHP).</option><option value="KU.933">KU.933 - Tindak Lanjut/Tanggapan LHP.</option><option value="KU.940">KU.940 - Dokumentasi Penyelesaian Keuangan Negara:</option><option value="KU.941">KU.941 - Tuntutan Perbendaharaan</option><option value="KU.942">KU.942 - Tuntutan Ganti Rugi</option><option value="KP.000">KP.000 - FORMASI PEGAWAI</option><option value="KP.010">KP.010 - Usulan dari Unit Kerja.</option><option value="KP.020">KP.020 - Usulan Permintaan Formasi kepada Menpan dan Kepala BKN.</option><option value="KP.030">KP.030 - Persetujuan Menpan</option><option value="KP.040">KP.040 - Penetapan Formasi</option><option value="KP.050">KP.050 - Penetapan Formasi Khusus.</option><option value="KP.100">KP.100 - PENGADAAN DAN PENGANGKATAN PEGAWAI</option><option value="KP.110">KP.110 - Proses Penerimaan Pegawai:</option><option value="KP.111">KP.111 - Pengumuman.</option><option value="KP.112">KP.112 - Seleksi Administrasi</option><option value="KP.113">KP.113 - Pemanggilan Peserta Tes</option><option value="KP.114">KP.114 - Pelaksanaa Ujian (tertulis, psikotes, wawancara).</option><option value="KP.115">KP.115 - Keputusan Hasil Ujian</option><option value="KP.120">KP.120 - Penetapan Pengumuman Kelulusan</option><option value="KP.130">KP.130 - Berkas Lamaran yang Tidak Diterima</option><option value="KP.140">KP.140 - Nota Usul dan Kelengkapan Penetapan NIP</option><option value="KP.150">KP.150 - Nota Usul Pengangkatan CPNS menjadi PNS.</option><option value="KP.160">KP.160 - Nota Usul Pengangkatan CPNS menjadi PNS lebih 2 Tahun</option><option value="KP.170">KP.170 - SK CPNS/PNS Kolektif</option><option value="KP.200">KP.200 - BERKAS PEGAWAI TIDAK TETAP/MITRA STATISTIK</option><option value="KP.300">KP.300 - PEMBINAAN KARIR PEGAWAI</option><option value="KP.310">KP.310 - Diklat Kursus/ Tugas Belajar/ Ujian Dinas/ Izin Belajar Pegawai:</option><option value="KP.311">KP.311 - Surat Perintah/ Surat Tugas/ SK/ Surat Izin.</option><option value="KP.312">KP.312 - Laporan Kegiatan Pengembangan Diri.</option><option value="KP.313">KP.313 - Surat Tanda Tamat Pendidikan dan Pelatihan.</option><option value="KP.320">KP.320 - Ujian Kompetensi</option><option value="KP.321">KP.321 - Assesment Tes Pegawai</option><option value="KP.322">KP.322 - Pemetaan/Mapping Talent Pegawai.</option><option value="KP.330">KP.330 - Daftar Penilaian Pelaksanaan Pekerjaan (DP3) dan Sasaran Kinerja Pegawai (SKP).</option><option value="KP.340">KP.340 - Pakta Integritas Pegawai</option><option value="KP.350">KP.350 - Laporan Hasil Kekayaan Penyelenggara Negara (LHKPN)</option><option value="KP.360">KP.360 - Daftar Usul Penetapan Angka Kredit Fungsional.</option><option value="KP.370">KP.370 - Disiplin Pegawai:</option><option value="KP.371">KP.371 - Daftar Hadir</option><option value="KP.371">KP.371 - Rekapitulasi Daftar Hadir</option><option value="KP.380">KP.380 - Berkas Hukum Disiplin.</option><option value="KP.390">KP.390 - Penghargaan dan Tanda Jasa (Satya Lencana/Bintang Jasa).</option><option value="KP.400">KP.400 - PENYELESAIAN PENGELOLAAN KEBERATAN PEGAWAI</option><option value="KP.500">KP.500 - MUTASI PEGAWAI</option><option value="KP.510">KP.510 - Alih Status, Pindah Instansi, Pindah Wilayah Kerja, Diperbantukan, Dipekerjakan, Penugasan Sementara, Mutasi Antar Perwakilan, Mutasi ke dan dari perwakilan Sementara, Mutasi antar Unit</option><option value="KP.520">KP.520 - Nota Persetujuan/Pertimbangan Kepala BKN</option><option value="KP.530">KP.530 - Mutasi Keluarga:</option><option value="KP.531">KP.531 - Surat Izin Pernikahan/Perceraian.</option><option value="KP.532">KP.532 - Surat Penolakan Izin Pernikahan/perceraian</option><option value="KP.533">KP.533 - Akte Nikah/Cerai</option><option value="KP.534">KP.534 - Surat Keterangan Meninggal Dunia</option><option value="KP.540">KP.540 - Usul Kenaikan Pangkat/Golongan/Jabatan.</option><option value="KP.550">KP.550 - Usul Pengangkatan dan Pemberhentian dalam Jabatan Struktural/Fungsional</option><option value="KP.560">KP.560 - Usul Penetapan Perubahan Data Dasar/Status/kedudukan Hukum pegawai</option><option value="KP.570">KP.570 - Peninjauan Masa Kerja</option><option value="KP.580">KP.580 - Berkas Baperjakat.</option><option value="KP.600">KP.600 - ADMINISTRASI PEGAWAI</option><option value="KP.610">KP.610 - Dokumentasi Identitas Pegawai:</option><option value="KP.611">KP.611 - Usul Penetapan Karpeg/KPE/Karis/Karsu.</option><option value="KP.612">KP.612 - Keanggotaan Organisasi Profesi/Kedinasan.</option><option value="KP.613">KP.613 - Laporan Pajak Penghasilan Pribadi (LP2P)</option><option value="KP.614">KP.614 - Keterangan Penerimaan Penghasilan Pegawai (KP4).</option><option value="KP.620">KP.620 - Berkas Kepegawaian dan Daftar Urut Kepangkatan (DUK)</option><option value="KP.630">KP.630 - Berkas Perorangan Pegawai Negeri Sipil:</option><option value="KP.630">KP.630 - a. Nota Penetapan NIP dan kelengkapannya;</option><option value="KP.630">KP.630 - b. Nota Persetujuan/Pertimbangan Kepala BKN;</option><option value="KP.630">KP.630 - c. SK Pengangkatan CPNS;</option><option value="KP.630">KP.630 - d. Hasil Pengujian Kesehatan;</option><option value="KP.630">KP.630 - e. SK Pengangkatan PNS;</option><option value="KP.630">KP.630 - f. SK Peninjauan Masa Kerja</option><option value="KP.630">KP.630 - g. SK Kenaikan Pangkat;</option><option value="KP.630">KP.630 - f. SK Peninjauan Masa Kerja</option><option value="KP.630">KP.630 - g. SK Kenaikan Pangkat;</option><option value="KP.630">KP.630 - h. Surat Pernyataan Melaksanakan Tugas/Menduduki Jabatan/Surat Pernyataan Pelantikan;</option><option value="KP.630">KP.630 - i. SK Pengangkatan Dalam atau Pemberhentian Dari Jabatan Struktural/Fungsional;</option><option value="KP.630">KP.630 - j. SK Perpindahan Wilayah Kerja;</option><option value="KP.630">KP.630 - k. SK Perpindahan Antar Instansi;</option><option value="KP.630">KP.630 - l. SK Cuti di Luar Tanggungan Negara (CLTN);</option><option value="KP.630">KP.630 - m. Berita Acara Pemeriksaan;</option><option value="KP.630">KP.630 - n. SK Hukuman Jabatan/ Hukum Disiplin PNS;</option><option value="KP.630">KP.630 - o. SK Perbantuan/Dipekerjakan Diluar Instansi Induk;</option><option value="KP.630">KP.630 - p. SK Penarikan Kembali dan Perbantuan/Dipekerjakan;</option><option value="KP.630">KP.630 - q. SK Pemberian Uang Tunggu;</option><option value="KP.630">KP.630 - r. SK Pembebasan Dari Jabatan Organik Karena Diangkat Sebagai Pejabat Negara</option><option value="KP.630">KP.630 - s. SK Pengalihan PNS;</option><option value="KP.630">KP.630 - t. SK Pemberhentian Sebagai PNS;</option><option value="KP.630">KP.630 - u. SK Pemberhentian Sementara;</option><option value="KP.630">KP.630 - v. Surat Ketarangan Pernyataan Hilang;</option><option value="KP.630">KP.630 - w. Surat Keterangan Kembalinya PNS Yang Dinyatakan Hilang;</option><option value="KP.630">KP.630 - x. SK Penggantian Nama</option><option value="KP.630">KP.630 - y. Surat Perbaikan Tanggal Tahun Kelahiran;</option><option value="KP.630">KP.630 - z. Akta Nikah/Cerai;</option><option value="KP.630">KP.630 - aa. Akta Kelahiran;</option><option value="KP.630">KP.630 - bb. Isian Formulir PUPNS;</option><option value="KP.630">KP.630 - cc. Berita Acara Pengambilan Sumpah/Janji PNS dari Jabatan;</option><option value="KP.630">KP.630 - dd. Surat Permohonan Menjadi Anggota Parpol;</option><option value="KP.630">KP.630 - ee. Surat Keterangan Mutasi Keluarga;</option><option value="KP.630">KP.630 - ff. Surat Keterangan Meninggal Dunia;</option><option value="KP.630">KP.630 - gg. Surat Keterangan Peningkatan Pendidikan;</option><option value="KP.630">KP.630 - hh. Penetapan Angka Kredit Jabatan Fungsional;</option><option value="KP.630">KP.630 - ii. Surat Keterangan Hasil Penelitian Khusus;</option><option value="KP.630">KP.630 - jj. Surat Penelitian Kenaikan Gaji Berkala;</option><option value="KP.630">KP.630 - kk. Surat Tugas/Izin Belajar Dalam/Luar Negeri;</option><option value="KP.630">KP.630 - ll. Surat Izin Bepergian Ke Luar Negeri;</option><option value="KP.630">KP.630 - mm. Kartu Pendaftaran Ulang (Kardaf) PNS;</option><option value="KP.630">KP.630 - nn. Ijazah/Sertifikat;</option><option value="KP.630">KP.630 - oo. SK Penempatan/Penarikan Pegawai;</option><option value="KP.630">KP.630 - pp. SK Pengangkatan Pada Jabatan Diluar Instansi Induk;</option><option value="KP.630">KP.630 - qq. Surat Pertimbangan Status TNI;</option><option value="KP.630">KP.630 - rr. SK Pengaktifan Kembali Sebagai PNS;</option><option value="KP.630">KP.630 - ss. Surat Pernyataan Pengunduran Diri Dari Jabatan Organik Karena Dicalonkan Sebagai Kepala/Wakil Kepala Daerah;</option><option value="KP.640">KP.640 - Berkas Perseorangan Pejabat Negara</option><option value="KP.641">KP.641 - Kepala BPS;</option><option value="KP.642">KP.642 - Pejabat Negara Lain yang ditentukan oleh Undang-Undang;</option><option value="KP.650">KP.650 - Surat Perintah Dinas/Surat Tugas</option><option value="KP.660">KP.660 - Berkas Cuti Pegawai:</option><option value="KP.661">KP.661 - Cuti Sakit.</option><option value="KP.662">KP.662 - Cuti Bersalin.</option><option value="KP.663">KP.663 - Cuti Tahunan.</option><option value="KP.664">KP.664 - Cuti Alasan Penting.</option><option value="KP.665">KP.665 - Cuti Luar Tanggungan Negara (CLTN).</option><option value="KP.700">KP.700 - KESEJAHTERAAN PEGAWAI</option><option value="KP.710">KP.710 - Berkas Tentang Layanan Tunjangan/Gaji.</option><option value="KP.720">KP.720 - Berkas Tentang Pemeliharaan Kesehatan Pegawai.</option><option value="KP.730">KP.730 - Berkas Tentang Layanan Asuransi Pegawai.</option><option value="KP.740">KP.740 - Berkas Tentang Bantuan Sosial.</option><option value="KP.750">KP.750 - Berkas Tentang Layanan Olahraga Dan Rekreasi.</option><option value="KP.760">KP.760 - Berkas Tentang Layanan Pengurusan Jenazah.</option><option value="KP.770">KP.770 - Berkas Tentang Layanan Organisasi Non Kedinasan (Korpri, Dharma Wanita, Koperasi)</option><option value="KP.800">KP.800 - PEMBERHENTIAN PEGAWAI TANPA HAK PENSIUN</option><option value="KP.900">KP.900 - USUL PEMBERHENTIAN DAN PENETAPAN PENSIUN PEGAWAI/JANDA/DUDA &amp; PNS YANG TEWAS</option><option value="PR">PR - PERENCANAAN</option><option value="PR.0">PR.0 - POKOK-POKOK KEBIJAKAN DAN STRATEGI PEMBANGUNAN</option><option value="PR.010">PR.010 - Pengumpulan Data.</option><option value="PR.020">PR.020 - Rencana Pembangunan Jangka Panjang (RPJP).</option><option value="PR.030">PR.030 - Rencana Pembangunan Jangka Panjang (RPJP).</option><option value="PR.040">PR.040 - Rencana Kerja Pemerintah(RKP).</option><option value="PR.050">PR.050 - Penyelenggaraan Musyawarah Perencanaan Pembangunan(Musrenbang)</option><option value="PR.100">PR.100 - PENYUSUNAN RENCANA</option><option value="PR.110">PR.110 - Rencana Kegiatan Teknis</option><option value="PR.120">PR.120 - Rencana Kegiatan Non-Teknis</option><option value="PR.130">PR.130 - Keterpaduan Rencana Teknis dan Non Teknis</option><option value="PR.200">PR.200 - PROGRAM KERJA TAHUNAN</option><option value="PR.210">PR.210 - Usulan Unit Kerja beserta data penduduknya</option><option value="PR.220">PR.220 - Program Kerja Thunan Unit Kerja</option><option value="PR.230">PR.230 - Program Kerja Tahunan Instansi/Lembaga</option><option value="PR.300">PR.300 - RENCANA ANGGARAN PENDAPATAN DAN BELANJA NEGARA (RAPBN)</option><option value="PR.310">PR.310 - Penyusunan RAPBN</option><option value="PR.311">PR.311 - Arah kebijakan umum, strategi, prioritas, dan renstra:</option><option value="PR.311">PR.311 - a. Rencana kerja</option><option value="PR.311">PR.311 - b. Rencana kerja pemerintah</option><option value="PR.312">PR.312 - Rencana kerja dan anggaran kementrian lembaga (RKAKL)</option><option value="PR.313">PR.313 - Lembaga satuan anggaran per satuan kerja (SAPSKI), satuan rincian alokasi anggaran</option><option value="PR.320">PR.320 - Penyampaian APBN kepada DPR RI</option><option value="PR.321">PR.321 - Nota keuangan pemerintah dan rancangan Undang-Undang RAPBN:</option><option value="PR.321">PR.321 - a. Nota keuangan pemerintah;</option><option value="PR.321">PR.321 - b. Materi RAPBN dari lembaga negara dan badan pemerintah (RASKIP)</option><option value="PR.322">PR.322 - Pembahasan RAPBN oleh komisi DPR RI</option><option value="PR.323">PR.323 - Risalah rapat dengar pendapat dengan DPR RI</option><option value="PR.324">PR.324 - Nota jawaban DPR RI</option><option value="PR.330">PR.330 - Undang-Undang anggaranpendapatan dan belanja negara (APBN) dan rencana pembangunan tahunan (REPETA)</option><option value="PR.400">PR.400 - PENYUSUNAN ANGGARN PENDAPATAN NEGARA (APBN)</option><option value="PR.410">PR.410 - Ketetapan pagu indikatif/pagu sementara</option><option value="PR.420">PR.420 - ketetapan pagu definitif</option><option value="PR.430">PR.430 - Rencana kerja anggaran (RKA) lembaga negara dan badan pemerintah (LNBP)</option><option value="PR.440">PR.440 - Daftar isian pelaksanaan anggaran (DIPA) dan revisinya</option><option value="PR.450">PR.450 - Petunjuk operasional kegiatan (POK) dan revisinya</option><option value="PR.460">PR.460 - Petunjuk teknis tata laksana keterpaduan kegiatan dan pengelolaan anggaran</option><option value="PR.470">PR.470 - Target penerimaan negara bukan pajak</option><option value="PR.500">PR.500 - PENYUSUNAN STANDAR HARGA MONITORING PROGRAM</option><option value="PR.510">PR.510 - Pedoman pengumpulan dan pengolahan data standar harga</option><option value="PR.520">PR.520 - Pedoman teknis monitoring program dan kegiatan</option><option value="PR.530">PR.530 - Pedoman teknis evaluasi dan pelaporan program</option><option value="PR.600">PR.600 - LAPORAN</option><option value="PR.610">PR.610 - Laporan khusus</option><option value="PR.611">PR.611 - Pemantau prioritas</option><option value="PR.612">PR.612 - Laporan pelaksanaan kegiatan atas permintaan eksternal</option><option value="PR.613">PR.613 - Laporan atas pelaksanaan kegiatan/program tertentu</option><option value="PR.614">PR.614 - Rapat dengar pendapat dengan DPR RI</option><option value="PR.620">PR.620 - Laporan progress report</option><option value="PR.630">PR.630 - Laporan akuntabilitas kinerja instansi pemerintah (LAKIP)</option><option value="PR.640">PR.640 - Laporan berkala (harian,mingguan,triwulanan,semesteran,tahunan)</option><option value="PR.700">PR.700 - EVALUASI PROGRAM</option><option value="PR.710">PR.710 - Evaluasi program unit kerja</option><option value="PR.720">PR.720 - Evaluasi program lembaga/instansi</option><option value="HK.000">HK.000 - PROGRAM LEGILASI</option><option value="HK.010">HK.010 - Bahan/materi program legilasi nasional dan instansi</option><option value="HK.020">HK.020 - Program legilasi lembaga/instansi</option><option value="HK.100">HK.100 - PERATURAN PIMPINAN LEMBAGA/INSTANSI</option><option value="HK.110">HK.110 - Peraturan kepala BPS</option><option value="HK.200">HK.200 - KEPUTUSAN/KETETAPAN PIMPINAN LEMBAGA/INSTANSI Termasuk rancangan awal sampai dengan rancangan akhir dan telaah hukum</option><option value="HK.300">HK.300 - INSTRUKSI SURAT EDARAN Termasuk rancangan awal sampai dengan rancangan akhir dan telaah hukum</option><option value="HK.310">HK.310 - Istruksi/surat edaran kepala BPS</option><option value="HK.320">HK.320 - Instruksi/Surat edaran pejabat tinggi madya dan pejabat tinggi pratama</option><option value="HK.400">HK.400 - SURAT PERINTAH</option><option value="HK.410">HK.410 - Surat perintah kepala BPS</option><option value="HK.420">HK.420 - Surat perintah pejabat madya</option><option value="HK.430">HK.430 - Surat perintah pejabat pratama</option><option value="HK.500">HK.500 - PEDOMAN. Standar/pedoman/prosedur kerja/petunjuk pelaksanaan/petunjuk teknis yang bersifat nasional/regional/instansional termasuk rancangan awal sampai dengan rancangan akhir</option><option value="HK.600">HK.600 - NOTA KESEPAHAMAN</option><option value="HK.610">HK.610 - Dalam negeri</option><option value="HK.620">HK.620 - Luar negeri</option><option value="HK.700">HK.700 - DOKUMENTASI HUKUM. Undang-Undang peraturan pemerintah, keputusan presiden, dan peraturan-peraturan lain diluar produk hukum BPS yang dijadikan referensi</option><option value="HK.800">HK.800 - SOSIALISASI/PENYULUHAN/PEMBINAAN HUKUM</option><option value="HK.810">HK.810 - Berkas yang berhubungan dengan kegiatan sosialisasi atau penyuluhan hukum</option><option value="HK.820">HK.820 - Laporan hasil pelaksanaan sosialisasi penyukuhan hukum</option><option value="HK.900">HK.900 - BANTUAN KONSULTASI HUKUM/ADVOKASI. Berkas tentang pemberian bantuan/konsultasi hukum (pidana, perdata, tata usaha negara, dan agama)</option><option value="HK.1010">HK.1010 - KASUS/SENGKETA HUKUM</option><option value="HK.1010">HK.1010 - Pidana Berkas tentang kasus/sengketa pidana, baik kejahatan maupun pelanggaran:</option><option value="HK.1011">HK.1011 - Proses verbal mulai dari penyelidikan, penyidikan sampai dengan vonis</option><option value="HK.1012">HK.1012 - Berkas pembelaan dan bantuan hukum</option><option value="HK.1013">HK.1013 - Telaah hukum dan opini terbuka</option><option value="HK.1020">HK.1020 - Perdata</option><option value="HK.1021">HK.1021 - Proses gugatan sampai dengan putusan</option><option value="HK.1022">HK.1022 - Berkas pembelaan dan bantuan hukum</option><option value="HK.1023">HK.1023 - Telaah hukum dan opini hukum</option><option value="HK.1030">HK.1030 - Tata Usaha Negara</option><option value="HK.1031">HK.1031 - Proses gugatan sampai dengan putusan</option><option value="HK.1032">HK.1032 - Berkas pembelaan dan bantuan hukum</option><option value="HK.1033">HK.1033 - Telaah hukum dan opini hukum</option><option value="HK.1040">HK.1040 - Arbitrase</option><option value="HK.1041">HK.1041 - Proses gugatan sampai dengan putusan</option><option value="HK.1042">HK.1042 - Berkas pembelaan dan bantuan hukum</option><option value="HK.1043">HK.1043 - Telaah hukum dan opini hukum</option><option value="OT.000">OT.000 - ORGANISASI</option><option value="OT.010">OT.010 - Pembentukan organisasi</option><option value="OT.020">OT.020 - Pengubahan organisasi</option><option value="OT.030">OT.030 - Pembubaran organisasi</option><option value="OT.040">OT.040 - Evaluasi kelembagaan: Meliputi naskah-naskah yang berkaitan dengan penilaian dan penyempurnaan organisasi</option><option value="OT.050">OT.050 - Uraian Jabatan : Meliputi hal-hal yang berkenaan dengan masalah klasifikasi kepegawaian/pekerjaan,penelitian,jabatan dan analisa jabatan</option><option value="OT.100">OT.100 - TATA LAKSANA</option><option value="OT.110">OT.110 - Standar kompetensi Jabatan Struktual dan fungsional. Meliputi hal-hal yang berkenaan dengan standar kompetensi jabatan struktual dan jabatan fungsional</option><option value="OT.120">OT.120 - Tata Hubungan Kerja : Meliputi hal-hal berkenaan dengan masalah penyusunan tata hubungan kerja baik intern maupun ekstern BPS</option><option value="OT.130">OT.130 - Sistem dan Prosedur : Meliputi hal-hal berkenaan dengan masalah penelahan tata cara dan metode kegiatan dibidang perstatistikan</option><option value="HM.000">HM.000 - KEPROTOKOLAN</option><option value="HM.010">HM.010 - Penyelenggarakan acara kedinasan (Upacara,pelantikan,peresmian dan jamuan termasuk acara peringatan hari-hari besar)</option><option value="HM.020">HM.020 - Agenda kegiatan pimpinan</option><option value="HM.030">HM.030 - Kunjungan Dinas</option><option value="HM.031">HM.031 - Kunjungan dinas dalam dan luar negeri</option><option value="HM.032">HM.032 - Kunjungan dinas pimpinan lembaga/instansi</option><option value="HM.033">HM.033 - Kunjungan dinas pejabat lembaga/instansi</option><option value="HM.040">HM.040 - Buku tamu</option><option value="HM.050">HM.050 - daftar nama/alamat kantor/pejabat</option><option value="HM.100">HM.100 - LIPUTAN MEDIA MASSA. Liputan kegiatan dinas pimpinan acara kedinasan dan peristiwa-peristiwa bidang masing-masing yang diinput oleh media massa (cetak dan elektronik)</option><option value="HM.200">HM.200 - PENYAJIAN INFORMASI KELEMBAGAAN. Penyajian informasi kelembagaan,pengumpulan,pengelolahan dan penyajian informasi kelembagaan</option><option value="HM.210">HM.210 - Kliping koran.</option><option value="HM.220">HM.220 - Penerbitan majalah,buletin,koran dan jurnal</option><option value="HM.230">HM.230 - Brosur/leaflet/poster/plakat</option><option value="HM.240">HM.240 - Pengumuman/pemberitaan</option><option value="HM.300">HM.300 - HUBUNGAN ANTAR LEMBAGA</option><option value="HM.310">HM.310 - Hubungan antar lembaga pemerintah</option><option value="HM.320">HM.320 - Hubungan dengan organisasi sosial/LSM</option><option value="HM.330">HM.330 - Hubungan dengan perusahaan</option><option value="HM.340">HM.340 - Hubungan dengan peguruan tinggi/sekolah : magang,pendidikan sistem ganga, praktek kerja lapangan (PKL)</option><option value="HM.350">HM.350 - Forum Kehumasan (Bakohumas/Perhumas)</option><option value="HM.360">HM.360 - Hubungan dengan media massa:</option><option value="HM.360">HM.360 - a. Siaran pers/konferensi pers/pers realease</option><option value="HM.360">HM.360 - b. Kunjungan wartawan/peliputan</option><option value="HM.360">HM.360 - c. Wawanacara</option><option value="HM.400">HM.400 - DENGAR PENDAPAT/HEARING DPR</option><option value="HM.500">HM.500 - PENYIAPAN BAHAN MATERI PIMPINAN</option><option value="HM.600">HM.600 - PUBLIKASI MELALUI MEDIA CETAK MAUPUN ELEKTRONIK</option><option value="HM.700">HM.700 - PAMERAN/SAYEMBARA/LOMBA/FESTIVAL,PEMBUATAN SPANDUK DAN IKLAN</option><option value="HM.800">HM.800 - PENGHARGAAN/KENANG-KENAGAN/CINDERA MATA</option><option value="HM.900">HM.900 - UCAPAN TERIMA KASIH,UCAPAN SELAMAT,BELA SUNGKAWA,PERMOHONAN MAAF</option><option value="KA.000">KA.000 - PENCETAKAN</option><option value="KA.010">KA.010 - Penyiapan pembuatan buku kerja dan kalender BPS,</option><option value="KA.020">KA.020 - Penerimaan permintaan mencetak dan naskah yang akan dicetak</option><option value="KA.030">KA.030 - Menyusunan perencanaaan cetak</option><option value="KA.040">KA.040 - Pencetakan naskah,surat,dokumen secara digital dan risograph</option><option value="KA.100">KA.100 - PENGURUSAN SURAT</option><option value="KA.110">KA.110 - Surat masuk/surat keluar</option><option value="KA.120">KA.120 - Penggunaan aplikasi surat menyurat</option><option value="KA.200">KA.200 - PENGELOLAAN ARSIP DINAMIS</option><option value="KA.210">KA.210 - Penyusunan sisitem arsip</option><option value="KA.220">KA.220 - Penciptaan dan pemberkasan arsip</option><option value="KA.230">KA.230 - Pengelolahan data base menjadi informasi</option><option value="KA.240">KA.240 - Alih media</option><option value="KA.300">KA.300 - PENYIMPANGAN DAN PEMELIHARAAN ARSIP</option><option value="KA.310">KA.310 - Daftar arsip</option><option value="KA.320">KA.320 - Pemeliharan arsip dan ruang penyimpanan (seperti kegiatan fumigasi)</option><option value="KA.330">KA.330 - daftar pencarian arsip</option><option value="KA.340">KA.340 - daftar arsip informasi publik</option><option value="KA.350">KA.350 - Daftar arsip vital/aset</option><option value="KA.360">KA.360 - Layanan arsip (pemindahan,pengguna arsip)</option><option value="KA.370">KA.370 - Persetujuan jadwal retensi arsip</option><option value="KA.400">KA.400 - PEMINDAHAN ARSIP</option><option value="KA.410">KA.410 - Pemindahan Arsip Inaktif</option><option value="KA.420">KA.420 - Daftar Arsip yang Dimusnahkan</option><option value="KA.500">KA.500 - PEMUSNAHAN ARSIP YANG TIDAK BERNILAI GUNA</option><option value="KA.510">KA.510 - Berita Acara Pemusnahan</option><option value="KA.520">KA.520 - Daftar Arsip yang Dimusnahkan</option><option value="KA.530">KA.530 - Rekomendasi/Pertimbangan/Permusnahan Arsip dari ANRI</option><option value="KA.540">KA.540 - Surat Keputusan Pemusnahan</option><option value="KA.600">KA.600 - PENYERAHAN ARSIP STATIS</option><option value="KA.610">KA.610 - Berita Acara Serah Terima Arsip</option><option value="KA.620">KA.620 - Daftar Arsip yang Diserahkan</option><option value="KA.700">KA.700 - PEMBINAAN KEARSIPAN</option><option value="KA.710">KA.710 - Pembinaan Arsiparis</option><option value="KA.720">KA.720 - Aresiasi/Sosialisasi/Penyeluruhan Kearsipan,Diklat Profesi</option><option value="KA.730">KA.730 - Bimbingan Teknis</option><option value="KA.740">KA.740 - Penilaian dan sertifikasi SDM kearsipan</option><option value="KA.750">KA.750 - Supervisi dan Monitoring</option><option value="KA.760">KA.760 - penilaian Akreditasi Unit Kerja Kearsipan</option><option value="KA.770">KA.770 - Implementasi Pengelolaan Arsip Elektronik</option><option value="KA.780">KA.780 - Penghargaan Kearsipan</option><option value="KA.790">KA.790 - Pengawasan Kearsipan</option><option value="RT.000">RT.000 - TELEKOMUNIKASI. Administrasi penggunaan/langganan peralatan telekomunikasi meliputi: telepon,radio,telek,TV kabel dan internet</option><option value="RT.100">RT.100 - ADMINISTRASI PENGGUNAAN FASILITAS KANTOR. Administrasi penggunaan fasilitas kantor meliputi permintaan dan penggunaan ruang, gedung, kendaraan, wisma rumah dinas, dan fasilitas kantor lainnya</option><option value="RT.200">RT.200 - PENGURUSAN KENDARAAN DINAS</option><option value="RT.210">RT.210 - Pengurusan Surat kendaraan Dinas</option><option value="RT.220">RT.220 - Pemeliharaan dan Perbaikan</option><option value="RT.230">RT.230 - Pengurusan Kehilangan dan Masalah Kendaraan</option><option value="RT.300">RT.300 - PEMELIHARAAN GEDUNG DAN TAMAN</option><option value="RT.310">RT.310 - Pertamanan/Lanscaping</option><option value="RT.320">RT.320 - Penghijauan</option><option value="RT.330">RT.330 - Perbaiki Gedung</option><option value="RT.340">RT.340 - Perbaiki Rumah Dinas/Wisma</option><option value="RT.350">RT.350 - Kebersihan Gedung dan Taman</option><option value="RT.400">RT.400 - PENGELOLAAN JARINGAN LISTRIK,AIR,TELEPON,DAN KOMPUTER</option><option value="RT.410">RT.410 - Perbaikan/Pemeliharaan</option><option value="RT.420">RT.420 - Pemasangan</option><option value="RT.500">RT.500 - KETERTIBAN DAN KEAMANAN</option><option value="RT.510">RT.510 - Pengamanan,penjagaan dan pengawasan terhadap pejabat,kantor,dan rumah dinas:</option><option value="RT.511">RT.511 - Daftar Nama Satuan Pengamanan</option><option value="RT.512">RT.512 - Daftar Jaga/Daftar Piket</option><option value="RT.513">RT.513 - Surat Ijin Keluar Masuk Orang atau Barang</option><option value="RT.520">RT.520 - Laporan Ketertiban dan Keamanan</option><option value="RT.521">RT.521 - Kehilangan,kerusakan,kecelakaan,gangguan</option><option value="RT.600">RT.600 - ADMINISTRASI PENGELOLAAN PARKIR</option><option value="PL.000">PL.000 - Rencana Kebutuhan Barang dan Jasa</option><option value="PL.010">PL.010 - Usulan Unit Kerja</option><option value="PL.020">PL.020 - Rencana Kebutuhan Lembaga Pusat/Daerah</option><option value="PL.100">PL.100 - Berkas Perkenalan</option><option value="PL.200">PL.200 - Pengadaan Barang</option><option value="PL.210">PL.210 - Pengadaan/pembelian barang tidak melalui lelang(pengadaan langsung) Usulan Unit Kerja,Proses pengadaan barang,Serah terima barang</option><option value="PL.220">PL.220 - Pengadaan/pembelian barang melalui lelang, - Umum, - Pemilihan Langsung, - Serah terima barang</option><option value="PL.230">PL.230 - Perolehan barang melalui bantuan/hibah</option><option value="PL.240">PL.240 - Pengadaan barang melalui tukar menukar</option><option value="PL.250">PL.250 - Pemanfaatan barang melalui pinjam pakai</option><option value="PL.260">PL.260 - Pemanfaatan barang melalui sewa</option><option value="PL.270">PL.270 - Pemanfaatan barang melalui kerjasama pemanfaatan</option><option value="PL.280">PL.280 - Pemanfaatan barang melalui bangun serah guna/bangun serah guna</option><option value="PL.300">PL.300 - Pengadaan Jasa Berkas pengadaan jasa oleh pihak ketiga terdiri dari berkas penawaran sampai dengan kontrak perjanjian</option><option value="PL.400">PL.400 - Laporan kemajuan pelaksanaan belanja modal</option><option value="PL.500">PL.500 - INVENTARISASI</option><option value="PL.510">PL.510 - Inventarisasi Ruangan/Gedung/Bangunan</option><option value="PL.021">PL.021 - Daftar Opname Fisik Barang Inventaris(DOFBI)</option><option value="PL.022">PL.022 - Daftar Inventaris Barang(DIB)</option><option value="PL.023">PL.023 - Daftar Kartu Inventaris Barang</option><option value="PL.024">PL.024 - Buku Inventaris/pembantu Inventaris Barang</option><option value="PL.530">PL.530 - Penyusunan Laporan Tahunan Inventaris BMN</option><option value="PL.540">PL.540 - Sensus BMN</option><option value="PL.600">PL.600 - PENYIMPANAN</option><option value="PL.610">PL.610 - Penatausahaan Penyimpanan Barng/Publikasi :</option><option value="PL.611">PL.611 - Tanda terima/surat pengantar/surat pengiriman barang</option><option value="PL.612">PL.612 - Surat pernyataan harga dan mutu barang</option><option value="PL.613">PL.613 - Berita acara serah terima barang hasil pengadaan</option><option value="PL.614">PL.614 - Buku penerimaan</option><option value="PL.615">PL.615 - Buku persediaan barang/kartu stock barang</option><option value="PL.616">PL.616 - Kartu barang/kartu gudang</option><option value="PL.620">PL.620 - Penyusunan laporan persediaan barang</option><option value="PL.700">PL.700 - PENYALURAN</option><option value="PL.710">PL.710 - Penatausahaan penyaluran barang/publikasi</option><option value="PL.711">PL.711 - Surat permintaan dari unit kerja/formulir permintaan</option><option value="PL.712">PL.712 - Surat perintah mengeluarkan barang (SPMB)</option><option value="PL.713">PL.713 - Surat perintah mengeluarkan barang inventaris</option><option value="PL.714">PL.714 - Berita acara serah terima distribusi barang</option><option value="PL.800">PL.800 - PENGHAPUSAN BMN</option><option value="PL.810">PL.810 - Penghapusan barang bergerak/barang inventaris kantor , -Nota usulan penghapusan, - Surat pembentukan panitia pengahapusan, -Berita acara pemeriksaan/penelitian barang yang akan dihapus, -Surat izin/keputusan pengahapusan barang, -Surat keutusan penghapusan barang, -Surat keputusan panitia lelang, - Risalah lelang, - Berita acaca dan laporan tidak lanjut penghapusan</option><option value="PL.900">PL.900 - BUKTI-BUKTI KEPEMILIKAN DAN KELENGKAPAN BMN , a. Master Plan bangunan, b. sertifikat tanah, c.ijin mendirikan bangunan (IMB), d. Bukti kepemilikan kendaraan bermotor (BPKB), e. Surat tanda nomor kendaraan (STNK), f. denah/gambar bangunan/instalasi listrik,air dan gas</option><option value="DL.000">DL.000 - PERENCANAAN DIKLAT</option><option value="DL.010">DL.010 - Analisa kebutuhan penyelenggaraan diklat</option><option value="DL.020">DL.020 - Sistem dan metode</option><option value="DL.030">DL.030 - kurikulim</option><option value="DL.040">DL.040 - Bahan ajar/modul</option><option value="DL.050">DL.050 - Konsultasi penyelenggaraan diklat</option><option value="DL.100">DL.100 - AKREDITASI LEMBAGA DIKLAT</option><option value="DL.110">DL.110 - Surat permohonan akreditasi</option><option value="DL.120">DL.120 - laporan hasil verifikasi lapangan</option><option value="DL.130">DL.130 - Berita acara rapat verifikasi</option><option value="DL.140">DL.140 - Berita acara raoat tim penilai</option><option value="DL.150">DL.150 - Surat keputusan penetapan akreditasi</option><option value="DL.160">DL.160 - Surat akreditasi</option><option value="DL.170">DL.170 - Laporan akreditasi lembaga diklat</option><option value="DL.200">DL.200 - PENYELENGGARAKAN DIKLAT</option><option value="DL.210">DL.210 - Prajabatan</option><option value="DL.220">DL.220 - Kepemimpinan</option><option value="DL.230">DL.230 - Teknis</option><option value="DL.240">DL.240 - Fungsional</option><option value="DL.250">DL.250 - Evaluasi pasca diklat</option><option value="DL.300">DL.300 - SERTIFIKAT SUMBERDAYA MANUSIA KEDIKLATAN :Naskah-naskah yang berkaitan dengan kegiatan sertifikat sumberdaya kediklatan</option><option value="DL.400">DL.400 - SISTEM INFORMASI DIKLAT</option><option value="DL.410">DL.410 - Data lembaga diklat</option><option value="DL.420">DL.420 - Data prasarana diklat</option><option value="DL.430">DL.430 - Data sarana diklat</option><option value="DL.440">DL.440 - Data pengelola diklat</option><option value="DL.450">DL.450 - Data penyelenggara diklat</option><option value="DL.460">DL.460 - Data widyaiswara</option><option value="DL.470">DL.470 - Data program diklat</option><option value="DL.500">DL.500 - REGISTRASI SERTIFIKAT/STTPL PESERTA DIKLAT</option><option value="DL.510">DL.510 - Surat permohonan kode registrasi</option><option value="DL.520">DL.520 - Buku Registrasi</option><option value="DL.530">DL.530 - Surat penyampaian kode registrasi</option><option value="DL.600">DL.600 - EVALUASI PENYELENGGARAAN DIKLAT</option><option value="DL.610">DL.610 - Evaluasi materi penyenggaraan</option><option value="DL.620">DL.620 - Evaluasi pengajar/instruktur/fasilitator</option><option value="DL.630">DL.630 - Evaluasi peserta</option><option value="DL.640">DL.640 - Evaluasi sarana dan prasarana</option><option value="DL.650">DL.650 - Evaluasi alumni peserta</option><option value="DL.660">DL.660 - Laporan penyelenggaran</option><option value="DL.670">DL.670 - Evaluasi alumni diklat</option><option value="PW.000">PW.000 - RENCANA PENGAWASAN</option><option value="PW.010">PW.010 - Rencana strategis pengawasan</option><option value="PW.020">PW.020 - Rencana kerja tahunan</option><option value="PW.030">PW.030 - Rencana kinerja tahunan</option><option value="PW.040">PW.040 - Penetepan kinerja tahunan</option><option value="PW.050">PW.050 - Rakor pengawasan tingkat nasional</option><option value="PW.100">PW.100 - PELAKSANAAN PENGAWASAN</option><option value="PW.110">PW.110 - Naskah-naskah yang berkaitan dengan audit mulai dari surat penugasan sampai dengan surat menyurat</option><option value="PW.120">PW.120 - Laporan hasil audit (LHA),laporan hasil pemeriksaan operasional(LHPO),Laporan hasil evaluasi(LHE),Laporan akuntan (LA), laporan auditor indendent (LAI) yang memerlukan tindak lanjut (TL)</option><option value="PW.130">PW.130 - Laporan hasil audit investigasi (LHAI), Laporan hasil pemeriksaan operasionnal(LHPO), laporan hasil evaluasi (LHE), laporan akuntan (LA),Laporan auditor independent (LAI) yang memerlukan tindak lanjut (TL)</option><option value="PW.140">PW.140 - Laporan perkembangan penanganan surat pengaduan masyarakat</option><option value="PW.150">PW.150 - Laporan pemutakhitsn data</option><option value="PW.160">PW.160 - Laporan perkembangan BMN</option><option value="PW.170">PW.170 - Laporan kegiatan pendampingan penyusunan laporan keuangan dan reviu departmen/LPND</option><option value="PW.180">PW.180 - Good corporate governance (GCG)</option><option value="TS.000">TS.000 - PENYUSUNAN RENCANA KEGIATAN BIDANG TRANSFORMASI STATISTIK (TOR)</option><option value="TS.010">TS.010 - Transformasi proses bisnis statistik</option><option value="TS.020">TS.020 - Transformasi TIK dan Komunikasi</option><option value="TS.030">TS.030 - Transformasi manajemen sumberdaya manusia dan kelembagaan</option><option value="TS.100">TS.100 - PENYUSUNAN BAHAN TERKAIT TRANSFORMASI STATISTIK</option><option value="TS.110">TS.110 - Rencana Sarana dan Prasarana Transformasi Statistik</option><option value="TS.120">TS.120 - Statistical Business Frame Work and Architecture (SBFA)</option><option value="TS.130">TS.130 - Analysis Document</option><option value="TS.140">TS.140 - CSI</option><option value="TS.150">TS.150 - BPR</option><option value="TS.160">TS.160 - Sosialisasi, internalisasi, workshop terkait kegiatan transformasi</option><option value="TS.170">TS.170 - Deliverables</option><option value="TS.200">TS.200 - MANAJEMEN PERUBAHAN</option><option value="TS.210">TS.210 - Steering Committee (Dewan Pengarah)</option><option value="TS.220">TS.220 - Change Agent</option><option value="TS.230">TS.230 - Change Leader</option><option value="TS.240">TS.240 - Change Champion</option><option value="TS.250">TS.250 - Working Group</option><option value="TS.260">TS.260 - Evaluasi dan Monitoring Perkembangan Program STATCAP CERDAS; Sensus Daring/CPOC</option><option value="TS.270">TS.270 - Sosialisasi, Internalisasi, Workshop terkait kegiatan Manajemen Perubahan, Kick of Meeting</option><option value="TS.300">TS.300 - KETERPADUAN TRANSFORMASI</option><option value="TS.310">TS.310 - Mendukung Implementasi Transformasi: CAPI SAKERNAS, Continous Survey</option><option value="TS.400">TS.400 - LAPORAN TRANSFORMASI STATISTIK</option><option value="TS.410">TS.410 - Laporan Kemajuan</option><option value="TS.420">TS.420 - Laporan Bulanan</option><option value="TS.430">TS.430 - Laporan Triwulan</option><option value="TS.440">TS.440 - Laporan Tahunan</option>';
    </script>
@endsection
