@extends('layouts.app')
@section('headTitle', config('app.name') . ' - Administración')
@section('content')
<div class="wrapper flex-column">
    <header class="app-header navbar bg-white position-fixed shadow-sm w-100 px-0">
        <div class="d-flex align-items-center w-100">
            <nav class="navbar justify-content-between w-100 navbar-expand-lg navbar-light p-0">
                <div class="navbar__header">
                    <a href="{{ route('adm') }}">
                        @php
                        $img = "";
                        $ventor = \App\Models\Ventor\Ventor::first();
                        if ($ventor) {
                            if (!empty($ventor->images["favicon"]))
                                $img = asset($ventor["images"]["favicon"]["i"]) . "?t=" . time();
                        }
                        @endphp
                        @if (!empty($img))
                            <img src="{{ $img }}" style="height: 40px" alt="Ventor {{Auth::user()->role}}" srcset="">
                        @endif
                    </a>
                </div>
                <div class="collapse navbar-collapse bg-white" id="navbarNavDropdown">
                    <ul class="navbar-nav px-3">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex pr-5" href="#" id="navbarDropdownMenuUsuario" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{Auth::user()->name}}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUsuario">
                                @php
                                $permissions = Auth::user()->permissions;
                                @endphp
                                @foreach(MENU_NAV AS $i)
                                    @if (!empty($permissions) && isset($i['id']) && (!isset($permissions[$i['id']]) || isset($permissions[$i['id']]) && !$permissions[$i['id']]['read']))
                                        @continue
                                    @endif
                                    @if (isset($i["separar"]))
                                        <div class="dropdown-divider"></div>
                                    @else
                                        <a class="dropdown-item" href="{{ \URL::to('adm/' . $i['url']) }}"><i class="{{ $i['icon'] }} mr-2"></i>{{ $i['name'] }}</a>
                                    @endif
                                @endforeach
                            </div>
                        </li>
                    </ul>
                    <a href="{{ URL::to('/') }}" class="text-primary" target="blank">Ir a {{ config('app.url') }}</a>
                </div>
            </nav>
    </header>
    <div class="app-body">
        <!-- Sidebar -->
        <nav id="sidebar">@include("adm.menu", ['permissions' => Auth::user()->permissions])</nav>
        <!-- Page Content -->
        <div id="content">@include("adm." . $data["view"])</div>
    </div>
</div>
@endsection
