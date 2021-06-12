<div class="products">
    @foreach($data["elements"]["products"] AS $element)
        @include('page.mobile.__product', ['product' => $element])
    @endforeach
</div>