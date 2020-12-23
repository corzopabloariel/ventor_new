<script>
    window.pyrus = [];
    window.pyrus.push({entidad: new Pyrus("empresa"), tipo: "U"});
    window.pyrus.push({entidad: new Pyrus("empresa_mision"), tipo: "U", column: "mision"});
    window.pyrus.push({entidad: new Pyrus("empresa_vision"), tipo: "U", column: "vision"});
    window.pyrus.push({entidad: new Pyrus("empresa_anio"), tipo: "M", column: "anio", function: "year"});
    window.pyrus.push({entidad: new Pyrus("empresa_numero"), tipo: "M", column: "numero", function: "number"});

    numberFunction = (value = null) => {
        if (value) {
            if (typeof value === "string")
                value = JSON.parse(value);
        }
        const element = window.pyrus.find(x => {
            if (x.entidad.entidad === "empresa_numero")
                return x;
        });
        let target = document.querySelector(`#wrapper-number`);
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
        const ck = target.querySelector(".ckeditor");
        if (ck) {
            element.entidad.editor(window[element.column], "numero");
        }
        element.entidad.show(url_simple, value, window[element.column], element.column, 1);
    };
    yearFunction = (value = null) => {
        if (value) {
            if (typeof value === "string")
                value = JSON.parse(value);
        }
        const element = window.pyrus.find(x => {
            if (x.entidad.entidad === "empresa_anio")
                return x;
        });
        let target = document.querySelector(`#wrapper-year`);
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
        const ck = target.querySelector(".ckeditor");
        if (ck) {
            element.entidad.editor(window[element.column], "anio");
        }
        element.entidad.show(url_simple, value, window[element.column], element.column, 1);
    };
    init(data => {
        window.pyrus.forEach(p => {
            switch (p.tipo) {
                case "U":
                    if (p.column) {
                        if (window.data.data[p.column])
                            p.entidad.show(url_simple, window.data.data[p.column]);
                    } else
                        p.entidad.show(url_simple, window.data.data);
                break;
                case "A":
                case "M":
                    if (window.data.data[p.column])
                        window.data.data[p.column].forEach(a => {
                            const func = new Function(`${p.function}Function(${JSON.stringify(a)})`);
                            func.call(null);
                        });
                break;
            }
        })
    }, false, false, null, false, null, null, true);
</script>