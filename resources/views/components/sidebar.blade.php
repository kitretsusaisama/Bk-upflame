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
                @if($item->type === 'heading')
                    <li class="nav-header">{{ $item->label }}</li>
                @elseif($item->type === 'separator')
                    <li class="nav-separator">{{ $item->label }}</li>
                @else
                    <li class="nav-item {{ request()->routeIs($item->route ?? '') ? 'active' : '' }}">
                        <a href="{{ $item->route ? route($item->route) : ($item->url ?? '#') }}" class="nav-link">
                            @if($item->icon)
                                <span class="nav-icon">
                                    <i class="{{ $item->icon }}"></i>
                                </span>
                            @else
                                <span class="nav-icon">●</span>
                            @endif
                            <span class="nav-text">{{ $item->label }}</span>
                            @if(!empty($item->metadata['badge']))
                                <span class="nav-badge">{{ $item->metadata['badge'] }}</span>
                            @endif
                        </a>
                        
                        @if($item->children && $item->children->count() > 0)
                            <ul class="nav-submenu">
                                @foreach($item->children as $child)
                                    <li class="nav-item {{ request()->routeIs($child->route ?? '') ? 'active' : '' }}">
                                        <a href="{{ $child->route ? route($child->route) : ($child->url ?? '#') }}" class="nav-link">
                                            <span class="nav-text">{{ $child->label }}</span>
                                        </a>
                                        
                                        @if($child->children && $child->children->count() > 0)
                                            <ul class="nav-submenu">
                                                @foreach($child->children as $grandchild)
                                                    <li class="nav-item {{ request()->routeIs($grandchild->route ?? '') ? 'active' : '' }}">
                                                        <a href="{{ $grandchild->route ? route($grandchild->route) : ($grandchild->url ?? '#') }}" class="nav-link">
                                                            <span class="nav-text">{{ $grandchild->label }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
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