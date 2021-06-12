<section>
    <div class="checkout product">
        <div class="container-fluid">
            @if (session()->has('nrocta_client') && isset($data["client"]))
            <div class="checkout__container shadow-sm">
                <h2><strong>Cliente:</strong> {{ $data["client"]->razon_social }} ({{ $data["client"]->nrocta }})</h2>
                <div class="data-client">
                    @if (!empty($data["client"]->direml))
                        <p class="mb-0">
                            <i class="fas fa-envelope mr-2"></i><a href="mailto:{{ $data["client"]->direml }}">{{ $data["client"]->direml }}</a>
                        </p>
                    @endif
                    @if (!empty($data["client"]->telefn))
                        <p class="mb-0">
                            <i class="fas fa-phone-alt mr-2"></i><a href="tel:{{ $data["client"]->telefn }}">{{ $data["client"]->telefn }}</a>
                        </p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
<section>
    <div class="checkout product">
        <div class="container-fluid">
            <div class="products">
                @foreach($data["products"] AS $id => $element)
                    @include('page.mobile.__product', ['product' => $element["product"], 'checkout' => 1])
                @endforeach
            </div>
        </div>
    </div>
</section>
<div class="checkout checkout--float">
    <div class="shadow-sm bg-dark text-white p-3">
        <div class="d-flex justify-content-between">
            <span class="checkout--total">Total</span>
            <span class="checkout--total checkout--total__price">{{ $data["total"] }}</span>
        </div>
        <small class="">El total no incluye IVA ni impuestos internos</small>
        <div class="d-flex justify-content-between mt-3">
            <button id="btn--back" class="btn btn-light mr-3" type="button">ELEGIR MAS PRODUCTOS</button>
            <button id="btn--confirm" class="btn btn-primary" type="button">CONFIRMAR PEDIDO</button>
        </div>
    </div>
</div>