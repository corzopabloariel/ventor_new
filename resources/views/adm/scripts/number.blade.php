<script src="{{ asset('js/sorteable.js') }}"></script>
<script>
    window.pyrus = [];
    window.pyrus.push({entidad: new Pyrus("number"), tipo: "U"});
    window.pyrus.push({entidad: new Pyrus("empresa_email"), tipo: "M", column: "email", function: "email"});
    window.pyrus.push({entidad: new Pyrus("empresa_telefono"), tipo: "M", column: "phone", function: "phone"});

    const orderFunction = function(t) {
        $("#orderNumber").modal("show");
    };
    const orderNumbersSubmit = function(t) {
        let formData = new FormData(t);
        Toast.fire({
            icon: 'warning',
            title: 'Espere'
        });
        window.pyrus[0].entidad.call(t.action, data => {
            'use strict'
            if (data.data.error === 0) {
                Toast.fire({
                    icon: 'success',
                    title: data.data.txt
                });
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: data.data.txt
                });
            }
        }, "post", formData);
    };

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
        html += '<div class="col-12 col-md-6 mt-3 pyrus--element">';
            html += '<div class="pyrus--element__target">';
                html += `<i onclick="remove_(this, 'pyrus--element')" class="fas fa-times pyrus--element__close"></i>`;
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

    $(() => {
        new Sortable(swapList, {
            handle: '.handle', // handle's class
            swap: true, // Enable swap plugin
            swapClass: 'highlight_order', // The class applied to the hovered swap item
            animation: 150
        });
    });
</script>