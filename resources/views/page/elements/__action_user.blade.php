<div class="user--log">
    <div>
        <div class="d-flex">
            <button onclick="type(this, 'nuevos')" type="button" class="btn py-2 px-4 btn-light border-0">Productos nuevos</button>
            <button onclick="type(this, 'liquidacion')" type="button" class="btn py-2 px-4 btn-light border-0">Productos en liquidación</button>
        </div>
    </div>
    <div></div>
    <div>
        <div class="px-4 py-2 d-flex bg-dark text-white">
            <div class="form-check">
                <input tabindex="-1" checked="" class="form-check-input" onchange="changeMarkUp(this);" type="radio" name="markup" id="costo" value="costo">
                <label class="form-check-label" for="costo">
                    COSTO
                </label>
            </div>
            <div class="form-check ml-3">
                <input tabindex="-1" class="form-check-input" onchange="changeMarkUp(this);" type="radio" name="markup" id="venta" value="venta">
                <label class="form-check-label" for="venta">
                    VENTA
                </label>
            </div>
        </div>
    </div>
</div>