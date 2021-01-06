@foreach($elements AS $part)
    <h5 class="mt-3" data-toggle="collapse" style="color: {{ $part['color']['color'] }}" data-target=".collapse--{{ $part['slug'] }}" aria-expanded="false" aria-controls="collapse--{{ $part['slug'] }}">
        <a class="part--route" href="{{ route((auth()->guard('web')->check() ? 'order_part' : 'part'), ['part' => $part['slug']]) }}">{{ $part['name'] }}</a>
    </h5>
    <div class="@if(isset($data["elements"]["part"]) && $data["elements"]["part"]["name_slug"] == $part['slug'])show @endif collapse collapse--{{ $part['slug'] }}" id="collapse--{{ $part['slug'] }}">
        <div class="subparts">
            @foreach($part["subparts"] AS $subpart)
                @php
                $class = "";
                if (isset($data["elements"]["subpart"]) && $data["elements"]["subpart"]["name_slug"] == $subpart['slug'])
                    $class = "class=active";
                @endphp
                <a {{ $class }} href="{{ route((auth()->guard('web')->check() ? 'order_part_subpart' : 'subpart'), ['part' => $part['slug'], 'subpart' => $subpart['slug']]) }}" style="--hover-color: {{ $part['color']['color'] }}">{{ $subpart["name"] }}</a>
            @endforeach
        </div>
    </div>
@endforeach