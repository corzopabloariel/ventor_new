<div class="product">
    <a href="{{ route('product', ['product' => $product->name_slug]) }}">
        {!! $product->images(1, $no_img) !!}
        <p class="product--code">{{ $product->stmpdh_art }}</p>
        <p class="product--for">{{ $product->web_marcas }}</p>
        <p class="product--name">{{ $product->stmpdh_tex }}</p>
    </a>
</div>