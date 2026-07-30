<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-school me-2"></i>Sistem PKL
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    @if (auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-chart-line me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="masterDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-database me-1"></i> Master Data
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.guru.index') }}"><i
                                            class="fas fa-chalkboard-teacher me-2"></i> Guru</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.siswa.index') }}"><i
                                            class="fas fa-user-graduate me-2"></i> Siswa</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.kompetensi.index') }}"><i
                                            class="fas fa-tasks me-2"></i> Kompetensi</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.industri.index') }}"><i
                                            class="fas fa-building me-2"></i> Industri</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}"
                                href="{{ route('admin.pengajuan.index') }}">
                                <i class="fas fa-paper-plane me-1"></i> Pengajuan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.penempatan.*') ? 'active' : '' }}"
                                href="{{ route('admin.penempatan.index') }}">
                                <i class="fas fa-people-arrows me-1"></i> Penempatan
                            </a>
                        </li>
                    @elseif(auth()->user()->role == 'guru')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('guru.monitoring.*') ? 'active' : '' }}"
                                href="{{ route('guru.monitoring.index') }}">
                                <i class="fas fa-eye me-1"></i> Monitoring
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}"
                                href="{{ route('guru.nilai.index') }}">
                                <i class="fas fa-star me-1"></i> Penilaian
                            </a>
                        </li>
                    @elseif(auth()->user()->role == 'siswa')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('siswa.pengajuan.*') ? 'active' : '' }}"
                                href="{{ route('siswa.pengajuan.index') }}">
                                <i class="fas fa-paper-plane me-1"></i> Pengajuan PKL
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('siswa.jurnal.*') ? 'active' : '' }}"
                                href="{{ route('siswa.jurnal.index') }}">
                                <i class="fas fa-book me-1"></i> Jurnal Harian
                            </a>
                        </li>
                    @endif

                    <!-- Laporan untuk semua role -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="laporanDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-file-alt me-1"></i> Laporan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('laporan.jurnal') }}"><i
                                        class="fas fa-book me-2"></i> Jurnal PKL</a></li>
                            <li><a class="dropdown-item" href="{{ route('laporan.nilai') }}"><i
                                        class="fas fa-star me-2"></i> Nilai PKL</a></li>
                        </ul>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <button onclick="toggleTheme()" class="btn btn-outline-light btn-sm me-2" id="themeToggle">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </button>
                </li>
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
