<div class="product__container shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="th--image"></th>
                    <th class="th--name">producto</th>
                    <th class="th--venta">u. venta</th>
                    <th class="th--stock">stock</th>
                    <th class="th--precio">p. unitario</th>
                    @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                    <th class="th--action"></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($data["elements"]["products"] AS $element)
                    @include('page.elements.__product', ['product' => $element])
                @endforeach
            </tbody>
        </table>
    </div>
</div>