@extends('errors::minimal')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/page/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
@endpush
@section('title', __('Server Error'))
@section('code', '500')

@section('content')
    @php
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url = "https://";
    else  
        $url = "http://";
    $url.= $_SERVER['HTTP_HOST'];
    $url.= $_SERVER['REQUEST_URI'];
    @endphp
    <div class="error--elements">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle error-icon"></i>
                <h1 class="mt-3">{{ $exception->getMessage() }}</h1>
                <h2>Código 500</h2>
                <p class="mt-5">{{ $url }}</p>
            </div>
        </div>
    </div>
    <div class="error--btn">
        <div class="container">
            <div class="d-flex justify-content-center">
                <button id="redirect" class="btn btn-lg"><i class="fas fa-home"></i> ir al inicio</button>
            </div>
        </div>
    </div>
@endsection