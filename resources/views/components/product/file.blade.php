<section class="section ficha" data-id="{{ $product['path'] }}">

    <div class="section__holder">

        <div class="ficha__info">

            <h2 class="ficha__title">
                {{$product['name']}}
            </h2>
            <div class="breadcrumb breadcrumb--button-back">
                <span class="ficha__application">
                    <i class="fab fa-elementor"></i> {{ implode(', ', $product['application']) }}
                </span>

                <a href="{{$referer}}" class="section__back">
                    <i class="fas fa-backward"></i> Volver
                </a>
            </div>

            <div class="ficha__carousel-wrapper">

                <div class="ficha__carousel-top">

                    @if ($product['isSale'])
                    <div class="property-label"><i class="fas fa-bell"></i> Producto en liquidación</div>
                    @endif

                </div>

                <div id="propiedad_media_galeria" class="ficha__carousel-wrapper__div --active">
                    IMAGENES
                </div>

            </div>

        </div>
        <div class="ficha__column">

            <div class="breadcrumb breadcrumb--button-back">
                <a href="{{$referer}}" class="section__back">
                    <i class="fas fa-backward"></i> Volver
                </a>
            </div>
            <div class="ficha__prices">
                <div class="ficha__prices__item">
                    <span>Precio</span>
                    <span>{{isset($product['priceMarkup']) ? $product['priceMarkup'] : $product['price']}}</span>
                </div>
            </div>

        </div>

    </div>

</section>