<div class="modal fade bd-example-modal-lg" id="modalClientCart" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalClientCartLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalClientCartLabel">Último carrito del cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>CÓDIGO</th>
                            <th style="width: 250px;">PRODUCTO</th>
                            <th>PRECIO</th>
                            <th>CANTIDAD</th>
                            <th>MARCA</th>
                            <th style="white-space: nowrap;">MODELO Y AÑO</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <p class="mb-0"></p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-danger" id="btnClearCart" data-id="">Limpiar</button>
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
                @csrf
                <div class="modal-body">
                    <div class="modal-body-data mb-3"></div>
                    <div class="p-4 border boder-dark">
                        <div class="form-group">
                            <label for="input-pass">Contraseña nueva</label>
                            <input type="text" class="form-control" id="input-pass" name="password">
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