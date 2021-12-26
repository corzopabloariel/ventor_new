<section class="section listing-home">
    <div class="section__holder">
        <h2 class="section__title">{{ $titulo }}</h2>
        <h3 class="section__subtitle">{{ $subtitulo }}</h3>
        <div>{!! $texto !!}</div>
        <div class="section__frase">{!! $frase !!}</div>
    </div>
</section>
<section class="section" style="background-color: #eee">
    <div class="section__holder">
        <h4 class="section__subtitle">{{ $politica["titulo"] }}</h4>
        <div class="section__simple --legal__text">{!! $politica["texto"] !!}</div>

        <h4 class="section__subtitle">{{ $garantia["titulo"] }}</h4>
        <div class="section__simple --legal__text">{!! $garantia["texto"] !!}</div>
    </div>
</section>