<div class="notification">
    Espere
</div>
<section class="section">
    <div class="section__holder">
        <div class="section__title" style="justify-content: center;">
            <a download href="{{ $program }}" class="button button--lowered button--medium" id="download__program"><strong>Descargar:</strong> VENTOR Catálogo y Pedidos</a>
        </div>
    </div>
    @foreach($downloads AS $download)
    <div class="section__holder">
        <h2 class="section__title">{{$download['title']}}</h2>
        <div class="download">
            @foreach($download['items'] AS $element)

                @includeIf('components.page.descarga_item', ['element' => $element])

            @endforeach
        </div>
    </div>
    @endforeach
</section>