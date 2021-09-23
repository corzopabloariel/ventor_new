<ol class="breadcrumb bg-transparent p-0 border-0">
    <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('index', ['link' => auth()->guard('web')->check() ? 'pedido' : 'productos']) }}">{{ auth()->guard('web')->check() ? 'Pedido' : 'Productos' }}</a></li>
    @isset($data['elements']['request']['part'])
        <li class="breadcrumb-item"><a href="{{ route((auth()->guard('web')->check() ? 'order_part' : 'products_part'), ['part' => $data['elements']['request']['part']]) }}">{{ $data['elements']['request']['part'] }}</a></li>
    @endisset
    @if(isset($data['elements']['request']['subpart']) && isset($data['elements']['request']['part']))
        <li class="breadcrumb-item">
            <a href="{{ route((auth()->guard('web')->check() ? 'order_part_subpart' : 'products_part_subpart'),
                [
                    'part' => $data['elements']['request']['part'],
                    'subpart' => $data['elements']['request']['subpart']
                ]) }}">
                    {{ $data['elements']['request']['subpart'] }}
                </a>
        </li>
    @endif
</ol>