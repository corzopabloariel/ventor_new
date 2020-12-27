@push('styles')
    <link href="{{ asset('css/page/productos.css') }}" rel="stylesheet">
@endpush
<section>
    <div class="productos">
        <div class="container">
            <h3 class="productos--title">CATEGORIAS</h3>
            <div class="row justify-content-center mb-5 mt-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <form class="position-relative d-flex align-items-center buscador" action="{{ route('redirect') }}" method="post">
                        @csrf
                        <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
                        <button type="submit" class="btn btn-link py-0">
                            <i class="fas fa-search"></i>
                        </button>
                        <input placeholder="Estoy buscando..." type="search" name="search" class="form-control bg-transparent p-0 border-left-0 border-right-0 border-top-0 form-control-sm" required>
                    </form>
                </div>
            </div>
            <div class="container--productos">
                @foreach($data["families"] AS $item)
                <div class="family">
                    <a href="{{ route((auth()->guard('web')->check() ? 'order_part' : 'part'), ['part' => $item['slug']]) }}">
                        <img src="{{ asset($item['icon']) }}" alt="" srcset="">
                        <p>{{ $item['name'] }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>