<form action="{{ route('ventor.category.order') }}" method="post" onsubmit="event.preventDefault(); orderCategoriesSubmit(this);">
    <div class="modal fade" id="orderCategory" tabindex="-1" role="dialog" aria-labelledby="orderCategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="orderCategoryLabel">Orden de las categorias</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="swapList_category">
                        @foreach ($data["categoriesDATA"] AS $n)
                        <li class="list-group-item d-flex justify-content-between">
                            <input type="hidden" name="category[]" value="{{ $n }}">
                            {{ $data["categories"][$n] }}
                            <i class="fas fa-arrows-alt handle"></i>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{ route('ventor.download.order') }}" method="post" onsubmit="event.preventDefault(); orderDownloadsSubmit(this);">
    <div class="modal fade" id="orderDownload" tabindex="-1" role="dialog" aria-labelledby="orderDownloadLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="orderDownloadLabel">Orden de las descargas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                    $descargas = [];
                    foreach($data["all"] AS $d) {
                        if (!isset($descargas[$d->type]))
                            $descargas[$d->type] = [];
                        $descargas[$d->type][] = $d;
                    }
                    @endphp
                    @foreach($descargas AS $type => $descargasType)
                    <h4 class="text-center mt-3">{{ $data["categories"][$type] }}</h4>
                    <ul class="list-group" id="swapList_{{ $type }}">
                        @foreach($descargasType AS $descarga)
                        <li class="list-group-item d-flex justify-content-between">
                            <input type="hidden" name="ids[{{ $type }}][]" value="{{ $descarga->id }}">
                            {!! $descarga->name !!}
                            <i class="fas fa-arrows-alt handle"></i>
                        </li>
                        @endforeach
                    </ul>
                    @endforeach
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>