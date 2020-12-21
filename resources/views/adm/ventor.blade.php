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

    searchTypeElements = t => {
        let target = t.closest(".form_empresa_header").querySelector("select.form--element__data");
        target.innerHTML = "";
        if (t.value === "attention_schedule") {
            target.closest(".row").classList.add("d-none");
            target.value = "";
            target.disabled = true;
            $(target).selectpicker("refresh");
            return null;
        }
        target.closest(".row").classList.remove("d-none");
        let col = {"phones": "visible", "emails": "email"}
        const Arr = window.data.elementos[t.value].filter(x => {
            if (x.in_header) {
                if (parseInt(x.in_header))
                    return x;
            } else
                return x;
        });
        Arr.forEach((o, index) => {
            let opt = document.createElement("option");
            opt.value = index;
            opt.text = o[col[t.value]];
            target.appendChild(opt);
        });
        target.disabled = false;
        $(target).selectpicker("refresh");
    };
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

    opcionFunction = (value = null) => {
        if (value) {
            if (typeof value === "string") {
                try {
                    value = JSON.parse(value);
                } catch (error) {
                    value = {
                        opcion: value
                    };
                }
            }
        }
        const element = window.pyrus.find(x => {
            if (x.entidad.entidad === "empresa_como")
                return x;
        });
        let target = document.querySelector(`#wrapper-opcion`);
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