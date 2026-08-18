<header class="top-header">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="page-title">
            <h4>@yield('page-title', 'Dashboard')</h4>
            <small>@yield('page-subtitle', 'Sistem Manajemen PKL')</small>
        </div>
    </div>

    <div class="header-actions">
        <button class="btn-icon" onclick="toggleTheme()" title="Toggle Theme">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>

        <a href="#" class="user-info text-decoration-none" data-bs-toggle="dropdown">
            <div class="avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
            <i class="fas fa-chevron-down text-muted ms-1" style="font-size: 10px;"></i>
        </a>
        
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user me-2"></i> Profile
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</header>