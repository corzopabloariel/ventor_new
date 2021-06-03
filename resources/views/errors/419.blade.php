@extends('errors::minimal')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
@endpush
@push('js')
    <script>
    const goHome = function(evt) {
        let url = '{{ route("index") }}';
        location.href = url;
    };
    const btn = document.querySelector("#redirect");
    btn.addEventListener("click", goHome);
    </script>
@endpush
@section('title', __('Su sesión ha expirado'))
@section('code', '419')
@section('content')
    @php
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url = "https://";
    else  
        $url = "http://";
    $url.= $_SERVER['HTTP_HOST'];
    $url.= $_SERVER['REQUEST_URI'];
    @endphp
    <section>
        <div class="error--elements">
            <div class="container">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle error-icon"></i>
                    <h1 class="mt-3">{{ __($exception->getMessage()) }}</h1>
                    <h2>Código 419</h2>
                    <p class="mt-5">{{ $url }}</p>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="wrapper">
            <div class="error--btn">
                <div class="container">
                    <div class="d-flex justify-content-center">
                        <button id="redirect" class="btn btn-lg"><i class="fas fa-home"></i> ir al inicio</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
