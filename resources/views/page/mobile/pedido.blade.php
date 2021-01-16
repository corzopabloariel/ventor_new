@push('styles')
    <link href="{{ asset('css/mobile/product.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/color.js') }}"></script>
    <script src="{{ asset('js/solver.js') }}"></script>
    <script src="{{ asset('js/mobile/product.js') . '?t=' . time() }}"></script>
@endpush
<section>
    <div class="product">
        <div class="container-fluid">
            <div class="product__container product__container--btns shadow-sm">
                <button onclick="typeProduct(this, 'nuevos')" type="button" class="btn py-2 px-4 @if(session()->has('type') && session()->get('type') == 'nuevos') btn-primary @else btn-light @endif border-0">NUEVOS</button>
                <button onclick="typeProduct(this, 'liquidacion')" type="button" class="btn py-2 px-4 @if(session()->has('type') && session()->get('type') == 'liquidacion') btn-primary @else btn-light @endif border-0">EN LIQUIDACIÓN</button>
                <div class="price__type">
                    <input id="input-costo" @if((session()->has('markup') && session()->get('markup') == "costo") || !session()->has('markup')) checked @endif class="form-check-input" onchange="changeMarkUp(this, 'costo');" type="radio" name="markup">
                    <label for="input-costo">
                        COSTO
                    </label>
                </div>
                <div class="price__type">
                    <input id="input-venta" @if(session()->has('markup') && session()->get('markup') == "venta") checked @endif class="form-check-input" onchange="changeMarkUp(this, 'venta');" type="radio" name="markup">
                    <label for="input-venta">
                        VENTA
                    </label>
                </div>
            </div>
            @include('page.mobile.__products_table')
            @if ($data["elements"]["products"]->total() == 0)
                @include('page.elements.__not_found')
            @else
            <div class="mt-3">
                <div class="table-responsive">
                    {{ $data["elements"]["products"]->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</section>