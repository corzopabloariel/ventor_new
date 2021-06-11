<section>
    <div class="splide" id="slider-splide-enterprise">
        <div class="splide__track">
            <ul class="splide__list">
            @for($i = 0 ; $i < count($data['sliders']) ; $i++)
                <li class="splide__slide">
                    <img src="{{ asset($data['sliders'][$i]['image']) }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="" srcset="">
                </li>
            @endfor
            </ul>
        </div>
        
        <div class="splide__progress">
            <div class="splide__progress__bar">
            </div>
        </div>
    </div>
</section>
<section>
    <div class="enterprise">
        <div class="container-fluid">
            <div class="shadow-sm container__enterprise">
                {!! $data["content"]["texto"] !!}
            </div>
        </div>
    </div>
    <div class="enterprise">
        <div class="container-fluid">
            <div id="card-slider-enterprise" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                    @for($i = 0; $i < count($data["content"]["anio"]); $i++)
                        <li class="splide__slide">
                            <div class="enterprise__year shadow-sm">
                                {!! $data["content"]["anio"][$i]["texto"] !!}
                            </div>
                        </li>
                    @endfor
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>