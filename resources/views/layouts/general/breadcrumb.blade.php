<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item"><a href="{{ route(Auth::user()->redirect()) }}">Home</a></li>
    @isset($data["breadcrumb"])
        @foreach($data["breadcrumb"] AS $b)
        <li class="breadcrumb-item"><a href="{{ $b['href'] }}">{{ $b['name'] }}</a></li>
        @endforeach
    @endisset
    <li class="breadcrumb-item active" aria-current="page">{{$section}}</li>
</ol>