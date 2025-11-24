<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
    <a href="{{ route('home') }}" class="app-brand-link">
        <span class="app-brand-logo demo">
            <img src="{{ asset('img/logo/arellano_logo.png') }}" alt="">
        </span>
        <span class="app-brand-text demo menu-text fw-bold ms-4">AU-AIS</span>
    </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- Home -->
        {{-- <li class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div class="text-truncate" data-i18n="Home">Home</div>
            </a>
        </li> --}}

        <x-sidebar.item route='home' name='Home' icon='menu-icon tf-icons bx bx-home'/>

        @yield('menu_items')
    
    </ul>
</aside>