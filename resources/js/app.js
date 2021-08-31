require('./bootstrap');

import axios from 'axios';
import swal from 'sweetalert';
import Swal from 'sweetalert2';
import Choices from 'choices.js';
import bootstrapSelect from 'bootstrap-select';
import Splide from '@splidejs/splide';

window.time = new Date().getTime();
const formatter = new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
});
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
});

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};
const darkMode = function(t) {
    axios.post(document.querySelector('meta[name="type"]').content, {
        darkmode: 1,
        status: document.body.classList.contains("dark-mode")
    })
    .then(function (res) {
        if (res.data.status) {
            t.innerHTML = '<i class="fas fa-moon"></i>Activar modo oscuro'
            document.body.classList.remove("dark-mode");
        } else {
            t.innerHTML = '<i class="far fa-moon"></i>Desactivar modo oscuro'
            document.body.classList.add("dark-mode");
        }
    });
};

window.Ventor = {
    showNotification: function(text = "En proceso") {
        document.querySelector('#notification').classList.remove('d-none');
        document.querySelector('#notification').classList.add('d-flex');
        document.querySelector('#notification .notification--text').innerText = text;
    },
    hideNotification: function() {
        document.querySelector('#notification').classList.remove('d-flex');
        document.querySelector('#notification').classList.add('d-none');
        document.querySelector('#notification .notification--text').innerText = '';
    },
    syncProduct: function() {
        window.Ventor.showNotification('Sincronizando productos');
        axios.post(document.querySelector('meta[name="cart"]').content)
        .then(function (res) {
            window.Ventor.hideNotification();
            document.querySelector(".btn-cart_product").dataset.total = res.data.elements;
            window.Ventor.cartBody(res.data.html + res.data.totalHtml);
        });
    },
    cartPrice: function(t, isHeader = false) {
        const TARGET = this;
        if (TARGET.classList.contains('number--header')) {
            isHeader = true;
        }
        const { id } = TARGET.dataset;
        const value = TARGET.value;
        if (value == '0') {
            window.Ventor.deleteItem(id, isHeader);
            return;
        }
        window.Ventor.confirmProduct(id, value, isHeader);
    },
    cartBody: function(html) {
        document.querySelector(".header__cart .dropdown-menu").innerHTML = html;

        const header__product__amount = document.querySelectorAll(".header__cart__element .price input");
        if (header__product__amount.length > 0) {
            document.querySelector('.button__cart.button__cart--clear').addEventListener('click', window.Ventor.clearCart);
            document.querySelector('.button__cart.button__cart--end').addEventListener('click', window.Ventor.confirmCart);
            Array.prototype.forEach.call(header__product__amount, i => i.addEventListener('change', window.Ventor.cartPrice));
        }
    },
    confirmProduct: function(_id, quantity, isHeader = false) {
        window.Ventor.showNotification();
        let id = _id.replaceAll(' ', '_');
        if (document.querySelector(`#th--${id}`) && document.querySelector(`#th--${id}`).classList.contains('bg-dark')) {
            document.querySelector(`#th--${id}`).classList.remove('bg-dark');
            document.querySelector(`#th--${id}`).classList.add('bg-success');
        }
        axios.post(document.querySelector('meta[name="cart"]').content, {
            price: 1,
            _id,
            quantity,
            noticeClient: localStorage.noticeClient !== undefined ? localStorage.noticeClient == "1" : null
        })
        .then(function (response) {
            let {data} = response;
            let id = _id.replaceAll(' ', '_');
            window.Ventor.hideNotification();
            if (data.error == 0) {
                if (isHeader && document.querySelector(`.cart__product__amount[data-id='${id}']`)) {

                    document.querySelector(`.cart__product__amount[data-id='${id}']`).value = quantity;

                }
                if (document.querySelector('#cart__select') && data.cart.options !== null) {

                    document.querySelector('#cart__select').innerHTML = data.cart.options;

                }
                document.querySelector(".btn-cart_product").dataset.total = data.elements;
                window.Ventor.cartBody(data.cart.html + data.cart.totalHtml);
            } else {
                if (document.querySelector(`#th--${id}`) && document.querySelector(`#th--${id}`).classList.contains('bg-success')) {
                    document.querySelector(`#th--${id}`).classList.remove('bg-success');
                    document.querySelector(`#th--${id}`).classList.add('bg-dark');
                }
            }
        });
    },
    deleteItem: function(_id, isHeader = false) {
        let id = _id.replaceAll(' ', '_');
        if (document.querySelector(`#th--${id}`) && document.querySelector(`#th--${id}`).classList.contains('bg-success')) {
            document.querySelector(`#th--${id}`).classList.remove('bg-success');
            document.querySelector(`#th--${id}`).classList.add('bg-dark');
        }
        axios.post(document.querySelector('meta[name="cart"]').content, {
            _id,
            noticeClient: localStorage.noticeClient !== undefined ? localStorage.noticeClient == "1" : null
        })
        .then(function (res) {
            if (res.data.error === 0) {
                if (isHeader && document.querySelector(`.cart__product__amount[data-id='${id}']`))
                    document.querySelector(`.cart__product__amount[data-id='${id}']`).value = '0';
                document.querySelector(".btn-cart_product").dataset.total = res.data.elements;
                window.Ventor.cartBody(res.data.cart.html + res.data.cart.totalHtml);
            } else {
                if (document.querySelector(`#th--${id}`) && document.querySelector(`#th--${id}`).classList.contains('bg-dark')) {
                    document.querySelector(`#th--${id}`).classList.remove('bg-dark');
                    document.querySelector(`#th--${id}`).classList.add('bg-success');
                }
            }
        });
    },
    download: function(id, link) {
        window.Ventor.showNotification();
        axios.get(document.querySelector('meta[name="url"]').content + "/track_download/" + id)
        .then(function (res) {
            window.Ventor.hideNotification();
            if (res.data.error === 0) {
                link.click();
                link.remove();
            } else {
                swal("Atención!", res.data.msg, "error",{
                    buttons: {
                        cerrar: true,
                    },
                });
            }
        })
        .catch(err => {
            window.Ventor.hideNotification();
            link.remove();
            swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${name}`, "error",{
                buttons: {
                    cerrar: true,
                },
            });
        });
    },
    downloadsTrack: function(t) {
        let index = this.selectedIndex;
        let file = this.options[index].text;
        let { id, name, time = null } = this.dataset;
        let txt = name + ` [${file}]`;
        let link = document.createElement("a");
        if (this.value == "" && time !== null) {
            //Esta logueado, pero elige un select vacío
            return;
        }
        if (this.value == "") {
            swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
                buttons: {
                    cerrar: true,
                },
            });
            return;
        }
        link.href = document.querySelector('meta[name="url"]').content + '/' + this.value;
        link.download = this.options[index].dataset.name;
        window.Ventor.download(id, link);
    },
    notFile: function(evt) {
        evt.preventDefault();
        let { name } = this.dataset;
        swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${name}`, "error",{
            buttons: {
                cerrar: true,
            },
        });
    },
    downloadTrack: function(evt) {
        evt.preventDefault();
        let { id, name, href } = this.dataset;
        let link = document.createElement("a");
        link.href = href;
        link.download = name;
        window.Ventor.download(id, link);
    },
    confirmCart: function() {
        if ($("#clientList").val() == "") {
            Toast.fire({
                icon: 'error',
                title: 'Seleccione un cliente antes de continuar'
            });
            return;
        }
        window.Ventor.goTo(null, document.querySelector('meta[name="checkout"]').content);
    },
    clearCart: function() {
        Swal.fire({
            title: '¿Está seguro de limpiar el pedido?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009AD6',
            cancelButtonColor: '#f46954',
            confirmButtonText: 'Confirmar'
        }).then(result => {
            if (result.value) {
                axios.post(document.querySelector('meta[name="checkout"]').content, {
                    empty: 1
                })
                .then(function (res) {
                    if (res.data.error == 0) {
                        document.querySelector(".btn-cart_product").dataset.total = res.data.total;
                        window.Ventor.cartBody(res.data.html);
                        Array.prototype.forEach.call(document.querySelectorAll(".cart__product__amount"), input => {
                            if (input.value != '0') {
                                input.value = '0';
                                let { id } = input.dataset;
                                document.querySelector(`#th--${id}`).classList.remove('bg-success');
                                document.querySelector(`#th--${id}`).classList.add('bg-dark');
                            }
                        });
                    }
                });
            }
        });
    },
    send: function(evt) {
        evt.preventDefault();
        let target = this;
        let url = target.action;
        let method = target.method;
        let formData = new FormData(target);
        grecaptcha.ready(function() {
            $(target).find(".form-control").prop( "readonly" , true );
            $(target).find(".btn").prop("disabled", true);
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            grecaptcha.execute(document.querySelector('meta[name="captcha"]').content, {action: 'contact'}).then( function( token ) {
                formData.append( "token", token );
                axios({
                    method: method,
                    url: url,
                    data: formData,
                    responseType: 'json',
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                })
                .then((res) => {
                    $(target).find(".form-control").prop("readonly", false);
                    $(target).find(".btn").prop("disabled", false);
                    if(res.data.error === 0) {
                        $(target).find(".form-control").val("");
                        $(target).find(".btn").prop("disabled", false);
                        Toast.fire({
                            icon: 'success',
                            title: res.data.message
                        });
                    } else
                        Toast.fire({
                            icon: 'error',
                            title: res.data.message
                        });
                })
                .catch((err) => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Ocurrió un error'
                    });
                });
            });
        });
    },
    confirm: function(evt) {
        let transport = $("#transport").val();
        let obs = $("#obs").val();
        let btn = evt.target;
        if (transport == '') {
            Toast.fire({
                icon: 'error',
                title: 'Seleccione un transporte antes de continuar'
            });
            return;
        }
        btn.disabled = true;
        Swal.fire({
            title: '¿Está seguro de confirmar el pedido?',
            text: "El proceso puede tardar unos segundos",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009AD6',
            cancelButtonColor: '#f46954',
            confirmButtonText: 'Confirmar'
        }).then(result => {
            btn.disabled = false;
            if (result.value) {
                window.Ventor.showNotification();
                axios.post(document.querySelector('meta[name="checkout"]').content, {
                    transport,
                    obs
                })
                .then(function (res) {
                    window.Ventor.hideNotification();
                    if (res.data.error === 0) {
                        Toast.fire({
                            icon: 'success',
                            title: res.data.msg
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: res.data.msg
                        });
                    }
                });
            }
        });
    },
    goTo: function(evt, href = null) {
        location.href = evt !== null ? evt.currentTarget.href : href;
    },
    selectModel: function(evt) {
        let {target} = evt;
        let targetModel = document.querySelector('#modelList');
        let targetYear = document.querySelector('#yearList');
        if (targetModel) {
            window.model_brand__choice.destroy();
            window.model_year__choice.destroy();
            targetYear.innerHTML = '<option value="">Seleccione año</option>';
            window.model_year__choice = new Choices(targetYear, {
                position: 'bottom',
                itemSelectText: 'Click para seleccionar'
            });
            axios.get(document.querySelector('meta[name="url"]').content+'/application_json:'+target.value)
            .then(function (res) {
                let {data} = res;
                targetModel.innerHTML = '<option value="">Seleccione modelo</option>'+data.dataOptions;
                targetModel.disabled = false;
                window.model_brand__choice = new Choices(targetModel, {
                    position: 'bottom',
                    itemSelectText: 'Click para seleccionar'
                });
            });
        }
    },
    selectBrand: function(evt) {
        let {target} = evt;
        let targetModel = document.querySelector('#modelList');
        let targetYear = document.querySelector('#yearList');
        if (targetModel) {
            window.model_year__choice.destroy();
            axios.get(document.querySelector('meta[name="url"]').content+'/application_json:'+targetModel.value+'|'+target.value)
            .then(function (res) {
                let {data} = res;
                targetYear.innerHTML = '<option value="">Seleccione año</option>'+data.dataOptions;
                targetYear.disabled = false;
                window.model_year__choice = new Choices(targetYear, {
                    position: 'bottom',
                    itemSelectText: 'Click para seleccionar'
                });
            });
        }
    },
    selectClient: function(evt) {
        let nrocta = this.value;
        axios.post(document.querySelector('meta[name="client"]').content, {
            nrocta
        })
        .then(function (res) {});
    },
    selectClientOther: function(evt) {
        let nrocta = this.value;
        axios.post(document.querySelector('meta[name="client"]').content, {
            nrocta,
            client: 1
        })
        .then(function (res) {
            location.reload();
        });
    },
    checkStock: function(evt) {
        const TARGET = this;
        const {use, stock} = TARGET.dataset;
        TARGET.disabled = true;
        window.Ventor.showNotification("Comprobando stock");
        axios.post(document.querySelector('meta[name="soap"]').content, {
            use
        })
        .then(function (res) {
            window.Ventor.hideNotification();
            TARGET.disabled = false;
            switch(parseInt(res.data)) {
                case -3:
                case -2:
                case -1:
                    Toast.fire({
                        icon: 'error',
                        title: 'Información no disponible en este momento'
                    });
                    break;
                default:
                    if(res.data !== null) {
                        if (TARGET.nextElementSibling)
                            TARGET.nextElementSibling.innerText = res.data;
                        if (parseInt(res.data) > parseInt(stock)) {
                            TARGET.closest('td').classList.add('bg-success')
                            Toast.fire({
                                icon: 'success',
                                title: `Stock disponible`
                            });
                        } else if (parseInt(res.data) <= parseInt(stock) &&  parseInt(res.data) > 0) {
                            TARGET.closest('td').classList.add('bg-warning')
                            Toast.fire({
                                icon: 'warning',
                                title: `Stock inferior o igual a cantidad crítica`
                            });
                        } else {
                            TARGET.closest('td').classList.add('bg-danger')
                            Toast.fire({
                                icon: 'error',
                                title: `Sin stock`
                            });
                        }
                    }
            }
        }).catch(function (error) {
            Toast.fire({
                icon: 'error',
                title: 'Error interno'
            });
        });
    },
    changeMarkUp: function(evt) {
        let { type } = this.dataset;
        window.Ventor.showNotification();
        axios.post(document.querySelector('meta[name="type"]').content, {
            type,
            "markup": 1
        })
        .then(function (res) {
            window.Ventor.hideNotification();
            if (res.data.error == 0)
                location.reload();
        });
    },
    createPdfOrder: function(t) {
        this.submit();
        setTimeout(() => {
            location.reload();
        }, 300);
    },
    colorHSL: function(value) {
        let rgb = window.Ventor.hexToRgb(value);
        let color = new Color(rgb[0], rgb[1], rgb[2]);
        let solver = new Solver(color);
        let result = solver.solve();
        return result.filter.replace(";", "");
    },
    hexToRgb: function(hex) {
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
    },
    checkTabPress: function(e) {
        e = e || event;
        var activeElement;
        if (e.keyCode == 9) {
            activeElement = document.querySelectorAll(".cart__product__amount");
            if (activeElement.length > 0) {
                if (window.btnAddCart === undefined)
                    window.btnAddCart = 0;
                if (document.querySelectorAll(".cart__product__amount").length == window.btnAddCart)
                    window.btnAddCart = 0;
                activeElement[window.btnAddCart].focus();
                window.btnAddCart ++;
            }
        }
    },
    typeProduct: function(evt) {
        let { filter } = this.dataset;
        window.Ventor.showNotification();
        axios.post(document.querySelector('meta[name="type"]').content, {
            filter
        })
        .then(function (res) {
            window.Ventor.hideNotification();
            if (res.data.error == 0)
                location.reload();
        });
    },
}

