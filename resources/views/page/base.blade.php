@extends('page.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page/header.css') . '?t=' . time() }}">
    <link rel="stylesheet" href="{{ asset('css/page/footer.css') . '?t=' . time() }}">
@endpush
@section('headTitle', config('app.name'))
@section('content')
    @includeIf('page.elements.' . $data["page"])
@endsection