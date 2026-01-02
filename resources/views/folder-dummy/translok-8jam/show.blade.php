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
                    {{-- PERUBAHAN 1: Action ke Update dan Method PUT --}}
                    <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="jenis_kak" value="translok-biasa" />

                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Detail KAK Translok Biasa</div>
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
                                                        class="form-control" readonly
                                                        value="{{ old('singkatan_resmi', $kegiatan->singkatan_resmi) }}" />
                                                    @if ($errors->has('singkatan_resmi'))
                                                        <small
                                                            class="form-text text-muted">{{ $errors->first('singkatan_resmi') }}</small>
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
                                                    <textarea name="kak1_latar_belakang" id="kak1_latar_belakang" rows="10" class="form-control" readonly
                                                        placeholder="Masukkan latar belakang di sini">{{ old('kak1_latar_belakang', $kegiatan->kak1_latar_belakang) }}</textarea>
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

                                                    <select name="kak2_maksud" id="kak2_maksud" class="form-control"
                                                        disabled>
                                                        <option value="">( Pilih salah satu )</option>
                                                        <option value="pengawasan"
                                                            {{ old('kak2_maksud', $kegiatan->kak2_maksud) == 'pengawasan' ? 'selected' : '' }}>
                                                            Pengawasan</option>
                                                        <option value="supervisi"
                                                            {{ old('kak2_maksud', $kegiatan->kak2_maksud) == 'supervisi' ? 'selected' : '' }}>
                                                            Supervisi</option>
                                                        <option value="pendataan"
                                                            {{ old('kak2_maksud', $kegiatan->kak2_maksud) == 'pendataan' ? 'selected' : '' }}>
                                                            Pendataan</option>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="kak2_tujuan">Tujuan pengadaan ini adalah untuk pembayaran
                                                        transport lokal
                                                    </label>
                                                    <select name="kak2_tujuan" id="kak2_tujuan" class="form-control"
                                                        disabled>
                                                        <option value="">( Pilih salah satu )</option>
                                                        <option value="mitra"
                                                            {{ old('kak2_tujuan', $kegiatan->kak2_tujuan) == 'mitra' ? 'selected' : '' }}>
                                                            Mitra</option>
                                                        <option value="organik"
                                                            {{ old('kak2_tujuan', $kegiatan->kak2_tujuan) == 'organik' ? 'selected' : '' }}>
                                                            Organik</option>
                                                    </select>
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
                                                    <label for="kak3_target" id="label_kak3_target">Mitra/Sampel yang
                                                        diawasi/didata sejumlah
                                                    </label>
                                                    <input type="number" name="kak3_target" id="kak3_target"
                                                        class="form-control" readonly
                                                        value="{{ old('kak3_target', $kegiatan->kak3_target) }}" />
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
                                                                name="tgl_mulai" readonly
                                                                value="{{ old('tgl_mulai', $kegiatan->tgl_mulai) }}" />
                                                        </div>
                                                        <div class="col">
                                                            <label for="tgl_selesai">sampai tanggal</label>
                                                            <input type="date" class="form-control" id="tgl_selesai"
                                                                name="tgl_selesai" readonly
                                                                value="{{ old('tgl_selesai', $kegiatan->tgl_selesai) }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <label for="kak4_pjk">dengan penanggung jawab kegiatan </label>
                                                    <input type="text" name="kak4_pjk" id="kak4_pjk"
                                                        class="form-control" readonly
                                                        value="{{ old('kak4_pjk', $kegiatan->kak4_pjk) }}" />
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
                                            <p>Transport lokal sesuai SK Kepala BPS Kabupaten Simeulue...</p>
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
                                                    {{-- 1. PROGRAM --}}
                                                    <div class="mb-2">
                                                        <label for="kak6_program">Program</label>
                                                        <select name="kak6_program" id="kak6_program"
                                                            class="form-control" disabled>
                                                            <option value="">(Pilih Program)</option>
                                                            @foreach ($pok_awals as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $kegiatan->kak6_program == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->kode_program }} - {{ $item->uraian }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    {{-- 2. AKTIVITAS --}}
                                                    <div class="mb-2">
                                                        <label for="kak6_aktivitas">Aktivitas</label>
                                                        <select name="kak6_aktivitas" id="kak6_aktivitas"
                                                            class="form-control" disabled>
                                                            @if ($kegiatan->pokAktivitas)
                                                                <option value="{{ $kegiatan->kak6_aktivitas }}" selected>
                                                                    {{ $kegiatan->pokAktivitas->kode_aktivitas }} -
                                                                    {{ $kegiatan->pokAktivitas->uraian }}
                                                                </option>
                                                            @else
                                                                <option value="">(Pilih Aktivitas)</option>
                                                            @endif
                                                        </select>
                                                    </div>

                                                    {{-- 3. KRO --}}
                                                    <div class="mb-2">
                                                        <label for="kak6_kro">Klasifikasi Rincian Output</label>
                                                        <select name="kak6_kro" id="kak6_kro" class="form-control"
                                                            disabled>
                                                            @if ($kegiatan->pokKro)
                                                                <option value="{{ $kegiatan->kak6_kro }}" selected>
                                                                    {{ $kegiatan->pokKro->kode_klasifikasi_rincian_output }}
                                                                    - {{ $kegiatan->pokKro->uraian }}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    {{-- 4. RO --}}
                                                    <div class="mb-2">
                                                        <label for="kak6_ro">Rincian Output</label>
                                                        <select name="kak6_ro" id="kak6_ro" class="form-control"
                                                            disabled>
                                                            @if ($kegiatan->pokRo)
                                                                <option value="{{ $kegiatan->kak6_ro }}" selected>
                                                                    {{ $kegiatan->pokRo->kode_rincian_output }} -
                                                                    {{ $kegiatan->pokRo->uraian }}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>

                                                    {{-- 5. KOMPONEN --}}
                                                    <div class="mb-2">
                                                        <label for="kak6_komponen">Klasifikasi Komponen</label>
                                                        <select name="kak6_komponen" id="kak6_komponen"
                                                            class="form-control" disabled>
                                                            @if ($kegiatan->pokKomponen)
                                                                <option value="{{ $kegiatan->kak6_komponen }}" selected>
                                                                    {{ $kegiatan->pokKomponen->kode_komponen }} -
                                                                    {{ $kegiatan->pokKomponen->uraian }}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>

                                                    {{-- 6. SUB KOMPONEN --}}
                                                    <div class="mb-2">
                                                        <label for="kak6_sub_komponen">Klasifikasi Sub Komponen</label>
                                                        <select name="kak6_sub_komponen" id="kak6_sub_komponen"
                                                            class="form-control" disabled>
                                                            @if ($kegiatan->pokSubKomponen)
                                                                <option value="{{ $kegiatan->kak6_sub_komponen }}"
                                                                    selected>
                                                                    {{ $kegiatan->pokSubKomponen->kode_sub_komponen }} -
                                                                    {{ $kegiatan->pokSubKomponen->uraian }}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

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
                                                                @foreach ($kegiatan->kegiatanRincian as $akun)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" name="akun_kode[]"
                                                                                value="{{ $akun->akun_id }}">
                                                                            <small>{{ $akun->pok->kode_akun . '-' . $akun->pok->uraian }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <textarea name="rincian_detail[]" class="form-control form-control-sm" rows="4" readonly required>{{ $akun->rincian }}</textarea>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="rincian_volume[]"
                                                                                class="form-control form-control-sm hitung-total input-volume"
                                                                                min="0" readonly
                                                                                value="{{ $akun->vol }}" required>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-select form-control-sm"
                                                                                name="rincian_satuan[]" disabled>
                                                                                @php $satuans = ['Dokumen','SLS','BS','Ruta','OK','OH','OB','OP','Segmen','EA','Responden','Pasar']; @endphp
                                                                                <option value="">(Pilih)</option>
                                                                                @foreach ($satuans as $s)
                                                                                    <option value="{{ $s }}"
                                                                                        {{ $akun->satuan == $s ? 'selected' : '' }}>
                                                                                        {{ $s }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="rincian_harga[]"
                                                                                class="form-control form-control-sm hitung-total input-harga"
                                                                                min="0" readonly
                                                                                value="{{ $akun->harga_satuan }}"
                                                                                required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="rincian_total[]"
                                                                                class="form-control form-control-sm input-subtotal"
                                                                                readonly value="{{ $akun->jumlah }}">
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-sm hapus-baris"
                                                                                disabled>
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr class="table-light">
                                                                    <td colspan="2">
                                                                        <select id="pilih_akun" class="form-control"
                                                                            disabled>
                                                                            <option value="">-- Pilih Sub Komponen
                                                                                Dahulu --</option>
                                                                        </select>
                                                                    </td>
                                                                    <td colspan="5">
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-sm"
                                                                            id="btn_tambah_akun" disabled>
                                                                            <i class="fa fa-plus"></i> Tambah Baris Akun
                                                                        </button>
                                                                    </td>
                                                                </tr>
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
                                                    @foreach ($kegiatan->kegiatanLampiran as $idx => $t)
                                                        <tr>
                                                            <td>
                                                                @if ($t->tipe_personil == 'pegawai')
                                                                    <span class="badge bg-info text-dark mb-1">PNS</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-warning text-dark mb-1">Mitra</span>
                                                                @endif

                                                                <select name="peserta_id[]"
                                                                    class="form-select select2-server-side input-nama"
                                                                    id="row_db_{{ $idx }}" disabled required>
                                                                    @if ($t->tipe_personil == 'pegawai')
                                                                        @foreach ($pegawais as $p)
                                                                            <option value="{{ $p->id }}"
                                                                                data-nip="{{ $p->nip }}"
                                                                                {{ $t->peserta_id == $p->id ? 'selected' : '' }}>
                                                                                {{ $p->nama }}
                                                                            </option>
                                                                        @endforeach
                                                                    @else
                                                                        @foreach ($mitras as $m)
                                                                            <option value="{{ $m->id }}"
                                                                                data-nip="{{ $m->nik }}"
                                                                                {{ $t->peserta_id == $m->id ? 'selected' : '' }}>
                                                                                {{ $m->nama }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <input type="hidden" name="tipe_peserta[]"
                                                                    value="{{ $t->tipe_peserta }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="nip[]"
                                                                    class="form-control form-control-sm text-center input-nip"
                                                                    readonly value="{{ $t->nip_nik }}">
                                                            </td>
                                                            <td>
                                                                <select name="kecamatan_tujuan[]"
                                                                    class="form-select form-select-sm input-kecamatan"
                                                                    disabled required>
                                                                    <option value="{{ $t->kec_tujuan }}" selected
                                                                        data-loaded="true">{{ $t->kec_tujuan }}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="date" name="tanggal_pelaksanaan[]"
                                                                    class="form-control form-control-sm" readonly
                                                                    value="{{ $t->tgl_pelaksanaan }}">
                                                            </td>
                                                            <td>
                                                                <select name="pcl_diawasi[]"
                                                                    class="form-select form-select-sm select2-server-side form-select-mitra"
                                                                    disabled>
                                                                    <option value="">-- Pilih --</option>
                                                                    @foreach ($mitras as $m)
                                                                        <option value="{{ $m->id }}"
                                                                            {{ $t->pcl_diawasi == $m->id ? 'selected' : '' }}>
                                                                            {{ $m->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="jml_sampel_pcl[]"
                                                                    class="form-control form-control-sm text-center"
                                                                    min="0" readonly
                                                                    value="{{ $t->jml_sampel_pcl }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="jml_sampel_diawasi[]"
                                                                    class="form-control form-control-sm text-center"
                                                                    min="0" readonly
                                                                    value="{{ $t->jml_sampel_diawasi }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="jml_ok[]"
                                                                    class="form-control form-control-sm text-center"
                                                                    min="1" readonly value="{{ $t->jml_ok }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="transport_bayar[]"
                                                                    class="form-control form-control-sm text-end input-transport input-mask-rupiah"
                                                                    readonly value="{{ $t->transport_bayar }}">
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm hapus-baris" disabled>
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
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
                                                    id="btn_tambah_pegawai" disabled>
                                                    <i class="fa fa-user-plus"></i> Tambah Pegawai
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    id="btn_tambah_mitra" disabled>
                                                    <i class="fa fa-users"></i> Tambah Mitra
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Template Opsi (Hidden) --}}
                                <div class="d-none">
                                    <select id="template_opsi_pegawai">
                                        <option value="">-- Pilih Pegawai --</option>
                                        @foreach ($pegawais as $p)
                                            <option value="{{ $p->id }}" data-nip="{{ $p->nip ?? '-' }}">
                                                {{ $p->nama }}</option>
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
                                        <div class="form-group">
                                            <label for="kak6_pembiayaan">Pembiayaan digunakan untuk transport ...</label>
                                            <input type="text" class="form-control" name="kak6_pembiayaan"
                                                id="kak6_pembiayaan" readonly
                                                value="{{ old('kak6_pembiayaan', $kegiatan->kak6_pembiayaan) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kak8_pengaju">Yang mengajukan </label>
                                            <input type="text" class="form-control" name="kak8_pengaju"
                                                id="kak8_pengaju" readonly
                                                value="{{ old('kak8_pengaju', $kegiatan->kak8_pengaju) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kak8_tgl">Tanggal Pengajuan KAK</label>
                                            <input type="date" class="form-control" name="kak8_tgl" id="kak8_tgl"
                                                readonly value="{{ old('kak8_tgl', $kegiatan->kak8_tgl) }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="id_pjk">Nama Pegawai Penanggung Jawab Kegiatan</label>
                                            <select class="form-select" id="id_pjk" name="id_pjk" disabled>
                                                <option value="">(Pilih salah satu)</option>
                                                @foreach ($pegawais as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('id_pjk', $kegiatan->id_pjk) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <p>JUDUL KERANGKA ACUAN KERJA (KAK)</p>
                                            <input type="text" name="judul_kak" id="judul_kak" class="form-control"
                                                readonly value="{{ old('judul_kak', $kegiatan->nama) }}" />
                                        </div>
                                    </div>
                                </div>

                                <hr />
                            </div>
                            <div class="card-action">
                                <a href="{{ route('kegiatan.index') }}" class="btn btn-danger"><i
                                        class="fas fa-arrow-left"></i> Kembali</a>
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
            // Init Select2
            $('#filter_sbks, #kak6_program, #kak6_aktivitas, #kak6_kro, #kak6_ro, #kak6_komponen, #kak6_sub_komponen, #kak6_akun, #kak2_maksud, #kak2_tujuan, #id_pjk')
                .select2({
                    theme: "bootstrap-5",
                    width: '100%',
                    placeholder: $(this).data('placeholder'),
                    closeOnSelect: true,
                });

            $('.select2-server-side').select2({
                theme: "bootstrap-5",
                width: '100%',
                dropdownParent: $('#tabel_transport')
            });

            hitungGrandTotal();
            hitungTotalTransport();
        });

        var rowIndex = {{ isset($kegiatan) ? $kegiatan->kegiatanLampiran->count() : 0 }};

        function hitungGrandTotal() {
            var grandTotal = 0;
            $('.input-subtotal').each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $('#grand_total').val(grandTotal);
        }

        function hitungTotalTransport() {
            var total = 0;
            $('.input-transport').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#grand_total_transport').val(total);
        }
    </script>
@endsection
