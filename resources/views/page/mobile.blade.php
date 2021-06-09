@extends('page.app_mobile')
@push('js')
    <script src="{{ asset('js/mobile/swiped-events.min.js') }}"></script>
@endpush
@section('headTitle', config('app.name'))
@section('content')
    @includeIf('page.mobile.' . $data["page"])
@endsection