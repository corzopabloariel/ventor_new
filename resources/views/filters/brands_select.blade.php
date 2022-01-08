<div class="filters__item --header">
    <h4 class="filters__title @if(!isset($dark)) filters__title--white @endif">
        Marcas
    </h4>

    <div class="form-item form-item--select-icon js-select-brand">
        <div class="select select--white filterElemSelect" data-name="brand" @isset($dark) style="border-color: #3B3B3B" @endisset>
            <p>Seleccioná marca</p>
            <i class="fas fa-caret-down"></i>
        </div>
        <div class="filters__modal">
            <div class="filters__dropdown">
                @isset($brands)
                    @foreach($brands AS $brand)
                    <label class="checkbox-container">
                        {{$brand['name']}}
                        <input type="radio" name="brand" class="elemFilter" data-name="{{$brand['name']}}" data-element="brand" data-value="{{$brand['slug']}}" value="{{$brand['slug']}}"/>
                        <span class="checkmark-checkbox"></span>
                    </label>
                    @endforeach
                @endisset
            </div>
        </div>
    </div>
</div>