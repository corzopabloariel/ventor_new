require('./bootstrap');

import Swal from 'sweetalert2'
import Splide from '@splidejs/splide'


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
    visible: function(elm) {
        if(!elm.offsetHeight && !elm.offsetWidth) { return false; }
        if(getComputedStyle(elm).visibility === 'hidden') { return false; }
        return true;
    },
    send: function(evt) {
        let target = this;
        let url = target.action;
        let method = target.method;
        let formData = new FormData(t);
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
                            title: res.data.mssg
                        });
                    } else
                        Toast.fire({
                            icon: 'error',
                            title: res.data.mssg
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

    download: function(t, id) {
        let index = t.selectedIndex - 1;
        let file = t.item(index).text;
        let txt = t.dataset.name + ` [${file}]`;
        let link = $(t).next().children()[index];
        let value = $(t).val();
        if (value == "") {
            swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
                buttons: {
                    cerrar: true,
                },
            });
            return;
        }
        downloadTrack(t, id, link);
    },
    notFile: function(t) {
        let txt = t.dataset.name;
        swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
            buttons: {
                cerrar: true,
            },
        });
    },
    downloadTrack: function(t, id, link = null) {
        let txt = t.dataset.name
        let flag = false;
        if (link === null) {
            flag = true;
            link = document.createElement("a");
            link.href = t.dataset.href;
            link.download = t.dataset.name;
        }
        axios.get(document.querySelector('meta[name="url"]').content + "/track_download/" + id)
        .then(function (res) {
            if (res.data.error === 0) {
                link.click();
                if (flag)
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
            swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
                buttons: {
                    cerrar: true,
                },
            });
        });
    },
    selectClient: function(evt) {
        let nrocta = this.value;
        axios.post(document.querySelector('meta[name="client"]').content, {
            nrocta
        })
        .then(function (res) {});
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
                        title: 'Ocurrió un error'
                    });
                    break;
                default:
                    if(res.data !== null) {
                        if (TARGET.nextElementSibling)
                            TARGET.nextElementSibling.innerText = res.data;
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
            console.error(error)
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
            quantity
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
    changeProduct: function(evt) {
        let target = $(this);
        let price = target.closest(".product_element").find(".product__price p:nth-child(2)");
        let html = "";
        html += `<small class="table__product--price text-muted">${price.data("price")} x ${target.val()}</small> `;
        html += `<span class="table__product--price">${formatter.format(parseFloat(price.data("pricenumber")) * parseInt(target.val()))}</span>`;
        price.html(html);
    },
    showImages: function() {
        let images = this.dataset.images.split("|");
        let name = this.dataset.name;
        let noimg = this.dataset.noimg;
        $("#imagesProductModalLabel").text(name);
        images = images.map((i, index) => {
            return `<div class="carousel-item ${index == 0 ? 'active' : ''}"><img src="${i}" onerror="this.src='${noimg}'" class="d-block w-100" alt="${name}"/></div>`
        }).join("");
        let carousel = `<div id="carouselImagesControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner carousel-inner__modal">${images}</div>
            <a class="carousel-control-prev" href="#carouselImagesControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselImagesControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>`;
        $("#imagesProductModal .modal-body").html(carousel);
        //$('#carouselImagesControls').carousel();
        $("#imagesProductModal").modal("show");
    },
    confirmCart: function() {
        if ($("#clientList").length && $("#clientList").val() == "") {
            $("#menu-cart--close").click();
            Toast.fire({
                icon: 'error',
                title: 'Seleccione un cliente antes de continuar'
            });
            return;
        }
        let url = document.querySelector('meta[name="checkout"]').content;
        location.href = url;
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
                console.log(target.querySelector('.product__quantity'))
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
            quantity
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
            _id: id
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
    createPdfOrder: function(t) {
        t.submit();
        setTimeout(() => {
            location.reload();
        }, 300);
    },
    confirm: function() {
        let transport = $("#transport").val();
        let obs = $("#obs").val();
        if (transport == "") {
            Toast.fire({
                icon: 'error',
                title: 'Seleccione un transporte antes de continuar'
            });
            return;
        }
        Swal.fire({
            title: '¿Está seguro de confirmar el pedido?',
            text: "El proceso puede tardar unos segundos",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar'
        }).then(result => {
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
            console.log(res)
            window.Ventor.hideNotification();
            if (res.data.error == 0)
                location.reload();
        });
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

    const card__download_publ = document.querySelector('#card-slider-PUBL');
    const card__download_cata = document.querySelector('#card-slider-CATA');
    const card__download_prec = document.querySelector('#card-slider-PREC');
    const card__download_otra = document.querySelector('#card-slider-OTRA');

    const btn__filter = document.querySelector('#btn-filter');
    const btn__filter__close = document.querySelector('#filterClose');
    const product__quantity = document.querySelectorAll('.product__quantity');
    const images_liquidacion = document.querySelectorAll('.product--liquidacion__img');
    const product__cart = document.querySelectorAll('.product__cart');
    const product__images = document.querySelectorAll('.product__images');
    const product__stock = document.querySelectorAll('.product__stock');
    const type__product = document.querySelectorAll('.type__product');
    const changeMarkUp = document.querySelectorAll('.changeMarkUp');
    const element = document.querySelector('#brand-filter');
    const element_client = document.querySelector('#clientList');
    const element_transport = document.querySelector('#transport');

    const cart__confirm = document.querySelector('#cart--confirm');
    const btn__confirm = document.querySelector('#btn--confirm');
    const menu_cart__confirm = document.querySelector('#menu-cart--confirm');
    const menu_cart__clear = document.querySelector('#menu-cart--clear');
    const menu_cart__stock = document.querySelector('#menu-cart--stock');
    const menu_cart__close = document.querySelector('#menu-cart--close');

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
    if (product__images.length) {
        Array.prototype.forEach.call(product__images, q => {
            q.addEventListener("click", window.Ventor.showImages);
        });
    }
    if (product__quantity.length) {
        Array.prototype.forEach.call(product__quantity, q => {
            q.addEventListener("change", window.Ventor.changeProduct);
        });
    }
    
    if (images_liquidacion.length) {
        Array.prototype.forEach.call(images_liquidacion, image => {
            image.style.filter = colorHSL(image.dataset.color);
        });
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
    }
    if (product__cart.length) {
        Array.prototype.forEach.call(product__cart, cart => {
            cart.addEventListener("click", window.Ventor.addProduct);
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