<div class="number">
    <h3 class="number__title">{{$element->name}}</h3>
    <ul class="number__info">
        @if (!empty($number->person) && strlen($number->person) > 2)
        <li class="number__info__item">
            {{ $number->person }}
        </li>
        @endif
        @if (!empty($number->email))
        <li class="number__info__item">
            {!! $number->printEmail() !!}
        </li>
        @endif
        @if (!empty($number->internal))
            <li class="number__info__item">
                <strong>Interno</strong>{{ $number->internal }}
            </li>
        @endif
        @if (!empty($number->phone))
            <li class="number__info__item">
                {!! $number->printPhone() !!}
            </li>
        @endif
    </ul>
</div>