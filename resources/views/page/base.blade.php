@extends('page.app')
@section('headTitle', config('app.name'))
@section('content')
    @includeIf('page.elements.'.$data["page"])
@endsection

@section('script')
    @includeIf('script.'.$data["script"])
@endsection