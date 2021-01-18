@push('styles')
    <link href="{{ asset('css/page/home.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script>
        const urlParams = new URLSearchParams(location.search);
        $(() => {
            if (urlParams.get('login') !== null) {
                $('.login-link').click();
                $('#username-login').focus();
            }
        });
    </script>
@endpush
<section>
    <div class="news">
        <div class="container">
            <h2 class="news--title">¡Novedades!</h2>
            <p class="text-right"><a href="{{ route('index', ['link' => 'novedades']) }}" class="text-decoration-none">ver todas <i class="fas fa-angle-double-right"></i></a></p>
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
@includeIf('page.elements.productos')