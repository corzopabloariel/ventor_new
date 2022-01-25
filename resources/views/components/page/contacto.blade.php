<section class="section">
    <div class="section__holder">
        <div class="section__map">
            {!! $ventor->address["mapa"] !!}
        </div>
    </div>
    <div class="section__holder">
        <h2 class="section__title">Sede Ciudad de Buenos Aires</h2>
        <div class="section__numbers">
            @foreach($numeros AS $number)
                @include('components.page.contacto_number', ['element' => $number])
            @endforeach
        </div>
    </div>
</section>
<section class="section" style="background-color: #eee">
    <div class="section__holder">
        <div class="section__map">
        <iframe src="https://www.google.com/maps/d/embed?mid=1jX6Gl5rvxwMRNP-QFoWdofQHGxWr5zce" height="480"></iframe>
        </div>
    </div>
    <div class="section__holder">

		<div class="contacto__row">
			<div class="contacto__info__top">
				<i class="contacto__icon fas fa-comment-dots"></i><h3 class="contacto__title"><strong>{{ config('app.name') }}</strong></h3>
			</div>

			<div class="contacto__grid3 --contact">
				<a href="tel:{{ $ventor->first_phone['key'] }}" target="_blank" class="contacto__contact__items --phone">
					<i class="contacto__contact__icon fas fa-phone-alt"></i>
					<span class="contacto__contact__text">{{ $ventor->first_phone['value'] }}</span>
				</a>

				<a href="mailto:{{ $ventor->first_email }}" target="_blank" class="contacto__contact__items --email">
                    <i class="contacto__contact__icon fas fa-envelope"></i>
					<span class="contacto__contact__text">{{ $ventor->first_email }}</span>
				</a>

				<div class="contacto__contact__items --time">
					<i class="contacto__contact__icon far fa-clock"></i>
					<span class="contacto__contact__text --normal">Lunes a Viernes 8 a 17:30 hs.</span>
				</div>

			</div>

		</div>
    </div>
    <div class="section__holder">
        <form action="{{ route('client.datos', ['section' => 'contacto']) }}" novalidate id="contactoForm" method="post">
            <div class="contacto__form">
                <div class="form-item">
                    <label for="nombre">Nombre completo *</label>
                    <input placeholder="Nombre completo *" required id="nombre" type="text" value="{{ old('nombre') }}" name="nombre" class="input">
                </div>
                <div class="form-item">
                    <label for="email">Email *</label>
                    <input placeholder="Email *" required type="email" id="email" name="email" value="{{ old('email') }}" class="input">
                </div>
                <div class="form-item">
                    <label for="telefono">Teléfono</label>
                    <input placeholder="Teléfono" type="phone" id="telefono" name="telefono" value="{{ old('telefono') }}" class="input">
                </div>
                <div class="form-item">
                    <label for="mensaje">Mensaje *</label>
                    <textarea id="mensaje" name="mensaje" required rows="5" placeholder="Mensaje *" class="textarea">{{ old('mensaje') }}</textarea>
                </div>
                <button class="button button--primary-fuchsia" id="contactoSubmit">
                    Enviar consulta
                </button>
            </div>
        </div>
    </form>
</section>