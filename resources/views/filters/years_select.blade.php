<div class="filters__item --header" style="display: none;">
    <h4 class="filters__title @if(!isset($dark)) filters__title--white @endif">
        Año
    </h4>

    <div class="form-item form-item--select-icon js-select-year">
        <div class="select select--white filterElemSelect" data-name="year" @isset($dark) style="border-color: #3B3B3B" @endisset>
            <p>Seleccioná año</p>
            <i class="fas fa-caret-down"></i>
        </div>

        <div class="filters__modal">
            <div class="filters__dropdown"></div>
        </div>
    </div>
</div>