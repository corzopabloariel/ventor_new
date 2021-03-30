@extends('layouts.app')

@section('headTitle', config('app.name') . ' :: Login')
@push("styles")
    <link href="{{ asset('css/page/login.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="container--login">
    <div class="container-fluid">
        <div class="row justify-content-center align-content-center h-100">
            <div class="col-12 col-sm-9 col-md-7 bg-white shadow">
                <div class="row">
                    <div class="col-12 col-md">
                        <img src="{{ asset('login.png') }}" class="w-100 my-5" alt="">
                    </div>
                    <div class="col-12 col-md border-left align-items-center d-flex">
                        <div class="py-5 w-100 px-3">
                            <h2 class="text-center">Bienvenido</h2>
                            <form method="POST" action="{{ route('login.p', ['role' => $role]) }}">
                                @csrf
                                <div class="form-group">
                                    <label for="username">Usuario</label>
                                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete placeholder="Usuario" autofocus>
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button class="btn btn-block btn-dark text-center" type="submit">{{ __('Acceder') }}</button>
                            </form>
                            <p class="mt-3 text-center">
                                <a href="{{ route('index') }}">ventor.com.ar</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection