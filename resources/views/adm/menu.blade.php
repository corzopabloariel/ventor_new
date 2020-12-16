@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adm.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css.css') }}">
@endpush
<div class="position-relative h-100">
    <ul class="nav-pyrus">
        @foreach(MENU AS $i)
            @if(isset($i["separar"]))
            <li class="nav-pyrus__item"><hr class="nav-pyrus__separator"></li>
            @elseif(!isset($i["submenu"]))
            <li class="nav-pyrus__item">
                @php
                $class = "nav-pyrus__link";
                if (strcmp(url()->current(), $i['url']) === 0)
                    $class .= " active-pyrus";
                if (isset($data["url"])) {
                    if (strcmp($data["url"], $i['url']) === 0)
                        $class .= " active-pyrus";
                }
                @endphp
                <a class="{{$class}}" data-link="a" href="{{ $i['url'] }}">
                    <i class="{{$i['icon']}}"></i>
                    @if(empty($i['url']))
                    <strike class="nav-pyrus__label">{{ $i["name"] }}</strike>
                    @else
                    <span class="nav-pyrus__label">{{ $i["name"] }}</span>
                    @endif
                </a>
            </li>
            @else
            <li class="nav-pyrus__item">
                @php
                $class = "collapse nav-pyrus nav-pyrus--child";
                $aria_expanded="aria-expanded=false";
                if (in_array(url()->current(), $i['urls'])) {
                    $aria_expanded="aria-expanded=true";
                    $class .= " show";
                }
                if (isset($data["url"])) {
                    if (in_array($data["url"], $i['urls'])) {
                        $aria_expanded="aria-expanded=true";
                        $class .= " show";
                    }
                }
                @endphp
                <a class="nav-pyrus__link nav-pyrus__group" href="#{{ $i[ 'id' ] }}Submenu" data-toggle="collapse" {{$aria_expanded}}>
                    <i class="{{$i['icon']}}"></i>
                    <span class="nav-pyrus__label">{{$i['name']}}</span>
                </a>
                <ul class="{{$class}}" id="{{$i['id']}}Submenu">
                    @foreach($i["submenu"] AS $o)
                    <li class="nav-pyrus__item">
                        @php
                        $class = "nav-pyrus__link";
                        if (strcmp(url()->current(), $o['url']) === 0)
                            $class .= " active-pyrus";
                        if (isset($data["url"])) {
                            if (strcmp($data["url"], $o['url']) === 0)
                                $class .= " active-pyrus";
                        }
                        @endphp
                        <a class="{{$class}}" data-link="u" href="{{ $o['url'] }}">
                            <i class="{{$o['icon']}}"></i>
                            @if(empty($o['url']))
                            <strike class="nav-pyrus__label">{{$o['name']}}</strike>
                            @else
                            <span class="nav-pyrus__label">{{ $o['name'] }}</span>
                            @endif
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            @endif
        @endforeach
    </ul>
</div>