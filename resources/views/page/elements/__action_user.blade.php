@if(auth()->guard('web')->check())
<div class="user--log">
    <div>
        <div class="d-flex">
            <button onclick="typeProduct(this, 'nuevos')" type="button" class="btn py-2 px-4 @if(session()->has('type') && session()->get('type') == 'nuevos') btn-primary @else btn-light @endif border-0">Productos nuevos</button>
            <button onclick="typeProduct(this, 'liquidacion')" type="button" class="btn py-2 px-4 @if(session()->has('type') && session()->get('type') == 'liquidacion') btn-primary @else btn-light @endif border-0">Productos en liquidación</button>
        </div>
    </div>
    <div></div>
    <div class="d-flex">
        <form action="" method="post" target="_blank" class="btn btn-pdf" onclick="createPdf(this);">
            @csrf
            <i class="fas fa-file-pdf"></i>
        </form>
        <div class="px-4 py-2 d-flex bg-dark text-white">
            <div class="form-check">
                <input id="input-costo" @if((session()->has('markup') && session()->get('markup') == "costo") || !session()->has('markup')) checked @endif class="form-check-input" onchange="changeMarkUp(this, 'costo');" type="radio" name="markup">
                <label class="form-check-label" for="input-costo">
                    COSTO
                </label>
            </div>
            <div class="form-check ml-3">
                <input id="input-venta" @if(session()->has('markup') && session()->get('markup') == "venta") checked @endif class="form-check-input" onchange="changeMarkUp(this, 'venta');" type="radio" name="markup">
                <label class="form-check-label" for="input-venta">
                    VENTA
                </label>
            </div>
        </div>
    </div>
</div>
<div class="background d-none"></div>
<div class="cart">
    <div class="menu-cart-top">
        <h2>Producto</h2>
    </div>
    <div class="menu-cart-list">
        <ul>
            <li class="menu-cart-list-item">
                <div class="cart--img"></div>
            </li>
            <li class="menu-cart-list-item">
                <div class="cart--data text-center"></div>
            </li>
        </ul>
        <h2 class="text-center cart--price"></h2>
    </div>
    <div class="menu-cart-footer">
        <input onchange="cartPrice(this);" min="0" type="number" id="cart--total" class="form-control text-center">
        <button type="button" id="cart--close" class="btn mt-2 btn-block btn-ligth">CERRAR</button>
        <button type="button" id="cart--confirm" class="btn btn-block mt-2 btn-primary">AGREGAR</button>
    </div>
</div>
<div class="menu-cart">
    <div class="menu-cart-top">
        <h2>Productos</h2>
    </div>
    <div class="menu-cart-list"></div>
    <div class="menu-cart-footer">
        <div class="menu-cart-footer-text">
            <span class="menu-cart-total">Total</span>
            <span class="menu-cart-price"></span>
        </div>
        <button type="button" id="menu-cart--close" class="btn btn-block btn-ligth">ELEGIR MÁS PRODUCTOS</button>
        <button type="button" id="menu-cart--confirm" class="mt-2 btn-block btn btn-primary">FINALIZAR PEDIDIO</button>
    </div>
</div>
@endif