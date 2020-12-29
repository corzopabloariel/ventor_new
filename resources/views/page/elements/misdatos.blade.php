@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/page/datos.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
@endpush
<section>
    <div class="datos">
        <div class="container">
            <ol class="breadcrumb bg-transparent p-0 border-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Mis datos</li>
            </ol>
            
            <div class="container--datos">
                <div>
                    <p><strong>Número de cuenta:</strong> {{ $data["client"]->nrocta }}</p>
                    <p><strong>Razón social:</strong> {{ $data["client"]->razon_social }}</p>
                    @if(!empty($data["client"]->respon))<p><strong>Responsable:</strong> {{ $data["client"]->respon }}</p>@endif
                    @if(!empty($data["client"]->telefn))<p><strong>Teléfono:</strong> {{ $data["client"]->telefn }}</p>@endif
                    @if(!empty($data["client"]->direml))<p><strong>Email:</strong> {{ $data["client"]->direml }}</p>@endif
                    @if(!empty($data["client"]->nrodoc))<p><strong>Documento:</strong> {{ $data["client"]->nrodoc }}</p>@endif
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
                <div>
                
                </div>
            </div>
        </div>
    </div>
</section>