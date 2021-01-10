@push('styles')
    <link href="{{ asset('css/page/home.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
<section>
    <div class="news">
        <div class="container">
            <ol class="breadcrumb bg-transparent p-0 border-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Novedades</li>
            </ol>
            <div class="container--news">
                @foreach($data['newness'] AS $item)
                @php
                $filename = public_path() . "/{$item['file']}";
                @endphp
                <a @if(!empty($item['file']) && file_exists($filename)) href="{{ asset($item['file']) }}" download @endif class="new--download">
                    <img src="{{ asset($item['image']) }}" onerror="this.src='{{ $no_img }}'" alt="{{ $item['name'] }}">
                    <h5 class="text-center mb-0">{{ $item['name'] }}</h5>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>