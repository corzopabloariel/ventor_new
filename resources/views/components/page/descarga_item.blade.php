<div class="item">
    @if (count($element['files']) == 1)
    <a
        data-name="{{ html_entity_decode(strip_tags($element['files'][0]['nameExt'])) }}"
        class="downloadTrack"
        data-id="{{$element['id']}}"
        href="#"
    >
        <div class="card-map__image" style="background: url('{{ $element['image'] }}') center center no-repeat; background-size: 100% auto;">
            <div class="card-map__shadow"></div>
        </div>
        <div class="card-map__content">
            <div class="card-map__info card-map__info--special" title="{{ html_entity_decode(strip_tags($element['name'])) }}">
                {!! $element['name'] !!}
            </div>
        </div>
    </a>
    @else
    <div class="card-map__image" style="background: url('{{ $element['image'] }}') center center no-repeat; background-size: 100% auto;">
        <div class="card-map__shadow"></div>
    </div>
    <div class="card-map__content">
        <div class="card-map__info card-map__info--special" title="{{ html_entity_decode(strip_tags($element['name'])) }}">
            {!! $element['name'] !!}
        </div>
    </div>
    @endif
</div>