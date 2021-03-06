@push('js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
@endpush
<section>
    <div class="wrapper__contacto">
        <div class="mapa bg-white">
            {!! $ventor->address["mapa"] !!}
        </div>
        <div class="wrapper__numero wrapper">
            <div class="container">
                <h3 class="title">Sede ciudad de buenos aires</h3>
                <div class="row">
                    @foreach($data["number"] AS $number)
                    <div class="col-12 col-md-4 col-lg-3 mt-3 d-flex align-items-stretch flex-wrap">
                        <div class="numero p-3 w-100">
                            <h4 class="title">{{ $number->name }}</h4>
                            @if( !empty( $number->person ) )
                            <h5 class="responsable">{{ $number->person }}</h5>
                            @endif
                            @if(!empty($number->email))
                                <div class="mt-2 text-truncate">
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
    </div>
</section>
<section>
    <div class="container-fluid">
        <div class="wrapper wrapper__contacto">
            <div class="row">
                <div class="col-12 col-xl-6">
                    <iframe src="https://www.google.com/maps/d/embed?mid=1jX6Gl5rvxwMRNP-QFoWdofQHGxWr5zce" class="w-100 border-0" height="480"></iframe>
                </div>
                <div class="col-12 col-xl-6">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <h3 class="title mb-3">{{ config('app.name') }}</h3>
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
                            <form action="{{ route('client.datos', ['section' => 'contacto']) }}" novalidate id="form--contact" method="post">
                                {{ csrf_field() }}
                                <div class="row justify-content-center">
                                    <div class="col-12">
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
                                    <div class="col-12">
                                        <label for="nombre">Nombre completo *</label>
                                        <input placeholder="Nombre completo *" required id="nombre" type="text" value="{{ old('nombre') }}" name="nombre" class="form-control">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-6 col-12">
                                        <label for="email">Email *</label>
                                        <input placeholder="Email *" required type="email" id="email" name="email" value="{{ old('email') }}" class="form-control">
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="telefono">Teléfono</label>
                                        <input placeholder="Teléfono" type="phone" id="telefono" name="telefono" value="{{ old('telefono') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label for="mensaje">Mensaje *</label>
                                        <textarea id="mensaje" name="mensaje" required rows="5" placeholder="Mensaje *" class="form-control">{{ old('mensaje') }}</textarea>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <small>*Los campos son obligatorios</small><br/>
                                        <button type="submit" class="btn btn-primary px-5 text-uppercase">enviar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>