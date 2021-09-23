@foreach($elements AS $part)
    <h5 data-toggle="collapse" style="color: {{ $part['color']['color'] }}" data-target=".collapse--{{ $part['slug'] }}" aria-expanded="false" aria-controls="collapse--{{ $part['slug'] }}">
        <a class="part--route" href="{{ route((auth()->guard('web')->check() ? 'order_part' : 'products_part'), ['part' => $part['slug']]) }}">{{ $part['name'] }}</a>
    </h5>
    <div class="@if(isset($data['elements']['request']['part']) && $data['elements']['request']['part'] == $part['slug'])show @endif collapse collapse--{{ $part['slug'] }}" id="collapse--{{ $part['slug'] }}">
        <div class="subparts">
            @foreach($part["subparts"] AS $subpart)
                @php
                $class = "";
                if (isset($data['elements']['request']['subpart']) && $data['elements']['request']['subpart'] == $subpart['slug'])
                    $class = "class=active";
                @endphp
                <a {{ $class }} href="{{ route((auth()->guard('web')->check() ? 'order_part_subpart' : 'products_part_subpart'), ['part' => $part['slug'], 'subpart' => $subpart['slug']]) }}" style="--hover-color: {{ $part['color']['color'] }}">{{ $subpart["name"] }}</a>
            @endforeach
        </div>
    </div>
@endforeach