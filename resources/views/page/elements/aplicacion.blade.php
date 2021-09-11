@push('modal')
<div class="modal fade bd-example-modal-lg" id="applicationProductsModal" role="dialog" aria-labelledby="applicationProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applicationProductsModalLabel">Productos seleccionados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endpush
<img src="http://staticbcp.ventor.com.ar/img/parabrisas.jpg" alt="" srcset="" class="w-100">
<div class="wrapper wrapper__application">
    <section>
        <div class="container">
            <h2 class="title">Limpiaparabrisas</h2>
            <div class="row">
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
            <div class="row mt-4 mb-5">
                <div class="col d-flex justify-content-between">
                    <div>
                        @if(isset($data['models']) && isset($data['years']))
                        <button type="button" class="btn btn-lg btn-success" id="addCartApplication" disabled>Agregar al carrito</button>
                        @endif
                    </div>
                    <div>
                        @if(isset($data['models']) && isset($data['years']))
                        <a href="{{ URL::to('aplicacion') }}" class="btn btn-lg btn-dark mr-3">Resetear</a>
                        @endif
                        <button type="button" id="btnListApplication" @if(!isset($data['year']) || (isset($data['year']) && !empty($data['year']))) disabled @endif class="btn btn-lg btn-primary">Buscar</button>
                    </div>
                </div>
            </div>
        </div>
        @isset($data["products"])
            @auth
            <div class="container-fluid">
                <div class="container--table">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="th--image"></th>
                                    <th class="th--name">Modelo / Vehículo</th>
                                    <th class="th--venta">Año</th>
                                    <th class="th--venta">Conductor</th>
                                    <th class="th--venta">Pasajero</th>
                                    <th class="th--venta">Luneta</th>
                                    <th class="th--venta"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data["products"] AS $element)
                                    @include('page.elements.__product', ['application' => $element])
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