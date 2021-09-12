require('./bootstrap');

import axios from 'axios';
import Swal from 'sweetalert2';
import Choices from 'choices.js';
import bootstrapSelect from 'bootstrap-select';
import Splide from '@splidejs/splide'

window.overlay = null;
window.nav = null;
window.navUser = null;
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
            document.querySelector("#btn-cart_product").dataset.products = res.data.elements;
        });
    },
    visible: function(elm) {
        if(!elm.offsetHeight && !elm.offsetWidth) { return false; }
        if(getComputedStyle(elm).visibility === 'hidden') { return false; }
        return true;
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
                        if (TARGET.querySelector('.value'))
                            TARGET.querySelector('.value').innerText = res.data;
                        if (parseInt(res.data) > parseInt(stock)) {
                            TARGET.classList.add('bg-success')
                            Toast.fire({
                                icon: 'success',
                                title: `Stock disponible`
                            });
                        } else if (parseInt(res.data) <= parseInt(stock) &&  parseInt(res.data) > 0) {
                            TARGET.classList.add('bg-warning')
                            Toast.fire({
                                icon: 'warning',
                                title: `Stock inferior o igual a cantidad crítica`
                            });
                        } else {
                            TARGET.classList.add('bg-danger')
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
    confirmProduct: function(_id, quantity, target) {
        window.Ventor.showNotification();
        let product__elements = document.querySelectorAll(".product_element");
        axios.post(document.querySelector('meta[name="cart"]').content, {
            price: 1,
            _id,
            quantity,
            noticeClient: localStorage.noticeClient !== undefined ? localStorage.noticeClient == "1" : null
        })
        .then(function (res) {
            window.Ventor.hideNotification();
            if (res.data.error == 0) {
                delete window.activeSelect;
                document.querySelector('#btn-cart_product').dataset.products = res.data.elements;
                target.classList.remove('btn-warning');
                target.classList.add('btn-success');
                Array.prototype.forEach.call(product__elements, e => {
                    if (!window.Ventor.visible(e.querySelector('.product__quantity')))
                        e.classList.remove("product_element--no_click");
                });
            }
        });
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
    goTo: function(evt, href = null) {
        location.href = evt !== null ? evt.currentTarget.href : href;
    },
    changeProduct: function(evt) {
        let target = $(this);
        let price = target.closest(".product_element").find(".product__price p:nth-child(2)");
        let html = "";
        html += `<small class="table__product--price text-muted">${price.data("price")} x ${target.val()}</small> `;
        html += `<span class="table__product--price">${formatter.format(parseFloat(price.data("pricenumber")) * parseInt(target.val()))}</span>`;
        price.html(html);
    },
    confirmCart: function(evt) {
        if ($("#clientList").val() == "") {
            Toast.fire({
                icon: 'error',
                title: 'Seleccione un cliente antes de continuar'
            });
            return;
        }
        window.Ventor.goTo(null, document.querySelector('meta[name="checkout"]').content);
    },
    deleteProduct: function(evt) {
        let {target} = evt;
        let {id} = target.dataset;
        axios.post(document.querySelector('meta[name="cart"]').content, {
            _id: id,
            noticeClient: localStorage.noticeClient !== undefined ? localStorage.noticeClient == "1" : null
        })
        .then(function (res) {
            if (res.data.error === 0) {
                target.closest('.product_element').remove();
                document.querySelector('.checkout--total.checkout--total__price').innerText = formatter.format(parseFloat(res.data.total));
                if (parseInt(res.data.total) === 0) {
                    location.reload();
                }
            }
        });
    },
    addProduct: function(evt) {
        let target = this.closest(".product_element");
        let product__elements = document.querySelectorAll('.product_element');
        if (target.querySelector('.product__quantity')) {
            if (!window.Ventor.visible(target.querySelector('.product__quantity')) && window.activeSelect === undefined) {
                window.activeSelect = 1;
                if (this.classList.contains('btn-success')) {
                    this.classList.remove('btn-success');
                }
                if (!this.classList.contains('btn-warning')) {
                    this.classList.add('btn-warning');
                }
                target.querySelector('.product__quantity').style.display = "block";
                target.querySelector('.product__quantity').focus();
                Array.prototype.forEach.call(product__elements, e => {
                    if (!window.Ventor.visible(e.querySelector('.product__quantity')))
                        e.classList.add("product_element--no_click");
                });
            } else {
                if (target.querySelector('.product__quantity').value == "") {
                    this.classList.remove('btn-warning');
                    Array.prototype.forEach.call(product__elements, e => {
                        if (!window.Ventor.visible(e.querySelector('.product__quantity')))
                            e.classList.remove("product_element--no_click");
                    });
                } else {
                    window.Ventor.confirmProduct(this.dataset.id, target.querySelector('.product__quantity').value, this);
                }
                target.querySelector('.product__quantity').style.display = "none";
            }
        }
    },
    updateCart: function() {
        let target = $(this);
        let _id = target.data("id");
        let price = target.data("price");
        let quantity = target.val();
        target.parent().find("span").text(formatter.format(parseFloat(price) * parseInt(quantity)));
        let quantityProduct = document.querySelector(`.product__quantity[data-id="${_id}"]`);
        quantityProduct.value = quantity;
        quantityProduct.dispatchEvent(new Event("change"));
        axios.post(document.querySelector('meta[name="cart"]').content, {
            price: 1,
            _id,
            quantity,
            noticeClient: localStorage.noticeClient !== undefined ? localStorage.noticeClient == "1" : null
        })
        .then(function (res) {
            if (res.data.error === 0) {
                $(".menu-cart-price").data("price", res.data.totalPrice);
                $(".menu-cart-price").text(formatter.format(res.data.totalPrice))
            }
        });
    },
    deleteItem: function(t, id) {
        axios.post(document.querySelector('meta[name="cart"]').content, {
            _id: id,
            noticeClient: localStorage.noticeClient !== undefined ? localStorage.noticeClient == "1" : null
        })
        .then(function (res) {
            if (res.data.error === 0) {
                if ($(`.addCart[data-id='${id}']`).length) {
                    $(`.addCart[data-id='${id}']`).parent().addClass("bg-dark border-dark");
                    $(`.addCart[data-id='${id}']`).parent().removeClass("bg-success border-success");
                }
                $(t).parent().remove();
                document.querySelector("#btn-cart_product").dataset.products = res.data.elements;
                $(".menu-cart-price").data("price", res.data.total);
                $(".menu-cart-price").text(formatter.format(res.data.total));
                if (res.data.total == 0)
                    $("#menu-cart--confirm, #menu-cart--clear").prop("disabled", true);
            }
        });
    },
    createPdfOrder: function(evt) {
        evt.preventDefault();
        this.submit();
        setTimeout(() => {
            location.reload();
        }, 300);
    },
    confirm: function(evt) {
        let transport = $("#transport").val();
        let obs = $("#obs").val();
        let btn = evt.target;
        if (transport == "") {
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
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar'
        }).then(result => {
            btn.disabled = false;
            if (result.value) {
                $("#btn--confirm, #btn--back").prop("disabled", true);
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
                        $("#btn--confirm, #btn--back").prop("disabled", false)
                        Toast.fire({
                            icon: 'error',
                            title: res.data.msg
                        });
                    }
                });
            }
        });
    },
    stockCart: function() {
        let code = Array.prototype.map.call(document.querySelectorAll(".cart-show-product__code"), c => c.dataset.code);
        let promises = [];
        if (code.length) {
            promises = code.map(c => {
                return axios.post(document.querySelector('meta[name="soap"]').content, {
                    use: c
                });
            });
            Promise.all(promises).then(e => {
                if (e !== undefined) {
                    let total = 0;
                    e.forEach(ele => {
                        let config = JSON.parse(ele.config.data);
                        let codeElement = document.querySelector(`.cart-show-product__code[data-code="${config.use}"]`)
                        let element = codeElement.closest(".menu-cart-list-item");
                        let stockmini = codeElement.dataset.stockmini;
                        let price = element.querySelector(".cart-show-product__price").dataset.price;
                        let quantity = element.querySelector(".quantity-cart").value;
                        switch(parseInt(ele.data)) {
                            case -3:
                            case -2:
                            case -1:
                                break;
                            default:
                                if(ele.data !== null) {
                                    if (element.querySelector(".cart-show-product__stock"))
                                        element.querySelector(".cart-show-product__stock").textContent = ele.data;
                                    if (parseInt(ele.data) > parseInt(stockmini)) {
                                        total += price * quantity;
                                        element.style.backgroundColor = "#73e831";
                                        element.style.color = "#111111";
                                    } else if (parseInt(ele.data) <= parseInt(stockmini) &&  parseInt(ele.data) > 0) {
                                        total += price * stockmini;
                                        element.style.backgroundColor = "#fdf49f";
                                        element.style.color = "#111111";
                                        element.querySelector(".cart-show-product__details").textContent = `Solo se contabilizará ${stockmini} ${stockmini == 1 ? 'producto' : 'productos'}`;
                                    } else {
                                        element.style.backgroundColor = "#f34423";
                                        element.style.color = "#ffffff";
                                        element.querySelector(".cart-show-product__details").textContent = ""
                                    }
                                }
                        }
                    });
                    $(".menu-cart-price").html(`<strike>${formatter.format($(".menu-cart-price").data("price"))}</strike> ${formatter.format(total)}`);
                }
            });
        }
    },
    clearCart: function() {
        $("#menu-cart--close").click();
        Swal.fire({
            title: '¿Está seguro de limpiar el pedido?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar'
        }).then(result => {
            if (result.value) {
                axios.post(document.querySelector('meta[name="checkout"]').content, {
                    empty: 1
                })
                .then(function (res) {
                    if (res.data.error == 0) {
                        document.querySelector("#btn-cart_product").dataset.products = res.data.total;
                        Array.prototype.forEach.call(document.querySelectorAll(".product__cart.btn-success"), b => b.classList.remove("btn-success"))
                    }
                });
            }
        });
    },
    visibilityFilter: function(open = 1) {
        let duration = 600;
        let element = document.querySelector("#filter");
        if (open) {
            element.animate([
                { transform: 'translateX(-105%)' },
                { transform: 'translateX(0%)' }
                ], {
                    fill: "forwards",
                    duration: duration
                }
            );
        } else {
            element.animate([
                { transform: 'translateX(0%)' },
                { transform: 'translateX(-105%)' }
                ], {
                    fill: "forwards",
                    duration: duration
                }
            );
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
    visibilityNav: function(open = 1) {
        let duration = 600;
        if (open) {
            if (!window.Ventor.visible(window.overlay)) {
                window.navActive = window.nav;
                window.nav.animate([
                    { transform: 'translateX(-105%)' },
                    { transform: 'translateX(0%)' }
                    ], {
                        fill: "forwards",
                        duration: duration
                    }
                );
                window.overlay.style.display = "block";
                window.overlay.style.opacity = 1;
            }
        } else {
            if (window.Ventor.visible(window.overlay)) {
                delete window.navActive;
                window.nav.animate([
                    { transform: 'translateX(0%)' },
                    { transform: 'translateX(-105%)' }
                    ], {
                        fill: "forwards",
                        duration: duration
                    }
                );
                window.overlay.style.display = "none";
                window.overlay.style.opacity = 0;
            }
        }
    },
    visibilityUser: function(open = 1) {
        let duration = 600;
        if (open) {
            if (!window.Ventor.visible(window.overlay)) {
                window.navActive = window.navUser;
                window.navUser.animate([
                    { transform: 'translateX(205%)' },
                    { transform: 'translateX(' + (window.outerWidth - 300) + 'px)' }
                    ], {
                        fill: "forwards",
                        duration: duration
                    }
                );
                window.overlay.style.display = "block";
                window.overlay.style.opacity = 1;
            }
        } else {
            if (window.Ventor.visible(window.overlay)) {
                delete window.navActive
                window.navUser.animate([
                    { transform: 'translateX(' + (window.outerWidth - 300) + 'px)' },
                    { transform: 'translateX(205%)' }
                    ], {
                        fill: "forwards",
                        duration: duration
                    }
                );
                window.overlay.style.display = "none";
                window.overlay.style.opacity = 0;
            }
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const card__home = document.querySelector('#card-slider');
    const card__contact = document.querySelector('#card-slider-contact');
    const card__enterprise = document.querySelector('#card-slider-enterprise');
    const card__product = document.querySelector('#card-slider-product');
    const slider__enterprise = document.querySelector('#slider-splide-enterprise');
    const form__contact = document.querySelector('#form--contact');
    const form__transmission = document.querySelector('#form--transmission');
    const form__pay = document.querySelector('#form--pay');
    const form__consult = document.querySelector('#form--consult');
    const form__data = document.querySelector('#form--data');
    const form__pass = document.querySelector('#form--pass');
    const form__markup = document.querySelector('#form--markup');

    const card__download_publ = document.querySelector('#card-slider-PUBL');
    const card__download_cata = document.querySelector('#card-slider-CATA');
    const card__download_prec = document.querySelector('#card-slider-PREC');
    const card__download_otra = document.querySelector('#card-slider-OTRA');

    const btn__filter = document.querySelector('#btn-filter');
    const btn__filter__close = document.querySelector('#filterClose');
    const product__quantity = document.querySelectorAll('.product__quantity');
    let images_liquidacion = document.querySelectorAll('.product--liquidacion__img');
    let product__cart = document.querySelectorAll('.product__cart');
    const product__delete = document.querySelectorAll('.product__delete');
    let product__stock = document.querySelectorAll('.product__stock');
    const type__product = document.querySelectorAll('.type__product');
    const changeMarkUp = document.querySelectorAll('.changeMarkUp');
    const downloadTrack = document.querySelectorAll('.downloadTrack');// Elemento con 1 solo archivo
    const downloadsTrack = document.querySelectorAll('.downloadsTrack');// Elemento con varias partes
    const notFile = document.querySelectorAll('.notFile');
    const element = document.querySelector('#brand-filter');
    const element_client = document.querySelector('#clientList');
    const element_client__other = document.querySelector('#clientListOther');
    const element_transport = document.querySelector('#transport');

    const cart__confirm = document.querySelector('#cart--confirm');
    const btn__confirm = document.querySelector('#btn--confirm');
    const menu_cart__confirm = document.querySelector('#header__cart');
    const menu_cart__clear = document.querySelector('#menu-cart--clear');
    const menu_cart__stock = document.querySelector('#menu-cart--stock');
    const menu_cart__close = document.querySelector('#menu-cart--close');
    const loginLikeUser = document.querySelector('#loginLikeUser');
    const cart__select = document.querySelector('#cart__select');

    window.overlay = document.querySelector("#sidenav-overlay");
    window.nav = document.querySelector("#slide-out");
    window.navUser = document.querySelector("#slide-user");
    const buttonNav = document.querySelector("#button--nav");
    const buttonUser = document.querySelector("#button--user");

    const createPdfOrder = document.querySelector('#createPdfOrder');
    
    buttonNav.addEventListener("click", e => window.Ventor.visibilityNav(1));
    buttonUser.addEventListener("click", e => window.Ventor.visibilityUser(1));

    if (loginLikeUser) {
        /*loginLikeUser.addEventListener('change', function(evt) {
            let {target} = evt;
            localStorage.noticeClient = target.value;
        });
        if (localStorage.noticeClient !== undefined) {
            loginLikeUser.value = localStorage.noticeClient;
        } else {
            localStorage.noticeClient = "1";
        }*/
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

    if (createPdfOrder) {
        createPdfOrder.addEventListener('click', window.Ventor.createPdfOrder);
    }
    if (element_client__other) {
        new Choices(element_client__other, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
        element_client__other.addEventListener('change', window.Ventor.selectClientOther)
    }

    if (cart__confirm) {
        cart__confirm.addEventListener('click', window.Ventor.confirmProduct)
    }
    if (menu_cart__confirm) {
        menu_cart__confirm.addEventListener('click', window.Ventor.confirmCart)
    }
    if (menu_cart__clear) {
        menu_cart__clear.addEventListener('click', window.Ventor.clearCart)
    }
    if (menu_cart__stock) {
        menu_cart__stock.addEventListener('click', window.Ventor.stockCart)
    }
    if (menu_cart__close) {
        menu_cart__close.addEventListener('click', function() {
            $(".menu-cart").removeClass("expanded");
            overlay.style.display = "none";
            overlay.style.opacity = 0;
        });
    }
    $(".part--route").click(function(e){
        e.stopPropagation();
    });
    $("body").on("change", ".quantity-cart", window.Ventor.updateCart);
    
    $("#btn--back").click(function() {
        let url = document.querySelector('meta[name="order"]').content;
        location.href = url;
    });

    if (btn__confirm) {
        btn__confirm.addEventListener('click', window.Ventor.confirm)
    }
    
    if (btn__filter) {
        btn__filter.addEventListener("click", e => window.Ventor.visibilityFilter());
        btn__filter__close.addEventListener("click", e => window.Ventor.visibilityFilter(0));
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
    if (changeMarkUp.length) {
        Array.prototype.forEach.call(changeMarkUp, q => {
            q.addEventListener("change", window.Ventor.changeMarkUp);
        });
    }
    if (type__product.length) {
        Array.prototype.forEach.call(type__product, q => {
            q.addEventListener("click", window.Ventor.typeProduct);
        });
    }
    if (product__stock.length) {
        Array.prototype.forEach.call(product__stock, q => {
            q.addEventListener("click", window.Ventor.checkStock);
        });
    }
    if (product__quantity.length) {
        Array.prototype.forEach.call(product__quantity, q => {
            q.addEventListener("change", window.Ventor.changeProduct);
        });
    }
    
    if (images_liquidacion.length) {
        Array.prototype.forEach.call(images_liquidacion, image => {
            image.style.filter = window.Ventor.colorHSL(image.dataset.color);
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

    if (document.querySelectorAll('.product_element').length > 0) {
        window.pageProduct = 1;
        window.pageSearch = true;
        window.addEventListener('scroll',() => {
            if(window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 600 && window.pageLoad === undefined && window.pageSearch) {
                window.pageProduct ++;
                window.pageLoad = 1;
                document.querySelector('.product__loading').style.display = 'block';
                axios.get(window.location.href+'?page='+window.pageProduct+'&only=products')
                .then(function (response) {
                    let {data} = response;
                    document.querySelector('.product__loading').style.display = 'none';
                    if (data != '') {
                        document.querySelector('.products').innerHTML += data;
                        delete window.pageLoad;
                        product__cart = document.querySelectorAll('.product__cart');
                        product__stock = document.querySelectorAll('.product__stock');
                        images_liquidacion = document.querySelectorAll('.product--liquidacion__img');
                        if (product__cart.length) {
                            Array.prototype.forEach.call(product__cart, cart => {
                                cart.addEventListener("click", window.Ventor.addProduct);
                            });
                        }
                        if (product__stock.length) {
                            Array.prototype.forEach.call(product__stock, q => {
                                q.addEventListener("click", window.Ventor.checkStock);
                            });
                        }
                        if (images_liquidacion.length) {
                            Array.prototype.forEach.call(images_liquidacion, image => {
                                image.style.filter = window.Ventor.colorHSL(image.dataset.color);
                            });
                        }
                    } else {
                        window.pageSearch = false;
                        window.pageProduct --;
                    }
                });
            }
        })
    }
    
    if (element) {
        new Choices(element, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
    }
    if (element_client) {
        new Choices(element_client, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
        element_client.addEventListener('change', window.Ventor.selectClient);
    }
    if (product__cart.length) {
        Array.prototype.forEach.call(product__cart, cart => {
            cart.addEventListener("click", window.Ventor.addProduct);
        });
    }
    if (product__delete) {
        Array.prototype.forEach.call(product__delete, cart => {
            cart.addEventListener("click", window.Ventor.deleteProduct);
        });
    }
    if (element_transport) {
        new Choices(element_transport, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
    }
    if (card__product) {
        new Splide(card__product, {
            type        : 'loop',
            perPage     : 1,
            pauseOnHover: false,
        } ).mount();
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

    if (card__home) {
        new Splide(card__home, {
            perPage    : 2,
            breakpoints: {
                '425': {
                    perPage: 1,
                }
            },
            pagination: false,
        }).mount();
    }
    if (card__contact) {
        new Splide(card__contact, {
            perPage    : 1,
            breakpoints: {
                600: {
                    perPage: 1,
                }
            },
        } ).mount();
    }
    if (card__enterprise) {
        new Splide(card__enterprise, {
            perPage    : 1,
            breakpoints: {
                600: {
                    perPage: 1,
                }
            },
        } ).mount();
    }
    if (slider__enterprise) {
        new Splide(slider__enterprise , {
            type        : 'loop',
            perPage     : 1,
            autoplay    : true,
            pauseOnHover: false,
            arrows      : false
        } ).mount();
    }
    //////////
    if (card__download_publ) {
        new Splide(card__download_publ, {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }
    if (card__download_cata) {
        new Splide(card__download_cata, {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }
    if (card__download_prec) {
        new Splide(card__download_prec, {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }
    if (card__download_otra) {
        new Splide(card__download_otra, {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }

    ///////////////////////////
    const nav = document.querySelector("#slide-out");
    //const cart = document.querySelector(".header__cart");
    const search = document.querySelector(".header__search");
    const searchNav = document.querySelector("#search-nav");

    
    const showCart = function(evt) {
        if (this.dataset.user == "USR") {
            location.href = document.querySelector('meta[name="checkout"]').content;
            return;
        }
        if (!(typeof showNotification === 'function')) {
            location.href = document.querySelector('meta[name="order"]').content;
            return;
        }
        showNotification();
        axios.post(document.querySelector('meta[name="cart-show"]').content)
        .then(function (res) {
            $("#menu-cart--confirm, #menu-cart--clear").prop("disabled", false);
            $(".menu-cart").addClass("expanded");
            $(".menu-cart .menu-cart-list").html(res.data.html);
            $(".menu-cart-price").data("price", res.data.total);
            $(".menu-cart-price").text(formatter.format(res.data.total));
            if (res.data.total == 0)
                $("#menu-cart--confirm, #menu-cart--clear").prop("disabled", true);
            hideNotification();
            overlay.style.display = "block";
            overlay.style.opacity = 1;
        });
    };
    const showSearch = function(evt) {
        searchNav.style.display = "block";
        searchNav.querySelector("input[type=search]").focus()
    };
    search.addEventListener("click", showSearch);
    $(".nav__mobile--search .close").click(function() {
        searchNav.style.display = "none";
    });
    //if (cart)
        //cart.addEventListener("click", showCart);
    if (document.querySelector(".table-responsive")) {
        document.querySelector(".table-responsive").addEventListener('swiped-right', function(e) {
            window.noSwiped = 1;
        });
        document.querySelector(".table-responsive").addEventListener('swiped-left', function(e) {
            window.noSwiped = 1;
        });
    }
    document.addEventListener('swiped-right', function(e) {
        if (window.noSwiped === undefined) {
            if (!$(".menu-cart.expanded").length) {
                if (navUser === window.navActive) {
                    window.Ventor.visibilityUser(0);
                } else if (window.navActive === undefined) { 
                    window.Ventor.visibilityNav(1);
                }
            }
        } else 
            delete window.noSwiped;
    });
    document.addEventListener('swiped-left', function(e) {
        if (window.noSwiped === undefined) {
            if (!$(".menu-cart.expanded").length) {
                if (nav === window.navActive) {
                    window.Ventor.visibilityNav(0);
                } else if (window.navActive === undefined) {
                    window.Ventor.visibilityUser(1);
                }
            }
        } else 
            delete window.noSwiped;
    });
    overlay.addEventListener('click', e => {
        if (nav === window.navActive) {
            window.Ventor.visibilityNav(0);
        }
        if (navUser === window.navActive) {
            window.Ventor.visibilityUser(0);
        }
        if ($(".menu-cart.expanded").length) {
            $(".menu-cart").removeClass("expanded");
            overlay.style.display = "none";
            overlay.style.opacity = 0;
        }
    });

    ////////////////////////
    if (document.querySelector('#asyncProducts')) {
        window.Ventor.syncProduct();
    }

    $( ".dropdown-menu" ).click(function(e){
        e.stopPropagation();
    });
    $( ".datepicker" ).datepicker({
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
    window.evtSource.onerror = function(e) {};
    */
});




const checkTabPress = function(e) {
    "use strict";
    e = e || event;
    var activeElement;
    if (e.keyCode == 9) {
        if (!$(".cart.expanded").length) {
            activeElement = document.querySelectorAll(".addCart");
            if (!activeElement) {
                if (window.btnAddCart === undefined || document.querySelectorAll(".addCart").length == window.btnAddCart)
                    window.btnAddCart = 0;
                activeElement[window.btnAddCart].focus();
                window.btnAddCart ++;
            }
        } else {
            delete window.btnAddCart;
            if (window.cartInputBtn === undefined) {
                window.cartInputBtn = 1;
                document.querySelector("#cart--total").focus();
            } else {
                delete window.cartInputBtn;
                document.querySelector("#cart--confirm").focus();
            }
        }
    }
};