@push('modal')
<div class="modal fade bd-example-modal-lg" id="imagesProductModal" role="dialog" aria-labelledby="imagesProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagesProductModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endpush
<div class="container--table">
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="th--image"></th>
                    <th class="th--name">producto</th>
                    <th class="th--venta">u. venta</th>
                    <th class="th--stock">stock</th>
                    <th class="th--precio">p. unitario</th>
                    @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                    <th class="th--action"></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($data["elements"]["products"] AS $element)
                    @include('page.elements.__product', ['product' => $element])
                @endforeach
            </tbody>
        </table>
    </div>
</div>