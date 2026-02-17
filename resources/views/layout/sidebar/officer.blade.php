
@extends('layout.sidebar.bar')

@section('menu_items')

<x-sidebar.item route='#' name='Dashboard' icon='menu-icon tf-icons bx bxs-dashboard'/>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Academic Information</span>
</li>

    <x-sidebar.item route='officer.students' name='Student Progress' class="{{ request()->routeIs('officer.student.*') ? 'active' : '' }}" icon='fa-solid fa-user-graduate me-2' />

@endsection
