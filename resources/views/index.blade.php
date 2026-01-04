@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Progress Kegiatan Bulan Ini</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Daftar Kegiatan Bulan Ini</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table class="table table-hover table-striped" id="basic-datatables">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kegiatanBulanIni as $item)
                                            <tr>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ Carbon\Carbon::parse($item->tgl_mulai)->locale('id')->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ Carbon\Carbon::parse($item->tgl_selesai)->locale('id')->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ $item->progress }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row"> --}}

                </div>
            </div>
        @endsection

        @section('script')
            <script>
                var barChart = document.getElementById("barChart").getContext("2d");
                var myBarChart = new Chart(barChart, {
                    type: "bar",
                    data: {
                        labels: [
                            @foreach ($kegiatanBulanIni as $item)
                                "{{ $item->nama }}",
                            @endforeach
                        ],
                        datasets: [{
                            label: "Progress",
                            backgroundColor: "rgb(23, 125, 255)",
                            borderColor: "rgb(23, 125, 255)",
                            data: [
                                @foreach ($kegiatanBulanIni as $item)
                                    {{ $item->progress }},
                                @endforeach
                            ],
                        }, ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                display: false,
                            }, ],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                },
                            }, ],
                        },
                    },
                });

                // INI WORK
                // var myBarChart = new Chart(barChart, {
                //       type: "horizontalBar",
                //       data: {
                //         labels: [ 
                //           @foreach ($kegiatanBulanIni as $item)
                //             "{{ $item->nama }}",
                //           @endforeach
                //         ],
                //         datasets: [
                //           {
                //             label: "Progress",
                //             backgroundColor: "rgb(23, 125, 255)",
                //             borderColor: "rgb(23, 125, 255)",
                //             data: [
                //               @foreach ($kegiatanBulanIni as $item)
                //                 {{ $item->progress }},
                //               @endforeach
                //             ],
                //           },
                //         ],
                //       },
                //       options: {
                //         responsive: true,
                //         maintainAspectRatio: false,
                //         scales: {
                //           xAxes: [
                //             {
                //               ticks: {
                //                 beginAtZero: true,
                //               },
                //             },
                //           ],
                //           yAxes: [
                //             {
                //               barThickness: 20, // Set the bar thickness here
                //             },
                //           ],
                //         },
                //         tooltips: {
                //           callbacks: {
                //             label: function(tooltipItem, data) {
                //               var label = data.datasets[tooltipItem.datasetIndex].label || '';
                //               if (label) {
                //                 label += ': ';
                //               }
                //               label += tooltipItem.xLabel + '%';
                //               return label;
                //             }
                //           }
                //         }
                //       },
                //     });
                // YANG DI ATAS WORK


                // Adjust canvas height based on the number of items
                // var canvasHeight = {{ count($kegiatanBulanIni) }} * 30; // 30px per item
                // document.getElementById("barChart").height = canvasHeight;

                // var myBarChart = new Chart(barChart, {
                //   type: "z",
                //   data: {
                //     labels: [
                //       @foreach ($kegiatanBulanIni as $item)
                //         "{{ $item->nama }}",
                //       @endforeach
                //     ],
                //     datasets: [
                //       {
                //         label: "Progress",
                //         backgroundColor: "rgb(23, 125, 255)",
                //         borderColor: "rgb(23, 125, 255)",
                //         data: [
                //           @foreach ($kegiatanBulanIni as $item)
                //             {{ $item->progress }},
                //           @endforeach
                //         ],
                //       },
                //     ],
                //   },
                //   options: {
                //     responsive: true,
                //     maintainAspectRatio: false,
                //     scales: {
                //       xAxes: [
                //         {
                //           display: false,
                //         },
                //       ],
                //       yAxes: [
                //         {
                //           ticks: {
                //             beginAtZero: true,
                //           },
                //         },
                //       ],
                //     },
                //   },
                // });
            </script>
        @endsection
