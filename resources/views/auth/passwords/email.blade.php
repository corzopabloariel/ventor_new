@extends('page.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/page/footer.css') }}">
    <style>
        .container-reset {
            padding: 5em 0;
        }
        .container-reset .btn,
        .container-reset .form-control {
            border-radius: .5em;
        }
        .container-reset .btn-primary {
            background-color: #109bd4 !important;
        }
    </style>
@endpush
@section('headTitle', config('app.name'))
@section('content')
<div class="container container-reset">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Reestablecer contraseña</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group">
                            <label for="nrodoc">Número de documento</label>
                            <input id="nrodoc" type="text" class="form-control form-control-lg @error('nrodoc') is-invalid @enderror" placeholder="Número de documento" name="nrodoc" value="{{ old('nrodoc') }}" autofocus>
                            @error('nrodoc')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary">
                                Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center mb-0 mt-3">Use su número de documento, le llegará al mail registrado en el sistema los pasos a seguir</p>
        </div>
    </div>
</div>
@endsection
