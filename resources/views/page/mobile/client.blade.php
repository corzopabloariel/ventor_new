@push('styles')
    <link href="{{ asset('css/mobile/client.css') . '?t=' . time() }}" rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
    />
@endpush
@push("js")
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

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
    const element = document.querySelector('#clientList');
    const choices = new Choices(element);
    init = () => {};
</script>
@endpush
<section>
    <div class="client">
        <div class="container-fluid">
            @isset($data["clients"])
                <div class="client__list shadow-sm">
                    <select id="clientList" class="form-control form-control-lg" onchange="selectClient(this);">
                        <option value="">Seleccione cliente</option>
                        @foreach($data["clients"] AS $client)
                        @php
                        $selected = "";
                        //if (session()->has('nrocta_client') && session()->get('nrocta_client') == $client->nrocta)
                            //$selected = "selected=true";
                        @endphp
                        <option {{ $selected }} value="{{ $client->nrocta }}">{{ $client->nrocta }} | {{ $client->razon_social }} @if(!empty($client->direml))({{ $client->direml }})@endif</option>
                        @endforeach
                    </select>
                    @if (!empty($data["client"]))
                    <div class="client__select">
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
                </div>
            @endisset
            <h2 class="client__title">{{ $data["title"] }}</h2>
            @if (!empty($data["soap"]))
            <div class="client__list client__list--table shadow-sm">
                @isset($data["soap"]["total"])
                @php
                $total = $data["soap"]["total"];
                $class = $total < 0 ? "text-danger" : "text-success";
                $total = ($total < 0) ? "-$ " . number_format($total * -1, 2, ",", ".") : "$ " . number_format($total, 2, ",", ".");
                $total = "<span class='$class'>$total</span>";
                @endphp
                <h3>TOTAL: {!! $total !!}</h3>
                @endisset
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="thead-dark">
                            <tr>
                                @if ($data["action"] == "comprobantes")
                                <th>MÓDULO</th>
                                <th>CÓDIGO</th>
                                <th>NÚMERO</th>
                                <th>EMISIÓN</th>
                                <th>IMPORTE</th>
                                <th></th>
                                @elseif ($data["action"] == "faltantes")
                                <th>ARTÍCULO</th>
                                <th>DESCRIPCIÓN</th>
                                <th>FECHA</th>
                                <th>PRECIO</th>
                                <th>CANTIDAD</th>
                                <th>TOTAL</th>
                                <th class="client__importe">STOCK CENTRAL</th>
                                @elseif ($data["action"] == "analisis-deuda")
                                <th>APLICACIÓN</th>
                                <th class="client__importe">NRO APLICACIÓN</th>
                                <th>CÓDIGO</th>
                                <th>NÚMERO</th>
                                <th>CUOTA</th>
                                <th>IMPORTE</th>
                                <th>VENCIMIENTO</th>
                                <th>EMISIÓN</th>
                                <th>VENDEDOR</th>
                                <th>COMPROBANTE</th>
                                <th></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            if ($data["action"] == "comprobantes") {
                                $print = collect($data["soap"]["soap"])->map(function($item) {
                                    $class = $item["importeNumber"] < 0 ? "text-danger" : "text-success";
                                    $html = "";
                                    $html .= "<td>{$item["modulo"]}</td>";
                                    $html .= "<td>{$item["codigo"]}</td>";
                                    $html .= "<td class='text-center'>{$item["numero"]}</td>";
                                    $html .= "<td class='text-center'>{$item["emision"]}</td>";
                                    $html .= "<td class='text-right client__importe {$class}'>{$item["importe"]}</td>";
                                    $html .= "<td>{$item["pdf"]}</td>";
                                    return "<tr>{$html}</tr>";
                                })->join("");
                            }
                            if ($data["action"] == "faltantes") {
                                $print = collect($data["soap"]["soap"])->map(function($item) {
                                    $html = "";
                                    $html .= "<td class='client__importe'><a href='https://ventor.com.ar/products,{$item["articulo"]}' target='_blank'>{$item["articulo"]} <i class='text-dark ml-2 fas fa-external-link-alt'></i></a></td>";
                                    $html .= "<td>{$item["descripcion"]}</td>";
                                    $html .= "<td class='text-center'>{$item["fecha"]}</td>";
                                    $html .= "<td class='text-right client__importe'>{$item["precio"]}</td>";
                                    $html .= "<td class='text-center'>{$item["cantidad"]}</td>";
                                    $html .= "<td class='text-right client__importe'>{$item["total"]}</td>";
                                    $html .= "<td class='text-center'>{$item["stock"]}</td>";
                                    return "<tr>{$html}</tr>";
                                })->join("");
                            }
                            if ($data["action"] == "analisis-deuda") {
                                $print = collect($data["soap"]["soap"])->map(function($item) {
                                    $class = $item["importeNumber"] < 0 ? "text-danger" : "text-success";
                                    $html = "";
                                    $html .= "<td>{$item["aplicacion"]}</td>";
                                    $html .= "<td class='text-center'>{$item["nroAplicacion"]}</td>";
                                    $html .= "<td>{$item["codigo"]}</td>";
                                    $html .= "<td class='text-center'>{$item["numero"]}</td>";
                                    $html .= "<td class='text-center'>{$item["cuota"]}</td>";
                                    $html .= "<td class='text-right client__importe {$class}'>{$item["importe"]}</td>";
                                    $html .= "<td class='text-center'>{$item["vencimiento"]}</td>";
                                    $html .= "<td class='text-center'>{$item["emision"]}</td>";
                                    $html .= "<td class='text-center'>{$item["vendedor"]}</td>";
                                    $html .= "<td class='client__importe'>{$item["comprobante"]}</td>";
                                    $html .= "<td>{$item["pdf"]}</td>";
                                    return "<tr>{$html}</tr>";
                                })->join("");
                            }
                            @endphp
                            {!! $print !!}
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="client__list client__list--alert shadow-sm">Seleccione un cliente</div>
            @endif
        </div>
    </div>
</section>