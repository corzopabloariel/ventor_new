<div class="filters__item --header">
    <h4 class="filters__title filters__title--white">
        Marcas
    </h4>

    <div class="form-item form-item--select-icon js-select-prop">
        <div class="select select--white filterElemSelect" data-name="tipo-propiedad">
            <p>Seleccioná marca</p>
            <i class="fas fa-caret-down"></i>
        </div>

        <div class="filters__modal">
            <div class="filters__dropdown">
                @foreach($data['elements']['brands'] AS $brand)
                <label class="checkbox-container">
                    {{ $brand['name'] }}
                    <input type="checkbox" name="brand" class="filterElem" data-id="brand|{{ $brand['slug'] }}" data-name="{{ $brand['name'] }}" data-slug="{{ $brand['slug'] }}" value="{{ $brand['slug'] }}"/>
                    <span class="checkmark-checkbox"></span>
                </label>
                @endforeach
            </div> 
        </div>
    </div>
</div>