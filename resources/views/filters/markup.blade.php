<div class="filters__item --header markup">

    <h4 class="filters__title filters__title--white">Tipo de precio</h4>

</div>

<div class="tab-selector --wide --small --white">

    <label class="tab-selector__item">
        <input @if(empty($markup) || !empty($markup) && $markup == 'costo') checked @endif id="markup_costo" type="radio" class="markup" name="markup" value="costo">
        <div class="tab-selector__item__btn">Costo</div>
    </label>

    <label class="tab-selector__item">
        <input @if(!empty($markup) && $markup == 'venta') checked @endif id="markup_venta" type="radio" class="markup" name="markup" value="venta">
        <div class="tab-selector__item__btn">Venta</div>
    </label>

</div>