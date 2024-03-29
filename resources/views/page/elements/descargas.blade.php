@push('js')
<script src="{{ asset('js/alertify.js') }}"></script>
@endpush
@push('modal')
<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="modalDownload" tabindex="-1" role="dialog" aria-labelledby="modalDownloadLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="modalDownloadLabel">La descarga iniciará automáticamente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-5 text-center">
                    <p>Si la descarga no comienza, haga clic en el siguiente botón.</p>
                    <p><a download href="{{ $data['program'] }}"><strong>Descargar:</strong> VENTOR Catálogo y Pedidos</a></p>
                </div>
                <hr>
                <div class="mt-5">
                    @php
                    $download_1_type = pathinfo(config('app.static').'img/download_1.svg', PATHINFO_EXTENSION);
                    $download_1 = 'data:image/' . $download_1_type . '+xml;base64,' . base64_encode(file_get_contents(config('app.static').'img/download_1.svg'));
                    $download_2_type = pathinfo(config('app.static').'img/download_2.svg', PATHINFO_EXTENSION);
                    $download_2 = 'data:image/' . $download_2_type . '+xml;base64,' . base64_encode(file_get_contents(config('app.static').'img/download_2.svg'));
                    $download_3_type = pathinfo(config('app.static').'img/download_3.svg', PATHINFO_EXTENSION);
                    $download_3 = 'data:image/' . $download_3_type . '+xml;base64,' . base64_encode(file_get_contents(config('app.static').'img/download_3.svg'));
                    @endphp
                    <h3 class="text-center mb-3">Siga los siguientes pasos</h3>
                    <picture class="d-flex align-items-center">
                        <img src="{{$download_1}}" alt="Pasos para descarga - 1" srcset="">
                        <p class="ml-3">Click derecho sobre el botón de descarga. Seleccionar "Guardar enlace como..."</p>
                    </picture>
                    <picture class="d-flex align-items-center mt-3">
                        <img src="{{$download_2}}" alt="Pasos para descarga - 2" srcset="">
                        <p class="ml-3">Seleccione el destino de la descarga y clickee en Guardar</p>
                    </picture>
                    <picture class="d-flex align-items-center mt-3">
                        <img src="{{$download_3}}" alt="Pasos para descarga - 3" srcset="">
                        <p class="ml-3">Abajo a la derecha, clickee en la flecha para desplegar el menú y luego la opción "Guardar" o "Conservar"</p>
                    </picture>
                </div>
            </div>
        </div>
    </div>
</div>
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
                <a download href="{{ $data['program'] }}" class="download__program" id="download__program"><strong>Descargar:</strong> VENTOR Catálogo y Pedidos</a>
            </div>
            @foreach($data["order"] AS $order)
                @isset($data["downloads"][$order])
                    <div class="downloads">
                        <h3 class="download__title">{{ $categories[$order] }}</h3>
                        <div class="container__downloads">
                            @foreach($data["downloads"][$order] AS $download)
                                @if (count($download["files"]) == 1)
                                <a data-name="{{ html_entity_decode(strip_tags($download["files"][0]["nameExt"])) }}" @auth data-time="{{time()}}" @endauth @if(empty($download["files"][0]["file"])) class="notFile" href="#" @else class="downloadTrack" data-id="{{$download['id']}}" href="#" data-href="{{ asset($download["files"][0]["file"]) }}" @endif>
                                    <img src="{{$download['image']}}" alt="{{ html_entity_decode(strip_tags($download["name"])) }}" onerror="this.src='{{ $no_img }}'" srcset="">
                                    <div class="download__title download__title--name">{!! $download["name"] !!}</div>
                                </a>
                                @else
                                    <div>
                                        <img src="{{$download['image']}}" alt="{{ html_entity_decode(strip_tags($download["name"])) }}" onerror="this.src='{{ $no_img }}'" srcset="">
                                        @if (isset($download['separate']))
                                            @foreach($download["files"] AS $file)
                                            <a class="btn btn-link fa-3x" data-name="{{ $file['nameExt'] }}" @auth data-time="{{time()}}" @endauth @if(empty($file['file'])) class="notFile" href="#" @else class="" data-id="{{$download['id']}}" href="{{ asset($file['file']) }}" download data-href="{{ asset($file['file']) }}" @endif>{!! $file["name"] !!}</a>
                                            @endforeach
                                        @else
                                        <select class="form-control downloadsTrack" @auth data-time="{{time()}}" @endauth data-id="{{ $download['id'] }}" data-name="{{ html_entity_decode(strip_tags($download['name'])) }}">
                                            <option value="">-- SELECCIONE UN ARCHIVO --</option>
                                            @foreach($download["files"] AS $file)
                                            <option value="{{ $file['file'] }}" data-name="{{ $file['nameExt'] }}">{{ $file["name"] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="download__title download__title--name">{!! $download["name"] !!}</div>
                                        @endif
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