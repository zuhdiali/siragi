@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">
                        {{ strlen($kegiatan->nama) > 50 ? substr($kegiatan->nama, 0, 50) . '...' : $kegiatan->nama }}</h3>
                    {{-- <h6 class="op-7 mb-2">Rincian kegiatan </h6> --}}
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    @if (Auth::user()->role == 'Admin' ||
                            Auth::user()->id == $kegiatan->id_pjk ||
                            (Auth::user()->role == 'Ketua Tim' && Auth::user()->tim == $kegiatan->tim))
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-success btn-round mb-3" data-bs-toggle="modal"
                            data-bs-target="#importMitraHonorModal">
                            <i class="fa fa-upload"></i> Import
                        </button>

                        <a href="{{ url('kegiatan/edit', $kegiatan->id) }}" class="btn btn-primary btn-round mb-3"><i
                                class="fa fa-edit"></i> Edit </a>
                        <a href="{{ url('kegiatan/estimasi-honor', $kegiatan->id) }}"
                            class="btn btn-primary btn-round mb-3"><i class="fa fa-edit"></i> Perbarui Estimasi Honor</a>

                        <!-- Modal Import Mitra dan Honor -->
                        <div class="modal fade" id="importMitraHonorModal" tabindex="-1"
                            aria-labelledby="importMitraHonorModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importMitraHonorModalLabel">Import Mitra dan Honor</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('kegiatan.import-mitra-dan-honor', $kegiatan->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
                                            <div class="form-group mb-3">
                                                <label for="file">Pilih File (File harus bertipe Excel
                                                    <strong>xlsx</strong>)</label>
                                                <input type="file" class="form-control" name="file" id="file"
                                                    required>
                                            </div>
                                            <button type="submit" class="btn btn-success"> <i class="fa fa-upload"></i>
                                                Import</button>
                                            <a href="{{ route('kegiatan.export-mitra-id') }}" class="btn btn-primary "
                                                rel="noopener noreferrer">
                                                <i class="fa fa-download"></i> Export Template
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Export Translok -->
                        <div class="modal fade" id="exportTranslok" tabindex="-1" aria-labelledby="exportTranslokLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exportTranslokLabel">Export Translok</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('kegiatan.export-translok', $kegiatan->id) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
                                            <div class="form-group mb-3">
                                                <div class="row">
                                                    <div class="col-6"><label for="tujuan">Tujuan</label></div>
                                                    <div class="col-6">
                                                        <select name="tujuan" id="tujuan" class="form-control mb-3">
                                                            <option value="-- Pilih Tujuan --" selected disabled>-- Pilih
                                                                Tujuan --</option>
                                                            <option value="010">[010] Teupah Selatan</option>
                                                            <option value="020">[020] Simeulue Timur</option>
                                                            <option value="021">[021] Teupah Barat</option>
                                                            <option value="022">[022] Teupah Tengah</option>
                                                            <option value="030">[030] Simeulue Tengah</option>
                                                            <option value="031">[031] Teluk Dalam</option>
                                                            <option value="032">[032] Simeulue Cut</option>
                                                            <option value="040">[040] Salang</option>
                                                            <option value="050">[050] Simeulue Barat</option>
                                                            <option value="051">[051] Alafan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6"><label for="tgl_mulai">Tanggal Mulai
                                                            Tranlsok</label></div>
                                                    <div class="col-6"><input type="date" class="form-control mb-3"
                                                            name="tgl_mulai" id="tgl_mulai" required></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6"><label for="tgl_selesai">Tanggal Selesai
                                                            Translok</label></div>
                                                    <div class="col-6"><input type="date" class="form-control mb-3"
                                                            name="tgl_selesai" id="tgl_selesai" required></div>
                                                </div>

                                            </div>
                                            <button type="submit" class="btn btn-primary"> <i
                                                    class="fa fa-download"></i> Export</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Rincian Kegiatan</div>
                        {{-- <div class="card-category">Card Category</div> --}}
                    </div>
                    <div class="card-body">
                        <table class=" table table-responsive">
                            <tbody>
                                <tr>
                                    <td width="40%">
                                        <p>Nama</p>
                                    </td>
                                    <td>
                                        <p>: {{ $kegiatan->nama }} </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Penanggung Jawab Kegiatan</p>
                                    </td>
                                    <td>
                                        <p>: {{ $kegiatan->pjk->nama }} </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Tanggal Pelaksanaan</p>
                                    </td>
                                    <td>
                                        <p>: {{ Carbon\Carbon::parse($kegiatan->tgl_mulai)->locale('id')->translatedFormat('d M Y') }}
                                            -
                                            {{ Carbon\Carbon::parse($kegiatan->tgl_selesai)->locale('id')->translatedFormat('d M Y') }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Honor <strong>Pengawasan</strong></p>
                                    </td>
                                    <td>
                                        <p>: Rp {{ number_format($kegiatan->honor_pengawasan, 0, ',', '.') }} per
                                            {{ $kegiatan->satuan_honor_pengawasan }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Honor <strong>Pencacahan / Pengolahan</strong></p>
                                    </td>
                                    <td>
                                        <p>: Rp {{ number_format($kegiatan->honor_pencacahan, 0, ',', '.') }} per
                                            {{ $kegiatan->satuan_honor_pencacahan }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Progress</p>
                                    </td>
                                    <td>
                                        <p>: {{ $kegiatan->progress }} %</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a href="{{ route('kegiatan.export-mitra-dan-honor', $kegiatan->id) }}"
                                            class="btn btn-primary btn-sm" rel="noopener noreferrer">
                                            <i class="fa fa-download"></i> Export Honor untuk BOS
                                        </a>
                                        <button type="button" class="btn btn-sm btn-primary " data-bs-toggle="modal"
                                            data-bs-target="#exportTranslok">
                                            <i class="fa fa-download"></i> Export Translok untuk BOS
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-md-3">
                    {{-- <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div
                  class="icon-big text-center icon-primary bubble-shadow-small"
                >
                  <i class="fas fa-users"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Jumlah Kegiatan Tahun Ini</p>
                  <h4 class="card-title">{{$kegiatanTahunIni}}</h4>
                </div>
              </div>
            </div>
          </div>
        </div> --}}
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
                            <h4 class="card-title">Daftar Mitra Terlibat</h4>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="multi-filter-select" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Mitra</th>
                                            <th>Jumlah Pendataan/Pengolahan</th>
                                            <th>Estimasi Honor Dari Kegiatan Ini</th>
                                            {{-- <th>Tanggal Realisasi</th> --}}
                                            <th>Estimasi Total Honor Yang Didapat Setelah Kegiatan Ini</th>
                                            {{-- <th style="width: 10%">Aksi</th> --}}
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($kegiatan->mitra as $mitra)
                                            <!-- Modal -->
                                            {{-- <div class="modal fade" id="{{'exampleModal'.$mitra->id}}" tabindex="-1" aria-labelledby="{{'exampleModalLabel'.$mitra->id}}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="{{'exampleModalLabel'.$mitra->id}}">Yakin Menghapus Mitra?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Mitra <strong>{{$mitra->nama}}</strong> akan dihapus dari kegiatan ini!
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <form action="{{url('kegiatan/destroy/'.$mitra->id)}}">
                            <button type="submit" class="btn btn-danger hapus-kegiatan">Hapus</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div> --}}
                                            <tr>
                                                <th scope="row">{{ $mitra->pivot->is_pml == 1 ? 'PML' : 'PPL' }} -
                                                    {{ $mitra->nama }}</th>
                                                <td>{{ $mitra->pivot->jumlah }}</td>
                                                <td>Rp {{ number_format($mitra->pivot->estimasi_honor, 0, ',', '.') }}</td>
                                                {{-- <td> {{Carbon\Carbon::parse($mitra->pivot->tgl_realisasi)->locale('id')->translatedFormat('d M Y') }} </td> --}}
                                                <td>Rp {{ number_format($mitra->pivot->estimasi_honor, 0, ',', '.') }}</td>
                                                {{-- <td>
                             <div class="form-button-action">
                                <button
                                    type="button"
                                    title="Hapus"
                                    class="btn btn-link btn-danger"
                                    data-bs-toggle="modal" 
                                    data-bs-target="{{'#exampleModal'.$mitra->id}}"
                                    data-original-title="Hapus"
                                >
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </div> 
                        </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Pegawai Terlibat</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Pegawai</th>
                                            {{-- <th style="width: 10%">Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Nama Pegawai</th>
                                            {{-- <th>Aksi</th> --}}
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($kegiatan->pegawai as $pegawai)
                                            <!-- Modal -->
                                            {{-- <div class="modal fade" id="{{'exampleModal'.$pegawai->id}}" tabindex="-1" aria-labelledby="{{'exampleModalLabel'.$pegawai->id}}" aria-hidden="true"> 
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="{{'exampleModalLabel'.$pegawai->id}}">Yakin Menghapus Kegiatan?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Kegiatan <strong>{{$pegawai->nama}}</strong> akan ditandai sebagai kegiatan tidak aktif!
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <form action="{{url('kegiatan/destroy/'.$pegawai->id)}}">
                              <button type="submit" class="btn btn-danger hapus-kegiatan" >Hapus Kegiatan</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div> --}}
                                            <tr>
                                                <th scope="row">{{ $pegawai->nama }}</th>
                                                {{-- <td>
                         <div class="form-button-action">

                          <button
                            type="button"
                            title="Hapus"
                            class="btn btn-link btn-danger"
                            data-bs-toggle="modal" 
                            data-bs-target="{{'#exampleModal'.$pegawai->id}}"
                            data-original-title="Hapus"
                          >
                            <i class="fa fa-trash-alt"></i>
                          </button>
                        </div> 
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
