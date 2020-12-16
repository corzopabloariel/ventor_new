@extends('layouts.app')

@section('headTitle', config('app.name') . ' :: Login')

@section('content')
<div class="container--login">
    <div class="container-fluid">
        <div class="row justify-content-center align-content-center h-100">
            <div class="col-12 col-sm-9 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="py-5">
                            <form method="POST" action="{{ route('login', ['role' => $role]) }}">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection