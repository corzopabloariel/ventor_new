@extends('page.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/page/footer.css') }}">
@endpush
@section('headTitle', config('app.name'))
@section('content')
    @includeIf('page.elements.' . $data["page"])
@endsection