
<section class="section listing-home">
    <div class="section__holder">
        <div class="section --legal__text">
            {!! $texto !!}
        </div>
    </div>
    <div class="section__holder">
        <div class="numeros__row">
            <h3 class="numeros__title">Nuestra <strong>historia</strong></h3>
            <div class="historia">
                @foreach($anio AS $a)
				<div class="historia__item">
					<div class="historia__item__top @if($loop->first) --active @endif">
                        <div class="historia__item__title">
                            <i class="fas fa-calendar-day"></i>
                            {{ $a['order'] }}
                        </div>
                        <i class="fas fa-chevron-up @if($loop->first) --active @endif"></i>
                    </div>
					<div class="historia__item__content @if($loop->first) --active @endif">
                        {!! $a['texto'] !!}
                    </div>
				</div>
                @endforeach
			</div>
        </div>
    </div>
</section>