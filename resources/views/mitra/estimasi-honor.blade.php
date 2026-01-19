@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Daftar Kegiatan {{ $mitra->nama }}</h3>
                    <h6 class="op-7 mb-2"></h6>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    {{-- <a href="{{route('kegiatan.create')}}" class="btn btn-primary btn-round">Tambah kegiatan</a> --}}
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
                                        <h4 class="card-title">{{ count($kegiatan_mitra) }}</h4>
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
                            <h4 class="card-title">Filter Tahun dan Bulan Kegiatan</h4>
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
                            <h4 class="card-title">Daftar Kegiatan {{ $mitra->nama }}</h4>
                            <small class="text-danger" id="helper-text">Daftar di bawah masih menampilkan kegiatan dalam
                                satu tahun. Untuk filter bulanan, silakan pilih filter di atas.</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">Aksi</th>
                                            <th>Nama Kegiatan</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Estimasi Honor Yang Diterima</th>

                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>Nama Kegiatan</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Estimasi Honor Yang Diterima</th>

                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($kegiatan_mitra as $kegiatan)
                                            <tr>
                                                <td>

                                                    <div class="form-button-action">
                                                        <form
                                                            action="{{ url('kegiatan/' . str_replace('_', '-', $kegiatan->kegiatan->jenis_kak) . '/show', $kegiatan->kegiatan->id) }}">
                                                            <button type="submit" data-bs-toggle="tooltip"
                                                                title="Detil Kegiatan" class="btn btn-link btn-primary px-2"
                                                                data-original-title="Detil Kegiatan">
                                                                <i class="fa fa-eye"></i>
                                                        </form>

                                                    </div>
                                                </td>
                                                {{-- <th scope="row">{{(strlen($kegiatan->kegiatan->nama)>90 ? substr($kegiatan->kegiatan->nama, 0, 90) . '...' : $kegiatan->nama)}}</th> --}}
                                                <th scope="row">{{ $kegiatan->kegiatan->nama }}</th>
                                                <td>{{ Carbon\Carbon::parse($kegiatan->kegiatan->tgl_mulai)->locale('id')->translatedFormat('d M Y') }}
                                                </td>
                                                <td>{{ Carbon\Carbon::parse($kegiatan->kegiatan->tgl_selesai)->locale('id')->translatedFormat('d M Y') }}
                                                </td>
                                                <td data-order="{{ $kegiatan->transport_bayar }}">Rp
                                                    {{ number_format($kegiatan->transport_bayar, 0, ',', '.') }} </td>


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
        var urlKegiatan = "{{ route('kegiatan.index') }}";
        $(document).ready(function() {
            $('#filter-bulan, #filter-tahun').change(function() {
                var bulan = $('#filter-bulan').val();
                var tahun = $('#filter-tahun').val();
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
                url: "{{ route('dashboard-estimasi-honor-bulanan') }}",
                type: "POST",
                data: {
                    bulan: bulan,
                    tahun: tahun,
                    id_mitra: {{ $mitra->id }}
                },
                success: function(data) {
                    console.log(data);
                    $('#basic-datatables').DataTable().destroy();
                    $('#basic-datatables tbody').empty();
                    data.forEach(function(item) {
                        $('#basic-datatables tbody').append(`
                          <tr>
                            <td>
                              <div class="form-button-action">
                                <form action="${urlKegiatan}/${item.kegiatan.jenis_kak}/show/${item.kegiatan.id}">
                                  <button
                                    type="submit"
                                    data-bs-toggle="tooltip"
                                    title="Detil Kegiatan"
                                    class="btn btn-link btn-primary px-2"
                                    data-original-title="Detil Kegiatan"
                                  >
                                  <i class="fa fa-eye"></i>
                                </form>
                              </div>
                            <th scope="row">${item.kegiatan.nama}</th>
                            <td>${new Date(item.kegiatan.tgl_mulai).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                            <td>${new Date(item.kegiatan.tgl_selesai).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                            <td data-order="${(item.transport_bayar)}">Rp ${new Intl.NumberFormat('id-ID').format((item.transport_bayar))}</td>
                            
                          </tr>
                        `);
                    });

                    $("#basic-datatables").DataTable({
                        columnDefs: [{
                                targets: 4,
                                type: 'num-fmt'
                            },

                        ]
                    });
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }
    </script>
@endsection
