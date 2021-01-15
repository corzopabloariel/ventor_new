@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
    <link href="{{ asset('css/mobile/contact.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
    <script src="{{ asset('js/mobile/contact.js') }}"></script>
@endpush
<section class="section--no_pad">
    <div class="contact">
        <div class="container-fluid">
            <div class="contact__map">{!! $ventor->address["mapa"] !!}</div>

            <div id="card-slider" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                    @foreach($data["number"] AS $number)
                        <li class="splide__slide">
                            <div class="contact__number">
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
    <div class="contact contact__white">
        <div class="container-fluid">
            <form class="contact__form" action="{{ route('client.datos', ['section' => 'contacto']) }}" novalidate id="form" onsubmit="event.preventDefault(); enviar(this);" method="post">
                {{ csrf_field() }}
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
                <input placeholder="Nombre *" required type="text" value="{{ old('nombre') }}" name="nombre" class="form-control">
                <input placeholder="Apellido" type="text" value="{{ old('apellido') }}" name="apellido" class="form-control">
                <input placeholder="Email *" required type="email" name="email" value="{{ old('email') }}" class="form-control">
                <input placeholder="Teléfono" type="phone" name="telefono" value="{{ old('telefono') }}" class="form-control">
                <textarea name="mensaje" required rows="5" placeholder="Mensaje *" class="form-control">{{ old('mensaje') }}</textarea>
                
                <button type="submit" class="btn btn-primary px-5 text-white text-uppercase">enviar</button>
            </form>
        </div>
    </div>
</section>