/**
 * @description Entidad perteneciente a  declaration.js
 * @param {string} e
 * -----------------------------------------/
 * Herramienta para armado de formularios
 * Cada Formulario es único, las entidades anteponen su nombre a cada FORM
 * Función que construye los names e id de los elementos del FORM
 * Las entidades con EDITOR RICO pueden configurarse en el archivo DECLARATION con valores sacados de https://ckeditor.com/latest/samples/toolbarconfigurator/index.html#advanced
 * -----------------------------------------/
 * @date 02.2020
 * @last_change 20.02.2020
 * @version 2.6.0
 */
class Connect {
    /**
     * @param {string} url
     * @param {Function} callbackOK
     * @param {Function} callbackFail
     */
    static one(url, callbackOK, callbackFail) {
        axios.get(url, {
            responseType: 'json'
        })
        .then(res => callbackOK.call(this, res))
        .catch(err => callbackFail.call(this, err));
    }
    /**
     * @param {string} url
     * @param {FormData} formData
     * @param {Function} callbackOK
     * @param {Function} callbackFail
     */
    static post(url, formData, callbackOK, callbackFail) {
        return axios({
                method: "post",
                url: url,
                data: formData,
                responseType: 'json'
            })
            .then(res => {
                if (res.data.error === 1) {
                    Toast.fire({
                        icon: 'error',
                        title: res.data.msg ? res.data.msg : 'Error'
                    });
                }
                callbackOK.call(this, res);
            })
            .catch(err => {
                console.error(err);
                alertify.error("Error. Ver consola");
                if (callbackFail)
                    callbackFail.call(this, err);
            });
    }
    /**
     * @param {string} url
     * @param {Number} id
     * @param {Function} callbackOK
     * @param {Function} callbackFail
     */
    static delete(url, callbackOK, callbackFail) {
        axios.delete(url)
            .then(res => callbackOK.call(this, res))
            .catch(err => callbackFail.call(this, err));
    }

    //static 
}
class Pyrus {
	entidad = null;
    objeto = null;
    name = null;
    tableDB = null;
    especificacion = null;
    objetoSimple = null;

    /**
     * @param {string} e
     */
    constructor(e) {
        console.time("Time this");
		if (e === null || e === "") {
			console.warn("AVISO: No se ha pasado ninguna entidad. Uso limitado");
			return false;
        }
        this.entidad = e;
        /* ------------------- */
        if (!ENTIDADES[this.entidad]) {
            console.warn(`AVISO: Entidad "${this.entidad}" no encontrada`);
			return false;
        }
        this.objeto = ENTIDADES[ this.entidad ];
        this.especificacion = this.objeto.ATRIBUTOS;
        this.name = e;
        this.tableDB = this.objeto.TABLE === undefined ? e : this.objeto.TABLE;
        /* ------------------- */
        this.getEspecificacion();
        console.timeEnd( "Time this" );
    }

    /**
     * @param {string} e
     * @param {{}} attr
     * @param {string} order
     */
    static relation(e, attr = null, order = null) {
        return new Promise((resolve, reject) => {
            const url = `${url_simple}adm/relation`;
            let formData = new FormData();
            formData.set("table", e);
            formData.set("attr", attr ? JSON.stringify(attr) : null);
            formData.set("order", order);
            return Connect.post(url, formData, res => {
                if(res.data.error === 0)
                    resolve(res);
                else
                    reject(res);
            }, err => reject(err));
        });
    }
    /**
     * @param {string} e
     * @param {Number} id
     * @param {string} attr
     * @param {Function} callbackOK
     */
    static count(e, id, attr, callbackOK) {
        const url = `${url_simple}adm/count`;
        let formData = new FormData();
        formData.set("table", e);
        formData.set("attr", attr);
        formData.set("id", id);
        return Connect.post(url, formData, res => {
            if (res.data.error === 0)
                callbackOK(res);
        });
    }
    /**
     * @param {string} e
     * @param {Number} value
     * @param {{}} attr
     * @param {Function} callbackOK
     */
    static relationOne(e, value, attr, callbackOK = null) {
        const url = `${url_simple}adm/relation`;
        let formData = new FormData();
        formData.set("table", e);
        formData.set("attr", attr ? JSON.stringify(attr) : null);
        formData.set("id", value);
        return Connect.post(url, formData, res => {
            if (res.data.error === 0)
                callbackOK(res);
        });
    }

    static relationJoin(...args) {
        const url = `${url_simple}adm/join`;
        let formData = new FormData();
        formData.set("entities", args[0]);
        formData.set("attr", JSON.stringify(args[2]));
        formData.set("join", args[3]);
        formData.set("id", args[1]);
        return Connect.post(url, formData, res => {
            if (res.data.error === 0)
                args[4](res);
        });
    }
    /**
     * @description Construye conjuntos de elementos de la cabecera de la tabla
     * @returns @type Array
     */
    columnas = () => {
        let Arr = [];
        for(let COLUMN in this.especificacion ) {
            if( this.especificacion[COLUMN].VISIBILIDAD != "TP_VISIBLE" && this.especificacion[COLUMN].VISIBILIDAD != "TP_VISIBLE_TABLE" )
                continue;
            let WIDTH = "auto";
            let NAME = COLUMN.toUpperCase();
            let {NOMBRE, TH} = this.especificacion[COLUMN];
            if (NOMBRE)
                NAME = NOMBRE.toUpperCase();
            if (TH)
                WIDTH = TH;
            Arr.push({NAME, COLUMN, WIDTH});
        }
        return Arr;
    };
    /**
     * @description Crea cabecera de tabla
     * @param columns @type array c/parámetros de la cabecera de la tabla
     * @param replace @type boolean reemplaza cabecera completa o adiciona
     * @returns string
     */
    table = (columns = null) => {
        const target = document.createElement("div");
        const tableElement = document.createElement("table");
        const thead = document.createElement("thead");
        let columnsDATA = this.columnas();
        target.classList.add("table-responsive");
        tableElement.classList.add("table", "table-striped", "table-hover", "table-borderless", "pyrus-table");
        tableElement.id = "tabla";
        thead.classList.add("thead-dark");
        if (columns !== null)
            columnsDATA = columnsDATA.concat(columns);
        columnsDATA.forEach(t => {
            let th = document.createElement("th");
            th.style.width = t.WIDTH;
            th.style.maxWidth = "500px";
            th.classList.add("text-center");
            th.textContent = t.NAME;
            thead.appendChild(th);
        });
        tableElement.appendChild(thead);
        target.appendChild(tableElement);
        return target;
    };

