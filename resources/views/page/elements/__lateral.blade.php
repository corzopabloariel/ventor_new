@foreach($elements AS $part)
<div class="filters__item">
    <div class="filters__item__dropdown" id="part--{{$part['slug']}}">
        <h4 class="filters__title" style="color: {{ $part['color']['color'] }}">
            <a class="part--route" href="{{ route((auth()->guard('web')->check() ? 'order_part' : 'products_part'), ['part' => $part['slug']]) }}">{{ $part['name'] }}</a>
        </h4>
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
                        <input {{(isset($data['elements']['request']['subpart']) && $data['elements']['request']['subpart'] == $subpart['slug']) ? 'checked' : ''}} type="radio" name="subpart" class="filterElem" data-id="{{ $part['slug'].'|'.$subpart['slug'] }}" data-name="{{ $subpart['name'] }}" data-slug="{{$part['slug'].'|'.$subpart['slug'] }}" value="{{ $subpart['slug'] }}"/>
                        <span class="switch-slider round"></span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach