<script src="{{ asset('js/sorteable.js') }}"></script>
<script>
    window.pyrus = [];
    window.pyrus.push({entidad: new Pyrus("download"), tipo: "U"});
    window.pyrus.push({entidad: new Pyrus("download_part"), tipo: "M", column: "files", function: "file"});


    fileFunction = (value = null) => {
        if (value) {
            if (typeof value === "string")
                value = JSON.parse(value);
        }
        const element = window.pyrus.find(x => {
            if (x.entidad.entidad === "download_part")
                return x;
        });
        let target = document.querySelector(`#wrapper-file`);
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
        element.entidad.show(url_simple, value, window[element.column], element.column, 1);
    };

    const orderFunction = function(t) {
        $("#orderDownload").modal("show");
    };

    const orderDownloadsSubmit = function(t) {
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
                    title: data.data.message
                });
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: data.data.message
                });
            }
        }, "post", formData);
    };

    const orderCategoriesFunction = function(t) {
        $("#orderCategory").modal("show");
    };

    const orderCategoriesSubmit = function(t) {
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
            } else {
                Toast.fire({
                    icon: 'error',
                    title: data.data.txt
                });
            }
        }, "post", formData);
    };

    $(() => {
        new Sortable(swapList_category, {
            handle: '.handle', // handle's class
            swap: true, // Enable swap plugin
            swapClass: 'highlight', // The class applied to the hovered swap item
            animation: 150
        });
        if ($("#swapList_PUBL").length) {
            new Sortable(swapList_PUBL, {
                handle: '.handle', // handle's class
                swap: true, // Enable swap plugin
                swapClass: 'highlight', // The class applied to the hovered swap item
                animation: 150
            });
        }
        if ($("#swapList_CATA").length) {
            new Sortable(swapList_CATA, {
                handle: '.handle', // handle's class
                swap: true, // Enable swap plugin
                swapClass: 'highlight', // The class applied to the hovered swap item
                animation: 150
            });
        }
        if ($("#swapList_PREC").length) {
            new Sortable(swapList_PREC, {
                handle: '.handle', // handle's class
                swap: true, // Enable swap plugin
                swapClass: 'highlight', // The class applied to the hovered swap item
                animation: 150
            });
        }
        if ($("#swapList_OTRA").length) {
            new Sortable(swapList_OTRA, {
                handle: '.handle', // handle's class
                swap: true, // Enable swap plugin
                swapClass: 'highlight', // The class applied to the hovered swap item
                animation: 150
            });
        }
    });
</script>