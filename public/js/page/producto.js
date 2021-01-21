const formatter = new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
});

const showNotification = function(text = "En proceso") {
    $("#notification").removeClass("d-none").addClass("d-flex");
    $("#notification .notification--text").text(text);
}
const hideNotification = function() {
    $("#notification").removeClass("d-flex").addClass("d-none");
    $("#notification .notification--text").text("");
}

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
const addPedido = function(t, price, minvta, stock, maxvta, _id) {
    window.price = price;
    window.id = _id;
    window.btn = t;
    let target = $(t).closest("tr");
    let img = target.find("td:nth-child(1)").html();
    let data = target.find("td:nth-child(2)").html();

    $(".cart--price").text(formatter.format(price));
    $("#cart--total").prop("step", minvta);
    $("#cart--total").prop("min", minvta);
    $("#cart--total").val(t.dataset.quantity === undefined ? minvta : t.dataset.quantity);
    $("#cart--total").change();
    $(".cart--img").html(img);
    $(".cart--data").html(data);
    $(".cart").addClass("expanded");
    $(".background").removeClass("d-none");
};
const confirmProduct = function() {
    showNotification();
    axios.post(document.querySelector('meta[name="cart"]').content, {
        price: window.price,
        _id: window.id,
        quantity: document.querySelector("#cart--total").value
    })
    .then(function (res) {
        hideNotification();
        if (res.data.error == 0) {
            document.querySelector(".btn-cart_product").dataset.total = res.data.total;
            $(window.btn).parent().removeClass("bg-dark border-dark");
            $(window.btn).parent().addClass("bg-success border-success");
            window.btn.dataset.quantity = document.querySelector("#cart--total").value;
            $("#cart--close").click();
        }
    });
};
const cartPrice = function(t) {
    let price = window.price * t.value;
    $(".cart--price").html(formatter.format(price) + `<br/><small>${formatter.format(window.price)} x ${t.value}</small>`);
};
const createPdf = function(t) {
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
            $(t).prop("action", location.href);
            $(t).submit();
        }
    });
};
const changeMarkUp = function(t, type) {
    showNotification();
    axios.post(document.querySelector('meta[name="type"]').content, {
        type,
        "markup": 1
    })
    .then(function (res) {
        hideNotification();
        if (res.data.error == 0)
            location.reload();
    });
};
const typeProduct = function(t, filter) {
    showNotification();
    axios.post(document.querySelector('meta[name="type"]').content, {
        filter
    })
    .then(function (res) {
        hideNotification();
        if (res.data.error == 0)
            location.reload();
    });
};
const verificarStock = function(t, use, stock = null) {
    $(t).attr("disabled", true);
    showNotification("Comprobando stock");
    axios.post(document.querySelector('meta[name="soap"]').content, {
        use
    })
    .then(function (res) {
        hideNotification();
        $(t).attr("disabled",false);
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
                    if ($(t).find("+ .cantidad").length)
                        $(t).find("+ .cantidad").text(res.data);
                    if (parseInt(res.data) > parseInt(stock)) {
                        $(t).closest("td").removeClass("bg-light").addClass("btn-success");
                        Toast.fire({
                            icon: 'success',
                            title: `Stock disponible`
                        });
                    } else if (parseInt(res.data) <= parseInt(stock) &&  parseInt(res.data) > 0) {
                        $(t).closest("td").removeClass("bg-light").addClass("btn-warning");
                        Toast.fire({
                            icon: 'warning',
                            title: `Stock inferior o igual a cantidad crítica`
                        });
                    } else {
                        $(t).closest("td").removeClass("bg-light").addClass("btn-danger");
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
};
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
const showCart = function() {
    showNotification();
    $("#menu-cart--confirm, #menu-cart--stock").prop("disabled", false);
    axios.post(document.querySelector('meta[name="cart-show"]').content)
    .then(function (res) {
        $(".background").removeClass("d-none");
        $(".menu-cart").addClass("expanded");
        $(".menu-cart .menu-cart-list").html(res.data.html);
        $(".menu-cart-price").data("price", res.data.total);
        $(".menu-cart-price").text(formatter.format(res.data.total));
        if (res.data.total == 0)
            $("#menu-cart--confirm, #menu-cart--stock").prop("disabled", true);
        hideNotification();
    });
};
const colorHSL = function(value) {
    let rgb = hexToRgb(value);
    let color = new Color(rgb[0], rgb[1], rgb[2]);
    let solver = new Solver(color);
    let result = solver.solve();
    return result.filter.replace(";", "");
}
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
const updateCart = function() {
    let target = $(this);
    let id = target.data("id");
    let price = target.data("price");
    let quantity = target.val();
    target.parent().find("span:last-child()").text(formatter.format(parseFloat(price) * parseInt(quantity)));
    Array.prototype.forEach.call(document.querySelectorAll(".menu-cart-list-item"), c => {
        c.removeAttribute("style");
        if (c.querySelector(".cart-show-product__stock"))
            c.querySelector(".cart-show-product__stock").textContent = "";
        if (c.querySelector(".cart-show-product__details"))
            c.querySelector(".cart-show-product__details").textContent = "";
    });
    axios.post(document.querySelector('meta[name="cart"]').content, {
        price,
        _id: id,
        quantity,
        withTotal: 1
    })
    .then(function (res) {
        if (res.data.error === 0) {
            $(".menu-cart-price").data("price", res.data.totalPrice);
            $(".menu-cart-price").text(formatter.format(res.data.totalPrice))
        }
    });
};
const deleteItem = function(t, id) {
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
            document.querySelector(".btn-cart_product").dataset.total = res.data.elements;
            $(".menu-cart-price").data("price", res.data.total);
            $(".menu-cart-price").text(formatter.format(res.data.total));
            if (res.data.total == 0)
                $("#menu-cart--confirm").prop("disabled", true);
        }
    });
};
const confirmCart = function() {
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
};
const showImages = function() {
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
};

