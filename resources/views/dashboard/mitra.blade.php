@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard Mitra</h3>
                    <h6 class="op-7 mb-2">Rekap kegiatan dan honor mitra BPS Kabupaten Simeulue</h6>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    {{-- <a href="{{route('mitra.create')}}" class="btn btn-primary btn-round">Tambah mitra</a> --}}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Jumlah Mitra Aktif</p>
                                        <h4 class="card-title">{{ $mitraAktif }}</h4>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Filter Bulan dan Tahun</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <label for="filter-bulanan">Pilih tahun:</label>
                                        <select name="filter-tahun" id="filter-tahun" class="form-select">
                                            <option value="">-- Pilih Tahun ---</option>
                                            @for ($i = date('Y'); $i >= 2024; $i--)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="filter-bulanan">Pilih bulan:</label>
                                        <select class="form-select" id="filter-bulan" name="filter-bulan">
                                            <option value="">-- Pilih Bulan ---</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Mitra</h4>
                            <small class="text-danger" id="helper-text">Estimasi honor di bawah masih menampilkan rekap
                                dalam satu tahun. Untuk filter bulanan, silakan pilih filter di atas.</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Rincian Kegiatan</th>
                                            <th>Nama</th>
                                            <th>Kec. Asal</th>
                                            <th>Jumlah Kegiatan</th>
                                            <th>Estimasi Honor Diterima</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Rincian Kegiatan</th>
                                            <th>Nama</th>
                                            <th>Kec. Asal</th>
                                            <th>Jumlah Kegiatan</th>
                                            <th>Estimasi Honor Diterima</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($mitraAdaHonor as $mitra)
                                            <tr>
                                                <td>
                                                    <div class="form-button-action">
                                                        <form
                                                            action="{{ url('dashboard/estimasi-honor', property_exists($mitra, 'id_mitra') ? $mitra->id_mitra : $mitra->mitra_id) }}">
                                                            <button type="submit" data-bs-toggle="tooltip"
                                                                title="Detil Kegiatan Mitra"
                                                                class="btn btn-link btn-primary px-2" {{-- data-original-title="Detil Kegiatan Mitra" --}}>
                                                                <i class="fa fa-eye"></i>
                                                        </form>
                                                    </div>
                                                </td>
                                                <th scope="row">{{ $mitra->nama }}</th>
                                                <td>{{ $mitra->kec_asal }}</td>
                                                <td>{{ $mitra->total_kegiatan }}</td>
                                                <td data-order="{{ $mitra->total_estimasi_honor }}">Rp
                                                    {{ number_format($mitra->total_estimasi_honor, 0, ',', '.') }}</td>
                                                {{-- @if (property_exists($mitra, 'total_honor'))
                                                    <td data-order="{{ $mitra->total_honor }}">Rp
                                                        {{ number_format($mitra->total_honor, 0, ',', '.') }}</td>
                                                @else
                                                    <td data-order="0">-</td>
                                                @endif --}}
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

@section('script')
    <script>
        var urlDashboard = "{{ route('dashboard') }}";
        $(document).ready(function() {
            $('#filter-bulan').change(function() {
                var bulan = $(this).val();
                var tahun = $('#filter-tahun').val();
                if (bulan && tahun) {
                    ajax(bulan, tahun);
                }
                $('#helper-text').text('');
            });

            $('#filter-tahun').change(function() {
                var tahun = $(this).val();
                var bulan = $('#filter-bulan').val();
                if (bulan && tahun) {
                    ajax(bulan, tahun);
                }
                $('#helper-text').text('');
            });
        });

        function ajax(bulan, tahun) {
            // console.log(bulan, tahun);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('dashboard-bulanan') }}",
                type: "POST",
                data: {
                    bulan: bulan,
                    tahun: tahun
                },
                success: function(data) {
                    var table = $('#basic-datatables').DataTable().destroy();
                    $('#basic-datatables tbody').empty();
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            $('#basic-datatables tbody').append(`
              <tr>
                <td>
                  <div class="form-button-action">
                    <form action="${urlDashboard}/estimasi-honor/${item.id_mitra ? item.id_mitra : item.mitra_id}">
                      <button
                        type="submit"
                        data-bs-toggle="tooltip"
                        title="Detil Kegiatan Mitra"
                        class="btn btn-link btn-primary px-2"
                        data-original-title="Detil Kegiatan Mitra"
                      >
                        <i class="fa fa-eye"></i>
                      </button>
                    </form>
                  </div>
                </td>
                <th scope="row">${item.nama}</th>
                <td>${item.kec_asal}</td>
                <td>${item.total_kegiatan}</td>
                <td data-order="${item.total_estimasi_honor}">Rp ${new Intl.NumberFormat('id-ID').format(item.total_estimasi_honor)}</td>
                
              </tr>
            `);
                        });
                    } else {
                        $('#basic-datatables tbody').append(`
            <tr>
              <td colspan="5" class="text-center">No data available</td>
            </tr>
          `);
                    }

                    $("#basic-datatables").DataTable({});
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }
    </script>
@endsection
