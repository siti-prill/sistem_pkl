<nav class="sidebar">
    <div class="sidebar-brand" style="flex-direction: column; align-items: center; text-align: center;">
        <div style="display: flex; gap: 12px; align-items: center;">
            <img src="{{ logo_url() }}" alt="Logo SMK 2" class="sidebar-logo"
                style="width: 50px; height: 50px; object-fit: contain;">
            <img src="/images/barsilya.jpeg" alt="Barsilya" class="sidebar-logo"
                style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
        </div>
        <div style="margin-top: 10px;">
            <h3 style="margin-bottom: 2px;">Sistem PKL</h3>
            <small>Management PKL</small>
        </div>
    </div>

    <div class="sidebar-menu">
        @auth
            @if (auth()->user()->role == 'admin')
                <div class="menu-label">Menu Utama</div>

                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="menu-label">Master Data</div>

                <div class="nav-item">
                    <a href="{{ route('admin.guru.index') }}"
                        class="nav-link {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Data Guru</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('admin.siswa.index') }}"
                        class="nav-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i>
                        <span>Data Siswa</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('admin.kompetensi.index') }}"
                        class="nav-link {{ request()->routeIs('admin.kompetensi.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Data Kompetensi</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('admin.industri.index') }}"
                        class="nav-link {{ request()->routeIs('admin.industri.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Data Industri</span>
                    </a>
                </div>

                <div class="menu-label">Transaksi</div>

                <div class="nav-item">
                    <a href="{{ route('admin.pengajuan.index') }}"
                        class="nav-link {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
                        <i class="fas fa-paper-plane"></i>
                        <span>Pengajuan PKL</span>
                    </a>
                </div>

                <div class="menu-label">Pengaturan</div>

                <div class="nav-item">
                    <a href="{{ route('admin.template-penilaian.index') }}"
                        class="nav-link {{ request()->routeIs('admin.template-penilaian.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Template Penilaian</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('admin.settings.index') }}"
                        class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </div>
            @elseif(auth()->user()->role == 'guru')
                <div class="nav-item">
                    <a href="{{ route('guru.monitoring.index') }}"
                        class="nav-link {{ request()->routeIs('guru.monitoring.*') ? 'active' : '' }}">
                        <i class="fas fa-eye"></i>
                        <span>Monitoring</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('guru.nilai.index') }}"
                        class="nav-link {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>Penilaian</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('guru.kesimpulan.index') }}"
                        class="nav-link {{ request()->routeIs('guru.kesimpulan.*') ? 'active' : '' }}">
                        <i class="fas fa-award"></i>
                        <span>Kesimpulan Akhir</span>
                    </a>
                </div>
            @elseif(auth()->user()->role == 'siswa')
                @if (session('login_mode') === 'pengajuan')
                    <div class="menu-label">Pengajuan</div>

                    <div class="nav-item">
                        <a href="{{ route('siswa.pengajuan.index') }}"
                            class="nav-link {{ request()->routeIs('siswa.pengajuan.*') ? 'active' : '' }}">
                            <i class="fas fa-paper-plane"></i>
                            <span>Pengajuan PKL</span>
                        </a>
                    </div>

                    <div class="menu-label" style="margin-top:20px;">Akun</div>

                    <div class="nav-item">
                        <a href="{{ route('profile.edit') }}"
                            class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <i class="fas fa-user-circle"></i>
                            <span>Profile</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="#" class="nav-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @else
                    <div class="menu-label">Jurnal</div>

                    <div class="nav-item">
                        <a href="{{ route('siswa.jurnal.index') }}"
                            class="nav-link {{ request()->routeIs('siswa.jurnal.*') ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            <span>Jurnal Harian</span>
                        </a>
                    </div>
                @endif
            @elseif(auth()->user()->role == 'industri')
                <div class="menu-label">Penilaian</div>

                <div class="nav-item">
                    <a href="{{ route('industri.penilaian.index') }}"
                        class="nav-link {{ request()->routeIs('industri.penilaian.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Penilaian Siswa</span>
                    </a>
                </div>
            @endif

            {{-- ========== BAGIAN LAPORAN (hanya untuk Guru dan Siswa non-pengajuan) ========== --}}
            @if (in_array(auth()->user()->role, ['guru', 'siswa']) &&
                    !(auth()->user()->role == 'siswa' && session('login_mode') === 'pengajuan'))
                <div class="menu-label">Laporan</div>

                <div class="nav-item">
                    <a href="{{ route('laporan.jurnal') }}"
                        class="nav-link {{ request()->routeIs('laporan.jurnal') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan Jurnal</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('laporan.nilai') }}"
                        class="nav-link {{ request()->routeIs('laporan.nilai') ? 'active' : '' }}">
                        <i class="fas fa-file-pdf"></i>
                        <span>Laporan Nilai</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('laporan.pkl') }}"
                        class="nav-link {{ request()->routeIs('laporan.pkl*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Laporan PKL</span>
                    </a>
                </div>
            @endif

            {{-- ========== BAGIAN AKUN (untuk semua kecuali siswa mode pengajuan) ========== --}}
            @if (!(auth()->user()->role == 'siswa' && session('login_mode') === 'pengajuan'))
                <div class="menu-label" style="margin-top:20px;">Akun</div>

                <div class="nav-item">
                    <a href="{{ route('profile.edit') }}"
                        class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            @endif

        @endauth
    </div>
</nav>
