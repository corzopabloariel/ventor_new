@push('styles')
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mobile/product.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/mobile/product.js') . '?t=' . time() }}"></script>
@endpush
<section>
    <div class="checkout">
        <div class="container-fluid">
            @if (session()->has('nrocta_client') && isset($data["client"]))
            <div class="checkout__container shadow-sm">
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
            <div class="checkout__container shadow-sm">
                <div class="table-responsive container--table">
                    <table class="table table-striped table-borderless">
                        <thead class="thead-dark">
                            <tr class="text-uppercase">
                                <th class="th--image"></th>
                                <th class="th--name">producto</th>
                                <th class="th--venta">u. venta</th>
                                <th class="th--stock">p. unitario</th>
                                <th class="th--precio">cantidad</th>
                                <th class="th--action">subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            {!! $data["html"] !!}
                        </tbody>
                    </table>
                </div>
                <div class="checkout-data">
                    <label for="transport">Transporte</label>
                    <select name="transport" id="transport" class="form-control form-control-lg bg-secondary">
                        <option value="">Seleccione un transporte</option>
                        {!! $data["transport"] !!}
                    </select>
                    <div>
                        <label for="obs">Observaciones</label>
                        <textarea name="obs" id="obs" placeholder="Observaciones" rows="5" class="form-control form-control-lg"></textarea>
                    </div>
                </div>
            </div>
            <div class="checkout__container shadow-sm bg-dark text-white p-4">
                <div class="d-flex justify-content-between">
                    <span class="checkout--total">Total</span>
                    <span class="checkout--total checkout--total__price">{{ $data["total"] }}</span>
                </div>
                <small class="">El total no incluye IVA ni impuestos internos</small>
                <div class="d-flex justify-content-between mt-3">
                    <button id="btn--back" class="btn btn-light mr-3" type="button">ELEGIR MAS PRODUCTOS</button>
                    <button id="btn--confirm" class="btn btn-primary" type="button">CONFIRMAR PEDIDO</button>
                </div>
            </div>
        </div>
    </div>
</section>