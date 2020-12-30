@push('styles')
    <link href="{{ asset('css/page/contacto.css') }}" rel="stylesheet">
    <link href="{{ asset('css/page/form.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/page/datos.js') }}"></script>
@endpush
<div class="wrapper-form wrapper-pagos py-5 border-top-0 bg-white">
    <div class="container">
        <h3 class="title text-center">Consulta general</h3>
        <p class="subtitle text-center">Contáctanos y te brindaremos toda la información que necesites</p>

        <div class="row mt-3 justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <form action="{{ route('client.datos', ['section' => 'consulta']) }}" novalidate id="form" onsubmit="event.preventDefault(); enviar(this);" method="post">
                @csrf
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <input value="" required="true" name="nombre" class="form-control" type="text" placeholder="Razón Social o Nombre">
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-6">
                            <input value="" required="true" name="email" class="form-control" type="email" placeholder="Email">
                        </div>
                        <div class="col-12 col-md-6">
                            <input value="" name="telefono" class="form-control" type="phone" placeholder="Teléfono">
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12">
                            <input value="" name="localidad" class="form-control" type="text" placeholder="Localidad">
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12">
                            <textarea data-sample-short="" required="true" name="mensaje" class="form-control" placeholder="Mensaje"></textarea>
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