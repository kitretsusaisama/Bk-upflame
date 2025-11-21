<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <span class="brand-icon">⚡</span>
            <span class="brand-text">{{ config('app.name') }}</span>
        </div>
        <button class="sidebar-toggle" id="sidebarToggle">
            <span>☰</span>
        </button>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-list">
            @foreach($menuItems as $item)
                @if(isset($item['separator']))
                    <li class="nav-separator">{{ $item['label'] }}</li>
                @else
                    <li class="nav-item {{ request()->routeIs($item['route'] ?? '') ? 'active' : '' }}">
                        <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" class="nav-link">
                            <span class="nav-icon">{{ $item['icon'] ?? '●' }}</span>
                            <span class="nav-text">{{ $item['label'] }}</span>
                            @if(isset($item['badge']))
                                <span class="nav-badge">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                        
                        @if(isset($item['children']))
                            <ul class="nav-submenu">
                                @foreach($item['children'] as $child)
                                    <li class="nav-item {{ request()->routeIs($child['route'] ?? '') ? 'active' : '' }}">
                                        <a href="{{ isset($child['route']) ? route($child['route']) : '#' }}" class="nav-link">
                                            <span class="nav-text">{{ $child['label'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->email ?? 'User' }}</div>
                <div class="user-role">{{ $userRole ?? 'User' }}</div>
            </div>
        </div>
    </div>
</aside>
