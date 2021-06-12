@push('styles')
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
    />
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
@endpush
<section>
    <div class="client">
        <div class="container-fluid">
            <ol class="breadcrumb bg-transparent p-0 border-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
                <li class="breadcrumb-item active">{{ $data['title'] }}</li>
            </ol>
            @isset($data["clients"])
                <select id="clientListOther" class="form-control form-control-lg">
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
            @endif
        </div>
    </div>
</section>