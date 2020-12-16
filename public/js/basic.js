/**
 * @description Funciones básicas. 2020/06/01
 * @author Pablo Corzo <hola@pablocorzo.dev>
 * @version 2.0.0
 **/
/**
 * @description "Colores disponibles en los editores"
 * @param string
 */
const colorPick = "4f9232,808080,111111,191919,fbfb34,a6a6a6,343a40,86008f";
/**
 * @description "Cantidad permitida en MB para archivos"
 * @param number
 */
const max_size_file = 2;
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
const sourceAxios = axios.CancelToken.source();
const publicKey = document.querySelector('meta[name="public-key"]').content;
const url_simple = document.querySelector('meta[name="public-path"]').content;
const entity = document.querySelector('meta[name="entity"]').content;
const url_basic = document.querySelector('meta[name="url"]').content + "/";
const src = `${url_simple}images/no-img.jpg`;
function* fibo() {
    let a = 1;
    let b = 1;
    while(true) {
        const nextNumber = a + b;
        a = b;
        b = nextNumber;
        yield nextNumber;
    }
}

const dates = {
    string: date => {
        if (date === "" || !date)
            return "-";
        const d = dates.convert(date);
        let day = "";
        regexData = /([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/;
        regexDataT = /([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})/;
        let match = null;
        if (date.includes("T"))
            match = regexDataT.exec(date);
        else
            match = regexData.exec(date);
        day = `${match[3]}/${match[2]}/${match[1]}`;
        if (match[4] !== "00")
            day += `${match[4]}:${match[5]}`;
        return [d, day, `${match[1]}-${match[2]}-${match[3]}`];
    },
    convert: d => {
        return (
            d.constructor === Date ? d :
            d.constructor === Array ? new Date( d[ 0 ] , d [ 1 ] , d[ 2 ] ) :
            d.constructor === Number ? new Date( d ) :
            d.constructor === String ? new Date( d ) :
            d.constructor === "object" ? new Date( d.year , d.month , d.date ) :
            NaN
        );
    },
    /**
     * @return -1 if a < b
     * @return 0 if a = b
     * @return 1 if a > b
     */
    compare: ( a , b ) => {
        return ( ( a.getTime() === b.getTime() ) ? 0 : ( ( a.getTime() > b.getTime() ) ? 1 : - 1 ) );
    }
};
alertify.defaults.transition = "slide";
alertify.defaults.theme.ok = "btn btn-primary";
alertify.defaults.theme.cancel = "btn btn-danger";
alertify.defaults.theme.input = "form-control";
/** -------------------------------------
 *      FORMATO MONEDA
 ** ------------------------------------- */
formatter = new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
});
/**
 * @description Menu lateral en zona administrativa
 * @param {Element} t
 * @returns {void}
 */
function menu(t) {
    const target = document.querySelector("#sidebar");
    const icon = t.querySelector("i");
    if (target.classList.contains("compact")) {
        localStorage.removeItem("sidebar");
        target.classList.remove("compact");
    } else {
        localStorage.setItem("sidebar", 1);
        target.classList.add("compact");
    }
    icon.classList.toggle("fa-bars");
    icon.classList.toggle("fa-times");
}
/**
 * @description Los cambios de CKEDITOR son transladados a su textarea correspondiente
 * @param {*} x
 * @param {Event} evt
 * @returns {void}
 */
function changeCkeditor( x , evt ) {
    const target = document.querySelector(`#${evt.editor.name}`);
    target.value = CKEDITOR.instances[evt.editor.name].getData();
};
/**
 * @description Menu responsive zona pública
 * @param {Element} t
 * @returns {void}
 */
