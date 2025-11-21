    <header class="topbar">
    <div class="topbar-left">
        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <span>☰</span>
        </button>
        <div class="breadcrumb">
            @yield('breadcrumb')
        </div>
    </div>
    
    <div class="topbar-right">
        <div class="topbar-search">
            <input type="search" placeholder="Search..." class="search-input">
            <span class="search-icon">🔍</span>
        </div>
        
        <div class="topbar-notifications">
            <button class="notification-btn">
                <span class="notification-icon">🔔</span>
                <span class="notification-badge">3</span>
            </button>
            <div class="notification-dropdown">
                <div class="notification-header">
                    <h4>Notifications</h4>
                    <a href="#" class="mark-read">Mark all as read</a>
                </div>
                <div class="notification-list">
                    <div class="notification-item unread">
                        <div class="notification-icon">📧</div>
                        <div class="notification-content">
                            <p class="notification-text">New booking request received</p>
                            <span class="notification-time">5 minutes ago</span>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon">✅</div>
                        <div class="notification-content">
                            <p class="notification-text">Workflow approved</p>
                            <span class="notification-time">1 hour ago</span>
                        </div>
                    </div>
                </div>
                <div class="notification-footer">
                    <a href="#">View all notifications</a>
                </div>
            </div>
        </div>
        
        <div class="topbar-user">
            <button class="user-btn">
                <div class="user-avatar-small">{{ strtoupper(substr($user->email ?? 'U', 0, 1)) }}</div>
                <span class="user-name-small">{{ $user->email ?? 'User' }}</span>
                <span class="dropdown-arrow">▼</span>
            </button>
            <div class="user-dropdown">
                <a href="#" class="dropdown-item">
                    <span class="dropdown-icon">👤</span>
                    <span>Profile</span>
                </a>
                <a href="#" class="dropdown-item">
                    <span class="dropdown-icon">⚙️</span>
                    <span>Settings</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item">
                    <span class="dropdown-icon">🚪</span>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</header>
