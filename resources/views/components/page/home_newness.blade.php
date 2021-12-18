<section class="section --lowered">
    <div class="section__holder">
        <div class="listing-lowered">
            <div class="owl-carousel owl-theme">

                @foreach($items AS $new)

                    @includeIf('components.page.home_newness_item', ['new' => $new])

                @endforeach

            </div>
            <a href="/propiedades-venta-rebajas" class="button button--lowered button--medium"><i class="fas fa-angle-double-right"></i>Ver más novedades</a>
        </div>
    </div>
</section>