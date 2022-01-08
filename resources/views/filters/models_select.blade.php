<div class="filters__item --header" style="display: none;">
    <h4 class="filters__title @if(!isset($dark)) filters__title--white @endif">
        Modelos
    </h4>

    <div class="form-item form-item--select-icon js-select-model">
        <div class="select select--white filterElemSelect" data-name="model" @isset($dark) style="border-color: #3B3B3B" @endisset>
            <p>Seleccioná modelo</p>
            <i class="fas fa-caret-down"></i>
        </div>

        <div class="filters__modal">
            <div class="filters__dropdown"></div>
        </div>
    </div>
</div>