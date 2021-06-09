@extends('errors::minimal')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
@endpush
@push('js')
    <script>
    setInterval(() => {
        location.reload();
    }, 5000);
    </script>
@endpush
@section('title', __('Actualizando sistema'))
@section('code', '503')
@section('content')
    <div class="error--elements">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle error-icon"></i>
                <h1 class="mt-3">{{ __('Actualizando sistema') }}</h1>
                <h2>Servicio no disponible</h2>
            </div>
        </div>
    </div>
    <div class="error--btn">
        <div class="container">
        </div>
    </div>
@endsection