    getEspecificacion = () => {
        this.objetoSimple = {};
        this.objetoSimple["name"] = this.name;
        this.objetoSimple["especificacion"] = {};
        this.objetoSimple["detalles"] = {};
        this.objetoSimple["rules"] = {};
        this.objetoSimple["sorteable"] = null;
        for (let x in this.especificacion) {
            if (this.especificacion[x].TIPO == "TP_INT")
                continue;
            if (this.especificacion[x].SORTEABLE)
                this.objetoSimple["sorteable"] = x;
            if (this.especificacion[x].RULE)
                this.objetoSimple["rules"][x] = this.especificacion[ x ].RULE;
            if (this.especificacion[x].HIDDEN)
                continue;
            this.objetoSimple[ "especificacion" ][ x ] = this.especificacion[ x ].TIPO;
            switch (this.especificacion[x].TIPO) {
                case "TP_FILE":
                case "TP_IMAGE":
                case "TP_BLOB":
                    this.objetoSimple["detalles"][x] = {
                        FOLDER: this.especificacion[x].FOLDER === undefined ? this.name : this.especificacion[x].FOLDER
                    };
                    break;
                case "TP_CAST":
                    this.objetoSimple["detalles"][x] = {
                        CAST: this.especificacion[x].CAST === undefined ? null : this.especificacion[x].CAST
                    };
                    break;
                case "TP_PASSWORD":
                    this.objetoSimple["detalles"][x] = {
                        PASSWORD: 1
                    };
                    break;
            }
        }
    };
    clean = () => {
        let target = document.querySelector(".modal-body.pyrus--form");
        if (target)
            target.scrollTo(0,0);
        for (let i in CKEDITOR.instances)
            CKEDITOR.instances[i].destroy();
        target.innerHTML = "";
        editor(window.targetForm);
    };
    delete = (t, alertify_, url) => {
        $('[data-toggle="tooltip"]').tooltip('hide');
        Swal.fire({
            title: alertify_.title,
            text: alertify_.body,
            icon: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Confirmar'
        }).then(result => {
            if (result.value) {
                Connect.delete(url, res => {
                    if (res.data.error === 0) {
                        if (res.data.success) {
                            Swal.fire(
                                'Contenido eliminado!',
                                'Registro dado de baja.',
                                'success'
                            )
                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        }
                    } else {
                        Swal.fire(
                            'Atención',
                            res.data.msg,
                            'error'
                        )
                    }
                }, err => {
                    console.error(err);
                    Swal.fire(
                        'Atención',
                        'Ocurrió un error interno.',
                        'error'
                    )
                });
            }
        });
    };
    /**
     * @type object CKEDITOR
     */
    editor = (id = "", multiple = null) => {
        if (!this.objeto) {
            console.error( "#### SIN OBJETO" );
            return null;
        }
        if (this.objeto.EDITOR === undefined) {
            console.error( "#### SIN CARACTERÍSTICAS DE EDITOR" );
            return null;
        }
        for (let x in this.objeto.EDITOR) {
            let names = {
                element : x,
                id : id,
                multiple : multiple
            };
            let Arr = this.constructorNames(names);
            let e = this.objeto.EDITOR[x];
            e["on"] = {
                change: function(evt) {
                    changeCkeditor(x, evt);
                }
            }
            if (CKEDITOR.instances[Arr.idElementForm])
                CKEDITOR.instances[Arr.idElementForm].destroy();
            CKEDITOR.replace(`${Arr.idElementForm}`, e);
        }
    };
    card = (url, data, buttonsOK = ["c", "e", "d" ]) => {
        const columns = this.columnas();
        let html = "";
        let dataAUX = data;
        if( data === null ) {
            console.error( "#### SIN ELEMENTOS - En la base ####" );
            return null;
        }
        if( data.current_page !== undefined ) {
            data = data.data;
            dataAUX = data;
            console.warn( "### PAGINADO ACTIVO ####" );
        }
        console.info( "### CONSTRUYENDO CARDS ####" );

        if( !Array.isArray(data) )
            dataAUX = Object.keys( data );
        formatter = new Intl.NumberFormat('es-AR', {
            style: 'currency',
            currency: 'ARS',
        });
        dataAUX.map( x => {
            let id = null;
            if( !Array.isArray(data) ) {
                id = x;
                x = data[ x ];
            } else {
                if( x.id !== undefined )
                    id = x.id;
            }
            let elements = columns.map(y => {
                let td = document.createElement("div");
                return this.convert(x[y.COLUMN], td, url, this.especificacion[y.COLUMN].TIPO, this.especificacion[y.COLUMN], x, y).outerHTML;
            });
            html += `<div class="card">`;
                html += `<div class="card-body">${elements.join("")}</div>`;
                html += `<div class="card-footer">`;
                    html += `<div class="d-flex justify-content-center">`;
                    if( buttonsOK.indexOf( "c" ) >= 0 )
                        html += `<button data-toggle="tooltip" data-placement="left" title="Copiar elemento" style="font-size: 12px;" onclick="clone(this,'${id}')" class="btn text-center btn-info rounded-0" disabled><i class="far fa-clone"></i></button>`;
                    if( buttonsOK.indexOf( "e" ) >= 0 )
                        html += `<button data-toggle="tooltip" data-placement="left" title="Editar elemento" style="font-size: 12px;" onclick="edit(this,'${id}')" class="btn text-center rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></button>`;
                    if( buttonsOK.indexOf( "d" ) >= 0 )
                        html += `<button style="font-size: 12px;" data-toggle="tooltip" data-placement="left" title="Eliminar elemento" onclick="erase(this,'${id}')" class="btn text-center rounded-0 btn-danger"><i class="fas fa-trash-alt"></i></button>`;
                    html += `</div>`;
                html += `</div>`;
            html += `</div>`;
        }, this);
        return html;
    };
    convert = (value, target, url, type, specification, elements, column, id) => {
        if (value === null && type !== "TP_DELETE") {
            target.textContent = `SIN "${specification.NOMBRE}"`;
            return target;
        }
        const entidad = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
        const btn_element = document.createElement("button");
        switch(type) {
            case "TP_DELETE":
                target.innerHTML = value ? "Eliminado" : "Activo";
                break;
            case "TP_IMAGE":
                let date = new Date();
                let info = value.d;
                if( value.e == "mp4" ) {
                    value = value.i;
                    videoURL = value == "" ? value : `${url}/${value}`;
                    w = specification.WIDTH === undefined ? "auto" : ( specification.TABLE !== undefined ? specification.TABLE : specification.WIDTH );
                    value = `<video style="width: ${w};" class="d-block mx-auto" controls>`;
                        value += `<source src="${videoURL}" type="video/mp4">`;
                        value += `Your browser does not support the video tag.`;
                    value += `</video>`;
                    value += `<p class="text-center mx-auto mt-1"><strong class="mr-1">Tipo:</strong>video MP4</p>`;
                    value += `<p class="text-center mx-auto mt-1"><strong class="mr-1 text-truncate">URL:</strong>${videoURL}</p>`;
                    value += `<p class="text-center mx-auto mt-1 d-flex justify-content-center flex-wrap align-items-center">`;
                        value += `<a href="${videoURL}" target="blank"><i class="fas fa-external-link-alt"></i></a>`;
                    value += `</p>`;
                } else {
                    value = value.i;
                    let imgURL = value == "" ? value : `${url}${value}`;
                    let img = value == "" ? value : `${url}${value}?t=${date.getTime()}`;
                    let w = specification.WIDTH === undefined ? "auto" : ( specification.TABLE !== undefined ? specification.TABLE : specification.WIDTH );
                    value = `<img style="width: ${w};" class="table--image" src="${img}" onerror="this.src='${src}'"/>`;
                    if( info !== undefined ) {
                        value += `<p class="text-center mx-auto mt-2"><strong class="mr-1">Dimensiones:</strong>${info[ 0 ]}px x ${info[ 1 ]}px</p>`;
                        value += `<p class="text-center mx-auto mt-1"><strong class="mr-1">Tipo:</strong>${info.mime}</p>`;
                        value += `<p class="text-center mx-auto mt-1"><strong class="mr-1 text-truncate">URL:</strong>${imgURL}</p>`;
                        value += `<p class="text-center mx-auto mt-1 d-flex justify-content-center flex-wrap align-items-center">`;
                            value += `<a href="${imgURL}" target="blank"><i class="fas fa-external-link-alt"></i></a>`;
                            value += `<a onclick="copy( this , '${imgURL}' )" href="#" class="ml-1"><i style="cursor:pointer;" class="far fa-copy"></i></a>`;
                            value += `<a href="${imgURL}" download class="ml-1"><i class="fas fa-download"></i></a>`;
                        value += `</p>`;
                    }
                }
                target.innerHTML = value;
            break;
            case "TP_ARRAY":
                value = elements[specification.COLUMN];
                if (value !== null && value !== undefined)
                    value = value.length;
                else
                    value = 0;
                target.textContent = value;
                target.classList.add("text-center");
            break;
            case "TP_CHECK":
                value = Boolean(value);
                btn_element.classList.add("btn", "button--form", "btn-link", "btn-sm", "ml-2", "edit--check");
                btn_element.innerHTML = '<i class="fas fa-pen"></i>';
                btn_element.type = "button";
                btn_element.dataset.name = this.tableDB;
                btn_element.dataset.column = column.COLUMN;
                btn_element.dataset.id = id;
                btn_element.dataset.value = value;
                if (specification.OPTION) {
                    if (specification.OPTION[value])
                        target.textContent = specification.OPTION[value];
                    else
                        target.textContent = "-";
                } else
                    target.textContent = "-";
                target.innerHTML += specification.NOTEDIT === undefined ? btn_element.outerHTML : "";
            break;
            case "TP_YOUTUBE":
                y = `https://www.youtube.com/watch?v=${value}`;
                value = `<iframe class="w-100 h-100" src="https://www.youtube.com/embed/${value}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                value = `<p class="text-center"><a class="text-primary mb-2" href="${y}" target="blank">${y}</a><i class="fas fa-external-link-alt ml-2"></i></p>${value}`;
                target.innerHTML = value;
            break;
            case "TP_FILE":
                value = `<a href="${url}/${value.i}" target="_blank" class="text-primary">${value.i}</a><i class="fas fa-external-link-alt ml-2"></i>`;
                target.innerHTML = value;
            break;
            case "TP_COLOR":
                if (typeof value === "string")
                    value = JSON.parse(value);
                btn_element.classList.add("btn", "button--form", "btn-link", "btn-sm", "ml-2", "edit--check");
                btn_element.innerHTML = '<i class="fas fa-pen"></i>';
                btn_element.type = "button";
                btn_element.dataset.name = this.tableDB;
                btn_element.dataset.column = column.COLUMN;
                btn_element.dataset.id = id;
                btn_element.dataset.value = value[column.COLUMN];
                let elem = document.createElement("div");
                elem.classList.add("text-center");
                elem.innerHTML = `<p>${value[column.COLUMN]}${specification.NOTEDIT === undefined ? btn_element.outerHTML : ""}</p>`;
                elem.innerHTML += `<div class="pyrus--color__element" style="background-color: ${value[column.COLUMN]}"></div>`;
                target.innerHTML = elem.outerHTML;
            break;
            case "TP_LINK":
            case "TP_EMAIL":
                btn_element.classList.add("btn", "button--form", "btn-link", "btn-sm", "ml-2", "edit--check");
                btn_element.innerHTML = '<i class="fas fa-pen"></i>';
                btn_element.type = "button";
                btn_element.dataset.name = this.tableDB;
                btn_element.dataset.column = column.COLUMN;
                btn_element.dataset.id = id;
                btn_element.dataset.value = value;
                if (type == "TP_EMAIL")
                    value = `<a href="mailto:${value}" target="_blank" class="text-primary">${value}<i class="fas fa-external-link-alt ml-2"></i></a>`;
                else
                    value = `<a href="${value}" target="_blank" class="text-primary">${value}<i class="fas fa-external-link-alt ml-2"></i></a>`;
                target.innerHTML = `<div class="d-flex justify-content-between align-items-center">${value}${specification.NOTEDIT === undefined ? btn_element.outerHTML : ""}</div>`;
            break;
            case "TP_FECHA":
            case "TP_DATE":
                btn_element.classList.add("btn", "button--form", "btn-link", "btn-sm", "ml-2", "edit--check");
                btn_element.innerHTML = '<i class="fas fa-pen"></i>';
                btn_element.type = "button";
                btn_element.dataset.name = this.tableDB;
                btn_element.dataset.column = column.COLUMN;
                btn_element.dataset.id = id;
                value = dates.string(value);
                btn_element.dataset.value = value[2];

                target.innerHTML = `<div class="d-flex justify-content-between align-items-center">${value[1]}${specification.NOTEDIT === undefined ? btn_element.outerHTML : ""}</div>`;
            break;
            case "TP_MONEY":
                value = formatter.format(value);
                target.textContent = value;
                target.classList.add("text-right");
            break;
            case "TP_ENUM":
            case "TP_RELATIONSHIP":
                if (specification.ENUM) {
                    value = specification.ENUM.find(x => {
                        if(x.id == value)
                            return x;
                    }).text;
                    target.innerHTML = value;
                } else {
                    Pyrus.relationOne(specification.ENTIDAD, value, specification.ATTR, data => {
                        if (data.data.error == 0)
                            target.innerHTML = data.data.data.text;
                        else
                            Toast.fire({
                                icon: 'error',
                                title: data.data.msg
                            });
                    });
                }
            break;
            case 'TP_JOIN':
                value = elements[specification["ATRIBUTO"]];
                Pyrus.relationJoin(specification.ENTIDADES, value, specification.ATTR, specification.JOIN, data => {
                    if (data.data.error == 0)
                        target.innerHTML = data.data.data.text;
                    else
                        Toast.fire({
                            icon: 'error',
                            title: data.data.msg
                        });
                });
            break;
            case "TP_INT":
                target.classList.add("text-center");
                Pyrus.count(specification.ENTIDAD, id, specification.ATTR, data => {
                    if (data.data.error == 0)
                        target.innerHTML = data.data.data;
                    else
                        Toast.fire({
                            icon: 'error',
                            title: data.data.msg
                        });
                });
            break;
            case "TP_TEXT":
                target.innerHTML = `${value}`;
            break;
            default:
                btn_element.classList.add("btn", "button--form", "btn-link", "btn-sm", "ml-2", "edit--check");
                btn_element.innerHTML = '<i class="fas fa-pen"></i>';
                btn_element.type = "button";
                btn_element.dataset.name = this.tableDB;
                btn_element.dataset.column = column.COLUMN;
                btn_element.dataset.id = id;
                btn_element.dataset.value = value;
                target.innerHTML = `<div class="d-flex justify-content-between align-items-center">${value}${specification.NOTEDIT === undefined ? btn_element.outerHTML : ""}</div>`;
                //target.innerHTML = `<span class="edit" data-name="${this.name}" data-column="${column.COLUMN}" data-id="${id}">${value}</span>`;
        }
        return target;
    };
    row = (elements, url, tbody, id, buttonsOK, button) => {
        const columns = this.columnas();
        let tr = document.createElement("tr");
        tr.dataset.id = id;
        let cols = columns.map(y => {
            let td = document.createElement("td");
            td.style.maxWidth = "500px";
            td.style.width = y.WIDTH;
            td.dataset.column = y.COLUMN;
            return this.convert(elements[y.COLUMN], td, url, this.especificacion[y.COLUMN].TIPO, this.especificacion[y.COLUMN], elements, y, id);
        });
        cols.forEach(c => tr.appendChild(c));
        if (!window.td_pyrus)
            window.td_pyrus = [];
        if (!window.formAction)
            window.td_pyrus.push(tr);
        if (buttonsOK.length != 0 || button !== null) {
            let td = document.createElement("td");
            td.classList.add("text-center");
            td.style.width = "50px";
            td.style.maxWidth = "150px";
            let buttons = `<td class="text-center">`;
                buttons += `<div class="d-flex justify-content-center">`;
                if (buttonsOK.indexOf( "c" ) >= 0 && !elements.deleted_at)
                    buttons += `<button data-toggle="tooltip" data-placement="left" title="Copiar elemento" style="font-size: 12px;" onclick="clone(this,'${id}')" class="btn text-center btn-info rounded-0"><i class="far fa-clone"></i></button>`;
                if (buttonsOK.indexOf( "e" ) >= 0 && !elements.deleted_at)
                    buttons += `<button data-toggle="tooltip" data-placement="left" title="Editar elemento" style="font-size: 12px;" onclick="edit(this,'${id}')" class="btn text-center rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></button>`;
                if (buttonsOK.indexOf( "d" ) >= 0 && !elements.deleted_at)
                    buttons += `<button style="font-size: 12px;" data-toggle="tooltip" data-placement="left" title="Eliminar elemento" onclick="erase(this,'${id}')" class="btn text-center rounded-0 btn-danger"><i class="fas fa-trash-alt"></i></button>`;
                if (buttonsOK.indexOf( "df" ) >= 0 && elements.deleted_at)
                    buttons += `<button style="font-size: 12px;" data-toggle="tooltip" data-placement="left" title="Baja total elemento" onclick="erase(this,'${id}', 1)" class="btn text-center rounded-0 btn-dark"><i class="fas fa-trash-alt"></i></button>`;
                if (buttonsOK.indexOf( "s" ) >= 0)
                    buttons += `<button style="font-size: 12px;" data-toggle="tooltip" data-placement="left" title="Ver elemento" onclick="see(this,'${id}')" class="btn text-center rounded-0 btn-primary"><i class="fas fa-eye"></i></button>`;
                if (button !== null && !elements.deleted_at) {
                    button.forEach( function( b ) {
                        buttons += `<button data-toggle="tooltip" data-placement="left" title="${b.title}" style="font-size: 12px;" onclick="${b.function}Function(this,'${id}')" class="btn text-center rounded-0 ${b.class}">${b.icon}</button>`;
                    });
                }
                buttons += `</div>`;
            buttons += `</td>`;
            td.innerHTML = buttons;
            tr.appendChild(td);
        }
        if (!window.formAction)
            tbody.appendChild(tr);
        else
            return tr;
    };
    /**
     * @type object target
     * @type string url - url base
     * @type object / array data
     * @var button @type object @description elemento tipo objeto que contiene las características de un boton. Función que dispara, color e ícono
     */
    elements = (target , url , data , buttonsOK = [ "c" , "e" , "d" ] , button = null ) => {
        let table = document.querySelector(target);
        let dataAUX = data;
        if( data === null ) {
            console.error( "#### SIN ELEMENTOS - En la base ####" );
            return null;
        }
        if( window.usr_data !== undefined ) {
            if( parseInt( window.usr_data.is_admin ) > 1 ) {
                i = buttonsOK.indexOf( "d" );
                buttonsOK.splice( i , 1 );
            }
        }
        if (data.current_page !== undefined) {
            data = data.data;
            dataAUX = data;
            console.warn( "### PAGINADO ACTIVO ####" );
        }
        let tbody = null;
        if(table.querySelector("tbody"))
            tbody = table.querySelector("tbody");
        else {
            tbody = document.createElement("tbody");
            table.appendChild(tbody);
        }
        window.tbody_pyrus = tbody;
        if (!Array.isArray(data))
            dataAUX = Object.keys(data);
        if (dataAUX.length == 0) {
            console.info( "### SIN INFORMACIÓN ####" );
            return null;
        }
        console.info("### CONSTRUYENDO TABLA ####");
        formatter = new Intl.NumberFormat('es-AR', {
            style: 'currency',
            currency: 'ARS',
        });
        dataAUX.map( x => {
            let id = null;
            if( !Array.isArray(data) ) {
                id = x;
                x = data[ x ];
            } else {
                if( x.id !== undefined )
                    id = x.id;
            }
            return this.row(x, url, tbody, id, buttonsOK, button);
        }, this);
    };
    one = (url, callbackOK, callbackFail) => {
        Connect.one(url, callbackOK, callbackFail);
    };
    show = (url, data, identifierNAME = null, identifier = null) => {
        for (let x in this.especificacion) {
            let names = this.constructorNames({
                element : x,
                id : identifierNAME,
                multiple : identifier
            });
            switch (this.especificacion[x].TIPO) {
                case "TP_ENUM":
                    let element = document.querySelector(`#${names.idElementForm}`);
                    if (element)
                        $(element).selectpicker('render');
                    break;
            }
        }
        if (data === null || data === undefined) {
            console.warn( `### SIN DATOS ###` );
            return null;
        }
        let ARR_names = [];
        if(!window.ARR_names)
            window.ARR_names = [];
        for (let x in this.especificacion) {
            if (!data[x])
                continue;
            if (this.especificacion[x].TIPO == "TP_PASSWORD")
                continue;
            let flag = 1;
            if (flag) {
                let names = {
                    element : x,
                    id : identifierNAME,
                    multiple : identifier
                };
                ARR_names.push(this.constructorNames(names));
            }
        }

        window.ARR_names = window.ARR_names.concat(...ARR_names);

        ARR_names.forEach(element => {
            let x = element.element.element;
            let name = element.idElementForm;
            let flag = 1;
            let value = data[x];
            if(CKEDITOR) {
                if( CKEDITOR.instances[name] !== undefined ) {
                    document.querySelector(`#${name}`).value = value;
                    CKEDITOR.instances[name].setData(value);
                    flag = 0;
                }
            }
            if (flag && value) {
                if (this.especificacion[ x ]) {
                    switch (this.especificacion[ x ].TIPO) {
                        case "TP_FILE":
                            if (window.formAction !== "CLONE") {
                                let link = `<a target="blank" class="text-primary" href="${url_simple}${value.i}">${url_simple}${value.i}</a>`;
                                let f = `<p class="w-100"><strong>Extensión:</strong> ${value.e.toUpperCase()}</p>`;
                                f += `<p class="text-truncate w-100"><strong>Link:</strong> ${link}</p>`;
                                $( `#${name}` ).closest( ".input-group" ).find( "+ .input-group-text" ).html( f )
                                $( `#${name}` ).closest( ".input-group" ).find(".imgURL").val(`${value.i}`);
                            }
                        break;
                        case "TP_IMAGE":
                            if (window.formAction !== "CLONE") {
                                let ul = "";
                                let date = new Date();
                                let image = value;
                                let img = "";
                                if (image !== null) {
                                    if (typeof image == "object")
                                        image = image.i;
                                    if (url.substr(-1) != "/")
                                        url += "/";
                                    img = `${url}${image}`;
                                }
                                ul = `<ul class="image--wh image--wh__details"><li><strong>Ubicación:</strong> <a href="${img}" target="blank"><i class="fas fa-image"></i> [LINK]</a></li></ul>`;
                                if( img != "" ) {
                                    img += `?t=${date.getTime()}`;
                                    let button = document.querySelector(`#${element.idElementForm}_image`).nextSibling.querySelector(".image--button");
                                    button.disabled = false;
                                    button.dataset.url = image;
                                    if (this.objeto.COLUMN)
                                        button.dataset.column = this.objeto.COLUMN;
                                    button.dataset.attr = x;
                                    if (data.id)
                                        button.dataset.id = data.id;
                                    button.dataset.entidad = this.tableDB;
                                    document.querySelector(`#${element.idElementForm}_image`).previousElementSibling.innerHTML += ul;
                                    document.querySelector(`#${element.idElementForm}_image`).src = img;
                                    let imageAux = new Image();
                                    imageAux.src = img;
                                    imageAux.onload = function() {
                                        switchImage(this, document.querySelector(`#${element.idElementForm}_image`));
                                    }
                                }
                            }
                        break;
                        case "TP_CHECK":
                            if (value == 1) {
                                const elem = document.querySelector(`#${name}`);
                                elem.checked = true;
                                elem.nextElementSibling.value = 1;
                            }
                        break;
                        case "TP_ENUM":
                            if( this.especificacion[x].NOT_TRIGGER === undefined )
                                $( `#${name}` ).val( value ).trigger( "change" );
                            else
                                $( `#${name}` ).val( value );
                            if( $( `#${name}.selectpicker` ).length )
                                $( `#${name}.selectpicker` ).selectpicker( 'refresh' );
                        break;
                        case "TP_FECHA":
                        case "TP_DATE":
                            value = dates.string(value);
                            document.querySelector(`#${name}`).value = value[2];
                        break;
                        case "TP_COLOR":
                            let ele = document.querySelector(`#${name}`);
                            ele.value = value[x];
                            if (ele["onchange"])
                                ele.onchange();
                        break;
                        default:
                            $( `#${name}` ).val( value ).trigger( "change" );
                    }
                }
            }
        });
    };
    elementForm = (e, value) => {
        const especificacion = this.especificacion[e];
        const aux = this.inputAdecuado(especificacion, e, 1, null, especificacion.PLACEHOLDER === undefined ? "" : especificacion.PLACEHOLDER, null, value, 1);
        return aux;
    };
    formulario = (id = "", multiple = null) => {
        if( this.objeto === null )
            return "";

        if( this.objeto[ 'FORM' ] === undefined )
            return "";
        try {
            if (this.objeto.ONE) {
                if (this.objeto.MULTIPLE && id === "" && !multiple) {
                    let form = `<fieldset class="form--fieldset">`;
                        form += `<legend>${this.objeto.NOMBRE}</legend>`;
                        form += `<button type="button" class="btn button--form btn-dark px-5 mx-auto d-block text-uppercase" onclick="${this.objeto.MULTIPLE}Function();">${this.objeto.MULTIPLE}<i class="fas fa-plus ml-2"></i></button>`;
                        form += `<div class="row mt-3n" id="wrapper-${this.objeto.MULTIPLE}"></div>`;
                        form += `<button type="button" class="btn button--form btn-dark mt-3 px-5 mx-auto d-block text-uppercase" onclick="${this.objeto.MULTIPLE}Function();">${this.objeto.MULTIPLE}<i class="fas fa-plus ml-2"></i></button>`;
                    form += `</fieldset>`;
                    return form;
                }
            }
            let formulario = "";
            let OBJ_funciones = {}
            let ARR_form = Object.assign([], this.objeto.FORM);

            if (this.objeto.FUNCIONES)
                OBJ_funciones = this.objeto.FUNCIONES;

            ARR_form.forEach(rowElements => {
                let element_row = document.createElement("div");
                element_row.classList.add("row", "justify-content-center");
                for(let i in rowElements) {
                    let row = i;
                    let rowElementos = rowElements[i];
                    rowElementos.forEach( e => {
                        if(this.especificacion[e] === undefined && e != "BTN") {
                            console.warn(`ELEMENTO "${e}" NO ENCONTRADO *** Revise declaration.js`);
                            return false;
                        }
                        let aux = "";
                        let OBJ_funcion = {};
                        if (OBJ_funciones[e] !== undefined)
                            OBJ_funcion = this.objeto['FUNCIONES'][e];
                        if(e != "VACIO") {
                            let especificacion = this.especificacion[e];
                            aux = this.inputAdecuado(especificacion, e, id, OBJ_funcion, especificacion.PLACEHOLDER === undefined ? "" : especificacion.PLACEHOLDER, multiple, especificacion.DEFAULT ? especificacion.DEFAULT : null);
                            if (typeof aux != "string")
                                aux = `<div id="${aux.id}"></div>`;
                        }

                        if(row.indexOf(e) >= 0)
                            row = row.replace(`/${e}/`,aux);
                    }, this);
                    element_row.innerHTML += row;
                }
                formulario += element_row.outerHTML;
            }, this);
            if (multiple)
                formulario = `<input type="hidden" value="" class="remove-element" name="${this.name}_${multiple}[]"/>${formulario}`;
            if (this.objeto.ONE && id === "" && !multiple) {
                formulario = `<div class="contenedorForm w-100 form_${this.entidad}">${formulario}</div>`;
                let fieldset = document.createElement("fieldset");
                fieldset.classList.add("form--fieldset");
                fieldset.innerHTML = `<legend>${this.objeto.NOMBRE}</legend>${formulario}`;
                return fieldset.outerHTML;
            } else
                return `<div class="contenedorForm w-100 form_${this.entidad}">${formulario}</div>`;
        } catch (error) {
            console.error(error);
            return "Error en el armado";
        }
    };
    /**
     * @var Object @type JSON
     * @var e @type String
     */
    inputAdecuado = (Object_, element_name, id_name, OBJ_funcion, placeholder , multiple, value = null, add = null) => {
        let names = this.constructorNames({
            element : element_name,
            id : id_name,
            multiple : multiple
        });

        if( Object_.NOMBRE === undefined )
            Object_.NOMBRE = element_name;
        if( Object_.NAME === undefined )
            Object_.NAME = element_name;
        if( placeholder === undefined )
            placeholder = "";
        if( this.objeto.MINUSCULA === undefined )
            Object_.NOMBRE = (Object_.NOMBRE).toUpperCase();

        if( Object_.VISIBILIDAD == 'TP_VISIBLE' || Object_.VISIBILIDAD == 'TP_VISIBLE_FORM' ) {
            switch( Object_.TIPO ) {
                case 'TP_RELATIONSHIP':
                    this.dataRelation(Object_, names, OBJ_funcion, placeholder, value)
                    return {id: `${names.idElementForm}_target`};
                case 'TP_LIST':
                    return this.listDatails(Object_, names, OBJ_funcion, placeholder, value);
                case 'TP_ENTERO':
                    return this.inputString(Object_, names, "number", OBJ_funcion, placeholder, value);
                case 'TP_LINK':
                    return this.inputString(Object_, names, "url", OBJ_funcion, placeholder, value);
                case 'TP_CHECK':
                    return this.check(Object_, names, OBJ_funcion, value);
                case 'TP_MONEY':
                    return this.money( Object_ , names ,OBJ_funcion,placeholder );
                case 'TP_PHONE':
                    return this.inputString(Object_, names, "phone", OBJ_funcion, placeholder, value);
                case 'TP_EMAIL':
                    return this.inputString(Object_, names, "email", OBJ_funcion, placeholder, value);
                case 'TP_COLOR':
                    names = this.constructorNames({
                        element : element_name,
                        id : id_name,
                        multiple : multiple
                    }, "hsl");
                    return this.inputColor(Object_, names, OBJ_funcion, placeholder, value);
                case 'TP_IMAGE':
                    names = this.constructorNames({
                        element : element_name,
                        id : id_name,
                        multiple : multiple
                    }, "check");
                    return this.inputImage(Object_, names, OBJ_funcion);
                case 'TP_FILE':
                    return this.inputString(Object_, names, "file", OBJ_funcion, placeholder, value);
                case 'TP_STRING':
                    return this.inputString(Object_, names, "text", OBJ_funcion, placeholder, value);
                case 'TP_TEXT':
                    return this.inputText(Object_, names, OBJ_funcion, placeholder);
                case 'TP_FECHA':
                    return this.inputString(Object_, names, "date", OBJ_funcion, placeholder, value);
                case 'TP_PASSWORD':
                    return this.inputString(Object_, names, "password", OBJ_funcion, placeholder);
                case 'TP_ENUM':
                    return this.select(Object_, names, OBJ_funcion, placeholder, value);
                default:
                    return this.inputString(Object_, names, "text", OBJ_funcion, placeholder, value);
            }
        } else return this.inputHidden(Object_, names);
    };
    /**
     * @var names @type object
     * @var addName @type string
     */
    /**
     * @var names @type object
     * @var addName @type string
     */
    constructorNames = ( names , addName = null ) => {
        let Arr = {};
        Arr.element = names;
        Arr.nameElementForm = `${this.name}[${names.element}]`;
        Arr.idElementForm = `${this.name}_${names.element}`;
        Arr.nameURLForm = `${this.name}[${names.element}][URL]`;
        Arr.idURLForm = `${this.name}_${names.element}_URL`;
        if (addName) {
            Arr.nameElementForm = `${this.name}[${names.element}][${names.element}]`;
            Arr[`${addName}NameElementForm`] = `${this.name}[${names.element}][${addName}]`;
            Arr[`${addName}ElementForm`] = `${this.name}_${names.element}_${addName}`;
        }

        if (names.multiple) {
            if (addName) {
                Arr[`${addName}NameElementForm`] = `${this.name}[${names.element}][${names.multiple}][]`;
                Arr[`${addName}ElementForm`] = `${this.name}_${names.element}_${names.multiple}`;
            }

            Arr.nameElementForm = `${this.name}[${names.element}][${names.multiple}][]`;
            Arr.idElementForm = `${this.name}_${names.element}_${names.multiple}`;

            Arr.nameURLForm = `${this.name}[${names.element}][${names.multiple}][URL][]`;
            Arr.idURLForm = `${this.name}_${names.element}_${names.multiple}_URL`;
        }
        if (names.id) {
            if (names.id != "") {
                if (typeof names.id != "object") {
                    Arr.idElementForm += `_${names.id}`;
                    Arr.idURLForm += `_${names.id}`;
                    if (addName) {
                        if (names.multiple) {
                            Arr.nameElementForm = `${this.name}[${names.element}][${names.multiple}][][${names.element}]`;
                            Arr[ `${addName}NameElementForm`] = `${this.name}[${names.element}][${names.multiple}][][${addName}]`;
                            Arr[ `${addName}ElementForm`] += `_${names.id}_${addName}`;
                        } else {
                            Arr.nameElementForm = `${this.name}[${names.element}][${names.element}]`;
                            Arr[ `${addName}NameElementForm`] = `${this.name}[${names.element}][${addName}]`;
                            Arr[ `${addName}ElementForm`] += `_${names.id}_${addName}`;
                        }
                    }
                }

            }
        }
        return Arr;
    };
    /**
     * @var function_ @type object
     */
    constructorFunction = (functions, element) => {
        if (!functions)
            return null;

        for(let evt in functions)
            element.setAttribute(evt, functions[evt]);
    };
    //
    label = (id, nombre) => {
        let label = document.createElement("label");
        label.classList.add("form--label");
        label.setAttribute("for", id);
        label.textContent = nombre;
        return label.outerHTML;
    };
    help = (inner, max = null) => {
        if (!inner && !max)
            return "";
        let help = document.createElement("small");
        help.classList.add("form-text", "text-muted");
        if (inner)
            help.innerHTML = inner;
        if (max) {
            if (help.innerHTML != "")
                help.innerHTML += `. Cantidad máx. de caracteres: ${max}`;
            else
                help.innerHTML += `Cantidad máx. de caracteres: ${max}`;
        }
        return help.outerHTML;
    };
    elementAttr = (element, data) => {
        if (data.NECESARIO)
            element.required = true;
        if (data.DISABLED)
            element.disabled = true;
        if (data.CLASS)
            element.classList.add(...data.CLASS.split(" "));
    };
    //-----------
    inputImage = (Object_, Arr, OBJ_funcion) => {
        let contenedorImage = document.createElement("label");
        let aviso = document.createElement("label");
        let aviso_input = document.createElement("input");
        let element = document.createElement("input");
        let image = document.createElement("img");
        let hidden = document.createElement("input");
        let span = document.createElement("span");
        let help = Object_.HELP ? this.help(Object_.HELP) : "";
        let button = document.createElement("button");
        let attr = document.createElement("details");
        let attr_html = "";
        attr.classList.add("image--upload__attr");
        attr_html += `<summary>Detalles de la imagen ℹ️</summary>`;
        attr_html += "<ul class='image--wh'>";
        if (Object_.WIDTH) {
            attr_html += `<li><strong>Ancho:</strong> ${Object_.WIDTH}</li>`;
            image.style.width = Object_.WIDTH;
        } else
            attr_html += `<li><strong>Ancho:<strong> sin definir</li>`;
        if (Object_.HEIGHTop)
            attr_html += `<li><strong>Alto:</strong> ${Object_.HEIGHTop}</li>`;
        else if (Object_.HEIGHT) {
            attr_html += `<li><strong>Alto:</strong> ${Object_.HEIGHT}</li>`;
            image.style.height = Object_.HEIGHT;
        } else
            attr_html += `<li><strong>Alto:</strong> sin definir</li>`;
        if (Object_.SIZE)
            attr_html += `<li><strong>Peso:</strong> ${Object_.SIZE}</li>`;
        else
            attr_html += `<li><strong>Peso:</strong> sin definir</li>`;
        if (Object_.FOLDER)
            attr_html += `<li><strong>Carpeta de guardado:</strong> ${Object_.FOLDER}</li>`;
        if (Object_.EXT)
            attr_html += `<li><strong>Extensiones permitidas:</strong> ${Object_.EXT}</li>`;
        attr_html += "</ul>";
        button.classList.add("image--button");
        button.innerHTML = '<i class="fas fa-trash-alt"></i>';
        button.type = "button";
        button.disabled = true;
        button.setAttribute("onclick", "removeFile(this)");
        attr.innerHTML = attr_html;
        hidden.type = "hidden";
        hidden.classList.add("imgURL");
        span.innerHTML = "📂";
        span.dataset.name = "No se seleccionó ningún archivo";
        image.src = "";
        image.setAttribute("onError", `this.src='${src}'`);
        image.classList.add("image--upload__img");
        element.type = "file";
        contenedorImage.classList.add("image--upload");
        contenedorImage.appendChild(element);
        contenedorImage.appendChild(span);
        this.elementAttr(element, Object_);
        if (Object_.ACCEPT)
            element.setAttribute("accept", Object_.ACCEPT);
        aviso.classList.add("check");
        aviso_input.type = "checkbox";
        aviso_input.setAttribute("onchange", "check(this)");
        aviso.appendChild(aviso_input);
        aviso.innerHTML += `<input name="${Arr.checkNameElementForm}" value="0" type="hidden"/><div>¿Mantener nombre del archivo?</div>`;
        image.id = `${Arr.idElementForm}_image`;
        element.id = Arr.idElementForm;
        element.name = Arr.nameElementForm;
        element.setAttribute("onchange", `readURL(this, '${Arr.idElementForm}_image')`);
        hidden.id = Arr.idURLForm;
        hidden.name = Arr.nameURLForm;
        return attr.outerHTML + image.outerHTML + `<div class="d-flex justify-content-between align-items-center mb-2">${aviso.outerHTML}${button.outerHTML}</div>` + contenedorImage.outerHTML + help;
    };
    inputString = (Object_, Arr, STR_type, OBJ_funcion = null, placeholder = "", value = null) => {
        let element = document.createElement("input");
        let label = Object_.LABEL ? this.label(Arr.idElementForm, Object_.NOMBRE) : "";
        let help = this.help(Object_.HELP, Object_.MAXLENGTH);
        this.elementAttr(element, Object_);
        if (Object_.MAXLENGTH)
            element.maxLength = Object_.MAXLENGTH;
        if (Object_.DEFAULT)
            element.setAttribute("value", Object_.DEFAULT);
        element.type = STR_type;
        switch (STR_type) {
            case "number":
                if (Object_.MIN)
                    element.min = Object_.MIN;
                else
                    element.min = 0;
                if (Object_.MAX)
                    element.max = Object_.MAX;
                if (Object_.STEP)
                    element.step = Object_.STEP;
                element.classList.add("text-center");
            break;
            case "password":
            break;
            case "text":
                if (Object_.MAXLENGTH)
                    element.max = Object_.MAXLENGTH;
            break;
            case "phone":
                element.pattern = "[0-9]+";
                element.setAttribute("oninvalid", "this.setCustomValidity('Ingrese sólo números')");
                element.setAttribute("oninput", "this.setCustomValidity('')");
            break;
            case "url":
                element.pattern = "https?://.+";
                element.setAttribute("oninvalid", "this.setCustomValidity('Ingrese una URL válida')");
                element.setAttribute("oninput", "this.setCustomValidity('')");
            break;
            case "email":
                element.pattern = "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$";
                element.setAttribute("oninvalid", "this.setCustomValidity('Ingrese un email válido')");
                element.setAttribute("oninput", "this.setCustomValidity('')");
            break;
        }
        this.constructorFunction(OBJ_funcion, element);
        if (value)
            element.setAttribute("value", value);
        element.classList.add("form-control","form--input");
        element.name = Arr.nameElementForm;
        element.id = Arr.idElementForm;
        element.placeholder = placeholder === "" ? Object_.NOMBRE : placeholder;
        return label + `<div class="form--input__target">${element.outerHTML}<span></span></div>` + help;
    };
    //-----------
    check = (OBJ_elemento, Arr, OBJ_funcion = null, value = null) => {
        let check = document.createElement("label");
        let check_input = document.createElement("input");
        let label = OBJ_elemento.LABEL ? this.label(Arr.idElementForm, OBJ_elemento.NOMBRE) : "";
        let help = OBJ_elemento.HELP ? this.help(OBJ_elemento.HELP) : "";
        this.constructorFunction(OBJ_funcion, check);
        check.classList.add("check");
        check_input.type = "checkbox";
        check_input.id = Arr.idElementForm;
        check_input.setAttribute("onchange", "check(this)");
        if (value !== null && value === "true") {
            check_input.setAttribute("checked", true);
            check.appendChild(check_input);
            check.innerHTML += `<input name="${Arr.nameElementForm}" value="1" type="hidden"/><div>${OBJ_elemento.CHECK}</div>`;
        } else {
            check.appendChild(check_input);
            check.innerHTML += `<input name="${Arr.nameElementForm}" value="0" type="hidden"/><div>${OBJ_elemento.CHECK}</div>`;
        }
        return label + check.outerHTML + help;
    };
    inputHidden = (Object_, Arr) => {
        let element = document.createElement("input");
        element.type = "hidden";
        if (Object_.DEFAULT)
            element.value = Object_.DEFAULT;
        element.name = Arr.nameElementForm;
        element.id = Arr.idElementForm;
        return element.outerHTML;
    };
    listDatails = (OBJ_elemento, Arr, OBJ_funcion = null , placeholder = "", value = null) => {
        let element = document.createElement("input");
        let datalist = document.createElement("datalist");
        let label = OBJ_elemento.LABEL ? this.label(Arr.idElementForm, OBJ_elemento.NOMBRE) : "";
        let help = OBJ_elemento.HELP ? this.help(OBJ_elemento.HELP) : "";
        this.elementAttr(element, OBJ_elemento);
        element.classList.add("form--input", "form-control");
        element.placeholder = placeholder == "" ? OBJ_elemento.NOMBRE : placeholder;
        element.setAttribute("list", `${Arr.idElementForm}s`);
        element.name = Arr.nameElementForm;
        element.id = Arr.idElementForm;
        datalist.id = `${Arr.idElementForm}s`;
        let flag = 1;
        if (flag) {
            if (OBJ_elemento.DATA) {
                OBJ_elemento.DATA.map( x => {
                    let opt = document.createElement("option");
                    opt.value = x;
                    datalist.appendChild(opt);
                });
            }
        }
        if (value)
            element.value = value;
		return label + element.outerHTML + datalist.outerHTML + help;
    };
    /** REVISAR */
    money = ( Object_ , names , STR_class , OBJ_funcion = null , placeholder = "" ) => {
        let STR_funcion = "";
        let inputData = "";
		if( Object_.NECESARIO === undefined ) Object_.NECESARIO = 0;
        if( Object_.DISABLED === undefined ) Object_.DISABLED = 0;
        if( Object_.LABEL === undefined ) Object_.LABEL = 0;

        if( STR_class != "" ) STR_class += " ";

        if( Object_.CLASS !== undefined ) {
            if( STR_class != "" ) STR_class += " ";
            STR_class += Object_.CLASS;
        }
        if( Object_.NECESARIO )
            inputData = "required='true'";
        if( Object_.MAXLENGTH !== undefined ) {
            if( inputData != "" ) inputData += " ";
            inputData += `maxlength="${Object_.MAXLENGTH}"`;
        }
        Arr = this.constructorNames( names , 'button' );

        STR_funcion = this.constructorFunction( OBJ_funcion , Arr.idElementForm );

        if( STR_funcion !== null ) {
            if( inputData != "" )
                inputData += " ";
            inputData += STR_funcion;
        }
        input = `<input value="${Object_.DEFAULT !== undefined ? Object_.DEFAULT : ''}" ${(Object_["DISABLED"] ? "disabled='true'" : "")} ${Object_.READONLY === undefined ? '' : 'readonly'} ${inputData} name="${Arr.nameElementForm}" id="${Arr.idElementForm}" class="${STR_class} maskMoney" type="text" data-symbol="$ " data-thousands="." data-decimal="," placeholder="${placeholder == "" ? Object_["NOMBRE"] : placeholder}" />`;

        if( Object_.HELP !== undefined )
            input += `<small class="form-text text-muted">${Object_.HELP}</small>`
        if(Object_.LABEL)
            input = `<div class="form-label-group mb-0">${input}<label for="${Arr.idElementForm}" class="form-adm">${placeholder == "" ? Object_["NOMBRE"] : placeholder}</label></div>`;
        if(Object_.FIELDSET !== undefined)
            return `<fieldset><legend>${placeholder == "" ? Object_.NOMBRE : placeholder}</legend>${input}</fieldset>`;
        return input;
    };
    inputColor = (Object_, Arr, OBJ_funcion, placeholder, value = null) => {
        let element = document.createElement("input");
        let hsl = document.createElement("textarea");
        let color = document.createElement("input");
        let label = Object_.LABEL ? this.label(Arr.idElementForm, Object_.NOMBRE) : "";
        let help = Object_.HELP ? this.help(Object_.HELP) : "";
        this.elementAttr(element, Object_);
        element.classList.add("form-control", "form--input");
        hsl.classList.add("form-control", "form--input", "mt-2");
        hsl.textContent = "invert(0%) sepia(1%) saturate(7482%) hue-rotate(185deg) brightness(106%) contrast(100%);";
        if (value !== null) {
            let rgb = hexToRgb(value);
            let color = new Color(rgb[0], rgb[1], rgb[2]);
            let solver = new Solver(color);
            let result = solver.solve();
            hsl.textContent = result.filter;
        }
        hsl.readOnly = true;
        hsl.name = Arr.hslNameElementForm;
        hsl.id = Arr.hslElementForm;
        color.classList.add("form-control", "form--input", "text-right");
        element.type = "color";
        color.type = "text";
        element.name = Arr.nameElementForm;
        element.id = Arr.idElementForm;
        element.pattern = "^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$";
        element.setAttribute("value", value ? value : "#000000");
        element.setAttribute("onchange", "changeColor(this, 'text')");
        color.setAttribute("value", value ? value : "#000000");
        color.setAttribute("onchange", "changeColor(this, 'color')");
        element.placeholder = placeholder === "" ? Object_.NOMBRE : placeholder;
        this.constructorFunction(OBJ_funcion, element);

        return label + `<div class="pyrus--color">${element.outerHTML}${color.outerHTML}</div>` + hsl.outerHTML + help;
    };
    inputText = (Object_, Arr, OBJ_funcion, placeholder) => {
        let element = document.createElement("textarea");
        let label = Object_.LABEL ? this.label(Arr.idElementForm, Object_.NOMBRE) : "";
        let help = Object_.HELP ? this.help(Object_.HELP) : "";
        this.elementAttr(element, Object_);
        if (Object_.MAXLENGTH)
            element.MAXLENGTH = Object_.MAXLENGTH;
        this.constructorFunction(OBJ_funcion, element);
        element.classList.add("form--input", "form-control");
        if (!Object_.NORMAL)
            element.classList.add("ckeditor");
        element.name = Arr.nameElementForm;
        element.id = Arr.idElementForm;
        element.placeholder = placeholder === "" ? Object_.NOMBRE : placeholder;
        return label + element.outerHTML + help;
    };
    async dataRelation(Object_, Arr, OBJ_funcion, value) {
        const data = await Pyrus.relation(Object_.ENTIDAD, Object_.ATTR ? Object_.ATTR : null, Object_.ORDER ? Object_.ORDER : null);
        Object_.ENUM = data.data.data;
        const elem = {TIPO:"TP_ENUM",VISIBILIDAD: Object_.VISIBILIDAD,ENUM:data.data.data,RULE: Object_.RULE, NECESARIO: Object_.NECESARIO, LABEL: Object_.LABEL,NOMBRE:Object_.NOMBRE,CLASS:"form--input", NECESARIO: Object_.NECESARIO, MULTIPLE: Object_.MULTIPLE ? Object_.MULTIPLE : false, NORMAL: Object_.NORMAL};
        let cast = Promise.resolve(this.select(elem, Arr, OBJ_funcion, value));
        cast.then(function(value) {
            const e = document.querySelector(`#${Arr.idElementForm}_target`);
            e.innerHTML = value;
        });
    };
    async dataJoin(Object_, Arr, OBJ_funcion, value) {
        console.log(Object_)
        /*const data = await Pyrus.relation(Object_.ENTIDAD, Object_.ATTR ? Object_.ATTR : null, Object_.ORDER ? Object_.ORDER : null);
        Object_.ENUM = data.data.data;
        const elem = {TIPO:"TP_ENUM",VISIBILIDAD: Object_.VISIBILIDAD,ENUM:data.data.data,RULE: Object_.RULE, NECESARIO: Object_.NECESARIO, LABEL: Object_.LABEL,NOMBRE:Object_.NOMBRE,CLASS:"form--input", NECESARIO: Object_.NECESARIO, MULTIPLE: Object_.MULTIPLE ? Object_.MULTIPLE : false, NORMAL: Object_.NORMAL};
        let cast = Promise.resolve(this.select(elem, Arr, OBJ_funcion, value));
        cast.then(function(value) {
            const e = document.querySelector(`#${Arr.idElementForm}_target`);
            e.innerHTML = value;
        });*/
    };
    select = (Object_, Arr, OBJ_funcion, value) => {
        let element = document.createElement("select");
        let label = Object_.LABEL ? this.label(Arr.idElementForm, Object_.NOMBRE) : "";
        let help = Object_.HELP ? this.help(Object_.HELP) : "";
        this.elementAttr(element, Object_);
        if (Object_.MULTIPLE) {
            element.multiple = true;
            element.dataset.actionsBox = "true";
        }
        this.constructorFunction(OBJ_funcion, element);
        if (Object_.NORMAL === 1) {
            element.classList.add("form-control", "form--input");
            let opt = document.createElement("option");
            opt.value = "";
            opt.text = `- Seleccione ${Object_.NOMBRE} -`;
            opt.hidden = true;
            opt.selected = true;
            element.appendChild(opt);
        } else
            element.classList.add("select__2", "form--input");
        Arr.nameElementForm += Object_["MULTIPLE"] ? "[]" : "";
        element.name = Arr.nameElementForm;
        element.id = Arr.idElementForm;
        element.dataset.width = "100%";
        element.dataset.style = "btn-white";
        element.dataset.liveSearch = true;
        element.dataset.size = 4;
        element.dataset.container = "body";
        element.title = Object_.NOMBRE

        if(Object_.ENUM) {
            Object_.ENUM.forEach(o => {
                let opt = document.createElement("option");
                opt.value = o.id;
                opt.text = o.text;
                if (value) {
                    if (o.id == value)
                        opt.setAttribute("selected", true);
                } else {
                    if (Object_.DEFAULT) {
                        if (o.id == Object_.DEFAULT)
                            opt.setAttribute("selected", true);
                    }
                }
                element.appendChild(opt);
            });
        }
		return label + `<div class="form--input__target">${element.outerHTML}<span></span></div>` + help;
	};
}
