<div class="user--log">
    <div>
        <div class="d-flex">
            <button onclick="typeProduct(this, 'nuevos')" type="button" class="btn py-2 px-4 @if(session()->has('type') && session()->get('type') == 'nuevos') btn-primary @else btn-light @endif border-0">Productos nuevos</button>
            <button onclick="typeProduct(this, 'liquidacion')" type="button" class="btn py-2 px-4 @if(session()->has('type') && session()->get('type') == 'liquidacion') btn-primary @else btn-light @endif border-0">Productos en liquidación</button>
        </div>
    </div>
    <div></div>
    <div>
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