@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/page/productos.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/page/producto.js') }}"></script>
@endpush
<section>
    <div class="checkout">
        <div class="container-fluid">
            <div class="table-responsive container--table">
                <table class="table table-hover table-striped table-borderless">
                    <thead class="thead-dark">
                        <tr>
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
                    <tfoot>
                        <tr>
                            <th colspan="2"></th>
                            <th colspan="4" class="p-0">
                                <div class="bg-dark text-white p-4">
                                    <div class="d-flex justify-content-between">
                                        <span class="checkout--total">Total</span>
                                        <span class="checkout--total checkout--total__price">{{ $data["total"] }}</span>
                                    </div>
                                    <small class="">El total no incluye IVA ni impuestos internos</small>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button id="btn--back" class="btn btn-light" type="button">ELEGIR MAS PRODUCTOS</button>
                                        <button id="btn--confirm" class="btn btn-primary" type="button">CONFIRMAR PEDIDO</button>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row">
                <div class="col-12 col-md">
                    <select name="transport" id="transport" class="form-control form-control-lg bg-secondary selectpicker" multiple data-max-options="1" data-header="Seleccione tranporte" data-live-search="true" data-style="btn-white" data-width="100%" title="Seleccione un transporte">
                        {!! $data["transport"] !!}
                    </select>
                    <div class="mt-3">
                        <label for="">Observaciones</label>
                        <textarea name="obs" id="obs" placeholder="Observaciones" rows="5" class="form-control form-control-lg"></textarea>
                    </div>
                </div>
                <div class="col-12 col-md"></div>
            </div>
        </div>
    </div>
</section>