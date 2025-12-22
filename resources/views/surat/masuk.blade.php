@extends('layouts.app')

@section('content')

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" >
      <div>
        <h3 class="fw-bold mb-3">Surat Masuk</h3>
        <h6 class="op-7 mb-2">Daftar surat masuk </h6>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <a href="{{url('surat/create/masuk')}}" class="btn btn-primary btn-round">Tambah surat</a>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div
                  class="icon-big text-center icon-primary bubble-shadow-small"
                >
                <i class="fas fa-envelope"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Jumlah Surat</p>
                  <h4 class="card-title">{{count($surats)}}</h4>
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
            <h4 class="card-title">Daftar Surat Masuk</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table
                id="multi-filter-select"
                class="display table table-striped table-hover"
              >
                <thead>
                  <tr>
                    <th style="width: 10%">Aksi</th>
                    <th>Nomor Surat Masuk</th>
                    <th>Perihal</th>
                    <th>Tanggal Surat</th>
                    <th>Dinas Pemberi Surat</th>
                    {{-- <th>Tanggal Diterima</th> --}}
                    {{-- <th>Rincian Surat</th> --}}
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Aksi</th>
                    <th>Nomor Surat Masuk</th>
                    <th>Perihal</th>
                    <th>Tanggal Surat</th>
                    <th>Dinas Pemberi Surat</th>
                    {{-- <th>Tanggal Diterima</th> --}}
                    {{-- <th>Rincian Surat</th> --}}
                  </tr>
                </tfoot>
                <tbody>
                  @foreach($surats as $surat)
                    <!-- Modal -->
                    @if((Auth::user()->role == 'Admin')||($surat->id_pembuat_surat == Auth::user()->id)||(Auth::user()->role == 'Ketua Tim' && $surat->tim == Auth::user()->tim))
                    <div class="modal fade" id="{{'exampleModal'.$surat->id}}" tabindex="-1" aria-labelledby="{{'exampleModalLabel'.$surat->id}}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="{{'exampleModalLabel'.$surat->id}}">Yakin Menghapus Surat?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Surat dengan nomor <strong>{{$surat->nomor_surat}}</strong> akan dihapus.
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <form action="{{url('surat/destroy/'.$surat->id)}}">
                            <button type="submit" class="btn btn-danger" >Hapus Surat</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                    <tr>
                      <td>
                        <div class="form-button-action">
                            <a href="{{url('surat/detail/'.$surat->jenis_surat."/".$surat->id)}}" target="_blank" class="btn btn-link btn-primary px-2" data-bs-toggle="tooltip" title="Lihat Surat" data-original-title="Lihat Surat">
                            <i class="fa fa-eye"></i>
                            </a>
                        @if((Auth::user()->role == 'Admin')||($surat->id_pembuat_surat == Auth::user()->id)||(Auth::user()->role == 'Ketua Tim' && $surat->tim == Auth::user()->tim))
                          <form action="{{url('surat/edit/'.$surat->jenis_surat."/".$surat->id)}}">
                            <button
                              type="submit"
                              data-bs-toggle="tooltip"
                              title="Edit"
                              class="btn btn-link btn-primary px-2"
                              data-original-title="Edit Surat"
                            >
                            <i class="fa fa-edit"></i>
                          </button>
                        </form>

                          <button
                            type="button"
                            title="Hapus"
                            class="btn btn-link btn-danger px-2"
                            data-bs-toggle="modal" 
                            data-bs-target="{{'#exampleModal'.$surat->id}}"
                            data-original-title="Hapus"
                          >
                            <i class="fa fa-trash-alt"></i>
                          </button>
                          @endif
                        </div>
                      </td>
                      <th scope="row">{{$surat->no_surat_masuk}}</th>
                      <td>{{$surat->perihal}}</td>
                      <td>{{\Carbon\Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y')}}</td>
                      <td>{{$surat->dinas_surat_masuk}}</td>
                      {{-- <td>{{\Carbon\Carbon::parse($surat->created_at)->translatedFormat('d F Y')}}</td> --}}
                      {{-- <td>
                        <a href="{{url('surat/detail/'.$surat->jenis_surat."/".$surat->id)}}" class="btn btn-link btn-primary" target="_blank">Lihat Surat</a>
                      </td> --}}
                      {{-- <td>
                        @if($surat->flag == null)
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