const confirm = function() {
    let transport = $("#transport").val();
    let obs = $("#obs").val();
    if (!transport.length) {
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
            showNotification();
            axios.post(document.querySelector('meta[name="checkout"]').content, {
                transport,
                obs
            })
            .then(function (res) {
                hideNotification();
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
};
const selectClient = function(t) {
    let nrocta = t.value;
    axios.post(document.querySelector('meta[name="client"]').content, {
        nrocta
    })
    .then(function (res) {});
};
const createPdfOrder = function(t) {
    t.submit();
    setTimeout(() => {
        location.reload();
    }, 300);
};
const stockCart = function() {
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
                                    if (parseInt(quantity) <= parseInt(ele.data)) {
                                        total += price * quantity;
                                        element.style.backgroundColor = "#73e831";
                                        element.style.color = "#111111";
                                    } else {
                                        total += price * parseInt(ele.data);
                                        element.style.backgroundColor = "#fdf49f";
                                        element.style.color = "#111111";
                                        element.querySelector(".cart-show-product__details").textContent = `Solo se contabilizará ${ele.data} ${ele.data == 1 ? 'producto' : 'productos'}`;
                                    }
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
};

var body = document.querySelector('body');
body.addEventListener('keyup', checkTabPress);
$(() => {
    const element_client = document.querySelector('#clientList');
    if (element_client)
        new Choices(element_client, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
    $(".part--route").click(function(e){
        e.stopPropagation();
    });
    $("#menu-cart--close").click(function() {
        $(".background").addClass("d-none");
        $(".menu-cart").removeClass("expanded");
    });
    $("#cart--close").click(function() {
        $(".background").addClass("d-none");
        $(".cart").removeClass("expanded");

        delete window.price;
        delete window.id;

        $("#cart--total").prop("step", 1);
        $("#cart--total").prop("min", 1);
        $("#cart--total").val(1);
        $(".cart--img").html("");
        $(".cart--data").html("");
        $(".cart--price").text("");
    });
    $("#btn--back").click(function() {
        let url = document.querySelector('meta[name="order"]').content;
        location.href = url;
    });
    $(".product-images").click(showImages);
    $("#btn-pdf").click(createPdfOrder);
    $("#btn--confirm").click(confirm);
    $("#menu-cart--stock").click(stockCart);
    $("#menu-cart--confirm").click(confirmCart);
    $("#cart--confirm").click(confirmProduct);
    $(".btn-cart_product").click(showCart);
    $("body").on("change", ".quantity-cart", updateCart);

    const imgs = document.querySelectorAll(".product--liquidacion__img");
    if (imgs.length) {
        Array.prototype.forEach.call(imgs, img => {
            img.style.filter = colorHSL(img.dataset.color);
        });
    }
});