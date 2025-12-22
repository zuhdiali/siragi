@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('select2/css/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('select2/css/select2-bootstrap-5-theme.min.css')}}" />
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{route('kegiatan.store')}}" method="POST">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="card-header">
                            <div class="card-title">Tambah Kegiatan</div>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5> SBKS (Standard Biaya Kegiatan Statistik) </h5>
                                        {{-- <label for="filter_sbks">Pilih kegiatan:</label> --}}
                                        <select name="filter_sbks" id="filter_sbks" class="form-select"   data-placeholder="Pilih kegiatan">
                                            {{-- <option value="">-- Pilih Kegiatan ---</option> --}}
                                            <option value=""></option>
                                            @foreach($sbks as $item)
                                                <option value="{{$item->nama_kegiatan}}" {{(old("filter_sbks")==$item->nama_kegiatan ? 'selected' : '')}}>{{$item->nama_kegiatan_dan_singkatan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis Kegiatan</label><br />
                                        <div class="d-flex">
                                            <div class="form-check">
                                                <input
                                                class="form-check-input"
                                                type="radio"
                                                name="jenis_kegiatan"
                                                id="updating"
                                                value="updating"
                                                {{ old('jenis_kegiatan') == 'updating' ? 'checked' : '' }}
                                                />
                                                <label
                                                class="form-check-label"
                                                for="updating"
                                                >
                                                Updating
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                class="form-check-input"
                                                type="radio"
                                                name="jenis_kegiatan"
                                                id="pendataan"
                                                value="pendataan"
                                                {{ old('jenis_kegiatan') == 'pendataan' ? 'checked' : '' }}
                                                />
                                                <label
                                                class="form-check-label"
                                                for="pendataan"
                                                >
                                                Pendataan
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                class="form-check-input"
                                                type="radio"
                                                name="jenis_kegiatan"
                                                id="pengolahan"
                                                value="pengolahan"
                                                {{ old('jenis_kegiatan') == 'pengolahan' ? 'checked' : '' }}
                                                />
                                                <label
                                                class="form-check-label"
                                                for="pengolahan"
                                                >
                                                Pengolahan
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <hr />
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group {{$errors->has('nama') ? 'has-error has-feedback' : ''}}">
                                        <label for="nama">Nama Kegiatan</label>
                                        <input
                                          type="text"
                                          class="form-control"
                                          id="nama"
                                          name="nama"
                                          placeholder="Masukkan nama"
                                          value="{{ old('nama') }}"
                                        />
                                        @if ($errors->has('nama'))
                                        <small class="form-text text-muted">{{ $errors->first('nama') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group {{$errors->has('tgl_mulai') ? 'has-error has-feedback' : ''}}">
                                        <label for="tgl_mulai">Tanggal Mulai</label>
                                        <input
                                          type="date"
                                          class="form-control"
                                          id="tgl_mulai"
                                          name="tgl_mulai"
                                          value="{{ old('tgl_mulai') }}"
                                        />
                                        @if ($errors->has('tgl_mulai'))
                                        <small class="form-text text-muted">{{ $errors->first('tgl_mulai') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            Format: bulan/tanggal/tahun
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group {{$errors->has('tgl_selesai') ? 'has-error has-feedback' : ''}}">
                                        <label for="tgl_selesai">Tanggal Selesai</label>
                                        <input
                                          type="date"
                                          class="form-control"
                                          id="tgl_selesai"
                                          name="tgl_selesai"
                                          value="{{ old('tgl_selesai') }}"
                                        />
                                        @if ($errors->has('tgl_selesai'))
                                        <small class="form-text text-muted">{{ $errors->first('tgl_selesai') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            Format: bulan/tanggal/tahun
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group  {{$errors->has('id_pjk') ? 'has-error has-feedback' : ''}}">
                                        <label for="id_pjk">Penanggung Jawab Kegiatan</strong></label
                                        >
                                        <select
                                            class="form-select"
                                            id="id_pjk"
                                            name="id_pjk"
                                        >
                                            <option value="">(Pilih salah satu)</option>
                                            @foreach ($pegawais as $item)
                                                <option value="{{$item->id}}" {{ old('id_pjk') ? (old('id_pjk') == $item->id ? 'selected' : '') : (Auth::user()->id == $item->id ? 'selected' : '')}}>{{$item->nama}}</option>
                                            @endforeach
                                          
                                        </select>
                                        @if ($errors->has('id_pjk'))
                                        <small class="form-text text-muted">{{ $errors->first('id_pjk') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div>

                                    <div class="form-group  {{$errors->has('tim') ? 'has-error has-feedback' : ''}}">
                                        <label for="tim"
                                          >Tim</label
                                        >
                                        <select
                                          class="form-select"
                                          id="tim"
                                          name="tim"
                                        >
                                          <option value="">(Pilih salah satu)</option>
                                          <option value="11011" {{ old('tim') ? (old('tim') == "11011" ? 'selected' : '') : (Auth::user()->tim == "11011" ? 'selected' : '')}}>Umum</option>
                                          <option value="11012" {{ old('tim') ? (old('tim') == "11012" ? 'selected' : '') : (Auth::user()->tim == "11012" ? 'selected' : '')}}>Statistik Sosial</option>
                                          <option value="11013" {{ old('tim') ? (old('tim') == "11013" ? 'selected' : '') : (Auth::user()->tim == "11013" ? 'selected' : '')}}>Statistik Ekonomi Produksi</option>
                                          <option value="11015" {{ old('tim') ? (old('tim') == "11015" ? 'selected' : '') : (Auth::user()->tim == "11015" ? 'selected' : '')}}>Neraca dan Analisis Statistik</option>
                                          <option value="11014" {{ old('tim') ? (old('tim') == "11014" ? 'selected' : '') : (Auth::user()->tim == "11014" ? 'selected' : '')}}>Statistik Ekonomi Distribusi</option>
                                          <option value="11016" {{ old('tim') ? (old('tim') == "11016" ? 'selected' : '') : (Auth::user()->tim == "11016" ? 'selected' : '')}}>TI dan Pengolahan</option>
                                          <option value="11017" {{ old('tim') ? (old('tim') == "11017" ? 'selected' : '') : (Auth::user()->tim == "11017" ? 'selected' : '')}}>Diseminasi, Publisitas, dan Humas</option>
                                          <option value="11018" {{ old('tim') ? (old('tim') == "11018" ? 'selected' : '') : (Auth::user()->tim == "11018" ? 'selected' : '')}}>Pembinaan Statistik Sektoral</option>
                                        </select>
                                        @if ($errors->has('tim'))
                                        <small class="form-text text-muted">{{ $errors->first('tim') }}</small>
                                        @endif
                                    </div>

                                    <div class="form-group {{$errors->has('beban_anggaran') ? 'has-error has-feedback' : ''}}">
                                        <label for="beban_anggaran">Beban anggaran BOS</label>
                                        <input
                                          type="text"
                                          class="form-control"
                                          id="beban_anggaran"
                                          name="beban_anggaran"
                                          placeholder="Misal: 2904.BMA.006.005.521213"
                                          value="{{ old('beban_anggaran') }}"
                                        />
                                        @if ($errors->has('beban_anggaran'))
                                        <small class="form-text text-muted">{{ $errors->first('beban_anggaran') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            Misal: 2904.BMA.006.005.521213. (Kosongkan jika tidak ada atau lupa)
                                        </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    
                                    <div class="form-group  {{$errors->has('satuan_honor_pengawasan') ? 'has-error has-feedback' : ''}}">
                                        <label for="satuan_honor_pengawasan">Satuan Honor <strong>Pengawasan</strong></label
                                        >
                                        <select
                                            class="form-select"
                                            id="satuan_honor_pengawasan"
                                            name="satuan_honor_pengawasan"
                                        >
                                            <option value="">(Pilih salah satu)</option>
                                            <option value="Dokumen" {{ old('satuan_honor_pengawasan') == 'Dokumen' ? 'selected' : ''}}>Dokumen</option>
                                            <option value="SLS" {{old('satuan_honor_pengawasan') == 'SLS' ? 'selected' : ''}}>SLS (Satuan Lingkungan Setempat)</option>
                                            <option value="BS" {{old('satuan_honor_pengawasan') == 'BS' ? 'selected' : ''}}>BS (Blok Sensus)</option>
                                            <option value="Ruta" {{old('satuan_honor_pengawasan') == 'Ruta' ? 'selected' : ''}}>Rumah Tangga</option>
                                            <option value="OK" {{old('satuan_honor_pengawasan') == 'OK' ? 'selected' : ''}}>Orang Kegiatan (OK)</option>
                                            <option value="OH" {{old('satuan_honor_pengawasan') == 'OH' ? 'selected' : ''}}>Orang Harian (OH)</option>
                                            <option value="OB" {{old('satuan_honor_pengawasan') == 'OB' ? 'selected' : ''}}>Orang Bulan (OB)</option>
                                            <option value="OP" {{old('satuan_honor_pencacahan') == 'OP' ? 'selected' : ''}}>Orang Perjalanan (OP)</option>
                                            <option value="Segmen" {{old('satuan_honor_pengawasan') == 'Segmen' ? 'selected' : ''}}>Segmen</option>
                                            <option value="EA" {{old('satuan_honor_pengawasan') == 'EA' ? 'selected' : ''}}>Enumeration Area (EA)</option>
                                            <option value="Responden" {{old('satuan_honor_pengawasan') == 'Responden' ? 'selected' : ''}}>Responden</option>
                                            <option value="Pasar" {{old('satuan_honor_pengawasan') == 'Pasar' ? 'selected' : ''}}>Pasar</option>
                                          
                                        </select>
                                        @if ($errors->has('satuan_honor_pengawasan'))
                                        <small class="form-text text-muted">{{ $errors->first('satuan_honor_pengawasan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            {{-- Satuan honor bisa dikosongkan --}}
                                        </small>
                                        @endif
                                    </div>

                                    <div class="form-group {{$errors->has('honor_pengawasan') ? 'has-error has-feedback' : ''}}">
                                        <label for="honor_pengawasan">Honor <strong>Pengawasan</strong></label>
                                        <input
                                          type="number"
                                          class="form-control"
                                          id="honor_pengawasan"
                                          name="honor_pengawasan"
                                          placeholder="Masukkan honor per satuan"
                                          value="{{ old('honor_pengawasan') }}"
                                        />
                                        @if ($errors->has('honor_pengawasan'))
                                        <small class="form-text text-muted">{{ $errors->first('honor_pengawasan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            {{-- (isian honor dapat dikosongkan jika satuan honor belum dipilih) --}}
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group  {{$errors->has('satuan_honor_pencacahan') ? 'has-error has-feedback' : ''}}">
                                        <label for="satuan_honor_pencacahan">Satuan Honor <strong>Pencacahan / Pengolahan</strong></label
                                        >
                                        <select
                                            class="form-select"
                                            id="satuan_honor_pencacahan"
                                            name="satuan_honor_pencacahan"
                                        >
                                            <option value="">(Pilih salah satu)</option>
                                            <option value="Dokumen" {{ old('satuan_honor_pencacahan') == 'Dokumen' ? 'selected' : ''}}>Dokumen</option>
                                            <option value="SLS" {{old('satuan_honor_pencacahan') == 'SLS' ? 'selected' : ''}}>SLS (Satuan Lingkungan Setempat)</option>
                                            <option value="BS" {{old('satuan_honor_pencacahan') == 'BS' ? 'selected' : ''}}>BS (Blok Sensus)</option>
                                            <option value="Ruta" {{old('satuan_honor_pencacahan') == 'Ruta' ? 'selected' : ''}}>Rumah Tangga</option>
                                            <option value="OK" {{old('satuan_honor_pencacahan') == 'OK' ? 'selected' : ''}}>Orang Kegiatan (OK)</option>
                                            <option value="OH" {{old('satuan_honor_pencacahan') == 'OH' ? 'selected' : ''}}>Orang Harian (OH)</option>
                                            <option value="OB" {{old('satuan_honor_pencacahan') == 'OB' ? 'selected' : ''}}>Orang Bulan (OB)</option>
                                            <option value="OP" {{old('satuan_honor_pencacahan') == 'OP' ? 'selected' : ''}}>Orang Perjalanan (OP)</option>
                                            <option value="Segmen" {{old('satuan_honor_pencacahan') == 'Segmen' ? 'selected' : ''}}>Segmen</option>
                                            <option value="EA" {{old('satuan_honor_pencacahan') == 'EA' ? 'selected' : ''}}>Enumeration Area (EA)</option>
                                            <option value="Responden" {{old('satuan_honor_pencacahan') == 'Responden' ? 'selected' : ''}}>Responden</option>
                                            <option value="Pasar" {{old('satuan_honor_pencacahan') == 'Pasar' ? 'selected' : ''}}>Pasar</option>
                                        </select>
                                        @if ($errors->has('satuan_honor_pencacahan'))
                                        <small class="form-text text-muted">{{ $errors->first('satuan_honor_pencacahan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            {{-- Satuan honor bisa dikosongkan --}}
                                        </small>
                                        @endif
                                    </div>

                                    <div class="form-group {{$errors->has('honor_pencacahan') ? 'has-error has-feedback' : ''}}">
                                        <label for="honor_pencacahan">Honor <strong>Pencacahan / Pengolahan</strong></label>
                                        <input
                                          type="number"
                                          class="form-control"
                                          id="honor_pencacahan"
                                          name="honor_pencacahan"
                                          placeholder="Masukkan honor per satuan"
                                          value="{{ old('honor_pencacahan') }}"
                                        />
                                        @if ($errors->has('honor_pencacahan'))
                                        <small class="form-text text-muted">{{ $errors->first('honor_pencacahan') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            {{-- (isian honor dapat dikosongkan jika satuan honor belum dipilih) --}}
                                        </small>
                                        @endif
                                    </div> 

                                    <div class="form-group">
                                        <label for="progress">Progress (Kemajuan) Kegiatan</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="progress"
                                            name="progress"
                                            placeholder="Masukkan progress kegiatan"
                                            min="0"
                                            max="100"
                                            value="{{ old('progress') ? old('progress') : 0 }}"
                                        />
                                        @if ($errors->has('progress'))
                                        <small class="form-text text-muted">{{ $errors->first('progress') }}</small>
                                        @else
                                        <small  class="form-text text-muted">
                                            Isikan dalam rentang 0-100
                                        </small>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <hr />
                                    <div class="{{$errors->has('pegawai[]') ? 'has-error has-feedback' : ''}}">
                                        <label for="pegawai[]" >Pegawai Terlibat</label>
                                        <select class="form-select" id="pegawai" name="pegawai[]" multiple="multiple">
                                            @foreach ($pegawais as $item)
                                            <option value="{{$item->id}}">
                                                {{$item->nama}}</option>
                                                {{-- <option value="{{$item->id}}">{{$item->nama}}</option> --}}
                                            @endforeach
                                        </select>
                                        @if ($errors->has('pegawai[]'))
                                        <small class="form-text text-muted">{{ $errors->first('pegawai[]') }}</small>
                                        @else
                                        <small class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <hr />
                                    <div class="{{$errors->has('mitra[]') ? 'has-error has-feedback' : ''}}">
                                        <label for="mitra[]">Mitra Terlibat</label>
                                        <select class=" form-select" id="mitra" name="mitra[]" multiple="multiple">
                                            @foreach ($mitras as $item)
                                            <option value="{{$item->id}}" >
                                                {{$item->nama}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('mitra[]'))
                                        <small class="form-text text-muted">{{ $errors->first('mitra[]') }}</small>
                                        @else
                                        <small class="form-text text-muted">
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Tambah Kegiatan</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('select2/js/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#pegawai').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
        });

        $('#mitra').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
        });

        $('#filter_sbks').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
        });

        $('#filter_sbks, input[name="jenis_kegiatan"]').on('change', function() {
            var jenis_kegiatan = $('input[name="jenis_kegiatan"]:checked').val();
            var nama_kegiatan = $('#filter_sbks').val();
            // console.log(jenis_kegiatan, nama_kegiatan);
            $.ajax({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('sbks.get-honor') }}',
                type: 'POST',
                data: {
                    jenis_kegiatan: jenis_kegiatan,
                    nama_kegiatan: nama_kegiatan
                },
                success: function(data) {
                    // console.log(data);
                    $('#nama').val(data.nama_kegiatan);
                    $('#id_pjk').val(data.id_pjk);
                    $('#tim').val(data.tim);
                    $('#satuan_honor_pengawasan').val(data.satuan_honor_pengawasan);
                    $('#honor_pengawasan').val(data.honor_pengawasan);
                    $('#satuan_honor_pencacahan').val(data.satuan_honor_pendataan_atau_pengolahan);
                    $('#honor_pencacahan').val(data.honor_pendataan_atau_pengolahan);
                    $('#beban_anggaran').val(data.beban_anggaran);
                },
                error: function(err) {
                    // console.log(err);
                    $('#nama').val("");
                    $('#id_pjk').val("");
                    $('#tim').val("");
                    $('#satuan_honor_pengawasan').val("");
                    $('#honor_pengawasan').val(0);
                    $('#satuan_honor_pencacahan').val("");
                    $('#honor_pencacahan').val(0);
                }
            });
        });
    });
</script>

@endsection