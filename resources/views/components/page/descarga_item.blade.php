<div class="item">
    <img src="{{$element['image']}}" alt="{{ html_entity_decode(strip_tags($element["name"])) }}" onerror="this.src='{{ $no_img }}'" srcset="">
    <div class="card-map__content" title="{{ html_entity_decode(strip_tags($element['name'])) }}">
        <select
        class="card-map__option download--element select"
        data-id="{{ $element['id'] }}"
        data-name="{{ html_entity_decode(strip_tags($element['name'])) }}"
        >
            <option data-index="-1" value="">{{ html_entity_decode(strip_tags($element['name'])) }}</option>
            <optgroup label="Parte{{ (count($element['files']) > 1 ? 's' : '') }}">
                @foreach($element["files"] AS $file)
                <option data-index="{{$loop->index}}" data-name_ext="{{$file['nameExt']}}" data-type="{{$file['type']}}" value="{{ $file['file'] ? $loop->index : '' }}" data-name="{{ $file['nameExt'] }}">{{ $file["name"] }}</option>
                @endforeach
            </optgroup>
        </select>
    </div>
</div>