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
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">
                                    {{ $kegiatan->is_approved == 1 ? 'Reject KAK?' : 'Approve KAK?' }}
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Yakin ingin {{ $kegiatan->is_approved == 1 ? 'membatalkan persetujuan' : 'menyetujui' }} KAK
                                ini?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                                @if ($kegiatan->is_approved == 1)
                                    <form action="{{ url('kegiatan/reject', $kegiatan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger hapus-kegiatan"> <i
                                                class="fa fa-x"></i>Reject KAK</button>
                                    </form>
                                @else
                                    <form action="{{ url('kegiatan/approve', $kegiatan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success hapus-kegiatan"> <i
                                                class="fa fa-check"></i>Approve KAK</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    {{-- PERUBAHAN 1: Action ke Update dan Method PUT --}}
                    <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST">
                        @csrf

                        <input type="hidden" name="jenis_kak" value="translok-biasa" />

                        {{-- <div class="card">
                            <div class="card-header">
                                <div class="card-title">Edit KAK: {{ $kegiatan->judul_kak }}</div>
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
                                                        {{ old('filter_sbks', $kegiatan->sbks_acuan) == $item->singkatan_resmi ? 'selected' : '' }}>
                                                        {{ $item->nama_kegiatan_dan_singkatan }}</option>
                                                @endforeach
                                                <option value="LAINNYA"
                                                    {{ old('filter_sbks', $kegiatan->sbks_acuan) == 'LAINNYA' ? 'selected' : '' }}>
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
                                                        {{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) == 'updating' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="updating">
                                                        Updating
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="jenis_kegiatan"
                                                        id="pendataan" value="pendataan"
                                                        {{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) == 'pendataan' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="pendataan">
                                                        Pendataan
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="jenis_kegiatan"
                                                        id="pengolahan" value="pengolahan"
                                                        {{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) == 'pengolahan' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="pengolahan">
                                                        Pengolahan
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

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
                                                        class="form-control"
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
                                                    <textarea name="kak1_latar_belakang" id="kak1_latar_belakang" rows="10" class="form-control"
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

                                                    <select name="kak2_maksud" id="kak2_maksud" class="form-control">
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
                                                    <select name="kak2_tujuan" id="kak2_tujuan" class="form-control">
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
                                                        class="form-control"
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
                                                                name="tgl_mulai"
                                                                value="{{ old('tgl_mulai', $kegiatan->tgl_mulai) }}" />
                                                        </div>
                                                        <div class="col">
                                                            <label for="tgl_selesai">sampai tanggal</label>
                                                            <input type="date" class="form-control" id="tgl_selesai"
                                                                name="tgl_selesai"
                                                                value="{{ old('tgl_selesai', $kegiatan->tgl_selesai) }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <label for="kak4_pjk">dengan penanggung jawab kegiatan </label>
                                                    <select class="form-select" id="kak4_pjk" name="kak4_pjk">
                                                        <option value="">(Pilih salah satu)</option>
                                                        <option value="11011"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11011' ? 'selected' : '') : ($kegiatan->kak4_pjk == '11011' ? 'selected' : '') }}>
                                                            Umum</option>
                                                        <option value="11012"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11012' ? 'selected' : '') : ($kegiatan->kak4_pjk == '11012' ? 'selected' : '') }}>
                                                            Statistik Sosial</option>
                                                        <option value="11013"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11013' ? 'selected' : '') : ($kegiatan->kak4_pjk == '11013' ? 'selected' : '') }}>
                                                            Statistik Ekonomi Produksi</option>
                                                        <option value="11014"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11014' ? 'selected' : '') : ($kegiatan->kak4_pjk == '11014' ? 'selected' : '') }}>
                                                            Statistik Ekonomi Distribusi</option>
                                                        <option value="11015"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11015' ? 'selected' : '') : ($kegiatan->kak4_pjk == '11015' ? 'selected' : '') }}>
                                                            Neraca dan Analisis Statistik</option>
                                                        <option value="11016"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11016' ? 'selected' : '') : ($kegiatan->kak4_pjk == '11016' ? 'selected' : '') }}>
                                                            TI dan Pengolahan</option>
                                                        <option value="11017"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11017' ? 'selected' : '') : ($kegiatan->kak4_pjk == '11017' ? 'selected' : '') }}>
                                                            Diseminasi, Publisitas, dan Humas</option>
                                                        <option value="11018"
                                                            {{ old('kak4_pjk') ? (old('kak4_pjk') == '11018' ? 'selected' : '') : ($kegiatan->kak4_pjk == '11018' ? 'selected' : '') }}>
                                                            Pembinaan Statistik Sektoral</option>
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

                                            {{-- PERUBAHAN: Menampilkan opsi yang tersimpan (Selected) --}}
                                            {{-- Catatan: Jika user mengubah Parent, Child akan tereset via JS --}}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{-- 1. PROGRAM --}}
                                                    <div class="mb-2">
                                                        <label for="kak6_program">Program</label>
                                                        <select name="kak6_program" id="kak6_program"
                                                            class="form-control">
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
                                                            class="form-control">
                                                            @if ($kegiatan->pokAktivitas)
                                                                {{-- Tampilkan Data Terpilih (Kode + Uraian) --}}
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
                                                        <select name="kak6_kro" id="kak6_kro" class="form-control">
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
                                                        <select name="kak6_ro" id="kak6_ro" class="form-control">
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
                                                            class="form-control">
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
                                                            class="form-control">
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
                                                                {{-- PERUBAHAN: Load Existing Data --}}
                                                                @foreach ($kegiatan->kegiatanRincian as $akun)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" name="akun_kode[]"
                                                                                value="{{ $akun->pok_id }}">
                                                                            {{-- Kita tampilkan nama/kode akun. Asumsi relasi akun->uraian ada --}}
                                                                            <small>{{ $akun->pok->kode_akun . '-' . $akun->pok->uraian }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <textarea name="rincian_detail[]" class="form-control form-control-sm" rows="4" required>{{ $akun->rincian }}</textarea>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="rincian_volume[]"
                                                                                class="form-control form-control-sm hitung-total input-volume"
                                                                                min="0"
                                                                                value="{{ $akun->vol }}" required>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-select form-control-sm"
                                                                                name="rincian_satuan[]">
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
                                                                                min="0"
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
                                                                                class="btn btn-danger btn-sm hapus-baris">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
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
                                                    {{-- PERUBAHAN: Load Existing Transport Data --}}
                                                    {{-- Asumsi relasi di model Kegiatan adalah 'transports' --}}
                                                    @foreach ($kegiatan->kegiatanLampiran as $idx => $t)
                                                        <tr>
                                                            <td>
                                                                @if ($t->tipe_personil == 'pegawai')
                                                                    <span class="badge bg-info text-dark mb-1">PNS</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-warning text-dark mb-1">Mitra</span>
                                                                @endif

                                                                {{-- Select2 Dinamis (Server Side Rendered) --}}
                                                                <select name="peserta_id[]"
                                                                    class="form-select select2-server-side input-nama"
                                                                    id="row_db_{{ $idx }}" required>
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
                                                                    value="{{ $t->tipe_personil }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="nip[]"
                                                                    class="form-control form-control-sm text-center input-nip"
                                                                    readonly value="{{ $t->nip_nik }}">
                                                            </td>
                                                            <td>
                                                                <select name="kecamatan_tujuan[]"
                                                                    class="form-select form-select-sm input-kecamatan"
                                                                    required>
                                                                    {{-- Opsi ini akan diisi ulang oleh JS Helper di bawah agar konsisten dengan standar biaya, 
                                                                     tapi kita set value selected-nya --}}
                                                                    <option value="{{ $t->kec_tujuan }}" selected
                                                                        data-loaded="true">{{ $t->kec_tujuan }}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="date" name="tanggal_pelaksanaan[]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $t->tgl_pelaksanaan }}">
                                                            </td>
                                                            <td>
                                                                <select name="pcl_diawasi[]"
                                                                    class="form-select form-select-sm select2-server-side form-select-mitra">
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
                                                                    min="0" value="{{ $t->jml_sampel_pcl }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="jml_sampel_diawasi[]"
                                                                    class="form-control form-control-sm text-center"
                                                                    min="0" value="{{ $t->jml_sampel_diawasi }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="jml_ok[]"
                                                                    class="form-control form-control-sm text-center"
                                                                    min="1" value="{{ $t->jml_ok }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="transport_bayar[]"
                                                                    class="form-control form-control-sm text-end input-transport input-mask-rupiah"
                                                                    value="{{ $t->transport_bayar }}">
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm hapus-baris">
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
                                                id="kak6_pembiayaan"
                                                value="{{ old('kak6_pembiayaan', $kegiatan->kak6_pembiayaan) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kak8_pengaju">Yang mengajukan </label>
                                            <input type="text" class="form-control" name="kak8_pengaju"
                                                id="kak8_pengaju"
                                                value="{{ old('kak8_pengaju', $kegiatan->kak8_pengaju) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kak8_tgl">Tanggal Pengajuan KAK</label>
                                            <input type="date" class="form-control" name="kak8_tgl" id="kak8_tgl"
                                                value="{{ old('kak8_tgl', $kegiatan->kak8_tgl) }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="id_pjk">Nama Pegawai Penanggung Jawab Kegiatan</label>
                                            <select class="form-select" id="id_pjk" name="id_pjk">
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
                                                value="{{ old('judul_kak', $kegiatan->nama) }}" />
                                        </div>
                                    </div>
                                </div>

                                <hr />
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('kegiatan.index') }}" class="btn btn-danger">Batal</a>
                                @if (Auth::user()->nama == env('NAMA_PPK'))
                                    @if ($kegiatan->is_approved == 1)
                                        <button type="button" class="btn btn-danger" id="btn_reject"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="fa fa-undo"></i> Reject
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-success" id="btn_approve"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="fa fa-check"></i> Approve
                                        </button>
                                    @endif
                                @endif
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
            // --- Init Standard Select2 ---
            $('#filter_sbks, #kak6_program, #kak6_aktivitas, #kak6_kro, #kak6_ro, #kak6_komponen, #kak6_sub_komponen, #kak6_akun, #kak2_maksud, #kak2_tujuan, #id_pjk')
                .select2({
                    theme: "bootstrap-5",
                    width: '100%',
                    placeholder: $(this).data('placeholder'),
                    closeOnSelect: true,
                });

            // --- Init Select2 for Existing Table Rows (Server Side Rendered) ---
            $('.select2-server-side').select2({
                theme: "bootstrap-5",
                width: '100%',
                dropdownParent: $('#tabel_transport')
            });

            // --- Hitung Total Awal (Saat Page Load) ---
            hitungGrandTotal();
            hitungTotalTransport();

            // --- Logic AJAX POK (Sama seperti create) ---
            $('#kak6_program').on('change', function() {
                var kode_program = $(this).val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('pok.filter') }}',
                    type: 'POST',
                    data: {
                        kak6_program: kode_program
                    },
                    success: function(data) {
                        $('#kak6_aktivitas').empty().append(
                            '<option value="">(Pilih Aktivitas)</option>');
                        data.map(item => {
                            $('#kak6_aktivitas').append(
                                `<option value="${item.kode_aktivitas}">${item.kode_aktivitas} - ${item.uraian}</option>`
                            );
                        });
                        // Reset child dropdowns
                        $('#kak6_kro').empty();
                        $('#kak6_ro').empty();
                        $('#kak6_komponen').empty();
                        $('#kak6_sub_komponen').empty();
                    }
                });
            });

            // ... (Kode AJAX aktivitas, KRO, RO, Komponen sama persis seperti di create.blade.php) ...
            // ... (Disarankan copy-paste blok logic on('change') untuk aktivitas s.d sub_komponen dari file create) ...

            // Copy Paste Logic AJAX Aktivitas -> KRO
            $('#kak6_aktivitas').on('change', function() {
                var kode_program = $('#kak6_program').val();
                var kode_aktivitas = $(this).val();
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
                        $('#kak6_kro').empty().append('<option value="">(Pilih KRO)</option>');
                        data.map(item => $('#kak6_kro').append(
                            `<option value="${item.kode_klasifikasi_rincian_output}">${item.kode_klasifikasi_rincian_output} - ${item.uraian}</option>`
                        ));
                        $('#kak6_ro').empty();
                        $('#kak6_komponen').empty();
                        $('#kak6_sub_komponen').empty();
                    }
                });
            });

            // Copy Paste Logic AJAX KRO -> RO
            $('#kak6_kro').on('change', function() {
                var dataKirim = {
                    kak6_program: $('#kak6_program').val(),
                    kak6_aktivitas: $('#kak6_aktivitas').val(),
                    kak6_kro: $(this).val()
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('pok.filter') }}',
                    type: 'POST',
                    data: dataKirim,
                    success: function(data) {
                        $('#kak6_ro').empty().append('<option value="">(Pilih RO)</option>');
                        data.map(item => $('#kak6_ro').append(
                            `<option value="${item.kode_rincian_output}">${item.kode_rincian_output} - ${item.uraian}</option>`
                        ));
                        $('#kak6_komponen').empty();
                        $('#kak6_sub_komponen').empty();
                    }
                });
            });

            // Copy Paste Logic AJAX RO -> Komponen
            $('#kak6_ro').on('change', function() {
                var dataKirim = {
                    kak6_program: $('#kak6_program').val(),
                    kak6_aktivitas: $('#kak6_aktivitas').val(),
                    kak6_kro: $('#kak6_kro').val(),
                    kak6_ro: $(this).val()
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('pok.filter') }}',
                    type: 'POST',
                    data: dataKirim,
                    success: function(data) {
                        $('#kak6_komponen').empty().append(
                            '<option value="">(Pilih Komponen)</option>');
                        data.map(item => $('#kak6_komponen').append(
                            `<option value="${item.kode_komponen}">${item.kode_komponen} - ${item.uraian}</option>`
                        ));
                        $('#kak6_sub_komponen').empty();
                    }
                });
            });

            // Copy Paste Logic AJAX Komponen -> Sub Komponen
            $('#kak6_komponen').on('change', function() {
                var dataKirim = {
                    kak6_program: $('#kak6_program').val(),
                    kak6_aktivitas: $('#kak6_aktivitas').val(),
                    kak6_kro: $('#kak6_kro').val(),
                    kak6_ro: $('#kak6_ro').val(),
                    kak6_komponen: $(this).val()
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('pok.filter') }}',
                    type: 'POST',
                    data: dataKirim,
                    success: function(data) {
                        $('#kak6_sub_komponen').empty().append(
                            '<option value="">(Pilih Sub Komponen)</option>');
                        data.map(item => $('#kak6_sub_komponen').append(
                            `<option value="${item.kode_sub_komponen}">${item.kode_sub_komponen} - ${item.uraian}</option>`
                        ));
                        $('#kak6_akun').empty();
                    }
                });
            });


            // --- Logic Pilih Akun ---
            $('#pilih_akun').select2({
                theme: "bootstrap-5",
                placeholder: "Pilih Akun Belanja",
                width: '100%'
            });

            // AJAX Ambil Akun (Copy from create)
            $('#kak6_sub_komponen').on('change', function() {
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
                    url: '{{ route('pok.filter') }}',
                    type: 'POST',
                    data: semua_kode,
                    success: function(data) {
                        $('#pilih_akun').empty().append(
                            '<option value="">(Pilih Akun)</option>');
                        data.map(function(item) {
                            $('#pilih_akun').append(
                                `<option value="${item.id}" data-nama="${item.uraian}">${item.kode_akun} - ${item.uraian}</option>`
                            );
                        });
                    }
                });
            });

            // --- Logic Tambah Baris Akun (Sama) ---
            $('#btn_tambah_akun').click(function() {
                var akunId = $('#pilih_akun').val();
                var akunText = $('#pilih_akun option:selected').text();
                if (!akunId) {
                    alert("Silakan pilih akun terlebih dahulu!");
                    return;
                }

                var barisBaru = `
                    <tr>
                        <td>
                            <input type="hidden" name="akun_kode[]" value="${akunId}">
                            <small>${akunText}</small>
                        </td>
                        <td><textarea name="rincian_detail[]" class="form-control form-control-sm" rows="4" required></textarea></td>
                        <td><input type="number" name="rincian_volume[]" class="form-control form-control-sm hitung-total input-volume" min="0" value="0" required></td>
                        <td>
                             <select class="form-select form-control-sm" name="rincian_satuan[]">
                                <option value="">(Pilih)</option>
                                <option value="Dokumen">Dokumen</option><option value="SLS">SLS</option><option value="BS">BS</option>
                                <option value="Ruta">Ruta</option><option value="OK">OK</option><option value="OH">OH</option>
                                <option value="OB">OB</option><option value="OP">OP</option><option value="Segmen">Segmen</option>
                                <option value="EA">EA</option><option value="Responden">Responden</option><option value="Pasar">Pasar</option>
                            </select>
                        </td>
                        <td><input type="number" name="rincian_harga[]" class="form-control form-control-sm hitung-total input-harga" min="0" value="0" required></td>
                        <td><input type="number" name="rincian_total[]" class="form-control form-control-sm input-subtotal" readonly value="0"></td>
                        <td class="text-center"><button type="button" class="btn btn-danger btn-sm hapus-baris"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `;
                $('#body_rincian_akun').append(barisBaru);
            });

            // --- Logic Generate Judul (Sama) ---
            // ... (Copy function generateJudulKAK & event listenernya dari file create) ...
            function generateJudulKAK() {
                var jenis_kegiatan = $('input[name="jenis_kegiatan"]:checked').val();
                var nama_kegiatan = $('#filter_sbks').val();
                var maksud = $('#kak2_maksud').val();
                var tujuan = $('#kak2_tujuan').val();

                // Cek jika field lengkap, baru generate (kecuali user mau manual edit)
                // Note: Di edit mungkin kita tidak ingin menimpa judul manual user kecuali mereka mengubah field penentu
            }

            // Update logic maksud/tujuan untuk label target
            $('#kak2_maksud').on('change', function() {
                var kode_maksud = $(this).val();
                if (kode_maksud == 'pengawasan' || kode_maksud == 'supervisi') {
                    $('#label_kak3_target').html('Mitra yang diawasi ada sejumlah');
                } else {
                    $('#label_kak3_target').html('Sampel yang didata ada sejumlah');
                }
                $('#kak6_pembiayaan').val(kode_maksud);
            });

        }); // End Document Ready


        // --- Global Functions (Outside Document Ready) ---

        // Hitung Grand Total Akun
        $(document).on('keyup change', '.hitung-total', function() {
            var baris = $(this).closest('tr');
            var vol = parseFloat(baris.find('.input-volume').val()) || 0;
            var hrg = parseFloat(baris.find('.input-harga').val()) || 0;
            baris.find('.input-subtotal').val(vol * hrg);
            hitungGrandTotal();
        });

        function hitungGrandTotal() {
            var grandTotal = 0;
            $('.input-subtotal').each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $('#grand_total').val(grandTotal);
        }

        // --- Logic Tabel Transport ---

        // Set index awal berdasarkan jumlah baris yang sudah ada dari database
        // Asumsi variabel JS ini dirender server-side
        var rowIndex = {{ isset($kegiatan) ? $kegiatan->kegiatanLampiran->count() : 0 }};

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

        function getOptionsKecamatan() {
            let options = '<option value="">- Pilih Kecamatan -</option>';
            for (const [kode, data] of Object.entries(standarBiaya)) {
                options +=
                    `<option value="${kode}" data-biaya="${data.biaya}" data-nama="${data.nama}">[${kode}] ${data.nama}</option>`;
            }
            return options;
        }

        // Inisialisasi ulang opsi kecamatan pada baris yang sudah ada dari database
        $(document).ready(function() {
            $('.input-kecamatan').each(function() {
                var select = $(this);
                var savedVal = select.find('option:selected').val(); // Ambil nilai yang tersimpan dari DB

                // Generate opsi lengkap
                var allOptions = getOptionsKecamatan();
                select.html(allOptions); // Replace HTML

                // Set kembali value yang tersimpan
                if (savedVal) {
                    select.val(savedVal);
                }
            });
        });

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
                    <td><input type="text" name="nip[]" class="form-control form-control-sm text-center input-nip" readonly placeholder="-"></td>
                    <td><select name="kecamatan_tujuan[]" class="form-select form-select-sm input-kecamatan" required>${optionsKecamatan}</select></td>
                    <td><input type="date" name="tanggal_pelaksanaan[]" class="form-control form-control-sm"></td>
                    <td><select name="pcl_diawasi[]" class="form-select form-select-sm select2-dinamis form-select-mitra">${templateOpsiMitra}</select></td>
                    <td><input type="number" name="jml_sampel_pcl[]" class="form-control form-control-sm text-center" min="0" value="0"></td>
                    <td><input type="number" name="jml_sampel_diawasi[]" class="form-control form-control-sm text-center" min="0" value="0"></td>
                    <td><input type="number" name="jml_ok[]" class="form-control form-control-sm text-center" min="1" value="1"></td>
                    <td><input type="text" name="transport_bayar[]" class="form-control form-control-sm text-end input-transport input-mask-rupiah" placeholder="0"></td>
                    <td class="text-center align-middle">
                        <button type="button" class="btn btn-danger btn-sm hapus-baris"><i class="fa fa-trash"></i></button>
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

        $('#btn_tambah_pegawai').click(function() {
            tambahBarisTransport('pegawai');
        });
        $('#btn_tambah_mitra').click(function() {
            tambahBarisTransport('mitra');
        });

        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
            hitungGrandTotal();
            hitungTotalTransport();
        });

        $(document).on('change', '.input-nama', function() {
            var optionSelected = $(this).find(':selected');
            var row = $(this).closest('tr');
            var nip = optionSelected.data('nip');
            row.find('.input-nip').val(nip ? nip : '-');
        });

        $(document).on('input change', '.input-transport', function() {
            hitungTotalTransport();
        });

        function hitungTotalTransport() {
            var total = 0;
            $('.input-transport').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#grand_total_transport').val(total);
        }

        $(document).on('change', '.input-kecamatan', function() {
            var optionSelected = $(this).find(':selected');
            var row = $(this).closest('tr');
            var biaya = optionSelected.data('biaya');
            row.find('.input-transport').val(biaya ? biaya : 0);
            hitungTotalTransport();
        });
    </script>
@endsection
