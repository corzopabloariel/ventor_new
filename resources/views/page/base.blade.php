@extends('page.app')
@section('headTitle', config('app.name'))
@section('content')
    @includeIf('page.elements.' . $data["page"])
@endsection