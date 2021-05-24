@push('js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/page/datos.js') . '?t=' . time() }}"></script>
@endpush
<section>
    <div class="wrapper wrapper__pagos">
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
</section>
<section>
    <div class="wrapper wrapper__form">
        <div class="container">
            <h3 class="title text-center">Informar pago</h3>
            <p class="subtitle text-center">Contáctanos y te brindaremos toda la información que necesites</p>
            <div class="row mt-3 justify-content-center">
                <div class="col-12 col-md-8 col-lg-7">
                    <form action="{{ route('client.datos', ['section' => 'pagos']) }}" novalidate id="form" onsubmit="event.preventDefault(); enviar(this);" method="post">
                        @csrf
                        @auth
                            @php
                            $client = auth()->guard('web')->user()->getClient();
                            if (!empty($client)) {
                                $nrocta = $client->nrocta;
                                $razon_social = $client->razon_social;
                            }
                            @endphp
                        @endauth
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6">
                                <label for="">Nro. Cliente</label>
                                <input @if(isset($nrocta)) value="{{ $nrocta }}" @endif required="true" name="nrocliente" class="form-control @if(isset($nrocta)) bg-warning @endif" type="text" placeholder="Nro. Cliente">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="">Razón Social</label>
                                <input @if(isset($razon_social)) value="{{ $razon_social }}" @endif name="razon" class="form-control @if(isset($razon_social)) bg-warning @endif" type="text" placeholder="Razón Social">
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-12 col-md-6">
                                <label for="">Fecha</label>
                                <input value="" required="true" name="fecha" class="form-control datepicker" type="text" placeholder="Fecha">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="">Importe</label>
                                <input value="" required="true" name="importe" class="form-control" type="text" placeholder="Importe">
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-12 col-md-6">
                                <label for="">Banco</label>
                                <input value="" required="true" name="banco" class="form-control" type="text" placeholder="Banco">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="">Sucursal</label>
                                <input value="" required="true" name="sucursal" class="form-control" type="text" placeholder="Sucursal">
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-12 col-md-6">
                                <label for="">Facturas canceladas</label>
                                <input value="" required="true" name="facturas" class="form-control" type="text" placeholder="Facturas canceladas">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="">Descuento efectuado</label>
                                <input value="" required="true" name="descuento" class="form-control" type="text" placeholder="Descuento efectuado">
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-12">
                                <label for="">Observaciones</label>
                                <textarea data-sample-short="" name="observaciones" class="form-control" placeholder="Observaciones"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary text-uppercase d-block mx-auto text-white px-5">enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>