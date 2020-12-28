@extends('errors::minimal')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/page/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
@endpush
@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('Service Unavailable'))
