@push('styles')
    <style>
        .client {
            padding: 3em 0;
            min-height: 400px
        }
        .client th {
            text-transform: uppercase;
            white-space: nowrap;
        }
        .client th, .client td {
            vertical-align: middle;
        }
        .client .btn {
            border-radius: .5em;
        }
    </style>
@endpush
@push("js")
<script src="{{ asset('js/alertify.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script>
    window.axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    const formatter = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    });
    const selectClient = function(t) {
        let nrocta = t.value;
        axios.post(document.querySelector('meta[name="client"]').content, {
            nrocta,
            client: 1
        })
        .then(function (res) {
            location.reload();
        });
    };
    init = () => {};
    @if ($data["action"] == "analisis-deuda")
        init = () => {
            let tbody = document.querySelector("tbody");
            if (!tbody)
                return;
            let tr = tbody.getElementsByTagName("tr");
            for(let t of tr) {
                const link = "http://181.15.104.2/comprobantes/";
                let td = document.createElement("td");
                let tds = t.getElementsByTagName("td");
                let name = `VT${tds[2].textContent}${tds[3].textContent}.PDF`;
                const btn = `<a class="btn btn-danger" target="blank" href="${link}${name}"><i class="fas fa-file-pdf"></i></a>`;
                td.classList.add("text-center");
                td.innerHTML = btn;
                t.appendChild(td);
            }
            let total = 0;
            $(".client table tbody tr td:nth-child(8)" ).each((i, x) => {
                total += parseFloat($(x).text());
                $(x).text(formatter.format($(x).text()));
            });
            $(".total").text(formatter.format(total));
        };
    @endif
    @if ($data["action"] == "faltantes")
    init = () => {
        if( $( ".client table tbody" ).length ) {
            let total = 0;
            $( ".client table tbody tr td:nth-child( 5 ),.client table tbody tr td:nth-child( 7 )" ).each( ( i , x ) => {
                $(x).addClass( "text-right" );
                $(x).text( formatter.format( $(x).text() ) );
            });
            $( ".client table tbody tr td:nth-child( 1 ),.client table tbody tr td:nth-child( 4 ),.client table tbody tr td:nth-child( 6 ),.client table tbody tr td:nth-child( 8 )" ).addClass( "text-center" );
            $( ".client table tbody tr td:nth-child( 2 ),.client table tbody tr td:nth-child( 3 )" ).addClass( "text-left" );

            $( ".client table tbody tr td:nth-child( 6 ),.client table tbody tr td:nth-child( 8 )" ).each( ( i , x ) => {
                $(x).text( parseInt( $(x).text() ) );
            });
        }
    };
    @endif
    @if ($data["action"] == "comprobantes")
    init = () => {
        let tbody = document.querySelector("tbody");
        if (!tbody)
            return;
        let tr = tbody.getElementsByTagName("tr");
        for(let t of tr) {
            const link = "http://181.15.104.2/comprobantes/";
            let td = document.createElement("td");
            let tds = t.getElementsByTagName("td");
            let name = `${tds[0].textContent}${tds[1].textContent}${tds[2].textContent}.PDF`;
            const btn = `<a class="btn btn-danger" target="blank" href="${link}${name}"><i class="fas fa-file-pdf"></i></a>`;
            td.classList.add("text-center");
            td.innerHTML = btn;
            t.appendChild(td);
        }
        let total = 0;
        $( ".client table tbody tr td:nth-child( 7 )" ).each( ( i , x ) => {
            $(x).addClass( "text-right" );
            $(x).text( formatter.format( $(x).text() == "" ? "0" : $(x).text() ) );
        });
        $( ".client table tbody tr td:nth-child( 4 )" ).addClass( "text-center" );
        $( ".client table tbody tr td:nth-child( 1 ),.client table tbody tr td:nth-child( 2 ),.client table tbody tr td:nth-child( 6 )" ).addClass( "text-left" );
    };
    @endif
    init();
</script>
@endpush
<section>
    <div class="client">
        <div class="container-fluid">
            <ol class="breadcrumb bg-transparent p-0 border-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
                <li class="breadcrumb-item active">{{ $data['title'] }}</li>
            </ol>
            @isset($data["clients"])
                <select id="clientList" class="form-control form-control-lg selectpicker" multiple data-max-options="1" data-container="body" data-header="Seleccione cliente" data-live-search="true" data-width="100%" title="Seleccione cliente" onchange="selectClient(this);">
                    @foreach($data["clients"] AS $client)
                    @php
                    $selected = "";
                    //if (session()->has('nrocta_client') && session()->get('nrocta_client') == $client->nrocta)
                        //$selected = "selected=true";
                    @endphp
                    <option {{ $selected }} data-subtext="{{ $client->nrocta }}" value="{{ $client->nrocta }}">{{ $client->razon_social }} @if(!empty($client->direml))({{ $client->direml }})@endif</option>
                    @endforeach
                </select>
                @if (!empty($data["client"]))
                <div class="my-3">
                    <h2><strong>Cliente:</strong> {{ $data["client"]->razon_social }} ({{ $data["client"]->nrocta }})</h2>
                    <div class="data-client">
                        @if (!empty($data["client"]->direml))
                            <p class="mb-0">
                                <i class="fas fa-envelope mr-2"></i><a href="mailto:{{ $data["client"]->direml }}">{{ $data["client"]->direml }}</a>
                            </p>
                        @endif
                        @if (!empty($data["client"]->telefn))
                            <p class="mb-0">
                                <i class="fas fa-phone-alt mr-2"></i><a href="tel:{{ $data["client"]->telefn }}">{{ $data["client"]->telefn }}</a>
                            </p>
                        @endif
                    </div>
                </div>
                @endif
            @endisset
            @if (!empty($data["soap"]))
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="thead-dark">
                        <tr>
                            @if ($data["action"] == "comprobantes")
                            <th>Módulo</th>
                            <th>Código</th>
                            <th>Número</th>
                            <th>Emisión</th>
                            <th>Cuenta</th>
                            <th>Nombre</th>
                            <th>Importe</th>
                            <th></th>
                            @elseif ($data["action"] == "faltantes")
                            <th>Cuenta</th>
                            <th>Artículo</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Stock Central</th>
                            @elseif ($data["action"] == "analisis-deuda")
                            <th>Aplicación</th>
                            <th>Nro. Aplicación</th>
                            <th>Código</th>
                            <th>Número</th>
                            <th>Cuota</th>
                            <th>Cód. Cliente</th>
                            <th>Cliente</th>
                            <th>Importe</th>
                            <th>Vencimiento</th>
                            <th>Emisión</th>
                            <th>Vendedor</th>
                            <th>Comprobante</th>
                            <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>{!! $data["soap"] !!}</tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</section>