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
                    <button type="button" class="btn  btn-primary " data-bs-toggle="modal" data-bs-target="#pilihKAK">
                        <i class="fa fa-plus"></i> Tambah KAK
                    </button>
                    {{-- <a href="{{ route('kegiatan.create') }}" class="btn btn-primary btn-round">Tambah kegiatan</a> --}}
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
                                            <th>Nama Singkatan Resmi</th>
                                            <th>Jenis KAK</th>
                                            <th>Nama KAK</th>
                                            <th>PJK</th>
                                            <th>Tanggal Pelaksanaan</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Nama Singkatan Resmi</th>
                                            <th>Jenis KAK</th>
                                            <th>Nama KAK</th>
                                            <th>PJK</th>
                                            <th>Tanggal Pelaksanaan</th>
                                            <th>Progress</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($kegiatans as $kegiatan)
                                            <!-- Modal -->
                                            @if (Auth::user()->role == 'Admin' ||
                                                    Auth::user()->id == $kegiatan->id_pjk ||
                                                    (Auth::user()->role == 'Ketua Tim' && Auth::user()->tim == $kegiatan->kak4_pjk))
                                                <div class="modal fade" id="{{ 'exampleModal' . $kegiatan->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="{{ 'exampleModalLabel' . $kegiatan->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5"
                                                                    id="{{ 'exampleModalLabel' . $kegiatan->id }}">Yakin
                                                                    Menghapus Kegiatan?</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Kegiatan <strong>{{ $kegiatan->nama }}</strong> akan
                                                                ditandai sebagai kegiatan tidak aktif!
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batalkan</button>
                                                                <form
                                                                    action="{{ url('kegiatan/destroy/' . $kegiatan->id) }}">
                                                                    <button type="submit"
                                                                        class="btn btn-danger hapus-kegiatan">Hapus
                                                                        Kegiatan</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="{{ 'modalDuplicate' . $kegiatan->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="{{ 'modalDuplicateLabel' . $kegiatan->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5"
                                                                    id="{{ 'modalDuplicateLabel' . $kegiatan->id }}">
                                                                    Duplikasi
                                                                    Kegiatan</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menduplikasi
                                                                <strong>{{ $kegiatan->nama }}</strong> ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batalkan</button>
                                                                <form
                                                                    action="{{ url('kegiatan/duplicate/' . $kegiatan->id) }}">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Duplikasi</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <tr>
                                                <td>
                                                    <div class="form-button-action">
                                                        <form
                                                            action="{{ url('kegiatan/' . str_replace('_', '-', $kegiatan->jenis_kak) . '/show', $kegiatan->id) }}"
                                                            style="display: inline">
                                                            <button type="submit" data-bs-toggle="tooltip"
                                                                title="Detil Kegiatan"
                                                                class="btn btn-link btn-primary px-2"
                                                                data-original-title="Detil Kegiatan">
                                                                <i class="fa fa-eye"></i>
                                                        </form>
                                                        @if (
                                                            $kegiatan->is_approved == 1 &&
                                                                (Auth::user()->role == 'Admin' ||
                                                                    (Auth::user()->role == 'Ketua Tim' && Auth::user()->tim == $kegiatan->kak4_pjk)))
                                                            <form action="#" style="display: inline">
                                                                <button type="button"
                                                                    class="btn btn-link btn-success px-2"
                                                                    data-bs-toggle="tooltip" title="Unduh KAK"
                                                                    data-original-title="Unduh KAK">
                                                                    <i class="fa fa-download"></i>
                                                            </form>
                                                        @endif
                                                        @if (Auth::user()->role == 'Admin' ||
                                                                Auth::user()->id == $kegiatan->id_pjk ||
                                                                (Auth::user()->role == 'Ketua Tim' &&
                                                                    Auth::user()->tim == $kegiatan->kak4_pjk &&
                                                                    $kegiatan->is_approved == 0) ||
                                                                Auth::user()->nama == env('NAMA_PPK'))
                                                            <form
                                                                action="{{ url('kegiatan/' . str_replace('_', '-', $kegiatan->jenis_kak) . '/edit/' . $kegiatan->id) }}"
                                                                style="display: inline">
                                                                <button type="submit" data-bs-toggle="tooltip"
                                                                    title="Edit" class="btn btn-link btn-primary px-2"
                                                                    data-original-title="Edit Kegiatan">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                            </form>

                                                            <button type="button" title="Duplikasi"
                                                                class="btn btn-link btn-primary px-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="{{ '#modalDuplicate' . $kegiatan->id }}"
                                                                data-original-title="Duplikasi">
                                                                <i class="far fa-copy"></i>
                                                            </button>


                                                            <button type="button" title="Hapus"
                                                                class="btn btn-link btn-danger px-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="{{ '#exampleModal' . $kegiatan->id }}"
                                                                data-original-title="Hapus">
                                                                <i class="fa fa-trash-alt"></i>
                                                            </button>
                                                        @endif

                                                    </div>
                                                </td>
                                                <th scope="row">
                                                    {{ $kegiatan->singkatan_resmi }}
                                                </th>
                                                <td>{{ $kegiatan->jenis_kak }}</td>
                                                <td>{{ strlen($kegiatan->nama) > 90 ? substr($kegiatan->nama, 0, 90) . '...' : $kegiatan->nama }}
                                                </td>
                                                <td>{{ $kegiatan->pjk->nama }}</td>
                                                <td>
                                                    <p>{{ Carbon\Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d M Y') }}
                                                    </p>
                                                    <p>s/d</p>
                                                    <p>{{ Carbon\Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d M Y') }}
                                                    </p>
                                                </td>
                                                <td>{{ $kegiatan->progress }} %</td>
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
@endsection
