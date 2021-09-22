<ol class="breadcrumb bg-transparent p-0 border-0">
    <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('index', ['link' => auth()->guard('web')->check() ? 'pedido' : 'productos']) }}">{{ auth()->guard('web')->check() ? 'Pedido' : 'Productos' }}</a></li>
    @isset($data["elements"]["part"])
        <li class="breadcrumb-item"><a href="{{ route((auth()->guard('web')->check() ? 'order_part' : 'products_part'), ['part' => $data["elements"]["part"]["name_slug"]]) }}">{{ $data["elements"]["part"]["name"] }}</a></li>
    @endisset
    @if(isset($data["elements"]["subpart"]) && isset($data['elements']['part']['name_slug']))
        <li class="breadcrumb-item">
            <a href="{{ route((auth()->guard('web')->check() ? 'order_part_subpart' : 'products_part_subpart'),
                [
                    'part' => $data['elements']['part']['name_slug'],
                    'subpart' => $data['elements']['subpart']['name_slug']
                ]) }}">
                    {{ $data["elements"]["subpart"]["name"] }}
                </a>
        </li>
    @endif
</ol>