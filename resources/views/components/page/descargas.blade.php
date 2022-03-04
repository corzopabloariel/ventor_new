<div class="notification">
    Espere
</div>
<section class="section">
    <div class="section__holder">
        <div class="section__title" style="justify-content: center;">
            <a download href="{{ $program }}" class="button button--lowered button--medium" id="download__program"><strong>Descargar:</strong> VENTOR Catálogo y Pedidos</a>
        </div>
        @auth
        <div class="alert-errors --alert">
            <p><i class="fas fa-info-circle"></i> En caso de que las descargas no comiencen, use la vista simplificada [<strong><a href="#" id="download__change">CLICK AQUÍ</a></strong>]</p>
        </div>
        @endauth
    </div>
    @auth
    <div class="section__holder" style="display: none" id="download__simple">
        <div class="categorias">
        @foreach($downloads AS $download)
            <div class="categorias__item">
                <h2 class="categorias__item__title">{{$download['title']}}</h2>
                @foreach($download['items'] AS $element)

                    @includeIf('components.page.descarga_item_simple', ['element' => $element])

                @endforeach
            </div>
        @endforeach
        </div>
    </div>
    @endauth
    <div id="download__normal">
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
    </div>
</section>