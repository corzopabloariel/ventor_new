@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="{{ asset('css/page/productos.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="{{ asset('js/color.js') }}"></script>
    <script src="{{ asset('js/solver.js') }}"></script>
    <script>
    $(() => {
        $(".part--route").click(function(e){
            e.stopPropagation();
        });
    });
    </script>
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
                    <li class="breadcrumb-item"><a href="{{ route(auth()->guard('web')->check() ? 'order_part' : 'part', ['part' => $data["part"]->name_slug]) }}">{{ $data["part"]->name }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route(auth()->guard('web')->check() ? 'order_subpart' : 'subpart', ['part' => $data["part"]->name_slug, 'subpart' => $data["subpart"]->name_slug]) }}">{{ $data["subpart"]->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $data["product"]["stmpdh_tex"] }}</li>
                </ol>
                <button class="btn btn-light border-0" data-toggle="modal" data-target="#partesModal" id="btnPartesModal">PARTES</button>
                <div class="product--data">
                    <div class="product--images">
                        <div id="slider_product" class="carousel slide wrapper-slider" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @for($i = 0 ; $i < count($data['product']['images']) ; $i++)
                                    <li data-target="#slider_product" data-slide-to="{{$i}}" @if( $i == 0 ) class="active" @endif></li>
                                @endfor
                            </ol>
                            <div class="carousel-inner">
                                @for($i = 0 ; $i < count($data['product']['images']) ; $i++)
                                    <div class="carousel-item @if( $i == 0 ) active @endif">
                                        <img src="{{ asset($data['product']['images'][$i]) }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="" srcset="">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="product--code">{{ $data["product"]["stmpdh_art"] }}</h3>
                        <h1 class="product--color product--title">{{ $data["product"]["stmpdh_tex"] }}</h1>
                        <table class="table mt-4 mb-0">
                            <tbody>
                                <tr>
                                    <td class="product--color">Código</td>
                                    <td>{{ $data["product"]["stmpdh_art"] }}</td>
                                </tr>
                                <tr>
                                    <td class="product--color">Cantidad Envasada</td>
                                    <td>{{ $data["product"]["cantminvta"] == 1 ? $data["product"]["cantminvta"] . " unidad" : $data["product"]["cantminvta"] . " unidades"}}</td>
                                </tr>
                                @isset($data["product"]["web_marcas"])
                                <tr>
                                    <td class="product--color">Marca</td>
                                    <td>{{ $data["product"]["web_marcas"] }}</td>
                                </tr>
                                @endisset
                                @isset($data["product"]["modelo_anio"])
                                <tr>
                                    <td class="product--color">Modelo</td>
                                    <td>{{ $data["product"]["modelo_anio"] }}</td>
                                </tr>
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>