function menuBody(t) {
    const section = document.querySelector("section");
    const header = document.querySelector("header");
    const footer = document.querySelector("footer");
    const hamburger1 = document.querySelector("#hamburger-");
    const hamburger2 = document.querySelector("#hamburger");
    const menu = document.querySelector("#wrapper-menu");
    if( window.typeMENU === undefined ) {
        window.typeMENU = 1;
        section.classList.add("isDisabled");
        header.classList.add("isDisabled");
        footer.classList.add("isDisabled");
        hamburger1.classList.remove("d-none");
        hamburger2.classList.add("open");
        menu.animate([
            { transform: 'translateX(0px)' },
            { transform: 'translateX(-300px)' }
        ], {
            fill: "forwards",
            duration: 600,
        });
    } else {
        delete window.typeMENU;
        section.classList.remove("isDisabled");
        header.classList.remove("isDisabled");
        footer.classList.remove("isDisabled");
        hamburger1.classList.add("d-none");
        hamburger2.classList.remove("open");
        menu.animate([
            { transform: 'translateX(-300px)' },
            { transform: 'translateX(0px)' }
        ], {
            fill: "forwards",
            duration: 600,
        });
    }
}
//---
navMenu = ( t ) => {
    if( $( ".app-body.isDisabled").length )
        $( ".app-body.isDisabled").removeClass( "isDisabled" );
    else
        $( ".app-body").addClass( "isDisabled" );
};
/**
 * @description "CAMBIAR COLORES"
 * @param {Element} t
 * @param {string} type
 * @returns {void}
 */
function changeColor(t, type) {
    const target = t.closest(".pyrus--color");
    const value = t.value;
    target.querySelector(`[type="${type}"]`).value = value;

    let rgb = hexToRgb($(t).val());
    let color = new Color(rgb[0], rgb[1], rgb[2]);
    let solver = new Solver(color);
    let result = solver.solve();
    target.nextElementSibling.value = result.filter;
}
/**
 * @param {String} hex
 * @returns {[]}
 */
function hexToRgb(hex) {
    const shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, (m, r, g, b) => {
        return r + r + g + g + b + b;
    });

    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result
        ? [
        parseInt(result[1], 16),
        parseInt(result[2], 16),
        parseInt(result[3], 16),
        ]
        : null;
}
/** -------------------------------------
 *      MOSTRAR COMBINACIONES DE TECLAS
 ** ------------------------------------- */
/**
 * @param {Element} t
 * @returns {void}
 */
function showCombinacion(t) {
    const target = document.querySelector("#modalCombinacion");
    $(target).modal("show");
};
/**
 * @description Copiar URL de la imagen
 * @param {Element} t
 * @param {String} url
 * @returns {void}
 */
function copy(t, url) {
    const temp = document.createElement("input");
    temp.setAttribute("value", url);
    document.querySelector("body").appendChild(temp);
    temp.select()
    document.execCommand("copy");
    temp.remove();
    Toast.fire({
        icon: 'success',
        title: 'URL de imagen copiada'
    });
}
/**
 * @description "Cerrar formulario con alerta"
 * @param {Element} t
 * @returns {void}
 */
function remove(t) {
    const modal = document.querySelector("#formModal");
    const elementsNo = document.querySelector(".no--send");
    const entidad = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
    $('[data-toggle="tooltip"]').tooltip('hide');
    if (elementsNo) {
        entidad.clean();
        $(modal).modal("hide");
    } else {
        Swal.fire({
            title: '¿Cerrar sin guardar registro?',
            text: "Perderá la información añadida",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar'
        }).then(result => {
            if (result.value) {
                entidad.clean();
                if (modal)
                    $(modal).modal("hide");
                add(null);
                if (data.values_form !== undefined) {
                    data.values_form.forEach(x => {
                        const e = document.querySelector(`#${x.id}`);
                        if (e)
                            e.value = x.value;
                    });
                }
            }
        })
    }
};
/**
 * @description Quita elemento múltiple
 * @param {Element} t
 * @param {String} class_
 * @returns {void}
 */
function remove_(t, class_) {
    let target =  t.closest(`.${class_}`);
    let img = target.querySelector(".imgURL");
    Swal.fire({
        title: "Atención!",
        text: "¿Eliminar elemento?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            if (window.formAction === "UPDATE") {
                if (!window.imgDelete)
                    window.imgDelete = [];
                if (img) {
                    if (img.value !== "")
                        window.imgDelete.push(img.value);
                }
                Toast.fire({
                    icon: 'warning',
                    title: 'Debe guardar el contenido para ver los cambios'
                })
            }
            target.remove();
        }
    });
}
/**
 * @description Editar un registro
 * @param {Element} t
 * @param {Number} id
 * @param {Number} disabled
 * @returns {void}
 */
function edit(t, id, disabled = 0) {
    const entidad = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
    t.disabled = true
    entidad.one(`${url_simple}${url_basic}${entidad.tableDB}/${id}`, res => {
        $('[data-toggle="tooltip"]').tooltip('hide');
        t.disabled = false;
        add(null, parseInt(id), res.data, disabled);
    }, err => {
        console.error(err)
    });
}
/**
 * @description Función pública para eliminar registros
 * @param {Element} t
 * @param {Number} id
 * @returns {void}
 */
