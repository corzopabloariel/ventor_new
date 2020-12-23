<script>
    window.pyrus = [];
    window.pyrus.push({entidad: new Pyrus("calidad"), tipo: "U"});
    window.pyrus.push({entidad: new Pyrus("calidad_politica"), tipo: "U", column: "politica"});
    window.pyrus.push({entidad: new Pyrus("calidad_garantia"), tipo: "U", column: "garantia"});

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