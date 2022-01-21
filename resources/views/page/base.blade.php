@extends('page.app')
@section('headTitle', config('app.name'))
@section('content')
    @isset($data['slider'])
        @includeIf('page.elements.slider', ['slider' => $data['slider'],'page' => $data['page']])
    @endisset
    @includeIf('page.elements.'.$data['page'])
@endsection
@push("styles")
    @includeIf('style.'.$data['script'])
    @isset($data['slider'])
    <link
        rel="stylesheet"
        href="https://unpkg.com/swiper@7/swiper-bundle.min.css"
    />
    @endisset
@endpush
@section('script')
    @isset($data['script'])
        @includeIf('script.'.$data['script'], $data['scriptParams'] ?? [])
    @endisset
@endsection