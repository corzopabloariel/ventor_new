@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/page/productos.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/page/producto.js') }}"></script>
@endpush
<section>
    <div class="container--product">
        <div class="lateral">
            <div class="container-fluid mt-n3 sticky-top">
                @include("page.elements.__lateral", ['elements' => $data["lateral"]])
            </div>
        </div>
        <div class="main">
            <div class="container-fluid">
                <ol class="breadcrumb bg-transparent p-0 border-0">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('index', ['link' => auth()->guard('web')->check() ? 'pedido' : 'productos']) }}">{{ auth()->guard('web')->check() ? 'Pedido' : 'Productos' }}</a></li>
                    @if (isset($data["brand"]))
                        @php
                        $filtered = collect($data["elements"]["brand"])->where('slug', $data["brand"])->first();
                        $name = $filtered["name"];
                        $route = auth()->guard('web')->check() ? 'order_part' : 'part';
                        @endphp
                        @if(isset($data["part"]))
                            <li class="breadcrumb-item"><a href="{{ route($route, ['part' => $data["part"]->name_slug]) }}">{{ $data["part"]->name }}</a></li>
                        @else
                            @php
                            $name = "{$data["search"]} {$name}";
                            @endphp
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
                    @else
                        @if(isset($data["part"]))
                        <li class="breadcrumb-item active" aria-current="page">{{ $data["part"]->name }}</li>
                        @else
                        <li class="breadcrumb-item active" aria-current="page">{{ $data["search"] }}</li>
                        @endif
                    @endif
                </ol>
                <form action="{{ route('redirect') }}" method="post">
                    @csrf
                    @if(isset($data['part']))
                    <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order_part' : 'part' }}">
                    <input type="hidden" name="part" value="{{ $data['part']->name_slug }}">
                    @else
                    <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
                    @endif
                    <div class="search">
                        <input type="search" @isset($data["search"]) value="{{ $data["search"] }}" @endisset name="search" placeholder="Buscar código o nombre" class="form-control border-0">
                        <select name="brand" class="form-control selectpicker" multiple data-max-options="1" data-header="Seleccione marca" data-live-search="true" data-style="btn-white" data-width="100%" title="Seleccione una marca">
                            @foreach($data["elements"]["brand"] AS $brand)
                            @php
                            $selected = "";
                            if (isset($data["brand"]) && $data["brand"] == $brand['slug'])
                                $selected = "selected=true";
                            @endphp
                            <option {{ $selected }} value="{{ $brand['slug'] }}">{{ $brand['name'] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-dark btn-block text-uppercase text-center"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                @include('page.elements.__action_user')
                @if (auth()->guard('web')->check())
                    @include('page.elements.__products_table')
                @else
                <div class="container--main">
                    @foreach($data["elements"]["products"] AS $element)
                        @include('page.elements.__product', ['product' => $element])
                    @endforeach
                </div>
                @endif
                @if ($data["elements"]["products"]->total() == 0)
                    @include('page.elements.__not_found')
                @else
                <div class="main--footer">
                    <div class="table-responsive d-flex justify-content-center">
                        {{ $data["elements"]["products"]->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>