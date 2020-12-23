<form action="{{ route('ventor.product.category.order') }}" method="post" onsubmit="event.preventDefault(); orderCategoriesSubmit(this);">
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
                        @foreach ($data["families"] AS $family)
                        <li class="list-group-item d-flex justify-content-between">
                            <input type="hidden" name="family[]" value="{{ $family->id }}">
                            {{ $family->name }}
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
<form action="{{ route('ventor.product.category.part') }}" method="post" onsubmit="event.preventDefault(); partCategoriesSubmit(this);">
    <div class="modal fade bd-example-modal-lg" id="parts" tabindex="-1" role="dialog" aria-labelledby="partsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="partsLabel">Partes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data["parts"] AS $part)
                                <tr>
                                    <td>{{ $part->name }}</td>
                                    <td class="w-50">
                                        <input type="hidden" name="part[]" value="{{ $part->id }}">
                                        <select name="family[]" class="form-control selectpicker show-tick" data-size="5" data-container="body" data-width="100%" data-live-search="true" title="Categoría">
                                            @foreach($data["families"] AS $family)
                                            <option @if($family->id == $part->family_id) selected @endif value="{{ $family->id }}">{{ $family->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>