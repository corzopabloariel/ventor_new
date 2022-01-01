<section class="section">
    <div class="section__holder">
        <div class="">
            <a download href="{{ $program }}" class="download__program" id="download__program"><strong>Descargar:</strong> VENTOR Catálogo y Pedidos</a>
        </div>
    </div>
    @foreach($downloads AS $download)
    <div class="section__holder">
        <h2 class="section__title">{{$download['title']}}</h2>
        <div class="listing-lowered" style="border-bottom: none">
            <div class="owl-carousel owl-theme">

                @foreach($download['items'] AS $element)

                    @includeIf('components.page.descarga_item', ['element' => $element])

                @endforeach

            </div>
        </div>
    </div>
    @endforeach
</section>