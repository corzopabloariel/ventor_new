<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item"><a href="{{ route(Auth::user()->redirect()) }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$section}}</li>
</ol>