@push('styles')
    <link href="{{ asset('css/page/descarga.css') . '?t=' . time() }}" rel="stylesheet">
    <style>
        .descargas {
            margin-bottom: 40px;
        }
    </style>
@endpush
@push('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/alertify.js') }}"></script>
<script>
    const download = function(t, id) {
        let index = t.selectedIndex - 1;
        let file = t.item(index).text;
        let txt = t.dataset.name + ` [${file}]`;
        let link = $(t).next().children()[index];
        let value = $(t).val();
        if (value == "") {
            swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
                buttons: {
                    cerrar: true,
                },
            });
            return;
        }
        downloadTrack(t, id, link);
    };
    const notFile = function(t) {
        let txt = t.dataset.name;
        swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
            buttons: {
                cerrar: true,
            },
        });
    };
    const downloadTrack = function(t, id, link = null) {
        let txt = t.dataset.name
        let flag = false;
        if (link === null) {
            flag = true;
            link = document.createElement("a");
            link.href = t.dataset.href;
            link.download = t.dataset.name;
        }
        axios.get(document.querySelector('meta[name="url"]').content + "/track_download/" + id)
        .then(function (res) {
            if (res.data.error === 0) {
                link.click();
                if (flag)
                    link.remove();
            } else {
                swal("Atención!", res.data.msg, "error",{
                    buttons: {
                        cerrar: true,
                    },
                });
            }
        })
        .catch(err => {
            swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
                buttons: {
                    cerrar: true,
                },
            });
        });
    };
</script>
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
    <div class="descargas p-0">
        <div class="container">
            <div class="mb-4 text-center">
                <a download href="{{ $data['program'] }}" class="btn btn-inline-block btn-info rounded-pill px-5 mx-auto"><strong>Descargar:</strong> VENTOR Catálogo y Pedidos</a>
            </div>
            @foreach($data["order"] AS $order)
                @isset($data["downloads"][$order])
                    <div class="downloads">
                        <h3 class="descarga--title">{{ $categories[$order] }}</h3>
                        <div class="downloads--container">
                            @foreach($data["downloads"][$order] AS $download)
                                @if (count($download["files"]) == 1)
                                <a data-name="{{ html_entity_decode(strip_tags($download["name"])) }}" @if(empty($download["files"][0]["file"])) onclick="event.preventDefault(); notFile(this);" href="#" @else onclick="event.preventDefault(); downloadTrack(this, {{$download['id']}})" href="#" data-href="{{ asset($download["files"][0]["file"]) }}" @endif>
                                    <img src="{{ asset($download["image"]) }}" alt="{{ html_entity_decode(strip_tags($download["name"])) }}" onerror="this.src='{{ $no_img }}'" srcset="">
                                    <div class="download--name">{!! $download["name"] !!}</div>
                                </a>
                                @else
                                <div>
                                    <img src="{{ asset($download["image"]) }}" alt="{{ html_entity_decode(strip_tags($download["name"])) }}" onerror="this.src='{{ $no_img }}'" srcset="">
                                    <select class="form-control" onchange="download(this, {{ $download['id'] }});" data-name="{{ html_entity_decode(strip_tags($download["name"])) }}">
                                        <option value="" hidden>SELECCIONE UN ARCHIVO</option>
                                        @foreach($download["files"] AS $file)
                                        <option value="{{ $file['file'] }}">{{ $file["name"] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="download--files">
                                        @foreach($download["files"] AS $file)
                                        <a href="{{ asset($file['file']) }}" download class="d-none"></a>
                                        @endforeach
                                    </div>
                                    <div class="download--name">{!! $download["name"] !!}</div>
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