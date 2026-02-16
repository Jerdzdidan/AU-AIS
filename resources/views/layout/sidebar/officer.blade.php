
@extends('layout.sidebar.bar')

@section('menu_items')

<x-sidebar.item route='#' name='Dashboard' icon='menu-icon tf-icons bx bxs-dashboard'/>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Academic Information</span>
</li>

    <x-sidebar.item route='#' name='Student Progress' icon='fa-solid fa-user-graduate me-2' />

    {{-- <li class="menu-item"> --}}
    {{--     <a href="javascript:void(0);" class="menu-link menu-toggle"> --}}
    {{--     <i class="fa-solid fa-user-graduate me-2"></i> --}}
    {{--     <div class="text-truncate" data-i18n="Front Pages">Student Progress</div> --}}
    {{--     </a> --}}
    {{--     <ul class="menu-sub"> --}}
    {{--         <x-sidebar.item route='#' name='2nd Year' icon='' /> --}}
    {{--         <x-sidebar.item route='#' name='3rd Year' icon='' /> --}}
    {{--         <x-sidebar.item route='#' name='4th Year' icon='' /> --}}
    {{--         <x-sidebar.item route='#' name='Others' icon='' /> --}}
    {{----}}
    {{--         <!-- --}}
    {{--             <li class="menu-item"> --}}
    {{--                 <a --}}
    {{--                 href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/front-pages/pricing-page.html" --}}
    {{--                 class="menu-link" --}}
    {{--                 target="_blank"> --}}
    {{--                 <div class="text-truncate" data-i18n="Pricing">Pricing</div> --}}
    {{--                 </a> --}}
    {{--             </li> --}}
    {{--         ---> --}}
    {{--     </ul> --}}
    {{-- </li> --}}
@endsection
