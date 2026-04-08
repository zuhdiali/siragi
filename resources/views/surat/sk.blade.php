@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">SK</h3>
                    <h6 class="op-7 mb-2">Daftar sk </h6>
                </div>
                @if (Auth::user()->role == 'Admin')
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="{{ url('surat/create/sk') }}" class="btn btn-primary btn-round">Tambah surat</a>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Jumlah Surat</p>
                                        <h4 class="card-title">{{ count($surats) }}</h4>
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
                            <h4 class="card-title">Daftar Riwayat Nomor SK</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="multi-filter-select" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">Aksi</th>
                                            <th style="width: 10%">Nomor SK</th>
                                            <th style="width: 10%">Tanggal Surat</th>
                                            <th>Perihal</th>
                                            <th>Kegiatan</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>Nomor SK</th>
                                            <th>Tanggal Surat</th>
                                            <th>Perihal</th>
                                            <th>Kegiatan</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($surats as $surat)
                                            <!-- Modal Hapus surat -->
                                            <div class="modal fade" id="{{ 'exampleModal' . $surat->id }}" tabindex="-1"
                                                aria-labelledby="{{ 'exampleModalLabel' . $surat->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5"
                                                                id="{{ 'exampleModalLabel' . $surat->id }}">Yakin Menghapus
                                                                Nomor Surat?</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Nomor surat <strong>{{ $surat->nomor_surat }}</strong> akan
                                                            dihapus.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batalkan</button>
                                                            <form action="{{ url('surat/destroy/' . $surat->id) }}">
                                                                <button type="submit" class="btn btn-danger">Hapus
                                                                    Surat</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal unggah SK -->
                                            <div class="modal fade" id="{{ 'uploadSK' . $surat->id }}" tabindex="-1"
                                                aria-labelledby="{{ 'uploadSKLabel' . $surat->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form action="{{ url('surat/upload-sk/' . $surat->id) }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5"
                                                                    id="{{ 'uploadSKLabel' . $surat->id }}">Unggah SK </h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label for="file">Pilih File SK:</label>
                                                                    <input type="file" class="form-control"
                                                                        id="file" name="file" accept=".pdf">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batalkan</button>

                                                                <button type="submit" class="btn btn-success">Unggah
                                                                    SK</button>

                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col">
                                                            @if (Auth::user()->role == 'Admin')
                                                                <div class="form-button-action">
                                                                    <form
                                                                        action="{{ url('surat/edit/' . $surat->jenis_surat . '/' . $surat->id) }}">
                                                                        <button type="submit" data-bs-toggle="tooltip"
                                                                            title="Edit"
                                                                            class="btn btn-link btn-primary px-2"
                                                                            data-original-title="Edit Surat">
                                                                            <i class="fa fa-edit"></i>
                                                                        </button>
                                                                    </form>

                                                                    <button type="button" title="Hapus"
                                                                        class="btn btn-link btn-danger px-2"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="{{ '#exampleModal' . $surat->id }}"
                                                                        data-original-title="Hapus">
                                                                        <i class="fa fa-trash-alt"></i>
                                                                    </button>
                                                                    <a href="{{ url('surat/generate-sk/' . $surat->id) }}"
                                                                        data-bs-toggle="tooltip" title="Generate SK"
                                                                        class="btn btn-link btn-primary px-2"
                                                                        data-original-title="Generate SK">
                                                                        <i class="fa fa-file-download"></i>
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            @if (Auth::user()->role == 'Admin')
                                                                <button type="button" title="Upload SK"
                                                                    class="btn btn-link btn-primary px-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="{{ '#uploadSK' . $surat->id }}"
                                                                    data-original-title="Upload SK">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                            @endif
                                                            @if ($surat->file)
                                                                <a href="{{ url('surat/download-sk/' . $surat->id) }}"
                                                                    data-bs-toggle="tooltip" title="Download SK"
                                                                    class="btn btn-link btn-success px-2"
                                                                    data-original-title="Download SK">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </td>
                                                <th scope="row">{{ $surat->no_terakhir }}</th>
                                                <td>{{ \Carbon\Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ $surat->perihal }}</td>
                                                <td>{{ $surat->kegiatan ? $surat->kegiatan->singkatan_resmi : 'N/A' }}</td>
                                                {{-- <td>
                        @if ($surat->flag == null)
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
