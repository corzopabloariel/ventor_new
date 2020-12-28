@push('styles')
    <link href="{{ asset('css/page/home.css') }}" rel="stylesheet">
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
            <div class="container--news">
                @foreach($data['newness'] AS $item)
                <a href="" class="new--download" download>
                    <img src="{{ asset($item['image']) }}" onerror="this.src='{{ $no_img }}'" alt="{{ $item['name'] }}">
                    <h5 class="text-center mb-0">{{ $item['name'] }}</h5>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@includeIf('page.elements.productos')