@push('modal')
<div class="modal fade" id="modalHistory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalHistoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalHistoryLabel">Historial del registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mt-n3"></div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endpush
<section class="my-3">
    <div class="container-fluid">
        @isset($data["section"])
            @include('layouts.general.breadcrumb', ['section' => $data["section"]])
        @endisset
        @include( 'layouts.general.form', [ 'buttonADD' => 0 , 'form' => 1 , 'close' => 0, 'url' => url('/adm/content/' . $data['content']) , 'modal' => 0 ] )
    </div>
</section>
@push('js')
<script src="//cdn.ckeditor.com/4.13.1/full/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/alertify.js') }}"></script>
<script src="{{ asset('js/shortcut.js') }}"></script>

<script src="{{ asset('js/basic.js') }}"></script>
<script src="{{ asset('js/pyrus.js') }}"></script>
<script src="{{ asset('js/declarations.js') }}"></script>
<script>
    window.data = @json($data["elements"]);
    window.formAction = "UPDATE";

    const historyFunction = function(t) {
        let url = url_simple + url_basic + "history";
        let formData = new FormData();
        let entity = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
        formData.append("id", window.data.id);
        formData.append("table", "contents"),
        Toast.fire({
            icon: 'warning',
            title: 'Espere'
        });
        entity.call(url, data => {
            'use strict'
            if (data.data.error === 0) {
                $("#modalHistory .modal-body").html(data.data.txt.toString());
                $("#modalHistory").modal("show");
            } else {
                Toast.fire({
                    icon: 'error',
                    title: data.data.txt
                });
            }
        }, "post", formData);
    };
</script>
@includeIf('adm.content.' . $data["content"])
@endpush