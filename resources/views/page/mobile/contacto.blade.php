
@push('js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
@endpush
<section>
    <div class="contact">
        <div class="contact__map">{!! $ventor->address["mapa"] !!}</div>
    </div>
</section>
<section>
    <div class="contact wrapper">
        <div class="container-fluid">
            <div id="card-slider-contact" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                    @foreach($data["number"] AS $number)
                        <li class="splide__slide">
                            <div class="contact__number shadow-sm">
                                <h4 class="contact__title">{{ $number->name }}</h4>
                                @if( !empty( $number->person ) )
                                <h5 class="contact__responsable">{{ $number->person }}</h5>
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
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="contact wrapper">
        <div class="container-fluid">
            <form class="contact__form" action="{{ route('client.datos', ['section' => 'contacto']) }}" novalidate id="form--contact" method="post">
                {{ csrf_field() }}
                <div class="form-group mb-0">
                    <label for="mandar">Enviar a</label>
                    <select name="mandar" id="mandar" class="form-control">
                        <option value="" selected hidden>Enviar a</option>
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
                <div class="form-group mb-0">
                    <label for="nombre">Nombre completo <span class="text-danger">*</span></label>
                    <input id="nombre" placeholder="Nombre" required type="text" value="{{ old('nombre') }}" name="nombre" class="form-control">
                </div>
                <div class="form-group mb-0">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input id="email" placeholder="Email" required type="email" name="email" value="{{ old('email') }}" class="form-control">
                </div>
                <div class="form-group mb-0">
                    <label for="telefono">Teléfono</label>
                    <input id="telefono" placeholder="Teléfono" type="phone" name="telefono" value="{{ old('telefono') }}" class="form-control">
                </div>
                <div class="form-group mb-0">
                    <label for="mensaje">Mensaje <span class="text-danger">*</span></label>
                    <textarea id="mensaje" name="mensaje" required rows="5" placeholder="Mensaje" class="form-control">{{ old('mensaje') }}</textarea>
                </div>
                <small>*Los campos son obligatorios</small>
                <button type="submit" class="btn btn-primary text-uppercase d-block mx-auto text-white px-5">enviar</button>
            </form>
        </div>
    </div>
</section>