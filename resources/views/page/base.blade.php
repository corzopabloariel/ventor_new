@extends('page.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page/header.css') }}">
@endpush
@section('headTitle', config('app.name'))
@section('content')

@endsection