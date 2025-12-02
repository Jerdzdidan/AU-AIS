@extends('layout.sidebar.bar')

@section('menu_items')

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Academic Information</span>
</li>

<x-sidebar.item route='#' name='Academic Progress' icon='fa-solid fa-user-graduate me-2'/>

<x-sidebar.item route='#' name='Grades' icon='fa-solid fa-star me-2'/>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Student Information</span>
</li>

<x-sidebar.item route='#' name='Manual' icon='fa-solid fa-book me-2'/>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">General Information</span>
</li>

<x-sidebar.item route='#' name='FAQs' icon='fa-solid fa-question-circle me-2' />

<x-sidebar.item route='#' name='Help' icon='fa-solid fa-question-circle me-2' />

@endsection