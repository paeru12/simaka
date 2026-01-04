@php
use App\Models\Jadwal;
$user = Auth::user();
$jabatan = strtolower($user->jabatan->jabatan);
$punyaJadwal = Jadwal::where('guru_id', $user->guru_id)->exists();
@endphp
@if($jabatan == 'admin')
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
            <a class="nav-link {{Request::is('absensi-mapel') ? 'active' : 'collapsed'}}"
                href="{{route('absensi-mapel.index')}}">
                <i class="ri ri-qr-scan-line"></i>
                <span>Absensi Mapel</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{Request::is('absensi-harian') ? 'active' : 'collapsed'}}"
                href="/absensi-harian">
                <i class="ri ri-qr-scan-line"></i>
                <span>Absensi Harian</span>
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
            <a class="nav-link {{Request::is('rekap-absensi') ? 'active' : 'collapsed'}}"
                href="{{route('rekap-absensi.index')}}">
                <i class="ri ri-file-list-3-line"></i>
                <span>Rekapitulasi Absensi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{Request::is('laporan-gaji') ? 'active' : 'collapsed'}}"
                href="{{route('laporan-gaji.index')}}">
                <i class="ri ri-money-dollar-circle-line"></i>
                <span>Laporan Gaji</span>
            </a>
        </li>

        <li class="nav-heading">Administrator</li>
        <li class="nav-item ">
            <a class="nav-link {{Request::is('administrator') ? 'active' : 'collapsed'}}" href="{{route('administrator.index')}}">
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
            <a class="nav-link {{Request::is('potongan-gaji') ? 'active' : 'collapsed'}}"
                href="{{route('potongan-gaji.index')}}">
                <i class="bi bi-gear"></i>
                <span>Potongan Gaji</span>
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
@elseif($jabatan == 'guru' || $punyaJadwal)
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
            <a class="nav-link {{Request::is('absensi-mapel') ? 'active' : 'collapsed'}}"
                href="{{route('absensi-mapel.index')}}">
                <i class="ri ri-qr-scan-line"></i>
                <span>Absensi Mapel</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{Request::is('absensi-harian') ? 'active' : 'collapsed'}}"
                href="/absensi-harian">
                <i class="ri ri-qr-scan-line"></i>
                <span>Absensi Harian</span>
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
            <a class="nav-link {{Request::is('rekap-absensi/guru') ? 'active' : 'collapsed'}}"
                href="{{route('rekap.dindex')}}">
                <i class="ri ri-file-list-3-line"></i>
                <span>Rekapitulasi Absensi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{Request::is('laporan-gaji') ? 'active' : 'collapsed'}}"
                href="{{route('laporan-gaji.index')}}">
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
            <a class="nav-link {{Request::is('absensi-harian') ? 'active' : 'collapsed'}}"
                href="/absensi-harian">
                <i class="ri ri-qr-scan-line"></i>
                <span>Absensi Harian</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{Request::is('rekapp/staff') ? 'active' : 'collapsed'}}"
                href="{{route('rekap.indexAll')}}">
                <i class="ri ri-file-list-3-line"></i>
                <span>Rekapitulasi Absensi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{Request::is('laporan-gaji-all') ? 'active' : 'collapsed'}}"
                href="{{route('gaji.indexAll')}}">
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