$(() => {
    const loginLikeUser = document.querySelector('#loginLikeUser');
    ////////////////
    const urlParams = new URLSearchParams(location.search);
    const cart__product__amount = document.querySelectorAll('.cart__product__amount');
    const header__product__amount = document.querySelectorAll('.header__cart__element .price input');
    const button__stock = document.querySelectorAll('.button--stock');
    const btn__create__pdf = document.querySelector('#createPDF');
    const cart__select = document.querySelector('#cart__select');
    const btn__back = document.querySelector('#btn--back');
    const btn__confirm = document.querySelector('#btn--confirm');
    const element_client = document.querySelector('#clientList');
    const element_client__other = document.querySelector('#clientListOther');
    const button__download = document.querySelector('#download__program');
    const element_brand = document.querySelector('#brandList');
    const model_brand = document.querySelector('#modelList');
    const year_brand = document.querySelector('#yearList');
    const transport = document.querySelector('#transport');
    const create_pdf_order = document.querySelector('#createPdfOrder');
    const images_liquidacion = document.querySelectorAll(".product-table__image--liquidacion");
    const changeMarkUp = document.querySelectorAll('.changeMarkUp');
    const downloadTrack = document.querySelectorAll('.downloadTrack');// Elemento con 1 solo archivo
    const downloadsTrack = document.querySelectorAll('.downloadsTrack');// Elemento con varias partes
    const notFile = document.querySelectorAll('.notFile');
    const form__contact = document.querySelector('#form--contact');
    const form__transmission = document.querySelector('#form--transmission');
    const form__pay = document.querySelector('#form--pay');
    const form__consult = document.querySelector('#form--consult');
    const form__data = document.querySelector('#form--data');
    const form__pass = document.querySelector('#form--pass');
    const form__markup = document.querySelector('#form--markup');
    const type__product = document.querySelectorAll('.type__product');

    document.querySelector('body').addEventListener('keyup', window.Ventor.checkTabPress);

    if (loginLikeUser) {
        /*
        loginLikeUser.addEventListener('change', function(evt) {
            let {target} = evt;
            localStorage.noticeClient = target.value;
        });
        if (localStorage.noticeClient !== undefined) {
            loginLikeUser.value = localStorage.noticeClient;
        } else {
            localStorage.noticeClient = "1";
        }*/
    }

    if (document.querySelector('meta[name="preference"]')) {
        const preference = JSON.parse(document.querySelector('meta[name="preference"]').content);
        if (document.querySelectorAll(".cart__product__amount").length > 0 && (Object.keys(preference).length == 0 || preference.messageTab === undefined || preference.messageTab !== undefined && !preference.messageTab)) {
            Swal.fire({
                text: 'Use tecla TAB para moverse entre productos',
                target: 'body',
                customClass: {
                    container: 'position-fixed'
                },
                toast: true,
                position: 'bottom-right'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(document.querySelector('meta[name="type"]').content, {
                        messageTab: 1
                    });
                }
            });
        }
    }
    if (type__product.length) {
        Array.prototype.forEach.call(type__product, q => {
            q.addEventListener("click", window.Ventor.typeProduct);
        });
    }

    if (cart__select) {
        cart__select.addEventListener('change', function(evt) {
            let {target} = evt;
            axios.post(document.querySelector('meta[name="type"]').content, {
                cartSelect: target.value,
            }).then(response => {
                let {data} = response;
                if (data.error === 0) {
                    location.reload();
                }
            });
        })
    }

    if (form__contact) {
        form__contact.addEventListener('submit', window.Ventor.send);
    }
    if (form__transmission) {
        form__transmission.addEventListener('submit', window.Ventor.send);
    }
    if (form__pay) {
        form__pay.addEventListener('submit', window.Ventor.send);
    }
    if (form__consult) {
        form__consult.addEventListener('submit', window.Ventor.send);
    }
    if (form__data) {
        form__data.addEventListener('submit', window.Ventor.send);
    }
    if (form__pass) {
        form__pass.addEventListener('submit', window.Ventor.send);
    }
    if (button__download) {
        button__download.addEventListener('click', evt => {
            $('#modalDownload').modal('show');
        });
    }

    if (form__markup) {
        form__markup.addEventListener('submit', evt => {
            evt.preventDefault();
            let form = evt.target;
            let markup = form.querySelector('[name="markup"]').value;
            axios.post(form.action, {
                markup
            })
            .then(function (res) {
                if (res.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: res.data.message
                    });
                    if ($('#input-venta').length && $('#input-venta').is(':checked')) {
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: res.data.message
                    });
                }
            });
        });
    }

    if (element_client__other) {
        new Choices(element_client__other, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
        element_client__other.addEventListener('change', window.Ventor.selectClientOther)
    }
    if (images_liquidacion.length) {
        Array.prototype.forEach.call(images_liquidacion, img => {
            img.style.filter = window.Ventor.colorHSL(img.dataset.color);
        });
    }

    if (changeMarkUp.length) {
        Array.prototype.forEach.call(changeMarkUp, q => {
            q.addEventListener("change", window.Ventor.changeMarkUp);
        });
    }
    if (cart__product__amount.length > 0) {
        Array.prototype.forEach.call(cart__product__amount, i => i.addEventListener('change', window.Ventor.cartPrice));
    }
    if (header__product__amount.length > 0) {
        document.querySelector('.button__cart.button__cart--clear').addEventListener('click', window.Ventor.clearCart);
        document.querySelector('.button__cart.button__cart--end').addEventListener('click', window.Ventor.confirmCart);
        Array.prototype.forEach.call(header__product__amount, i => i.addEventListener('change', window.Ventor.cartPrice));
    }
    if (button__stock.length > 0) {
        Array.prototype.forEach.call(button__stock, i => i.addEventListener('click', window.Ventor.checkStock));
    }

    if (btn__back) {
        btn__back.addEventListener('click', window.Ventor.goTo);
        btn__back.href = document.querySelector('meta[name="order"]').content;
    }

    if (btn__confirm) {
        btn__confirm.addEventListener('click', window.Ventor.confirm);
    }

    if (create_pdf_order) {
        create_pdf_order.addEventListener('submit', window.Ventor.createPdfOrder);
    }
    if (notFile.length) {
        Array.prototype.forEach.call(notFile, q => {
            q.addEventListener("click", window.Ventor.notFile);
        });
    }
    if (downloadsTrack.length) {
        Array.prototype.forEach.call(downloadsTrack, q => {
            q.addEventListener("change", window.Ventor.downloadsTrack);
        });
    }
    if (downloadTrack.length) {
        Array.prototype.forEach.call(downloadTrack, q => {
            q.addEventListener("click", window.Ventor.downloadTrack);
        });
    }

    if (element_client) {
        new Choices(element_client, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
        element_client.addEventListener('change', window.Ventor.selectClient)
    }
    if (element_brand) {
        new Choices(element_brand, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
        element_brand.addEventListener('change', window.Ventor.selectModel);
    }
    if (model_brand) {
        window.model_brand__choice = new Choices(model_brand, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
        model_brand.addEventListener('change', window.Ventor.selectBrand);
    }
    if (year_brand) {
        window.model_year__choice = new Choices(year_brand, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
    }
    if (transport) {
        new Choices(transport, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
    }

    if (btn__create__pdf) {
        btn__create__pdf.addEventListener('submit', function(evt) {
            evt.preventDefault();
            let {target} = evt;
            Swal.fire({
                title: '¿Imprimir listado de productos?',
                text: "El proceso puede tardar unos minutos",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar'
            }).then(result => {
                if (result.value) {
                    target.submit();
                }
            });
        });
    }

    if (urlParams.get('login') !== null) {
        document.querySelector('#dropdownMenuLogin').click();
        document.querySelector('#username-login').focus();
    }

    if (document.querySelector('#asyncProducts')) {
        window.Ventor.syncProduct();
    }

    $(".dropdown-menu").click(function(e){
        e.stopPropagation();
    });
    $(".datepicker").datepicker({
        autoSize: true,
        maxDate: new Date(),
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    });
    $(".date-incorporaciones").on('change', function() {
        let form = this.closest('form');
        let datestart = form.querySelector('[name="datestart"]').value;
        let dateend = form.querySelector('[name="dateend"]').value;
        axios.post(form.action, {
            datestart,
            dateend
        })
        .then(function (res) {
            if (res.data.error === 0) {
                Toast.fire({
                    icon: 'success',
                    title: res.data.message
                });
                if ($('#input-venta').length) {
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            } else {
                Toast.fire({
                    icon: 'error',
                    title: res.data.message
                });
            }
        });
    });

    // TODO Quito el eventSource
    /*
    window.evtSource = new EventSource(document.querySelector('meta[name="eventSource"]').content);
    window.evtSource.onopen = function(e) {};
    window.evtSource.onmessage = function(e) {};

    window.evtSource.addEventListener('eventClient', function(e) {

        if (e.lastEventId !== '0') {

            let {data, lastEventId} = e;
            data = JSON.parse(data);
            if (data.message !== undefined) {

                switch(data.action) {
                    case 'clearCart':
                        axios.post(document.querySelector('meta[name="checkout"]').content, {
                            empty: 1
                        }).then(function (res) {});
                        break;
                }

                Toast.fire({
                    icon: 'warning',
                    title: data.message
                });
                setTimeout(() => {
                    location.reload();
                }, 3000);

            }

        }
    });
    window.evtSource.onerror = function(e) {};*/
});