<div class="products__filter" id="filter">
    <button class="btn btn-sm" id="filterClose"><i class="fas fa-arrow-left"></i></button>
    <form action="{{ route('redirect') }}" method="post">
        @csrf
        <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
        @isset($data['elements']['part'])
        <input type="hidden" name="part" value="{{ $data['elements']['part']['name_slug'] }}">
        @endisset
        @isset($data['elements']['subpart'])
        <input type="hidden" name="subpart" value="{{ $data['elements']['subpart']['name_slug'] }}">
        @endisset
        <div class="search">
            <input type="search" @isset($data["elements"]["search"]) value="{{ $data["elements"]["search"] }}" @endisset name="search" placeholder="Buscar código o nombre" class="form-control border-0">
            <select name="brand" class="form-control" id="brand-filter">
                <option value="">Seleccione una marca</option>
                @foreach($data["elements"]["brands"] AS $brand)
                @php
                $selected = "";
                if (isset($data["elements"]["brand"]) && $data["elements"]["brand"] == $brand['slug'])
                    $selected = "selected=true";
                @endphp
                <option {{ $selected }} value="{{ $brand['slug'] }}">{{ $brand['name'] }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-dark btn-block text-uppercase text-center"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <div class="products__filter--list">
        @foreach($elements AS $part)
        <div>
            <h5 data-toggle="collapse" style="color: {{ $part['color']['color'] }}" data-target=".collapse--{{ $part['slug'] }}" aria-expanded="false" aria-controls="collapse--{{ $part['slug'] }}">
                <a class="part--route" href="{{ route((auth()->guard('web')->check() ? 'order_part' : 'products_part'), ['part' => $part['slug']]) }}">{{ $part['name'] }}</a>
            </h5>
            <div class="@if(isset($data["elements"]["part"]) && $data["elements"]["part"]["name_slug"] == $part['slug'])show @endif collapse collapse--{{ $part['slug'] }}" id="collapse--{{ $part['slug'] }}">
                <div class="subparts">
                    @foreach($part["subparts"] AS $subpart)
                        @php
                        $class = "";
                        if (isset($data["elements"]["subpart"]) && $data["elements"]["subpart"]["name_slug"] == $subpart['slug'])
                            $class = "class=active";
                        @endphp
                        <a {{ $class }} href="{{ route((auth()->guard('web')->check() ? 'order_part_subpart' : 'products_part_subpart'), ['part' => $part['slug'], 'subpart' => $subpart['slug']]) }}" style="--hover-color: {{ $part['color']['color'] }}">{{ $subpart["name"] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>