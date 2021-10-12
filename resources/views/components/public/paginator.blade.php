@if ($hasPrevious)
    <a href="{{$urls['previous']}}" class="paginator__prev">
        <i class="fas fa-angle-left"></i>
    </a>
@endif

<ul class="paginator__list">

    @for($i = $start; $i <= $end; $i ++)

        @if ($page == $i)

        <li class="paginator__item paginator__item--active">
            <a href="#">{{$i}}</a>
        </li>

        @else

        <li class="paginator__item">
            <a href="{{URL::to('/').'/'.$urls['clean'].$i}}">
            {{$i}}
            </a>
        </li>

        @endif

    @endfor

</ul>

@if ($hasNext)
    <a href="{{$urls['next']}}" class="paginator__next">
        <i class="fas fa-angle-right"></i>
    </a>
@endif