function erase(t, id) {
    const entidad = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
    entidad.delete(t, {title: "ATENCIÓN", body: "¿Eliminar registro?"}, `${url_simple}${url_basic}${entidad.tableDB}/${id}`);
}
/**
 * @description Clonar un registro. Trae la información que corresponda
 * @param {Element} t
 * @param {Number} id
 * @param {Number} disabled
 * @returns {void}
 */
function clone(t, id, disabled = 0) {
    const entidad = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
    t.disabled = true
    entidad.one(`${url_simple}${url_basic}${entidad.tableDB}/${id}/edit`, res => {
        $('[data-toggle="tooltip"]').tooltip('hide');
        t.disabled = false;
        add(null, parseInt(id), res.data, disabled, true);
    }, err => {
        console.error(err)
    });
}
/**
 * @description Ver registro
 * @param {Element} t
 * @param {Number} id
 * @returns {void}
 */
function see(t, id) {
    edit(t, id, 1);
}
/** -------------------------------------
 *      PREVIEW DE IMAGEN
 ** ------------------------------------- */
/**
 * @description Preview de la imagen cargada y su nombre
 * @param {Element} t
 * @param {string} id
 * @returns {void}
 */
function readURL(t, id = null) {
    const img = id ? document.querySelector(`#${id}`) : null;
    if (t.files && t.files[0]) {
        let reader = new FileReader();
        reader.onload = ( e ) => {
            const size = Math.round(t.files[0].size / 1024 /1024);
            if (id) {
                if (max_size_file < size) {
                    img.src = "";
                    img.classList.remove("image--upload__validate");
                    t.parentElement.classList.remove("image--upload__not-empty");
                    t.parentElement.classList.add("image--upload__no-validate");
                    t.nextSibling.dataset.name = `El archivo supera el máximo permitido ${max_size_file}MB`;
                    return null;
                }
                img.src = e.target.result;
                img.classList.add("image--upload__validate");
            }
            let image = new Image();
            image.src = e.target.result;
            image.onload = function() {
                switchImage(this, img);
            }
            t.parentElement.classList.add("image--upload__not-empty");
            t.nextElementSibling.dataset.name = `${t.files[0].name} ~ ${size}MB`;
        };
        reader.readAsDataURL(t.files[0]);
    } else {
        if (id) {
            img.src = "";
            img.classList.remove("image--upload__validate");
        }
        img.style.backgroundColor = null;
        t.parentElement.classList.remove("image--upload__not-empty");
        t.nextElementSibling.dataset.name = "No se selccionó ningún archivo";
    }
    t.parentElement.classList.remove("image--upload__no-validate");
}
/**
 * @param {Image} imagen
 * @returns {[]}
 */
function getaverageColor(imagen) {
    let r=0, g=0, b=0, count = 0, canvas, ctx, imageData, data, i;
    canvas = document.createElement('canvas');
    ctx = canvas.getContext("2d");
    canvas.width = imagen.width;
    canvas.height = imagen.height;
    ctx.drawImage(imagen, 0, 0);
    imageData = ctx.getImageData(0, 0, imagen.width, imagen.height);
    data = imageData.data;
    for(i = 0, n = data.length; i < n; i += 4) {
        ++count;
        r += data[i];
        g += data[i+1];
        b += data[i+2];
    }
    r = ~~(r/count);
    g = ~~(g/count);
    b = ~~(b/count);
    return [r, g, b];
}
/**
 * @param {[]} arr
 * @returns {String}
 */
function rgbToHex(arr) {
    return "#" + ((1 << 24) + (arr[0] << 16) + (arr[1] << 8) + arr[2]).toString(16).slice(1);
}
/**
 * @param {Image} image
 * @param {Element} target
 * @returns {void}
 */
function switchImage(image, target) {
    let averagecolor = getaverageColor(image);
    let color = rgbToHex(averagecolor);
    target.style.backgroundColor = color;
}
/**
 * @description Para checkbox
 * @param {Element} input
 * @returns {void}
 */
