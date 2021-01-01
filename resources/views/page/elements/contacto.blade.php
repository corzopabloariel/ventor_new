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
<div class="wrapper-contacto">
    <div class="mapa bg-white">
        {!! $ventor->address["mapa"] !!}
    </div>
    <div class="wrapper-numero bg-white py-3">
        <div class="container">
            <h3 class="title">Sede ciudad de buenos aires</h3>
            <div class="row">
                @foreach($data["number"] AS $number)
                <div class="col-12 col-md-4 col-lg-3 mt-3 d-flex align-items-stretch flex-wrap">
                    <div class="numero p-3 w-100">
                        <h4 class="title">{{ $number->nombre }}</h4>
                        @if( !empty( $number->person ) )
                        <h5 class="responsable">{{ $number->person }}</h5>
                        @endif
                        @if(!empty($number->email))
                            <div class="mt-2">
                                {!! $number->printEmail() !!}
                            </div>
                        @endif
                        @if(!empty($number->internal))
                            <h5 class="interno mt-2"><strong class="mr-2">Interno</strong>{{ $number->internal }}</h5>
                        @endif
                        @if(!empty($number->phone))
                            <div class="mt-2">
                                {!! $number->printPhone() !!}
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container-fluid mb-2">
        <div class="row">
            <div class="col-12 px-0">
                <iframe src="https://www.google.com/maps/d/embed?mid=1jX6Gl5rvxwMRNP-QFoWdofQHGxWr5zce" class="w-100 border-0" height="480"></iframe>
            </div>
        </div>
    </div>
    <div class="bg-white py-5 mt-n3">
        <div class="container pb-4">
            <div class="row">
                <div class="col-12 col-md-4">
                    <h3 class="title mb-3">Ventor</h3>
                    <ul class="list-unstyled info mb-0">
                        <li class="d-flex align-items-start">
                            {!! $ventor->addressPrint() !!}
                        </li>
                        <li class="d-flex mt-3 align-items-start">
                            {!! $ventor->phonesPrint() !!}
                        </li>
                        <li class="d-flex mt-3 align-items-start">
                            {!! $ventor->emailsPrint() !!}
                        </li>
                    </ul>
                </div>
                <div class="col-12 wrapper-form col-md-8">
                    <form action="{{ route('client.datos', ['section' => 'contacto']) }}" novalidate id="form" onsubmit="event.preventDefault(); enviar(this);" method="post">
                        {{ csrf_field() }}
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6 my-2">
                                <label for="mandar">Enviar a</label>
                                <select name="mandar" id="mandar" class="form-control">
                                <option value="" selected hidden>Seleccione email</option>
                                @foreach($data["number"] AS $n)
                                    @if (!empty($n["email"]))
                                    <optgroup label="{{ $n['name'] . ' - ' . $n['person'] }}">
                                        @foreach($n["email"] AS $e)
                                        <option>{!!$e["email"]!!}</option>
                                        @endforeach
                                    </optgroup>
                                    @endif
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 col-md-6 my-2">
                                <input placeholder="Nombre *" required type="text" value="{{ old('nombre') }}" name="nombre" class="form-control">
                            </div>
                            <div class="col-12 col-md-6 my-2">
                                <input placeholder="Apellido" type="text" value="{{ old('apellido') }}" name="apellido" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-6 col-12 my-2">
                                <input placeholder="Email *" required type="email" name="email" value="{{ old('email') }}" class="form-control">
                            </div>
                            <div class="col-lg-6 col-12 my-2">
                                <input placeholder="Teléfono" type="phone" name="telefono" value="{{ old('telefono') }}" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 my-2">
                                <textarea name="mensaje" required rows="5" placeholder="Mensaje *" class="form-control">{{ old('mensaje') }}</textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-5 text-white text-uppercase">enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>