@if (!empty($data['sliders']))
<div id="slider_{{ $data['page'] }}" class="carousel slide wrapper-slider" data-ride="carousel">
    <ol class="carousel-indicators">
        @for($i = 0 ; $i < count($data['sliders']) ; $i++)
            <li data-target="#slider_{{ $data['page'] }}" data-slide-to="{{$i}}" @if( $i == 0 ) class="active" @endif></li>
        @endfor
    </ol>
    <div class="carousel-inner">
        @for($i = 0 ; $i < count($data['sliders']) ; $i++)
            <div class="carousel-item @if( $i == 0 ) active @endif">
                <img src="{{ asset($data['sliders'][$i]['image']) }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="" srcset="">
                @if (!empty($data['sliders'][$i]['text']))
                <div class="carousel-caption position-absolute w-100" style="top: 0; left: 0;">
                    <div class="container position-relative h-100 w-100 d-flex align-items-center justify-content-start">
                        <div class="texto w-100">
                            {!! $data['sliders'][$i]['text'] !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endfor
    </div>
</div>
@endif