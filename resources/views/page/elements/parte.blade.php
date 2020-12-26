@push('styles')
    <link href="{{ asset('css/page/productos.css') }}" rel="stylesheet">
@endpush
<section>
    <div class="container--product">
        <div class="lateral sticky-top">
            <div class="container-fluid mt-n3">
                @foreach($data["lateral"] AS $part)
                    <h5 class="mt-3" data-toggle="collapse" style="color: {{ $part['color']['color'] }}" data-target=".collapse--{{ $part['slug'] }}" aria-expanded="false" aria-controls="collapse--{{ $part['slug'] }}">{{ $part['name'] }}</h5>
                    <div class="@if($data["part"] == $part['slug'])show @endif collapse collapse--{{ $part['slug'] }}" id="collapse--{{ $part['slug'] }}">
                        <div class="subparts">
                            @foreach($part["subparts"] AS $subpart)
                                <a href="" style="--hover-color: {{ $part['color']['color'] }}">{{ $subpart["name"] }}</a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="main">
            @foreach($data["elements"]["products"] AS $element)
                @include('page.elements.__product', ['product' => $element])
            @endforeach
        </div>
    </div>
</section>