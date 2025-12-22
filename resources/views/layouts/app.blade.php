
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>SiRaGi (Sistem Penyuratan dan Kegiatan)</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="{{asset('img/logo.svg')}}"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="{{asset('js/plugin/webfont/webfont.min.js')}}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{asset('css/fonts.min.css')}}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
    
    <link rel="stylesheet" href="{{asset('css/plugins.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/kaiadmin.min.css')}}" />

    {{-- <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" /> --}}

    @yield('meta')
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="{{route('index')}}" class="logo">
              <img
                src="{{asset('img/siragi.svg')}}"
                alt="navbar brand"
                class="navbar-brand"
                height="30"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <li class="nav-item {{ Request::path() ==  '/' ? 'active' : ''  }}">
                    <a href="{{route('index')}}">
                        <i class="fas fa-home"></i>
                        <p>Beranda</p>
                    </a>
                </li>

                <li class="nav-item {{ str_contains(Request::path(), 'dashboard') ? 'active' : ''  }}">
                  <a href="{{route('dashboard')}}">
                      <i class="fas fa-tachometer-alt"></i>
                      <p>Dashboard Mitra</p>
                  </a>
              </li>

                <li class="nav-item {{ str_contains(Request::path(), 'kegiatan') ? 'active' : ''  }}">
                    <a href="{{route('kegiatan.index')}}">
                        <i class="fas fa-clipboard"></i>
                        <p>Kegiatan</p>
                    </a>
                </li>

                {{-- @if(Auth::user()->role == 'Admin') --}}
                <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#submenupembayaran">
                    <i class="fas fa-money-bill-wave"></i>
                    <p>Pembayaran</p>
                    <span class="caret"></span>
                  </a>
                  <div class="collapse {{ str_contains(Request::path(), 'pembayaran') ? 'show' : ''  }}" id="submenupembayaran">
                    <ul class="nav nav-collapse">
                      <li class="{{ str_contains(Request::path(), 'honor') ? 'active' : ''  }}">
                        <a href="{{route('pembayaran.honor')}}">
                          <span class="sub-item">Honor</span>
                        </a>
                      </li>
                      {{-- <li class="{{ str_contains(Request::path(), 'translok') ? 'active' : ''  }}">
                        <a href="{{route('pembayaran.translok')}}">
                          <span class="sub-item">Translok</span>
                        </a>
                      </li> --}}
                    </ul>
                  </div>
                </li>

                {{-- <li class="nav-item {{ str_contains(Request::path(), 'pembayaran') ? 'active' : ''  }}">
                    <a href="{{route('pembayaran.honor')}}">
                        <i class="fas fa-money-bill-wave"></i>
                        <p>Pembayaran</p>
                    </a>
                </li> --}}
                {{-- @endif --}}

                <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#submenu">
                    <i class="fas fa-envelope"></i>
                    <p>Surat Menyurat</p>
                    <span class="caret"></span>
                  </a>
                  <div class="collapse {{ str_contains(Request::path(), 'surat') ? 'show' : ''  }}" id="submenu">
                    <ul class="nav nav-collapse">
                      <li class="{{ str_contains(Request::path(), 'permintaan') ? 'active' : ''  }}">
                        <a href="{{route('surat.permintaan')}}">
                          <span class="sub-item">Surat Form Permintaan</span>
                        </a>
                      </li>

                      <li class="{{ str_contains(Request::path(), 'tugas') ? 'active' : ''  }}">
                        <a href="{{route('surat.tugas')}}">
                          <span class="sub-item">Surat Tugas</span>
                        </a>
                      </li>
                      
                      <li class="{{ str_contains(Request::path(), 'spd') ? 'active' : ''  }}">
                        <a href="{{route('surat.spd')}}">
                          <span class="sub-item">SPPD / Translok</span>
                        </a>
                      </li>

                      <li class="{{ str_contains(Request::path(), 'masuk') ? 'active' : ''  }}">
                        <a href="{{route('surat.masuk')}}">
                          <span class="sub-item">Surat Masuk</span>
                        </a>
                      </li>

                      <li class="{{ str_contains(Request::path(), 'keluar') ? 'active' : ''  }}">
                        <a href="{{route('surat.keluar')}}">
                          <span class="sub-item">Surat Keluar</span>
                        </a>
                      </li>

                      

                      @if(Auth::user()->role == 'Admin')
                      <li class="{{ str_contains(Request::path(), 'spk') ? 'active' : ''  }}">
                        <a href="{{route('surat.spk')}}">
                          <span class="sub-item">SPK</span>
                        </a>
                      </li>
                      
                      <li class="{{ str_contains(Request::path(), 'sk') ? 'active' : ''  }}">
                        <a href="{{route('surat.sk')}}">
                          <span class="sub-item">SK</span>
                        </a>
                      </li>
                      @endif
                    </ul>
                  </div>
                </li>

                <li class="nav-item {{ Request::path() ==  'sbks' ? 'active' : ''  }}">
                  <a href="{{route('sbks.index')}}">
                      <i class="fas fa-info-circle"></i>
                      <p>SBKS</p>
                  </a>
                </li>

                {{-- <li class="nav-item {{ Request::path() ==  'rekap' ? 'active' : ''  }}">
                  <a href="{{route('rekap')}}">
                      <i class="fas fa-clipboard-check"></i>
                      <p>Rekap</p>
                  </a>
              </li> --}}

                {{-- @if(Auth::check()) --}}
                  {{-- @if (Auth::user()->role == 'Penilai') --}}
                  {{-- <li class="nav-section">
                    <span class="sidebar-mini-icon">
                      <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Penilaian</h4>
                  </li> --}}
                  
                    {{-- @if (Auth::user()->id != 0)
                    <li class="nav-item {{ Request::path() ==  'penilaian' ? 'active' : ''  }}">
                      <a href="{{route('penilaian.index')}}">
                          <i class="fas fa-user-check"></i>
                          <p>Pegawai</p>
                      </a>
                    </li>
                    @endif --}}

                  {{-- <li class="nav-item {{ Request::path() ==  'penilaian/ruangan' ? 'active' : ''  }}">
                    <a href="{{route('penilaian.ruangan.index')}}">
                        <i class="fas fa-clipboard-check"></i>
                        <p>Ruangan</p>
                    </a>
                  </li> --}}
                  {{-- @endif --}}
                  

                  
                  
                  <li class="nav-section">
                    <span class="sidebar-mini-icon">
                      <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Admin</h4>
                  </li>

                  <li class="nav-item {{ str_contains(Request::path(), 'pegawai') ? 'active' : ''  }}">
                    <a href="{{route('pegawai.index')}}">
                      <i class="fas fa-users-cog"></i>
                        <p>Manajemen Pegawai</p>
                    </a>
                  </li>
                  
                  @if (Auth::user()->role == 'Admin')
                  <li class="nav-item {{ str_contains(Request::path(), 'mitra') && !str_contains(Request::path(), 'pembayaran') ? 'active' : ''  }}">
                    <a href="{{route('mitra.index')}}">
                      <i class="fas fa-users"></i>
                        <p>Manajemen Mitra</p>
                    </a>
                  </li>

                  {{-- <li class="nav-item {{ str_contains(Request::path(), 'user') ? 'active' : ''  }}">
                      <a href="{{route('user.index')}}">
                          <i class="fas fa-users-cog"></i>
                          <p>Manajemen Akun</p>
                      </a>
                  </li> --}}
                  @endif
                {{-- @endif --}}

            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->
      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
     
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="{{asset('img/siragi.svg')}}"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="{{asset('img/profile.jpg')}}"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hai,</span>
                      <span class="fw-bold">
                        @if(Auth::check())
                        {{Auth::user()->nama}}
                        @else
                        {{'Tamu'}}
                        @endif
                      </span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="{{asset('img/profile.jpg')}}"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4>
                              @if(Auth::check()) {{Auth::user()->nama}} @else {{'Tamu'}} @endif
                            </h4>
                            <p class="text-muted">
                              @if(Auth::check()) {{Auth::user()->role}} @else {{'Tamu'}} @endif
                            </p>
                            {{-- <a
                              href="profile.html"
                              class="btn btn-xs btn-secondary btn-sm"
                              >View Profile</a
                            > --}}
                          </div>
                        </div>
                      </li>
                      <li>

                        <div class="dropdown-divider"></div>
                        @if (Auth::check())
                        <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
                        @else
                        <a class="dropdown-item" href="{{route('login')}}">Login</a>
                        @endif
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>

            </div>
          </nav>
          <!-- End Navbar -->
        </div>
        


        @yield('content')

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              {{-- <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    ThemeKita
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul> --}}
            </nav>
            <div class="copyright">
              2025, dikembangkan oleh
              <a href="https://wa.me/6282328839199">Zuhdi Ali Hisyam</a>
            </div>
            <div>
              Didesain oleh
              <a target="_blank" href="http://www.themekita.com">ThemeKita</a>.
            </div>
          </div>
        </footer>
      </div>

    </div>
    <!--   Core JS Files   -->
    <script src="{{asset('js/core/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('js/core/popper.min.js')}}"></script>
    <script src="{{asset('js/core/bootstrap.min.js')}}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{asset('js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>

    <!-- Chart JS -->
    <script src="{{asset('js/plugin/chart.js/chart.min.js')}}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{asset('js/plugin/jquery.sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Chart Circle -->
    <script src="{{asset('js/plugin/chart-circle/circles.min.js')}}"></script>

    <!-- Datatables -->
    <script src="{{asset('js/plugin/datatables/datatables.min.js')}}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{asset('js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{asset('js/plugin/jsvectormap/jsvectormap.min.js')}}"></script>
    <script src="{{asset('js/plugin/jsvectormap/world.js')}}"></script>

    <!-- Sweet Alert -->
    <script src="{{asset('js/plugin/sweetalert/sweetalert.min.js')}}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{asset('js/kaiadmin.min.js')}}"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    {{-- <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script> --}}
    <script>
      $(document).ready(function () {
        $("#basic-datatables").DataTable({});
        $("#basic-datatables-2").DataTable({});
        $("#basic-datatables-3").DataTable({});

        $("#multi-filter-select").DataTable({
          pageLength: 10,
          initComplete: function () {
            // Tambahkan baris filter di bawah header
            $("#multi-filter-select thead").append('<tr></tr>');
                var filterRow = $("#multi-filter-select thead tr").last();

            this.api()
              .columns()
              .every(function () {
                var column = this;

                // Tambahkan dropdown filter ke setiap kolom di baris filter
                var select = $('<select class="form-select"><option value=""></option></select>')
                            .appendTo($("<th></th>").appendTo(filterRow))
                            .on("change", function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column.search(val ? "^" + val + "$" : "", true, false).draw();
                            });

                column
                  .data()
                  .unique()
                  .sort()
                  .each(function (d, j) {
                    select.append(
                      '<option value="' + d + '">' + d + "</option>"
                    );
                  });
              });
          },
        });

        SessionSuccess.init();
      });
      //== Class definition
      var SessionSuccess = (function () {
        return {
          //== Init
          init: function () {
            @if(session('success'))
              swal({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                type: "success",
                buttons: {
                  confirm: {
                    className: "btn btn-success",
                  },
                },
              });
            @endif

            @if(session('error'))
              swal({
                title: "Gagal!",
                text: "{{ session('error') }}",
                type: "error",
                buttons: {
                  confirm: {
                    className: "btn btn-danger",
                  },
                },
              });
            @endif

            @if(session('warning'))
              swal({
                title: "Peringatan!",
                text: "{{ session('warning') }}",
                type: "warning",
                buttons: {
                  confirm: {
                    className: "btn btn-warning",
                  },
                },
              });
            @endif
          },
        };
      })();

      function copyText(text) {
          navigator.clipboard.writeText(text).then(() => {
              // Pesan muncul di sini
              alert('Teks berhasil disalin: ' + text);
          }).catch(err => {
              console.error('Gagal salin:', err);
          });
      }
    </script>




    @yield('script')
  </body>
</html>