function check(input) {
    if (input.checked)
        input.nextSibling.value = 1;
    else
        input.nextSibling.value = 0;
}
/**
 * @description Guarda información
 * @param {Element} t
 * @param {FormData} formData
 * @param {{}} message
 * @param {Function} callback
 * @returns {void}
 */
function formSave(t, formData, message = { wait : "Espere. Guardando contenido" , err: "Ocurrió un error en el guardado. Reintente" , catch: "Ocurrió un error en el guardado." , success : "Contenido guardado" }, callback = null) {
    const entidad = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
    let url = t.action;
    let method = t.method;
    if (!verificarForm())
        return null;
    if (window.formAction === "UPDATE")
        method = "POST";
    document.querySelector(".wrapper").classList.add("isDisabled");
    window.Arr_aux = [];
    Toast.fire({
        icon: 'warning',
        title: message.wait
    })
    if( window.imgDelete !== undefined )
        formData.append("REMOVE", JSON.stringify(window.imgDelete));
    axios({
        method: method,
        url: url,
        data: formData,
        responseType: 'json',
        config: { headers: {'Content-Type': 'multipart/form-data' }}
    })
    .then(res => {
        if (callback === null) {
            document.querySelector(".wrapper").classList.remove("isDisabled");
            if (res.data.error === 0) {
                if (window.refresh) {
                    location.reload();
                    return null;
                }
                const elem = res.data.data;
                let tr_target = document.querySelector(`tr[data-id='${elem.id}']`);
                let tr_index = 0;
                let tr = null;
                if (tr_target)
                    tr_index = tr_target.rowIndex - 1;
                Toast.fire({
                    icon: 'success',
                    title: message.success
                });
                tr = entidad.row(elem, url_simple, window.tbody_pyrus, elem.id, window.button_pyrus[0], window.button_pyrus[1]);
                if (window.formAction !== "UPDATE") {
                    if (Array.isArray(window.data.elements)) {
                        window.td_pyrus.push(tr);
                        window.tbody_pyrus.appendChild(tr);
                    } else {
                        if (!window.total_elements_pyrus)
                            window.total_elements_pyrus = window.data.elements.total;
                        window.total_elements_pyrus ++;
                        if (window.tbody_pyrus.childElementCount !== window.data.elements.per_page)
                            window.tbody_pyrus.appendChild(tr);
                        if (window.total_elements_pyrus % window.data.elements.per_page === 0) {
                            Toast.fire({
                                icon: 'warning',
                                title: 'Espere. Recargando vista'
                            });
                            setTimeout(() => {
                                location.reload();
                            });
                            return null;
                        }
                    }
                } else {
                    if (tr_target) {
                        tr_target.replaceWith(tr);
                        window.td_pyrus[tr_index] = tr;
                    }
                }
                const edit__check = tr.querySelectorAll(".edit--check");
                if (edit__check.length > 0) {
                    Array.prototype.forEach.call(edit__check, e => {
                        e.addEventListener("click", editElement);
                    })
                }
                entidad.clean();
                if( $("#formModal").length )
                    $("#formModal").modal( "hide" );
            } else if (res.data.msg) {
                Toast.fire({
                    icon: 'error',
                    title: res.data.msg
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: message.err
                });
            }
        } else
            callback.call(this, res.data);
    })
    .catch(err => {
        console.error(err);
        document.querySelector(".wrapper").classList.remove("isDisabled");
        Swal.fire(
            'Atención',
            message.catch,
            'error'
        );
    });
}
/**
 * @description Valida el formulario
 * @returns {Boolean}
 */
