<ul class="categorias__item__list">
    <li class="categorias__item__list__item">
        <a href="#" class="categorias__item__list__item__title">
            {{ html_entity_decode(strip_tags($element['name'])) }} ({{ count($element["files"])}})
        </a>
        <ul class="categorias__item__sublist">
            @foreach($element["files"] AS $file)
            <li class="categorias__item__sublist__item">
                <a href="{{URL::to(($element['id'] == 0 ? 'file/' : 'files/descargas/').$file['nameExt'])}}" download>{{ $file["name"] }}</a>
            </li>
            @endforeach
        </ul>
    </li>
</ul>