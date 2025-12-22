@extends('layouts.app')

@section('content')

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" >
      <div>
        <h3 class="fw-bold mb-3">SBKS</h3>
        <h6 class="op-7 mb-2">Daftar standar biaya kegiatan statistik (SBKS) beserta ketersediannya di Simeulue </h6>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <a href="{{route('sbks.create')}}" class="btn btn-primary btn-round">Tambah SBKS</a>
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
                <i class="fas fa-info-circle"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Jumlah SBKS</p>
                  <h4 class="card-title">{{count($sbks)}}</h4>
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
            <h4 class="card-title">Daftar SBKS</h4>
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
                    <th>Kegiatan</th>
                    <th>Tugas</th>
                    <th>Honor Per Satuan</th>
                    <th>Satuan Honor</th>
                    <th>Ada di Simeulue?</th>
                    <th>Tim</th>
                    <th>PJK</th>
                    <th>Singkatan Resmi</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Aksi</th>
                    <th>Tugas</th>
                    <th>Kegiatan</th>
                    <th>Honor Per Satuan</th>
                    <th>Satuan Honor</th>
                    <th>Ada di Simeulue?</th>
                    <th>Tim</th>
                    <th>PJK</th>
                    <th>Singkatan Resmi</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach($sbks as $item)
                    <!-- Modal -->
                    @if((Auth::user()->role == 'Admin')||(Auth::user()->role == 'Ketua Tim' && $item->tim == Auth::user()->tim))
                    <div class="modal fade" id="{{'exampleModal'.$item->id}}" tabindex="-1" aria-labelledby="{{'exampleModalLabel'.$item->id}}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="{{'exampleModalLabel'.$item->id}}">Yakin Menghapus SBKS?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            SBKS <strong>{{$item->nama_kegiatan}}</strong> akan dihapus.
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <form action="{{url('sbks/destroy/'.$item->id)}}">
                            <button type="submit" class="btn btn-danger" >Hapus SBKS</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                    <tr>
                      <td>
                        @if((Auth::user()->role == 'Admin')||(Auth::user()->role == 'Ketua Tim' && $item->tim == Auth::user()->tim))
                        <div class="form-button-action">
                          <form action="{{url('sbks/edit/'.$item->id)}}">
                            <button
                              type="submit"
                              data-bs-toggle="tooltip"
                              title="Edit"
                              class="btn btn-link btn-primary px-2"
                              data-original-title="Edit SBKS"
                            >
                            <i class="fa fa-edit"></i>
                          </button>
                        </form>

                          <button
                            type="button"
                            title="Hapus"
                            class="btn btn-link btn-danger px-2"
                            data-bs-toggle="modal" 
                            data-bs-target="{{'#exampleModal'.$item->id}}"
                            data-original-title="Hapus"
                          >
                            <i class="fa fa-trash-alt"></i>
                          </button>
                        </div>
                        @endif
                      </td>
                      <th scope="row">{{$item->nama_kegiatan}}</th>
                      <td>{{$item->tugas}}</td>
                      <td data-order="{{ $item->honor_per_satuan }}">{{ 'Rp ' . number_format($item->honor_per_satuan, 0, ',', '.') }}</td>
                      <td>{{$item->satuan}}</td>
                      <td>{{$item->ada_di_simeulue == 1 ? "Ada" : "Tidak Ada"}}</td>
                      <td>{{$item->nama_tim}}</td>
                      <td>{{$item->nama_pjk}}</td>
                      <td>{{$item->singkatan_resmi}}</td>
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