function verificarForm() {
    if (!window.pyrus)
        return true;
    if (!Array.isArray(window.pyrus)) {
        if( window.pyrus.objeto.NECESARIO !== undefined ) {
            flag = 0;
            alert = "";
            for( let x in window.pyrus.objeto.NECESARIO ) {
                if( window.pyrus.objeto.NECESARIO[ x ][ window.formAction ] !== undefined ) {
                    if( $(`#${window.pyrus.name}_${x}`).length) {
                        if( $(`#${window.pyrus.name}_${x}`).is( ":invalid" ) || $(`#${window.pyrus.name}_${x}`).val() == "" ) {
                            if( alert != "" )
                                alert += ", ";
                            alert += window.pyrus.especificacion[ x ].NOMBRE;
                            flag = 1;
                        }
                    }
                }
            }
            if( flag ) {
                Swal.fire(
                    'Atención',
                    `Complete los siguientes campos: ${alert}`,
                    'error'
                )
                return false;
            }
            return true;
        }
    } else {
        flag = 0;
        let alert = "";
        window.pyrus.forEach(p => {
            if( p.entidad.objeto.NECESARIO !== undefined ) {
                for( let x in p.entidad.objeto.NECESARIO ) {
                    if( p.entidad.objeto.NECESARIO[ x ][ window.formAction ] !== undefined ) {
                        if( $(`#${p.entidad.name}_${x}`).is( ":invalid" ) || $(`#${p.entidad.name}_${x}`).val() == "" ) {
                            if( alert != "" )
                                alert += ", ";
                            alert += p.entidad.especificacion[ x ].NOMBRE;
                            flag = 1;
                        }
                    }
                }
                if( flag )
                    Swal.fire(
                        'Atención',
                        `Complete los siguientes campos: ${alert}`,
                        'error'
                    )
            }
        });
        if( flag )
            return false;
    }
    return true
};
/**
 * @description Prepara objeto a guardar
 * @param {Element} t
 * @returns {void}
 */
function formSubmit(t) {
    let idForm = t.id;
    let formElement = document.getElementById( idForm );
    let formData = new FormData( formElement );
    let Arr = [];
    if (Array.isArray(window.pyrus)) {
        window.pyrus.forEach(p => {
            let target = document.querySelector(`.form_${p.entidad.entidad}`);
            if (target) {
                let aux = {};
                aux["DATA"] = p.entidad.objetoSimple;
                aux["TIPO"] = p.tipo;
                if (p.column)
                    aux["COLUMN"] = p.column;
                if (p.tag)
                    aux["TAG"] = p.tag;
                if (p.key)
                    aux["KEY"] = p.key;
                Arr.push(aux);
            } else {
                if (p.column)
                    Arr.push({EMPTY: p.column});
            }
        });
    } else
        Arr.push({ DATA: window.pyrus.objetoSimple , TIPO: "U" });
    formData.append("ATRIBUTOS",JSON.stringify(Arr));
    formSave(t, formData);
}
/** -------------------------------------
 *      ABRIR FORMULARIO
 ** ------------------------------------- */
/**
 * @param {Element} t
 * @param {Number} id
 * @param {JSON} data
 * @param {Number} disabled
 * @param {Boolean} clone
 * @returns {void}
 */
function add(t, id = 0, data = null, disabled = 0, clone = false) {
    const entidad = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
    if (!entidad.objeto) {
        Toast.fire({
            icon: 'error',
            title: "La entidad no fue definida"
        });
        return null;
    }
    const modal = document.querySelector("#formModal");
    let label = document.querySelector("#formModalLabel");
    let form = document.querySelector("#form");
    let action = `${url_simple}${url_basic}${entidad.tableDB}`;
    if (disabled) {
        let form_control = form.querySelectorAll(".form-control");
        Array.prototype.forEach.call(form_control, f => {
            f.disabled = true;
        });
        label.textContent = "Ver elemento";
        if ($("#form .select__2").length)
            $("#form .select__2").selectpicker('destroy');
        form.classList.add("no--send");
    } else {
        let form_control = form.querySelectorAll(".form-control");
        Array.prototype.forEach.call(form_control, f => {
            f.disabled = false;
        });
        let method = "POST";
        form.classList.remove("no--send");
        if ($("#form .select__2").length)
            $("#form .select__2").selectpicker('refresh');
        window.formAction = "CREATE";
        window.elementData = data;
        if (label)
            label.textContent = "Nuevo elemento";
        if(id != 0) {
            if (!clone) {
                if (label)
                    label.textContent = "Editar elemento";
                action += `/${id}`;
                method = "POST";
                form.dataset.id = id;
                window.formAction = "UPDATE";
            } else
                window.formAction = "CLONE";
        }
        form.action = action;
        form.method = method;
    }
    if (Array.isArray(window.pyrus) && data !== null) {
        window.pyrus.forEach(p => {
            switch (p.tipo) {
                case "U":
                    if (p.column) {
                        if (data[p.column])
                            p.entidad.show(url_simple, data[p.column]);
                    } else
                        p.entidad.show(url_simple, data);
                break;
                case "A":
                case "M":
                    if (data[p.column])
                        data[p.column].forEach(a => {
                            const func = new Function(`${p.function}Function(${JSON.stringify(a)})`);
                            func.call(null);
                        });
                break;
            }
        });
        // window.pyrus.forEach(e => e.entidad.show(url_simple, data));
    } else
        entidad.show(url_simple, data);
    if (window.data.hidden)
        window.data.hidden.forEach(a => {
            let e = document.querySelector(`#${entidad.entidad}_${a.attr}`);
            if (e)
                e.value = a.value;
        });
    $(modal).modal("show");

    try {
        addfinish(data);
    } catch (error) {}
}
/** -------------------------------------
 *      ELIMINAR ARCHIVO
 ** ------------------------------------- */
