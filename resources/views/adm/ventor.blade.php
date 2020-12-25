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
        @include( 'layouts.general.form', [ 'buttonADD' => 0 , 'form' => 1 , 'close' => 0, 'url' => url('/adm/data') , 'modal' => 0 ] )
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
    window.pyrus = [];
    window.pyrus.push({entidad: new Pyrus("empresa_captcha"), tipo: "U", column: "captcha"});
    window.pyrus.push({entidad: new Pyrus("empresa_direccion"), tipo: "U", column: "address"});
    window.pyrus.push({entidad: new Pyrus("empresa_images"), tipo: "U", column: "images"});
    window.pyrus.push({entidad: new Pyrus("empresa_email"), tipo: "M", column: "email", function: "email"});
    window.pyrus.push({entidad: new Pyrus("empresa_telefono"), tipo: "M", column: "phone", function: "phone"});

    /** ------------------------------------- */
    const phoneFunction = (value = null) => {
        if (value) {
            if (typeof value === "string")
                value = JSON.parse(value);
        }
        const element = window.pyrus.find(x => {
            if (x.entidad.entidad === "empresa_telefono")
                return x;
        });
        let target = document.querySelector(`#wrapper-phone`);
        let html = "";
        if (window[element.column] === undefined)
            window[element.column] = 0;
        window[element.column] ++;
        html += '<div class="col-12 col-md-4 mt-3 pyrus--element">';
            html += '<div class="pyrus--element__target">';
                html += `<i onclick="remove_(this, 'pyrus--element')" class="fas fa-times pyrus--element__close"></i>`;
                html += element.entidad.formulario(window[element.column], element.column);
            html += '</div>';
        html += '</div>';
        target.insertAdjacentHTML('beforeend', html);
        element.entidad.show(url_simple, value, window[element.column], element.column);
    };

    socialFunction = (value = null) => {
        if (value) {
            if (typeof value === "string")
                value = JSON.parse(value);
        }
        const element = window.pyrus.find(x => {
            if (x.entidad.entidad === "empresa_social")
                return x;
        });
        let target = document.querySelector(`#wrapper-social`);
        let html = "";
        if (window[element.column] === undefined)
            window[element.column] = 0;
        window[element.column] ++;
        html += '<div class="col-12 col-md-4 mt-3 pyrus--element">';
            html += '<div class="pyrus--element__target">';
                html += `<i onclick="remove_( this , 'pyrus--element' )" class="fas fa-times pyrus--element__close"></i>`;
                html += element.entidad.formulario(window[element.column], element.column);
            html += '</div>';
        html += '</div>';
        target.insertAdjacentHTML('beforeend', html);
        element.entidad.show(url_simple, value, window[element.column], element.column);
    };

    emailFunction = (value = null) => {
        if (value) {
            if (typeof value === "string")
                value = JSON.parse(value);
        }
        const element = window.pyrus.find(x => {
            if (x.entidad.entidad === "empresa_email")
                return x;
        });
        let target = document.querySelector(`#wrapper-email`);
        let html = "";
        if (window[element.column] === undefined)
            window[element.column] = 0;
        window[element.column] ++;
        html += '<div class="col-12 col-md-6 mt-3 pyrus--element">';
            html += '<div class="pyrus--element__target">';
                html += `<i onclick="remove_( this , 'pyrus--element' )" class="fas fa-times pyrus--element__close"></i>`;
                html += element.entidad.formulario(window[element.column], element.column);
            html += '</div>';
        html += '</div>';
        target.insertAdjacentHTML('beforeend', html);
        element.entidad.show(url_simple, value, window[element.column], element.column, 1);
    };

    const historyFunction = function(t) {
        let url = url_simple + url_basic + "history";
        let formData = new FormData();
        let entity = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
        formData.append("id", 1);
        formData.append("table", "ventor");
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
    /** */
    init(data => {
        window.pyrus.forEach(p => {
            switch (p.tipo) {
                case "U":
                    if (p.column) {
                        if (window.data[p.column])
                            p.entidad.show(url_simple, window.data[p.column]);
                    } else
                        p.entidad.show(url_simple, window.data);
                break;
                case "A":
                case "M":
                    if (window.data[p.column])
                        window.data[p.column].forEach(a => {
                            const func = new Function(`${p.function}Function(${JSON.stringify(a)})`);
                            func.call(null);
                        });
                break;
            }
        })
    }, false, false, null, false, null, null, true);
</script>
@endpush