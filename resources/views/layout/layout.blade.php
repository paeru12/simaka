@php
use App\Models\Setting;
$setting = Setting::all();
$logos = $setting->where('key', 'logo')->first();
$namas = $setting->where('key', 'nama')->first();
$logo = $logos->value;
$nama = $namas->value;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title') - {{ $nama }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="{{ asset($logo) }}" rel="icon">
    <link href="{{ asset($logo) }}" rel="apple-touch-icon">
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dist/sweetalert2.all.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
</head>

<body>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex justify-content-between">
            <a href="/" class="logo d-flex align-items-center">
                <img src="{{ asset($logo) }}">
                <span class="d-none d-lg-block fs-5">{{ $nama }}</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        <img src="{{ asset(Auth::user()->guru->foto) }}" style="width: 30px; aspect-ratio: 1/1; object-fit: cover;" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">Hi,
                            {{ ucfirst(Auth::user()->guru->nama) }}.</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6 class="text-capitalize">{{ Auth::user()->guru->nama }}</h6>
                            <span>Guru & Staff</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.edits', Auth::user()->guru_id) }}">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center logout" href="{{route('logout')}}">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <!-- ======= Sidebar ======= -->
    @if(Auth::user()->jabatan->jabatan == 'admin')
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item ">
                <a class="nav-link {{Request::is('/') ? 'active' : 'collapsed'}}"
                    href="/">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Absensi</li>

            <li class="nav-item">
                <a class="nav-link {{Request::is('absensi') ? 'active' : 'collapsed'}}"
                    href="{{route('absensi.index')}}">
                    <i class="ri ri-qr-scan-line"></i>
                    <span>Absensi</span>
                </a>
            </li>
            <li class="nav-heading">Data Guru</li>

            <li class="nav-item">
                <a class="nav-link {{Request::is('jabatan') ? 'active' : 'collapsed'}}"
                    href="{{route('jabatan.index')}}">
                    <i class="ri ri-briefcase-4-line"></i>
                    <span>Jabatan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('guru') ? 'active' : 'collapsed'}}"
                    href="{{route('guru.index')}}">
                    <i class="ri ri-user-3-line"></i>
                    <span>Guru</span>
                </a>
            </li>
            <li class="nav-heading">Master Data</li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('ruangan') ? 'active' : 'collapsed'}}"
                    href="{{route('ruangan.index')}}">
                    <i class="ri ri-building-3-fill"></i>
                    <span>Ruangan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('jurusan') ? 'active' : 'collapsed'}}"
                    href="{{route('jurusan.index')}}">
                    <i class="ri ri-coupon-4-line"></i>
                    <span>Jurusan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('kelas') ? 'active' : 'collapsed'}}"
                    href="{{route('kelas.index')}}">
                    <i class="ri ri-door-open-line"></i>
                    <span>Kelas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('mapel') ? 'active' : 'collapsed'}}"
                    href="{{route('mapel.index')}}">
                    <i class="ri ri-book-2-line"></i>
                    <span>Mapel</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('jadwal') ? 'active' : 'collapsed'}}"
                    href="{{route('jadwal.index')}}">
                    <i class="ri ri-calendar-todo-fill"></i>
                    <span>Jadwal</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('rekap') ? 'active' : 'collapsed'}}"
                    href="{{route('rekap.index')}}">
                    <i class="ri ri-file-list-3-line"></i>
                    <span>Rekapitulasi Absensi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('gaji') ? 'active' : 'collapsed'}}"
                    href="{{route('gaji.index')}}">
                    <i class="ri ri-money-dollar-circle-line"></i>
                    <span>Laporan Gaji</span>
                </a>
            </li>

            <li class="nav-heading">Administrator</li>
            <li class="nav-item ">
                <a class="nav-link collapsed" href="{{route('admin.index')}}">
                    <i class="ri ri-shield-user-line"></i>
                    <span>Administrator</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link {{ Request::is('profile/*') ? 'active' : 'collapsed' }}" href="{{ route('admin.edits', Auth::user()->guru_id) }}">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li class="nav-heading">Pengaturan</li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('setting') ? 'active' : 'collapsed'}}"
                    href="{{route('setting.index')}}">
                    <i class="bi bi-gear"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed logout" href="{{route('logout')}}">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                </a>
            </li>
        </ul>
    </aside>
    @else
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item ">
                <a class="nav-link {{Request::is('/') ? 'active' : 'collapsed'}}"
                    href="/">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Absensi</li>

            <li class="nav-item">
                <a class="nav-link {{Request::is('absensi') ? 'active' : 'collapsed'}}"
                    href="{{route('absensi.index')}}">
                    <i class="ri ri-qr-scan-line"></i>
                    <span>Absensi</span>
                </a>
            </li>
            <li class="nav-heading">Master Data</li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('jadwal') ? 'active' : 'collapsed'}}"
                    href="{{route('jadwal.index')}}">
                    <i class="ri ri-calendar-todo-fill"></i>
                    <span>Jadwal</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('rekap') ? 'active' : 'collapsed'}}"
                    href="{{route('rekap.index')}}">
                    <i class="ri ri-file-list-3-line"></i>
                    <span>Rekapitulasi Absensi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('gaji') ? 'active' : 'collapsed'}}"
                    href="{{route('gaji.index')}}">
                    <i class="ri ri-money-dollar-circle-line"></i>
                    <span>Laporan Gaji</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link {{ Request::is('profile/*') ? 'active' : 'collapsed' }}" href="{{route('admin.edits', Auth::user()->guru_id)}}">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed logout" href="{{route('logout')}}">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                </a>
            </li>
        </ul>
    </aside>
    @endif
    <!-- End Sidebar-->
    <main id="main" class="main">
        @yield('content')
    </main>

    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>
                    {{ Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('Y') }}</span></strong>. All Rights
            Reserved
        </div>

    </footer>
    @stack('scripts')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.logout').on('click', function(e) {
            e.preventDefault();
            const link = $(this).attr('href');
            Swal.fire({
                title: 'Anda Yakin Ingin Keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya!'
            }).then((result) => {
                if (result.isConfirmed) {
                    setTimeout(function() {
                        document.location.href = link;
                    });
                }
            })
        })
    </script>
</body>

</html>