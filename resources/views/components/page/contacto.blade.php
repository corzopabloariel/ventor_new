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