/**
 * @param {Element} t
 * @returns {void}
 */
removeFile = (t) => {
    const attr = {
        file: t.dataset.url,
        entidad: t.dataset.entidad,
        attr: t.dataset.attr,
        column: t.dataset.column ? t.dataset.column : null,
        id: t.dataset.id ? t.dataset.id : null,
        tabla: t.dataset.table,
        idPadre: window.data.elements.id
    };
    deleteFile(t, `${url_simple}${url_basic}file`, "¿Eliminar archivo de imagen?", attr, data => {
        if (data.error === 0) {
            t.parentElement.previousElementSibling.src = "";
            let details = t.parentElement.previousElementSibling.previousElementSibling.querySelectorAll(".image--wh__details");
            Array.prototype.forEach.call(details, d => d.remove());
            Toast.fire({
                icon: 'success',
                title: data.msg
            })
        } else {
            Toast.fire({
                icon: 'error',
                title: data.msg
            })
        }
    }, err => {
        console.error(err)
    });
}
/**
 * @param {Element} t
 * @param {string} url
 * @param {string} txt
 * @param {{}} data
 * @param {function} callbackOK
 * @param {function} callbackFail
 * @returns {void}
 */
function deleteFile(t, url, txt, data, callbackOK = null, callbackFail = null) {
    t.disabled = true;
    Swal.fire({
        title: "Atención!",
        text: txt,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            axios.delete(url, {
                data: data
            })
            .then(res => {
                if (callbackOK)
                    callbackOK.call(this, res.data);
                else {
                    t.disabled = false;
                    if (res.data.error === 0) {
                        Toast.fire({
                            icon: 'success',
                            title: res.data.msg
                        })
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: res.data.msg
                        })
                    }
                }
            })
            .catch(err => {
                if (callbackFail) {
                    callbackFail.call(this, res);
                    return null;
                }
                alertify.error("Ocurrió un error");
                t.disabled = false;
                console.error(err);
                console.error(`ERROR en ${url}`);
            })
            .then(() => {});
        } else
            t.disabled = false;
    });
}
/** -------------------------------------
 *      COMBINACIÓN DE TECLAS
 ** ------------------------------------- */
shortcut.add( "Alt+Ctrl+S" , function () {
    const form = document.querySelector("#form");
    if (form)
        formSubmit(form)
}, {
    type: "keydown",
    propagate: true,
    target: document
});
shortcut.add( "Alt+Ctrl+N" , function () {
    if( $( "#btnADD" ).length ) {
        if( !$( "#form" ).is( ":visible" ) )
            $( "#btnADD" ).click();
        else
            remove( null );
    }
}, {
    type: "keydown",
    propagate: true,
    target: document
});
shortcut.add( "Alt+Ctrl+Q" , function () {
    //window.location = `${url_simple}${url_basic}url`;
}, {
    type: "keydown",
    propagate: true,
    target: document
});
shortcut.add( "Alt+Ctrl+C" , function () {
    window.location = `${url_simple}${url_basic}comentarios`;
}, {
    type: "keydown",
    propagate: true,
    target: document
});
/** -------------------------------------
 *      INICIO
 ** ------------------------------------- */
/**
 * @param {Element} el
 * @returns {void}
 */
function getPosition(el) {
    var xPos = 0;
    var yPos = 0;
    while (el) {
        if (el.tagName == "BODY") {
            var xScroll = el.scrollLeft || document.documentElement.scrollLeft;
            var yScroll = el.scrollTop || document.documentElement.scrollTop;
            xPos += (el.offsetLeft - xScroll + el.clientLeft);
            yPos += (el.offsetTop - yScroll + el.clientTop);
        } else {
            xPos += (el.offsetLeft - el.scrollLeft + el.clientLeft);
            yPos += (el.offsetTop - el.scrollTop + el.clientTop);
        }
        el = el.offsetParent;
    }
    return {
        x: xPos,
        y: yPos
    };
}
/**
 * @param {*} evt
 * @returns {void}
 */
