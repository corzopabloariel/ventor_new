<section>
    <div class="wrapper container">
        <p class="news--small"><a href="{{ route('index', ['link' => 'novedades']) }}" class="text-decoration-none">ver todas <i class="fas fa-angle-double-right"></i></a></p>
        <h2 class="news--title">¡Novedades!</h2>
        <div class="wrapper__news">
            @foreach($data['newness'] AS $item)
            @php
            $filename = public_path() . "/{$item['file']}";
            @endphp
            <a @if(!empty($item['file']) && file_exists($filename)) href="{{ asset($item['file']) }}" download @endif class="news--download">
                <img src="{{ asset($item['image']) }}" onerror="this.src='{{ $no_img }}'" alt="{{ $item['name'] }}" class="w-100">
                <h5 class="text-center mb-0">{{ $item['name'] }}</h5>
            </a>
            @endforeach
        </div>
    </div>
</section>
@includeIf('page.elements.productos')