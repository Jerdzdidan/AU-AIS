@extends('layout.sidebar.bar')

@section('menu_items')

<x-sidebar.item route='#' name='Dashboard' icon='menu-icon tf-icons bx bxs-dashboard'/>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Core</span>
</li>

<x-sidebar.item route='#' name='Academic years' icon='fa-solid fa-school-flag me-2'/>

<x-sidebar.item route='#' name='Semester' icon='fa-solid fa-scroll me-2'/>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Academic Information</span>
</li>

<x-sidebar.item route='#' name='Departments' icon='fa-solid fa-building-user me-2'/>

<x-sidebar.item route='#' name='Programs' icon='fa-solid fa-table-list me-2' />

<x-sidebar.item route='#' name='Subjects' icon='fa-solid fa-book-open me-2' />

<x-sidebar.item route='#' name='Curriculums' icon='fa-solid fa-file-pen me-2' />

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">User Management</span>
</li>

<x-sidebar.item route='#' name='Student accounts' icon='fa-solid fa-user-graduate me-2' />

<x-sidebar.item route='#' name='E-R Officer accounts' icon='fa-solid fa-user-gear me-2' />

<x-sidebar.item route='users.index' param='admin' name='Admin accounts' icon='fa-solid fa-user-shield me-2' />

@endsection