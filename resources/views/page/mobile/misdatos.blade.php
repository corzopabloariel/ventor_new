@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mobile/datos.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/page/datos.js') . '?t=' . time() }}"></script>
@endpush
<section>
    <div class="datos">
        <div class="container-fluid">
            <div class="datos__container shadow-sm">
                <h3>Datos actuales</h3>
                <p><strong>Número de cuenta:</strong> {{ $data["client"]->nrocta }}</p>
                <p><strong>Razón social:</strong> {{ $data["client"]->razon_social }}</p>
                @if(!empty($data["client"]->nrodoc))<p><strong>Documento:</strong> {{ $data["client"]->nrodoc }}</p>@endif
                @if(!empty($data["client"]->respon))<p><strong>Responsable:</strong> {{ $data["client"]->respon }}</p>@endif
                @if(!empty($data["client"]->telefn))<p><strong>Teléfono:</strong> {{ $data["client"]->telefn }}</p>@endif
                @if(!empty($data["client"]->direml))<p><strong>Email:</strong> {{ $data["client"]->direml }}</p>@endif
                <fieldset>
                    <legend>Dirección</legend>
                    <p>{{ $data["client"]->address["direccion"] }} ({{ $data["client"]->address["codpos"] }}). {{ $data["client"]->address["provincia"] }}, {{ $data["client"]->address["localidad"] }}</p>
                </fieldset>
                @if(!empty($data["client"]->vendedor))
                <fieldset>
                    <legend>Vendedor</legend>
                    <p><strong>Nombre:</strong> {{ $data["client"]->vendedor["nombre"] }}</p>
                    <p><strong>Teléfono:</strong> {{ $data["client"]->vendedor["telefono"] }}</p>
                    <p><strong>Email:</strong> {{ $data["client"]->vendedor["email"] }}</p>
                </fieldset>
                @endif
                @if(!empty($data["client"]->transportista))
                <fieldset>
                    <legend>Transportista</legend>
                    <p><strong>Nombre:</strong> {{ $data["client"]->transportista["nombre"] }}</p>
                </fieldset>
                @endif
            </div>

            <div class="datos__container shadow-sm">
                <h3>Cambio en datos</h3>
                <form class="contact__form" onsubmit="event.preventDefault(); enviar(this);" action="{{ route('client.datos', ['section' => 'datos']) }}" method="post">
                    @csrf
                    <div class="form-group mb-0">
                        <label for="datos-responsable">Responsable</label>
                        <input placeholder="Responsable" type="text" class="form-control" id="datos-responsable" name="respon">
                    </div>
                    <div class="form-group mb-0">
                        <label for="datos-telefono">Teléfono</label>
                        <input placeholder="Teléfono" type="text" class="form-control" id="datos-telefono" name="telefn">
                    </div>
                    <div class="form-group mb-0">
                        <label for="datos-email">Email</label>
                        <input placeholder="Email" type="text" class="form-control" id="datos-email" name="direml">
                    </div>
                    <div class="form-group mb-0">
                        <label for="datos-obs">Observaciones</label>
                        <textarea name="obs" placeholder="Observaciones" id="datos-obs" rows="3" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary text-uppercase d-block mx-auto text-white px-5">enviar</button>
                </form>
            </div>
            <div class="datos__container datos__container--pass shadow-sm">
                <form class="contact__form" onsubmit="event.preventDefault(); enviar(this);" action="{{ route('client.datos', ['section' => 'password']) }}" method="post">
                    @csrf
                    <div class="form-group mb-0">
                        <label for="datos-pass">Contraseña nueva</label>
                        <input required placeholder="Contraseña nueva" type="password" class="form-control" id="datos-pass" name="password">
                    </div>
                    <div class="form-group mb-0">
                        <label for="datos-pass-2">Repetir contraseña nueva</label>
                        <input required placeholder="Repetir contraseña nueva" type="password" class="form-control" id="datos-pass-2" name="password_2">
                    </div>
                    <button type="submit" class="btn btn-dark text-uppercase d-block mx-auto text-white px-5">Cambiar</button>
                </form>
            </div>
        </div>
    </div>
</section>