function elementFocus(evt) {
    this.previousElementSibling.classList.add("form--label__active");
}
/**
 * @param {*} evt
 * @returns {void}
 */
function elementBlur(evt) {
    this.previousElementSibling.classList.remove("form--label__active");
}
/**
 * @param {Element} t
 * @returns {void}
 */
function saveEdit(t) {
    t.disabled = true;
    let formData = new FormData(t.parentElement.previousElementSibling);
    formData.append("table", t.dataset.table);
    formData.append("key", t.dataset.key);
    formData.append("id", t.dataset.id);
    formData.append("ATRIBUTOS",JSON.stringify([{ DATA: window.entidad_eventual.objetoSimple , TIPO: "U" }]));
    axios({
        method: "post",
        url: `${url_simple}${url_basic}edit`,
        data: formData,
        responseType: 'json',
        config: { headers: {'Content-Type': 'multipart/form-data' }}
    })
    .then((res) => {
        if(res.data.error === 0) {
            Toast.fire({
                icon: 'success',
                title: 'Guardado'
            });
            columna = window.entidad_eventual.columnas().find(c => {
                if(c.COLUMN == t.dataset.key)
                    return c;
            });
            let td = document.createElement("td");
            td.style.maxWidth = "500px";
            window.td_eventual.innerHTML = window.entidad_eventual.convert(res.data.obj[t.dataset.key],
                td,
                url_simple,
                window.entidad_eventual.especificacion[t.dataset.key].TIPO,
                window.entidad_eventual.especificacion[t.dataset.key],
                null,
                columna,
                res.data.obj.id
                ).innerHTML;
            const edit__check = window.td_eventual.querySelectorAll(".edit--check");
            if (edit__check.length > 0) {
                Array.prototype.forEach.call(edit__check, e => {
                    e.addEventListener("click", editElement);
                })
            }
            removeEdit(t);
        } else  {
            Toast.fire({
                icon: 'error',
                title: 'Error'
            })
        }
    })
    .catch((err) => {
        console.error( `ERROR en ${url}` );
        alertify.error( "Error" );
    })
    .then(() => {});
}
/**
 * @param {Element} t
 * @returns {void}
 */
function removeEdit(t) {
    let e = t.closest(".pyrus--edit__check");
    e.remove();
    delete window.entidad_eventual;
    delete window.td_eventual;
}
/**
 * @param {*} evt
 * @returns {void}
 */
function editElement(evt) {
    let e = document.querySelector(".pyrus--edit__check");
    if (e)
        e.remove();
    const td = this.closest("td");
    const pos = getPosition(td);
    const w = td.cellIndex === 0 ? (td.offsetWidth * -1) : 250;
    const h = pos.y + td.offsetHeight;
    let name = this.dataset.name;
    let column = this.dataset.column;
    let value = this.dataset.value;
    let div = document.createElement("div");
    let entidad = null;
    if (Array.isArray(window.pyrus))
        entidad = window.pyrus.find(e => {
            if(e.entidad.name == name)
                return e;
        }).entidad;
    else
        entidad = window.pyrus;
    window.entidad_eventual = entidad;
    window.td_eventual = this.closest("td");
    div.classList.add("p-2", "pyrus--edit__check", "shadow")
    div.setAttribute("style", `left: calc(${pos.x}px - ${w}px); bottom: calc(100% - ${h}px)`);
    div.innerHTML = '<h3 class="pyrus--edit__title">Edición del campo<button type="button" class="close" onclick="removeEdit(this);"><span aria-hidden="true">&times;</span></button></h3>';
    div.innerHTML += `<form onsubmit="event.preventDefault();">${entidad.elementForm(column, value)}</form>`;
    div.innerHTML += `<div class="d-flex justify-content-end border-top mt-2 pt-2"><button onclick="saveEdit(this);" data-table="${this.dataset.name}" data-key="${this.dataset.column}" data-id="${this.dataset.id}" class="btn btn-sm button--form btn-primary" type="button"><i class="fas fa-save"></i></button></div>`;
    document.querySelector("body").appendChild(div);
}
/**
 * @param {*} evt
 * @returns {void}
 */
