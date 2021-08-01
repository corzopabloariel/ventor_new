<div class="modal" id="sellerCart" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" onsubmit="event.preventDefault(); cartForm(this);">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="colFormCart" class="">Carritos disponibles</label>
                        <input type="number" min="1" class="form-control form-control-lg" name="cart" id="colFormCart" placeholder="Carritos disponibles">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>