<div class="item">

    @php
    $filename = public_path() . "/{$new['file']}";
    $foto = "https://ventor.com.ar/{$new['image']}";
    @endphp
    <a @if(!empty($new['file']) && file_exists($filename)) href="{{ asset($new['file']) }}" download @endif class="card-map">
        <div class="card-map__image" style="background: url('{{ $foto }}') center center no-repeat; background-size: 100% auto;">
            <div class="card-map__shadow"></div>
        </div>
        <div class="card-map__content">
            <div class="card-map__info card-map__info--special" title="{{ $new['name'] }}">
                {{ $new['name'] }}
            </div>
        </div>
    </a>

</div>