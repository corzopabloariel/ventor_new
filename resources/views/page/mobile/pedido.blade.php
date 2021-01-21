@push('modal')
<div class="modal fade bd-example-modal-lg" id="imagesProductModal" role="dialog" aria-labelledby="imagesProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagesProductModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endpush
@push('styles')
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
    />
    <link href="{{ asset('css/mobile/product.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/color.js') }}"></script>
    <script src="{{ asset('js/solver.js') }}"></script>
    <script src="{{ asset('js/mobile/product.js') . '?t=' . time() }}"></script>
@endpush
@includeIf('page.mobile.__filter', ['elements' => $data["lateral"]])
<section class="section--no_pad">
    <div class="product">
        <div class="container-fluid">
            @isset($data["clients"])
            <div class="product__container product__container--client shadow-sm">
                <select id="clientList" class="form-control" onchange="selectClient(this);">
                    <option value="">Seleccione cliente</option>
                    @foreach($data["clients"] AS $client)
                    @php
                    $selected = "";
                    if (session()->has('nrocta_client') && session()->get('nrocta_client') == $client->nrocta)
                        $selected = "selected=true";
                    @endphp
                    <option {{ $selected }} value="{{ $client->nrocta }}">{{ $client->nrocta }} | {{ $client->razon_social }} @if(!empty($client->direml))({{ $client->direml }})@endif</option>
                    @endforeach
                </select>
            </div>
            @endisset
            <div class="product__container product__container--filter shadow-sm text-truncate" id="btn-filter">
                <i class="fas fa-filter"></i>
                @if(isset($data["elements"]["part"]) || isset($data["elements"]["subpart"]))
                filtro aplicado: <span class="text-uppercase">{{$data["elements"]["part"]["name"]}}</span>
                @isset($data["elements"]["subpart"])
                | {{ $data["elements"]["subpart"]["name"] }}
                @endisset
                @else
                filtrar
                @endif
            </div>
            <div class="product__container product__container--btns shadow-sm">
                <button onclick="typeProduct(this, 'nuevos')" type="button" class="btn py-2 px-4 @if(session()->has('type') && session()->get('type') == 'nuevos') btn-dark @else btn-light @endif border-0">NUEVOS</button>
                <button onclick="typeProduct(this, 'liquidacion')" type="button" class="btn py-2 px-4 @if(session()->has('type') && session()->get('type') == 'liquidacion') btn-dark @else btn-light @endif border-0">EN LIQUIDACIÓN</button>
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