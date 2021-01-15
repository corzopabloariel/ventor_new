@extends('page.app_mobile')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/mobile/all.css') . '?t=' . time() }}">
    <link rel="stylesheet" href="{{ asset('css/mobile/header.css') . '?t=' . time() }}">
    <link rel="stylesheet" href="{{ asset('css/mobile/footer.css') . '?t=' . time() }}">
    <link rel="stylesheet" href="{{ asset('css/mobile/nav.css') . '?t=' . time() }}">
@endpush
@push('js')
    <script src="{{ asset('js/mobile/swiped-events.min.js') }}"></script>
@endpush
@section('headTitle', config('app.name'))
@section('content')
    @includeIf('page.mobile.' . $data["page"])
@endsection