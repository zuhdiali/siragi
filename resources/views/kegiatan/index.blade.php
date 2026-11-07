@extends('layouts.app')

@section('content')
    {{-- {{dd(Auth::user())}} --}}
    <div class="container">
        <div class="page-inner">
            <!-- Modal Pilih KAK -->
            <div class="modal fade" id="pilihKAK" tabindex="-1" aria-labelledby="pilihKAKLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pilihKAKLabel">Pilih KAK</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <a href="{{ route('kegiatan.translok-biasa.create') }}"
                                        class="btn btn-primary btn-round mb-1">Translok Biasa</a>
                                    <a href="{{ route('kegiatan.translok-8jam.create') }}"
                                        class="btn btn-primary btn-round mb-1">Translok > 8 Jam</a>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <a href="{{ route('kegiatan.pemanggilan-konsultasi.create') }}"
                                        class="btn btn-primary btn-round mb-1">Pemanggilan / Konsultasi</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <a href="{{ route('kegiatan.honor-mitra.create') }}"
                                        class="btn btn-primary btn-round mb-1">Honor Mitra</a>
                                    <a href="{{ route('kegiatan.honor-inda.create') }}"
                                        class="btn btn-primary btn-round mb-1">Honor Inda</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <a href="{{ route('kegiatan.lainnya.create') }}"
                                        class="btn btn-primary btn-round mb-1">Lainnya</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Shared activity modals --}}
            <div class="modal fade" id="modalDeleteKegiatan" tabindex="-1" aria-labelledby="modalDeleteKegiatanLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalDeleteKegiatanLabel">Yakin Menghapus Kegiatan?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">Kegiatan <strong id="deleteKegiatanName"></strong> akan ditandai sebagai kegiatan tidak aktif!</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <form id="deleteKegiatanForm" method="GET">
                                <button type="submit" class="btn btn-danger hapus-kegiatan">Hapus Kegiatan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modalDuplicateKegiatan" tabindex="-1" aria-labelledby="modalDuplicateKegiatanLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalDuplicateKegiatanLabel">Duplikasi Kegiatan</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">Apakah Anda yakin ingin menduplikasi <strong id="duplicateKegiatanName"></strong>?</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <form id="duplicateKegiatanForm" method="GET">
                                <button type="submit" class="btn btn-primary">Duplikasi</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modalImportMitra" tabindex="-1" aria-labelledby="modalImportMitraLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalImportMitraLabel">Impor Mitra Kegiatan</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex flex-column align-items-center justify-content-center">
                            <p>Unduh template berikut dan isi data mitra kegiatan dengan benar.</p>
                            <a href="{{ route('kegiatan.export-mitra-id') }}" class="btn btn-primary mb-3">
                                <i class="fa fa-download"></i> Unduh Template
                            </a>
                            <p>Setelah mengisi template, unggah kembali file tersebut menggunakan formulir di bawah ini.</p>
                            <form id="importMitraForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3"><input type="file" name="file" class="form-control" required></div>
                                <button type="submit" class="btn btn-success">Unggah File</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Manajemen Kegiatan</h3>
                    <h6 class="op-7 mb-2">Daftar kegiatan </h6>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    @if (Auth::user()->role == 'Admin')
                        <a href="{{ route('kegiatan.export-pj-kegiatan') }}" class="btn btn-primary">
                            <i class="fa fa-download"></i> Daftar PJK
                        </a>
                    @endif
                    {{-- <a href="{{ route('kegiatan.export-mitra-id') }}" class="btn btn-primary">
                        <i class="fa fa-download"></i> Template Impor Mitra Kegiatan
                    </a> --}}
                    <button type="button" class="btn  btn-primary " data-bs-toggle="modal" data-bs-target="#pilihKAK">
                        <i class="fa fa-plus"></i> Tambah KAK
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-clipboard"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Jumlah Kegiatan Tahun Ini</p>
                                        <h4 class="card-title">{{ $kegiatanTahunIni }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                </div>
                <div class="col-sm-6 col-md-3">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Kegiatan</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="multi-filter-select" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">Aksi</th>
                                            <th>ID</th>
                                            <th>Nama Singkatan Resmi</th>
                                            <th>Jenis KAK</th>
                                            <th>PJK</th>
                                            <th>Pelaksana</th>
                                            <th>Nama KAK</th>
                                            <th>Tim</th>
                                            <th>Tanggal Pelaksanaan</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>ID</th>
                                            <th>Nama Singkatan Resmi</th>
                                            <th>Jenis KAK</th>
                                            <th>PJK</th>
                                            <th>Pelaksana</th>
                                            <th>Nama KAK</th>
                                            <th>Tim</th>
                                            <th>Tanggal Pelaksanaan</th>
                                            <th>Progress</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($kegiatans as $kegiatan)
                                            <tr>
                                                <td>
                                                    <div class="row">

                                                        <div class="form-button-action">
                                                            <form
                                                                action="{{ url('kegiatan/' . str_replace('_', '-', $kegiatan->jenis_kak) . '/show', $kegiatan->id) }}"
                                                                style="display: inline">
                                                                <button type="submit" data-bs-toggle="tooltip"
                                                                    title="Detil Kegiatan"
                                                                    class="btn btn-link btn-primary px-2"
                                                                    data-original-title="Detil Kegiatan">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </form>

                                                            @if (Auth::user()->role == 'Admin' ||
                                                                    Auth::user()->id == $kegiatan->id_pjk ||
                                                                    (Auth::user()->role == 'Ketua Tim' &&
                                                                        Auth::user()->tim == $kegiatan->kak4_pjk &&
                                                                        $kegiatan->is_approved == 0) ||
                                                                    Auth::user()->id == env('ID_PPK'))
                                                                <form
                                                                    action="{{ url('kegiatan/' . str_replace('_', '-', $kegiatan->jenis_kak) . '/edit/' . $kegiatan->id) }}"
                                                                    style="display: inline">
                                                                    <button type="submit" data-bs-toggle="tooltip"
                                                                        title="Edit"
                                                                        class="btn btn-link btn-primary px-2"
                                                                        data-original-title="Edit Kegiatan">
                                                                        <i class="fa fa-edit"></i>
                                                                    </button>
                                                                </form>

                                                                <button type="button" title="Duplikasi"
                                                                    class="btn btn-link btn-primary px-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalDuplicateKegiatan"
                                                                    data-action="{{ url('kegiatan/duplicate/' . $kegiatan->id) }}"
                                                                    data-name="{{ $kegiatan->nama }}"
                                                                    data-original-title="Duplikasi">
                                                                    <i class="far fa-copy"></i>
                                                                </button>


                                                                <button type="button" title="Hapus"
                                                                    class="btn btn-link btn-danger px-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalDeleteKegiatan"
                                                                    data-action="{{ url('kegiatan/destroy/' . $kegiatan->id) }}"
                                                                    data-name="{{ $kegiatan->nama }}"
                                                                    data-original-title="Hapus">
                                                                    <i class="fa fa-trash-alt"></i>
                                                                </button>
                                                            @endif

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-button-action">
                                                            @if ($kegiatan->is_approved == 1)
                                                                <form
                                                                    action="{{ route('kegiatan.unduh-kak', $kegiatan->id) }}"
                                                                    style="display: inline">
                                                                    <button type="submit"
                                                                        class="btn btn-link btn-success px-2"
                                                                        data-bs-toggle="tooltip" title="Unduh KAK"
                                                                        data-original-title="Unduh KAK">
                                                                        <i class="fa fa-file-download"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @if (
                                                                $kegiatan->jenis_kak == 'honor-mitra' &&
                                                                    (Auth::user()->role == 'Admin' ||
                                                                        Auth::user()->id == $kegiatan->id_pjk ||
                                                                        (Auth::user()->role == 'Ketua Tim' &&
                                                                            Auth::user()->tim == $kegiatan->kak4_pjk &&
                                                                            $kegiatan->is_approved == 0) ||
                                                                        Auth::user()->id == env('ID_PPK')))
                                                                <button type="button" title="Impor Mitra Kegiatan"
                                                                    class="btn btn-link btn-primary px-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalImportMitra"
                                                                    data-action="{{ route('kegiatan.import-mitra-dan-honor', $kegiatan->id) }}"
                                                                    data-original-title="Impor Mitra Kegiatan">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $kegiatan->id ?? '-' }}</td>
                                                <th scope="row">
                                                    {{ $kegiatan->singkatan_resmi ?? '-' }}
                                                </th>
                                                <td>{{ $kegiatan->jenis_kak ?? '-' }}</td>
                                                <td>{{ $kegiatan->pjk->nama ?? '-' }}</td>
                                                <td>{{ $kegiatan->nama_pelaksana ? (strlen($kegiatan->nama_pelaksana) > 30 ? substr($kegiatan->nama_pelaksana, 0, 30) . '...' : $kegiatan->nama_pelaksana) : '-' }}
                                                </td>
                                                <td>{{ $kegiatan->nama ? (strlen($kegiatan->nama) > 90 ? substr($kegiatan->nama, 0, 90) . '...' : $kegiatan->nama) : '-' }}
                                                </td>
                                                <td>{{ $kegiatan->namaTim ?? '-' }}</td>
                                                <td>
                                                    @if ($kegiatan->tgl_mulai && $kegiatan->tgl_selesai)
                                                        <p>{{ Carbon\Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d M Y') }}
                                                        </p>
                                                        <p>s/d</p>
                                                        <p>{{ Carbon\Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d M Y') }}
                                                        </p>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $kegiatan->progress ?? '-' }} %</td>
                                                {{-- <td>
                                                        @if ($kegiatan->flag == null)
                                                        <span class="badge bg-success">Aktif</span>
                                                        @else
                                                        <span class="badge bg-danger">Tidak Aktif</span>
                                                        @endif
                                                    </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('modalDeleteKegiatan').addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            document.getElementById('deleteKegiatanForm').action = trigger.dataset.action;
            document.getElementById('deleteKegiatanName').textContent = trigger.dataset.name;
        });

        document.getElementById('modalDuplicateKegiatan').addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            document.getElementById('duplicateKegiatanForm').action = trigger.dataset.action;
            document.getElementById('duplicateKegiatanName').textContent = trigger.dataset.name;
        });

        document.getElementById('modalImportMitra').addEventListener('show.bs.modal', function (event) {
            document.getElementById('importMitraForm').action = event.relatedTarget.dataset.action;
        });
    </script>
@endsection
