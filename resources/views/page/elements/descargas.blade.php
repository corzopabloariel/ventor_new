@push('js')
<script src="{{ asset('js/alertify.js') }}"></script>
@endpush
@php
$categories = [
    'PUBL' => 'Descargas e instructivos',
    'CATA' => 'Catálogo',
    'PREC' => 'Listas de precios',
    'OTRA' => 'Otra'
];
@endphp
<section>
    <div class="wrapper wrapper__download">
        <div class="container">
            <div class="mb-4 text-center">
                <a download target="_blank" href="{{ $data['program'] }}" class="download__program btn btn-inline-block rounded-pill px-5 mx-auto"><strong>Descargar:</strong> VENTOR Catálogo y Pedidos</a>
            </div>
            @foreach($data["order"] AS $order)
                @isset($data["downloads"][$order])
                    <div class="downloads">
                        <h3 class="download__title">{{ $categories[$order] }}</h3>
                        <div class="container__downloads">
                            @foreach($data["downloads"][$order] AS $download)
                                @if (count($download["files"]) == 1)
                                <a data-name="{{ html_entity_decode(strip_tags($download["name"])) }}" @if(empty($download["files"][0]["file"])) class="notFile" href="#" @else class="downloadTrack" data-id="{{$download['id']}}" href="#" data-href="{{ asset($download["files"][0]["file"]) }}" @endif>
                                    <img src="{{ asset($download["image"]) }}" alt="{{ html_entity_decode(strip_tags($download["name"])) }}" onerror="this.src='{{ $no_img }}'" srcset="">
                                    <div class="download__title download__title--name">{!! $download["name"] !!}</div>
                                </a>
                                @else
                                <div>
                                    <img src="{{ asset($download["image"]) }}" alt="{{ html_entity_decode(strip_tags($download["name"])) }}" onerror="this.src='{{ $no_img }}'" srcset="">
                                    <select class="form-control downloadsTrack" data-id="{{ $download['id'] }}" data-name="{{ html_entity_decode(strip_tags($download['name'])) }}">
                                        <option value="" hidden>SELECCIONE UN ARCHIVO</option>
                                        @foreach($download["files"] AS $file)
                                        <option value="{{ $file['file'] }}" data-name="{{ $file['nameExt'] }}">{{ $file["name"] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="download__title download__title--name">{!! $download["name"] !!}</div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endisset
            @endforeach
        </div>
    </div>
</section>