function editable(evt) {
    this.contentEditable = true;
}
/**
 * @param {*} evt
 * @returns {void}
 */
function editableSave(evt) {
    this.contentEditable = false;
    let formData = new FormData();
    formData.set("table", this.dataset.name);
    formData.set("key", this.dataset.column);
    formData.set("value", this.textContent);
    formData.set("id", this.dataset.id);
    axios({
        method: "post",
        url: `${url_simple}${url_basic}edit`,
        data: formData,
        responseType: 'json',
        config: { headers: {'Content-Type': 'multipart/form-data' }}
    })
    .then((res) => {
        if(res.data.error === 0) {
            Toast.fire({
                icon: 'success',
                title: 'Guardado'
            })
        } else  {
            Toast.fire({
                icon: 'error',
                title: 'Error'
            })
        }
    })
    .catch((err) => {
        console.error( `ERROR en ${url}` );
        alertify.error( "Error" );
    })
    .then(() => {});
}
/**
 *
 * @param {String} table
 * @param {String} name
 */
function generateExcel(table, name) {
    var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
            , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }

    if (!table.nodeType)
        table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    let downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = uri + base64(format(template, ctx));
    downloadLink.download = name;
    downloadLink.click();
}
/**
 * @param {Element} targetForm
 * @returns {void}
 */
function editor(targetForm) {
    if (Array.isArray(window.pyrus)) {
        window.pyrus.forEach(p => {
            targetForm.innerHTML += p.entidad.formulario();
            const target = document.querySelector(`.form_${p.entidad.entidad}`);
            if (target) {
                const ck = target.querySelector(".ckeditor");
                if (ck) {
                    setTimeout(() => {
                        p.entidad.editor();
                    }, 500);
                }
            }
        });
    } else {
        targetForm.innerHTML = window.pyrus.formulario();
        const ck = document.querySelector(".ckeditor");
        if (ck)
            window.pyrus.editor();
    }
}
/**
 * @property {Function} callbackOK
 * @property {Boolean} normal
 * @property {Boolean} widthElements
 * @property {string} type
 * @property {Boolean} withAction
 * @property {[]} btn
 * @property {[]} btnsAdd
 * @property {boolean} refresh
 * @returns {void}
 */
function init(callbackOK, normal = true, widthElements = true, type = "table", withAction = true, btn = ["e" , "d"], btnsAdd = null, refresh = false) {
    window.targetForm = document.querySelector(".pyrus--form");
    window.refresh = refresh;
    let targetElements = document.querySelector("#wrapper-tabla");
    const entidad = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
    if (window.targetForm)
        editor(window.targetForm);
    else
        withAction = false;
    if (normal) {
        if ((withAction || btnsAdd !== null) && entidad.withForm)
            targetElements.appendChild(entidad.table([{NAME: "-", COLUMN: "acciones", WIDTH: "100px"}]));
        else {
            btn = [];
            targetElements.appendChild(entidad.table());
        }
        entidad.editor();
        if (btnsAdd !== null) {
            /*btnsAdd.forEach(b => {
                const button = document.createElement("button");
                button.classList.add(b.class, "btn", "text-center", "button--form");
                button.innerHTML = `${b.icon} ${b.title}`;
                button.disabled = true;
                if (document.querySelector("#btnADD"))
                    document.querySelector("#btnADD").nextElementSibling.appendChild(button);
                else
                    document.querySelector("section").querySelector(".container-fluid").querySelector("div").appendChild(button);
            });*/
        }
        window.button_pyrus = [btn, btnsAdd];
        if (widthElements) {
            if (type == "table")
                entidad.elements("#tabla" , url_simple, window.data.elements, btn, btnsAdd);
            else
                targetElements.innerHTML = entidad.card(url_simple, window.data.elements, btn);
        }
    }
    //---------------------
    const edit__text = document.querySelectorAll(".edit");
    const edit__check = document.querySelectorAll(".edit--check");
    if (edit__text.length > 0) {
        Array.prototype.forEach.call(edit__text, e => {
            e.addEventListener("dblclick", editable);
            e.addEventListener("blur", editableSave);
        })
    }
    if (edit__check.length > 0) {
        Array.prototype.forEach.call(edit__check, e => {
            e.addEventListener("click", editElement);
        })
    }
    callbackOK.call(this, [window.targetForm, targetElements]);
}