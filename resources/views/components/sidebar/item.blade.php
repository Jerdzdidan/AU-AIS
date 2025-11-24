<li class="menu-item {{ request()->routeIs($route) ? 'active' : '' }}">
    <a href="{{ route($route) }}" class="menu-link">
        <i class="{{ $icon }}"></i>
        <div class="text-truncate" data-i18n="{{ Str::title($name) }}">
            {{ $name }}
        </div>
    </a>
</li>
