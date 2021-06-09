@push('js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/page/datos.js') . '?t=' . time() }}"></script>
@endpush
<div class="wrapper wrapper__form wrapper__pagos">
    <div class="container">
        <h3 class="title text-center">Consulta general</h3>
        <p class="subtitle text-center">Contáctanos y te brindaremos toda la información que necesites</p>

        <div class="row mt-3 justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <form action="{{ route('client.datos', ['section' => 'consulta']) }}" novalidate id="form" onsubmit="event.preventDefault(); enviar(this);" method="post">
                    @csrf
                    @auth
                        @php
                        $client = auth()->guard('web')->user()->getClient();
                        if (!empty($client)) {
                            $nrocta = $client->nrocta;
                            $razon_social = $client->razon_social;
                            $direml = $client->direml;
                            $telefn = $client->telefn;
                            if (isset($client->address) && isset($client->address['localidad']))
                                $localidad = $client->address['localidad'];
                        }
                        @endphp
                    @endauth
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <label for="">Razón Social o Nombre *</label>
                            <input @if(isset($razon_social)) value="{{ $razon_social }}" @endif required="true" name="nombre" class="form-control @if(isset($razon_social)) bg-warning @endif" type="text" placeholder="Razón Social o Nombre">
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-6">
                            <label for="">Email *</label>
                            <input @if(isset($direml)) value="{{ $direml }}" @endif required="true" name="email" class="form-control @if(isset($direml)) bg-warning @endif" type="email" placeholder="Email">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="">Teléfono</label>
                            <input @if(isset($telefn)) value="{{ $telefn }}" @endif name="telefono" class="form-control @if(isset($telefn)) bg-warning @endif" type="phone" placeholder="Teléfono">
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12">
                            <label for="">Localidad</label>
                            <input @if(isset($localidad)) value="{{ $localidad }}" @endif name="localidad" class="form-control @if(isset($localidad)) bg-warning @endif" type="text" placeholder="Localidad">
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12">
                            <label for="">Mensaje *</label>
                            <textarea rows="5" required="true" name="mensaje" class="form-control" placeholder="Mensaje"></textarea>
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