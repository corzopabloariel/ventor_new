@php
$categories = [
    'PUBL' => 'Descargas e instructivos',
    'CATA' => 'Catálogo',
    'PREC' => 'Listas de precios',
    'OTRA' => 'Otra'
];
@endphp
<section>
    <div class="downloads wrapper">
        <div class="container">
            @foreach($data["order"] AS $order)
                @isset($data["downloads"][$order])
                    <div class="download shadow-sm">
                        <h3 class="downloads--title">{{ $categories[$order] }}</h3>
                        <div class="container--downloads">
                            <div id="card-slider-{{ $order }}" class="splide">
                                <div class="splide__track">
                                    <ul class="splide__list">
                                    @foreach($data["downloads"][$order] AS $download)
                                    @if (!isset($download['separate']))<li class="splide__slide">@endif
                                            @if (count($download["files"]) == 1)
                                            <a data-name="{{ $download["files"][0]["nameExt"] }}" @auth data-time="{{time()}}" @endauth @if(empty($download["files"][0]["file"])) class="notFile" href="#" @else class="downloadTrack" data-id="{{$download['id']}}" href="#" data-href="{{ asset($download["files"][0]["file"]) }}" @endif>
                                                <img src="{{$download['image']}}" alt="{{ html_entity_decode(strip_tags($download["name"])) }}" onerror="this.src='{{ $no_img }}'" srcset="">
                                                <div class="download--name">{!! $download["name"] !!}</div>
                                            </a>
                                            @else
                                                @if (isset($download['separate']))
                                                    @foreach($download["files"] AS $file)
                                                    <li class="splide__slide">
                                                        <a data-name="{{ $file['nameExt'] }}" @auth data-time="{{time()}}" @endauth @if(empty($file['file'])) class="notFile" href="#" @else class="downloadTrack" data-id="{{$download['id']}}" href="#" data-href="{{ asset($file['file']) }}" @endif>
                                                            <img src="{{$download['image']}}" alt="{{ $file['name'] }}" onerror="this.src='{{ $no_img }}'" srcset="">
                                                            <div class="download__title download__title--name">{!! $file["name"] !!}</div>
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                @else
                                                    <div>
                                                        <img src="{{$download['image']}}" alt="{{ html_entity_decode(strip_tags($download["name"])) }}" onerror="this.src='{{ $no_img }}'" srcset="">
                                                        <select class="form-control downloadsTrack" @auth data-time="{{time()}}" @endauth data-id="{{ $download['id'] }}" data-name="{{ html_entity_decode(strip_tags($download['name'])) }}">
                                                            <option value="" hidden>SELECCIONE UN ARCHIVO</option>
                                                            @foreach($download["files"] AS $file)
                                                            <option value="{{ $file['file'] }}" data-name="{{ $file['nameExt'] }}">{{ $file["name"] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="download--name">{!! $download["name"] !!}</div>
                                                    </div>
                                                @endif
                                            @endif
                                        @if (!isset($download['separate']))</li>@endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endisset
            @endforeach
        </div>
    </div>
</section>