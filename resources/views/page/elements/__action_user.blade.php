@if(auth()->guard('web')->check())
<div class="user--log">
    <div>
        <button data-filter="nuevos" type="button" class="type__product user--log__btn @if(session()->has('type') && session()->get('type') == 'nuevos') --active @endif">Productos nuevos</button>
        <button data-filter="liquidacion" type="button" class="type__product user--log__btn @if(session()->has('type') && session()->get('type') == 'liquidacion') --active @endif">Productos en liquidación</button>
    </div>
    <div></div>
    <div>
        <form method="post" action="" target="_blank" id="createPDF">
            @csrf
            <button type="submit">
                <i class="fas fa-file-pdf"></i>
            </button>
        </form>
        <div class="price__type">
            <input id="input-costo" @if((session()->has('markup') && session()->get('markup') == "costo") || !session()->has('markup')) checked @endif class="form-check-input changeMarkUp" data-type="costo" type="radio" name="markup">
            <label for="input-costo">
                COSTO
            </label>
            <input id="input-venta" @if(session()->has('markup') && session()->get('markup') == "venta") checked @endif class="form-check-input changeMarkUp" data-type="venta" type="radio" name="markup">
            <label for="input-venta">
                VENTA
            </label>
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
    <div class="menu-cart__top">
        <h2>Productos</h2>
    </div>
    <div class="menu-cart__list"></div>
    <div class="menu-cart__footer">
        <div class="menu-cart__footer--text">
            <span class="menu-cart__total">Total</span>
            <span class="menu-cart__price"></span>
        </div>
        <div class="menu-cart__buttons">
            <button type="button" id="menu-cart--close" class="more btn btn-block btn-ligth">ELEGIR MÁS PRODUCTOS</button>
            <button type="button" id="menu-cart--stock" class="stock btn btn-block btn-ligth">COMPROBAR EXISTENCIA</button>
            <button type="button" id="menu-cart--clear" class="clear btn-block btn btn-danger">LIMPIAR PEDIDO</button>
            <button type="button" id="menu-cart--confirm" class="end btn-block btn btn-primary">FINALIZAR PEDIDO</button>
        </div>
    </div>
</div>
@endif