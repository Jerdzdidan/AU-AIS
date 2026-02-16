<li class="menu-item {{ $class }} {{ request()->routeIs($route) ? 'active' : '' }}">
    <a href="{{ route($route, $param) }}" class="menu-link">
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        <div class="text-truncate" data-i18n="{{ Str::title($name) }}">
            {{ $name }}
        </div>
    </a>
</li>
