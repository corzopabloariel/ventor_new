@extends('layouts.app')
@section('headTitle', config('app.name') . ' - Administración')
@section('content')
<div class="wrapper flex-column">
    @if (Auth::user()->hasRole("adm"))
        <header class="app-header navbar bg-white position-fixed shadow-sm w-100 px-0">
            <div class="d-flex align-items-center w-100">
                <nav class="navbar justify-content-between w-100 navbar-expand-lg navbar-light p-0">
                    <div class="navbar__header">
                        <a href="Auth::user()->redirect()) }}">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse bg-white" id="navbarNavDropdown">
                        <ul class="navbar-nav px-3">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex pr-5" href="#" id="navbarDropdownMenuUsuario" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{Auth::user()->name}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUsuario">
                                    @foreach(MENU_NAV AS $i)
                                        @if (isset($i["separar"]))
                                            <div class="dropdown-divider"></div>
                                        @else
                                            <a class="dropdown-item" href="{{ \URL::to(session('role'). '/' . $i['url']) }}"><i class="{{ $i['icon'] }} mr-2"></i>{{ $i['name'] }}</a>
                                        @endif
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
        </header>
        <div class="app-body">
            <!-- Sidebar -->
            <nav id="sidebar">@include(Auth::user()->redirect() . ".menu")</nav>
            <!-- Page Content -->
            <div id="content">@include(Auth::user()->redirect() . "." . $data["view"])</div>
        </div>
    @else
    @endif
</div>
@endsection
