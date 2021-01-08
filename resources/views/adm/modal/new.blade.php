<form action="{{ route('ventor.new.order') }}" method="post" onsubmit="event.preventDefault(); orderNewsSubmit(this);">
    <div class="modal fade" id="orderNew" tabindex="-1" role="dialog" aria-labelledby="orderNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="orderNewLabel">Orden de las novedades</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="swapList">
                        @foreach($data["all"] AS $new)
                        <li class="list-group-item d-flex justify-content-between">
                            <input type="hidden" name="ids[]" value="{{ $new->id }}">
                            {!! $new->name !!}
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