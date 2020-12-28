<div class="user--log">
    <div>
        <div class="d-flex">
            <button onclick="typeProduct(this, 'nuevos')" type="button" class="btn py-2 px-4 @if(session()->has('type') && session()->get('type') == 'nuevos') btn-primary @else btn-light @endif border-0">Productos nuevos</button>
            <button onclick="typeProduct(this, 'liquidacion')" type="button" class="btn py-2 px-4 @if(session()->has('type') && session()->get('type') == 'liquidacion') btn-primary @else btn-light @endif border-0">Productos en liquidación</button>
        </div>
    </div>
    <div></div>
    <div class="d-flex">
        <form action="" method="post" target="blank" class="btn btn-pdf" onclick="createPdf(this);">
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
@if(auth()->guard('web')->check())
<div class="background d-none"></div>
<div class="cart d-none">
    <div class="cart--container">
        <div class="container-fluid">
            <div class="cart--img mb-3"></div>
            <div class="cart--data text-center"></div>
            <h2 class="text-center cart--price"></h2>
        </div>
        <div class="cart--footer">
            <div class="container-fluid py-3">
                <input onchange="cartPrice(this);" min="0" type="number" id="cart--total" class="form-control text-center">
            </div>
            <button type="button" id="cart--close" class="btn btn-danger">Cerrar</button>
            <button type="button" id="cart--confirm" class="btn btn-primary">Agregar</button>
        </div>
    </div>
</div>
<div class="cart--product d-none">
    <div class="container-fluid">
        <h2>Productos</h2>
        <div class="cart-prod--container"></div>
    </div>
</div>
@endif