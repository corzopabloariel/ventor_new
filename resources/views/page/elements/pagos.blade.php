@push('styles')
    <link href="{{ asset('css/page/contacto.css') . '?t=' . time() }}" rel="stylesheet">
    <link href="{{ asset('css/page/form.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/page/datos.js') . '?t=' . time() }}"></script>
@endpush
<div class="wrapper-pagos bg-white py-5">
    <div class="container">
        <h2 class="title text-uppercase">atención al cliente</h2>
        <h4 class="subtitle">Información sobre pagos</h4>
        <div class="row mt-3 informacion">
            <div class="col-12 col-md-6 d-flex align-items-stretch">
                <div class="p-4 shadow-sm w-100 text-center info">
                    {!! $data["banco"]->value !!}
                </div>
            </div>
            <div class="col-12 col-md-6 d-flex align-items-stretch">
                <div class="p-4 shadow-sm w-100 text-center info">
                    {!! $data["pagos"]->value !!}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper-form wrapper-pagos py-5">
    <div class="container">
        <h3 class="title text-center">Informar pago</h3>
        <p class="subtitle text-center">Contáctanos y te brindaremos toda la información que necesites</p>
        <div class="row mt-3 justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <form action="{{ route('client.datos', ['section' => 'pagos']) }}" novalidate id="form" onsubmit="event.preventDefault(); enviar(this);" method="post">
                @csrf
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-6 my-2">
                            <input value="" required="true" name="nrocliente" class="form-control" type="text" placeholder="Nro. Cliente">
                        </div>
                        <div class="col-12 col-md-6 my-2">
                            <input value="" name="razon" class="form-control" type="text" placeholder="Razón Social">
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-6 my-2">
                            <input value="" required="true" name="fecha" max="{{ date('Y-m-d') }}" class="form-control" type="date" placeholder="Fecha">
                        </div>
                        <div class="col-12 col-md-6 my-2">
                            <input value="" required="true" name="importe" class="form-control" type="text" placeholder="Importe">
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-6 my-2">
                            <input value="" required="true" name="banco" class="form-control" type="text" placeholder="Banco">
                        </div>
                        <div class="col-12 col-md-6 my-2">
                            <input value="" required="true" name="sucursal" class="form-control" type="text" placeholder="Sucursal">
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-6 my-2">
                            <input value="" required="true" name="facturas" class="form-control" type="text" placeholder="Facturas canceladas">
                        </div>
                        <div class="col-12 col-md-6 my-2">
                            <input value="" required="true" name="descuento" class="form-control" type="text" placeholder="Descuento efectuado">
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 my-2">
                            <textarea data-sample-short="" name="observaciones" class="form-control" placeholder="Observaciones"></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary text-uppercase rounded-pill d-block mx-auto text-white px-5">enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>