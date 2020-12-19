<!-- Modal -->
<div class="modal fade" id="modalClient" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalClientLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalClientLabel">Datos del cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalClientPass" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalClientPassLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalClientPassLabel">Blanqueo de contraseña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" onsubmit="event.preventDefault(); passwordSubmit(this);">
                <div class="modal-body">
                    <div class="modal-body-data mb-3"></div>
                    <div class="p-4 border boder-dark">
                        <div class="form-group">
                            <label for="input-pass">Contraseña nueva</label>
                            <input type="text" class="form-control" id="input-pass" name="password">
                        </div>
                        <div class="form-group form-check mb-0">
                            <input type="checkbox" class="form-check-input" id="input-notice" name="notice">
                            <label class="form-check-label" for="input-notice">Avisar al cliente del cambio</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Cambiar</button>
                </div>
            </form>
        </div>
    </div>
</div>