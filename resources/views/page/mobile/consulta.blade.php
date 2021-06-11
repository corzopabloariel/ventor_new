@push('js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
@endpush
<section>
    <div class="contact wrapper">
        <div class="container-fluid">
            <div class="shadow-sm contact_container">
                <h3 class="contact__title text-center">Consulta general</h3>
                <p class="contact__title contact__title--secondary text-center">Contáctanos y te brindaremos toda la información que necesites</p>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="contact wrapper">
        <div class="container-fluid">
            <form class="contact__form" action="{{ route('client.datos', ['section' => 'consulta']) }}" novalidate id="form--consult" method="post">
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
                <div class="form-group mb-0">
                    <label for="">Razón Social o Nombre <span class="text-danger">*</span></label>
                    <input @if(isset($razon_social)) value="{{ $razon_social }}" @endif required="true" name="nombre" class="form-control @if(isset($razon_social)) bg-warning @endif" type="text" placeholder="Razón Social o Nombre">
                </div>
                <div class="form-group mb-0">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input @if(isset($direml)) value="{{ $direml }}" @endif required="true" name="email" class="form-control @if(isset($direml)) bg-warning @endif" type="email" placeholder="Email">
                </div>
                <div class="form-group mb-0">
                    <label for="telefono">Teléfono</label>
                    <input @if(isset($telefn)) value="{{ $telefn }}" @endif name="telefono" class="form-control @if(isset($telefn)) bg-warning @endif" type="phone" placeholder="Teléfono">
                </div>
                <div class="form-group mb-0">
                    <label for="localidad">Localidad</label>
                    <input @if(isset($localidad)) value="{{ $localidad }}" @endif name="localidad" class="form-control @if(isset($localidad)) bg-warning @endif" type="text" placeholder="Localidad">
                </div>
                <div class="form-group mb-0">
                    <label for="mensaje">Mensaje <span class="text-danger">*</span></label>
                    <textarea id="mensaje" data-sample-short="" required="true" name="mensaje" class="form-control" placeholder="Mensaje"></textarea>
                </div>
                <button type="submit" class="btn btn-primary text-uppercase d-block mx-auto text-white px-5">enviar</button>
            </form>
        </div>
    </div>
</section>