@foreach($elements AS $part)
<div class="filters__item">

    <div class="filters__item__dropdown elemFilter" id="part--{{$part['slug']}}" data-clean="subpart" data-remove="0" data-element="part" data-value="{{$part['slug']}}" data-name="{{ $part['name'] }}">

        <h4 class="filters__title" style="color: {{ $part['color']['color'] }}">{{ $part['name'] }}</h4>
        <i class="fas fa-caret-down {{(isset($data['elements']['request']['part']) && $data['elements']['request']['part'] == $part['slug']) ? '--active' : ''}}"></i>

    </div>
    <div class="filters__item__dropdown__content {{(isset($data['elements']['request']['part']) && $data['elements']['request']['part'] == $part['slug']) ? '--active' : ''}}">

        <div class="filters__dropdown">
            @foreach($part["subparts"] AS $subpart)
                <div class="filters__item__flex__list">
                    <h4 class="filters__title filters__title--small">
                        {{ $subpart["name"] }}
                    </h4>
                    <label class="switch">
                        <input {{(isset($data['elements']['request']['subpart']) && $data['elements']['request']['subpart'] == $subpart['slug']) ? 'checked' : ''}} type="radio" name="subpart" class="elemFilter" data-element="subpart" data-value="{{$subpart['slug']}}" data-name="{{ $subpart['name'] }}" data-remove="1" value="{{ $subpart['slug'] }}"/>
                        <span class="switch-slider round"></span>
                    </label>
                </div>
            @endforeach
        </div>

    </div>

</div>
@endforeach