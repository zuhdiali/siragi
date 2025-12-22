@extends('layouts.app')

@section('content')

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" >
      <div>
        <h3 class="fw-bold mb-3">Manajemen Pegawai</h3>
        <h6 class="op-7 mb-2">Daftar pegawai </h6>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <a href="{{route('pegawai.create')}}" class="btn btn-primary btn-round">Tambah pegawai</a>
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
                  <i class="fas fa-users"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Jumlah Pegawai Aktif</p>
                  <h4 class="card-title">{{$pegawaiAktif}}</h4>
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
            <h4 class="card-title">Daftar Pegawai</h4>
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
                    <th>Nama</th>
                    <th>Username</th>
                    <th>NIP</th>
                    <th>No. Rekening</th>
                    <th>Tim</th>
                    <th>Role</th>
                    <th>Flag</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Aksi</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>NIP</th>
                    <th>No. Rekening</th>
                    <th>Tim</th>
                    <th>Role</th>
                    <th>Flag</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach($pegawais as $pegawai)
                    <!-- Modal -->
                    @if(Auth::user()->id == $pegawai->id || Auth::user()->role == 'Admin')
                    <div class="modal fade" id="{{'exampleModal'.$pegawai->id}}" tabindex="-1" aria-labelledby="{{'exampleModalLabel'.$pegawai->id}}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="{{'exampleModalLabel'.$pegawai->id}}">Yakin Menghapus Pegawai?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Pegawai <strong>{{$pegawai->nama}}</strong> akan ditandai sebagai pegawai tidak aktif!
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <form action="{{url('pegawai/destroy/'.$pegawai->id)}}">
                            <button type="submit" class="btn btn-danger hapus-pegawai" >Hapus Pegawai</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                    <tr>
                      <td>
                        @if(Auth::user()->id == $pegawai->id || Auth::user()->role == 'Admin')
                        <div class="form-button-action">
                          <form action="{{url('pegawai/edit', $pegawai->id)}}">
                            <button
                              type="submit"
                              data-bs-toggle="tooltip"
                              title="Edit"
                              class="btn btn-link btn-primary px-2"
                              data-original-title="Edit Pegawai"
                            >
                            <i class="fa fa-edit"></i>
                            </button>
                          </form>

                          <button
                            type="button"
                            title="Hapus"
                            class="btn btn-link btn-danger px-2"
                            data-bs-toggle="modal" 
                            data-bs-target="{{'#exampleModal'.$pegawai->id}}"
                            data-original-title="Hapus"
                          >
                            <i class="fa fa-trash-alt"></i>
                          </button>
                        </div>
                        @endif
                      </td>
                      <th scope="row">{{$pegawai->nama}}</th>
                      <td>{{$pegawai->username}}</td>
                      <td>{{$pegawai->nip}}</td>
                      <td>{{$pegawai->no_rek}}</td>
                      <td>{{$pegawai->nama_tim}}</td>
                      <td>{{$pegawai->role}}</td>
                      <td>
                        @if($pegawai->flag == null)
                        <span class="badge bg-success">Aktif</span>
                        @else
                        <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                      </td>
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
