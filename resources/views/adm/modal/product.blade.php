@php
$file = configs("FILE_PRODUCTS", env('FILE_PRODUCTS'));
$filename = implode('/', [public_path(), env('FOLDER_TXT'), $file]);
@endphp
<!-- Modal -->
<div class="modal fade" id="modalProduct" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalProductLabel">Actualizar archivo de productos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('ventor.product.file') }}" onsubmit="event.preventDefault(); uploadFile(this);" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <h1 class="text-center">¡¡Atención!!</h1>
                    <p>Se reemplazará el contenido del archivo <strong>{{$file}}</strong></p>
                    <div class="mt-3">
                        <label for="inputGroupFile">Seleccione archivo</label>
                        <input id="inputGroupFile" class="form-control" type="file" name="file" accept=".txt">
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="update" value="1" id="update">
                        <label class="form-check-label" for="update">
                            Actualizar productos al terminar de subir el archivo?
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>