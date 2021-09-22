@push('modal')
<div class="modal fade bd-example-modal-xl" id="applicationProductsModal" role="dialog" aria-labelledby="applicationProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applicationProductsModalLabel">Presupuesto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <form action="{{route('to.pdf')}}" id="toPdfForm" target="_blank" method="post">
                    @csrf
                    <input type="hidden" name="title" id="toPdfTitle">
                    <input type="hidden" name="html" id="toPdfHtml">
                    <button type="button" class="btn btn-danger" id="budget--print">Imprimir <i class="fas fa-file-pdf"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush
@if(isset($data["products"]) && (session()->has('markup') && session()->get('markup') == "venta"))
    <button id="btn--budget" disabled data-toggle="modal" data-target="#applicationProductsModal">
        <i class="fas fa-vote-yea"></i>
        <small></small>
    </button>
@endif
<img src="{{$data['image']}}" alt="Aplicación Ventor" srcset="" class="w-100">
<div class="wrapper wrapper__application">
    <section>
        <div class="container">
            <h2 class="title">Limpiaparabrisas</h2>
            <div class="row mb-2">
                <div class="col-12 col-md">
                    <select name="brand" id="brandList" class="form-control" @if(isset($data['models']) && isset($data['years'])) disabled @endif>
                        <option value="">Seleccione marca</option>
                        {!! $data['brandsOptions'] !!}
                    </select>
                </div>
                <div class="col-12 col-md">
                    <select name="model" id="modelList" class="form-control" disabled>
                        <option value="">Seleccione modelo</option>
                        {!! $data['models']['dataOptions'] ?? '' !!}
                    </select>
                </div>
                <div class="col-12 col-md">
                    <select name="year" id="yearList" class="form-control" @if(!isset($data['year']) || (isset($data['year']) && !empty($data['year']))) disabled @endif>
                        <option value="">Seleccione año</option>
                        {!! $data['years']['dataOptions'] ?? '' !!}
                    </select>
                </div>
            </div>
            <div class="mb-2">
                @if(isset($data['models']) && isset($data['years']))
                <a href="{{ URL::to('aplicacion') }}" class="btn btn-lg btn-dark mr-3">Resetear</a>
                @endif
                <button type="button" id="btnListApplication" @if(!isset($data['year']) || (isset($data['year']) && !empty($data['year']))) disabled @endif class="btn btn-lg btn-primary">Buscar</button>
            </div>
            @if(isset($data['models']) && isset($data['years']))
            <div class="user--log mb-2" style="grid-template-columns: 1fr;">
                <div>
                    <div class="price__type">
                        <input id="input-costo" @if((session()->has('markup') && session()->get('markup') == "costo") || !session()->has('markup')) checked @endif class="form-check-input changeMarkUp" data-type="costo" type="radio" name="markup">
                        <label for="input-costo">
                            COSTO
                        </label>
                        <input id="input-venta" @if(session()->has('markup') && session()->get('markup') == "venta") checked @endif class="form-check-input changeMarkUp" data-type="venta" type="radio" name="markup">
                        <label for="input-venta">
                            VENTA
                        </label>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @isset($data["products"])
            <script>
                const products = @json($data["products"]);
            </script>
            @auth
            <div class="container-fluid">
                <div class="container--table">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @foreach($data["products"] AS $element)
                                    @include('page.mobile.__product', ['application' => $element, 'products' => $element->data->toArray()])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endauth
        @endisset
    </section>
</div>