<!-- Modal -->
<form action="{{ route('ventor.employee.role') }}" method="post" onsubmit="event.preventDefault(); updateRoleSubmit(this);">
    @csrf
    <div class="modal fade bd-example-modal-xl" id="modalEmployee" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalEmployeeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalEmployeeLabel">Todos los usuarios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Cambiar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal -->
<form action="{{ route('ventor.employee.permissions') }}" method="post" onsubmit="event.preventDefault(); updatePermissionsSubmit(this);">
    @csrf
    <div class="modal fade" id="modalAccess" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalAccessLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalAccessLabel">Accesos y permisos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="mb-3"></h4>
                    <input type="hidden" name="id" id="accessUserId">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ruta</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>