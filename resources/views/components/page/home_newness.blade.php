<section class="section --lowered">
    <div class="section__holder">
        @if (isset($all) && $all)
        <h2 class="section__title">Novedades</h2>
        @endif
        <div class="listing-lowered">
            <div class="owl-carousel owl-theme">

                @foreach($items AS $new)

                    @includeIf('components.page.home_newness_item', ['new' => $new])

                @endforeach

            </div>
            @if (!isset($all) || isset($all) && !$all)
            <a href="{{ URL::to('novedades') }}" class="button button--lowered button--medium"><i class="fas fa-angle-double-right"></i>Ver más novedades</a>
            @endif
        </div>
    </div>
</section>