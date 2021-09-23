@push('styles')
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/color.js') }}"></script>
    <script src="{{ asset('js/solver.js') }}"></script>
@endpush
<section>
    <div class="wrapper container__product">
        <div class="lateral">
            <div class="container-fluid">
                @include("page.elements.__lateral", ['elements' => $data["lateral"]])
            </div>
        </div>
        <div class="main">
            <div class="container-fluid">
                @include("page.elements.__breadcrumb")
                @include("page.elements.__clients")
                <form action="{{ route('redirect') }}" method="post">
                    @csrf
                    <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
                    @isset($data['elements']['request']['part'])
                    <input type="hidden" name="part" value="{{ $data['elements']['request']['part'] }}">
                    @endisset
                    @isset($data['elements']['request']['subpart'])
                    <input type="hidden" name="subpart" value="{{ $data['elements']['request']['subpart'] }}">
                    @endisset
                    <div class="search">
                        <input type="search" @isset($data['elements']['request']['search']) value="{{ str_replace('+', ' ', $data['elements']['request']['search']) }}" @endisset name="search" placeholder="Buscar código o nombre" class="form-control">
                        <select id="brandList" name="brand" class="form-control">
                            <option value="">Seleccione marca</option>
                            @foreach($data['elements']['brands'] AS $brand)
                            @php
                            $selected = "";
                            if (isset($data['elements']['request']['brand']) && $data['elements']['request']['brand'] == $brand['slug'])
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
                <div class="container__products" id="product-main">
                    @foreach($data["elements"]["products"] AS $element)
                        @include('page.elements.__product', ['product' => $element])
                    @endforeach
                </div>
                @endif
                @if ($data["elements"]["products"]->total() == 0)
                    @include('page.elements.__not_found')
                @else
                <div class="main--footer">
                    <div class="table-responsive">
                        <div class="table-responsive d-flex justify-content-center">
                            {{ $data["elements"]["products"]->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>