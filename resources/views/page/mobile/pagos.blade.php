@push('js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
@endpush
<section>
    <div class="contact wrapper">
        <div class="container-fluid">
            <div class="shadow-sm contact_container">
                <h3 class="contact__title text-center">Atención al cliente</h3>
                <p class="contact__title contact__title--secondary text-center">Información sobre pagos</p>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="contact wrapper">
        <div class="contact__number contact__number--slim">
            <div class="shadow-sm contact_container">
                {!! $data["banco"]->value !!}
            </div>
        </div>
        <div class="contact__number contact__number--slim">
            <div class="shadow-sm contact_container">
                {!! $data["pagos"]->value !!}
            </div>
        </div>
    </div>
</section>
<section>
    <div class="contact wrapper">
        <div class="container-fluid">
            <form class="contact__form" action="{{ route('client.datos', ['section' => 'pagos']) }}" novalidate id="form--pay" method="post">
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
                <div class="form-group mb-0">
                    <label for="nrocliente">Nro. cliente <span class="text-danger">*</span></label>
                    <input @if(isset($nrocta)) value="{{ $nrocta }}" @endif required="true" name="nrocliente" class="form-control @if(isset($nrocta)) bg-warning @endif" type="text" placeholder="Nro. Cliente">
                </div>
                <div class="form-group mb-0">
                    <label for="razon">Razón social</label>
                    <input @if(isset($razon_social)) value="{{ $razon_social }}" @endif name="razon" class="form-control @if(isset($razon_social)) bg-warning @endif" type="text" placeholder="Razón Social">
                </div>
                <div class="form-group mb-0">
                    <label for="fecha">Fecha <span class="text-danger">*</span></label>
                    <input id="fecha" required="true" name="fecha" max="{{ date('Y-m-d') }}" class="form-control" type="date" placeholder="Fecha">
                </div>
                <div class="form-group mb-0">
                    <label for="importe">Importe <span class="text-danger">*</span></label>
                    <input id="importe" required="true" name="importe" class="form-control" type="text" placeholder="Importe">
                </div>
                <div class="form-group mb-0">
                    <label for="banco">Banco <span class="text-danger">*</span></label>
                    <input id="banco" required="true" name="banco" class="form-control" type="text" placeholder="Banco">
                </div>
                <div class="form-group mb-0">
                    <label for="sucursal">Sucursal <span class="text-danger">*</span></label>
                    <input id="sucursal" required="true" name="sucursal" class="form-control" type="text" placeholder="Sucursal">
                </div>
                <div class="form-group mb-0">
                    <label for="facturas">Facturas <span class="text-danger">*</span></label>
                    <input id="facturas" required="true" name="facturas" class="form-control" type="text" placeholder="Facturas canceladas">
                </div>
                <div class="form-group mb-0">
                    <label for="descuento">Descuento <span class="text-danger">*</span></label>
                    <input id="descuento" required="true" name="descuento" class="form-control" type="text" placeholder="Descuento efectuado">
                </div>
                <div class="form-group mb-0">
                    <label for="observaciones">Observaciones</label>
                    <textarea id="observaciones" data-sample-short="" name="observaciones" class="form-control" placeholder="Observaciones"></textarea>
                </div>
                <button type="submit" class="btn btn-primary text-uppercase d-block mx-auto text-white px-5">enviar</button>
            </form>
        </div>
    </div>
</section>