window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};
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

const showNotification = function(text = "En proceso") {
    $("#notification").removeClass("d-none").addClass("d-flex");
    $("#notification .notification--text").text(text);
}
const hideNotification = function() {
    $("#notification").removeClass("d-flex").addClass("d-none");
    $("#notification .notification--text").text("");
}

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
const verificarStock = function(element, use, stock = null) {
    //showNotification("Comprobando stock");
    element.classList.add("product__stock--pre");
    axios.post(document.querySelector('meta[name="soap"]').content, {
        use
    })
    .then(function (res) {
        switch(parseInt(res.data)) {
            case -3:
            case -2:
            case -1:
                element.classList.remove("product__stock--pre");
                element.classList.add("product__stock--error");
                break;
            default:
                if(res.data !== null) {
                    let value = element.querySelector(".value");
                    if (value)
                        value.textContent = `(${res.data})`;
                    if (parseInt(res.data) > parseInt(stock)) {
                        element.classList.add("product__stock--ok");
                    } else if (parseInt(res.data) <= parseInt(stock) &&  parseInt(res.data) > 0) {
                        element.classList.add("product__stock--middle");
                    } else {
                        element.classList.add("product__stock--no");
                    }
                }
        }
    }).catch(function (error) {
        element.classList.remove("product__stock--pre");
        element.classList.add("product__stock--error");
        console.error(error);
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

const visibilityFilter = function(open = 1) {
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
};

///////////
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
const selectClient = function(t) {
    let nrocta = t.value;
    axios.post(document.querySelector('meta[name="client"]').content, {
        nrocta
    })
    .then(function (res) {});
};
const confirmProduct = function(_id, price, quantity, target) {
    showNotification();
    let product__elements = document.querySelectorAll(".product_element");
    axios.post(document.querySelector('meta[name="cart"]').content, {
        price,
        _id,
        quantity
    })
    .then(function (res) {
        hideNotification();
        if (res.data.error == 0) {
            delete window.activeSelect;
            document.querySelector("#btn-cart_product").dataset.products = res.data.total;
            target.removeClass("btn-warning").addClass("btn-success");
            Array.prototype.forEach.call(product__elements, e => {
                if ($(e).find(".product__quantity").is(":hidden"))
                    e.classList.remove("product_element--no_click");
            });
        }
    });
};
const changeProduct = function(evt) {
    console.log("as")
    let target = $(this);
    let price = target.closest(".product_element").find(".product__price p:nth-child(2)");
    let html = "";
    html += `<small class="table__product--price text-muted">${price.data("price")} x ${target.val()}</small> `;
    html += `<span class="table__product--price">${formatter.format(parseFloat(price.data("pricenumber")) * parseInt(target.val()))}</span>`;
    price.html(html);
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
const addProduct = function(evt) {
    let target = $(this).closest(".product_element");
    let product__elements = document.querySelectorAll(".product_element");
    if (target.find(".product__quantity").length) {
        if (target.find(".product__quantity").is(":hidden") && window.activeSelect === undefined) {
            window.activeSelect = 1;
            if ($(this).hasClass("btn-light"))
                $(this).removeClass("btn-light").addClass("btn-warning");
            if ($(this).hasClass("btn-success"))
                $(this).removeClass("btn-success").addClass("btn-warning");
            target.find(".product__quantity").show();
            target.find(".product__quantity").focus();
            Array.prototype.forEach.call(product__elements, e => {
                if ($(e).find(".product__quantity").is(":hidden"))
                    e.classList.add("product_element--no_click");
            });
        } else {
            if (target.find(".product__quantity").val() == "") {
                $(this).removeClass("btn-warning").addClass("btn-light");
                Array.prototype.forEach.call(product__elements, e => {
                    if ($(e).find(".product__quantity").is(":hidden"))
                        e.classList.remove("product_element--no_click");
                });
            } else {
                confirmProduct(this.dataset.id, 0, target.find(".product__quantity").val(), $(this));
            }
            target.find(".product__quantity").hide();
        }
    }
};
const updateCart = function() {
    let target = $(this);
    let id = target.data("id");
    let price = target.data("price");
    let quantity = target.val();
    target.parent().find("span").text(formatter.format(parseFloat(price) * parseInt(quantity)));
    let quantityProduct = document.querySelector(`.product__quantity[data-id="${id}"]`);
    quantityProduct.value = quantity;
    quantityProduct.dispatchEvent(new Event("change"));
    axios.post(document.querySelector('meta[name="cart"]').content, {
        price,
        _id: id,
        quantity,
        withTotal: 1
    })
    .then(function (res) {
        if (res.data.error === 0) {
            $(".menu-cart-price").text(formatter.format(res.data.totalPrice))
        }
    });
};
const createPdfOrder = function(t) {
    t.submit();
    setTimeout(() => {
        location.reload();
    }, 300);
};
const confirm = function() {
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
                    $("#btn--confirm, #btn--back").prop("disabled", false)
                    Toast.fire({
                        icon: 'error',
                        title: res.data.msg
                    });
                }
            });
        }
    });
};
$(() => {
    $(".part--route").click(function(e){
        e.stopPropagation();
    });
    $("body").on("change", ".quantity-cart", updateCart);
    $("#cart--confirm").click(confirmProduct);
    $("#menu-cart--confirm").click(confirmCart);
    $(".product-images").click(showImages);
    $("#menu-cart--close").click(function() {
        $(".menu-cart").removeClass("expanded");
        overlay.style.display = "none";
        overlay.style.opacity = 0;
    });
    $("#btn--back").click(function() {
        let url = document.querySelector('meta[name="order"]').content;
        location.href = url;
    });
    $("#btn--confirm").click(confirm);
    const btnFilter = document.querySelector("#btn-filter");
    const btnFilterClose = document.querySelector("#filterClose");
    const productQuantity = document.querySelectorAll(".product__quantity");
    if (btnFilter) {
        btnFilter.addEventListener("click", e => visibilityFilter());
        btnFilterClose.addEventListener("click", e => visibilityFilter(0));
    }
    if (productQuantity.length) {
        Array.prototype.forEach.call(productQuantity, q => {
            q.addEventListener("change", changeProduct);
        });
    }
    const imgs = document.querySelectorAll(".product--liquidacion__img");
    if (imgs.length) {
        Array.prototype.forEach.call(imgs, img => {
            img.style.filter = colorHSL(img.dataset.color);
        });
    }
    const product__cart = document.querySelectorAll(".product__cart");
    const element = document.querySelector('#brand-filter');
    const element_client = document.querySelector('#clientList');
    const element_transport = document.querySelector('#transport');
    if (element)
        new Choices(element, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
    if (element_client)
        new Choices(element_client, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
    if (product__cart.length)
        Array.prototype.forEach.call(product__cart, cart => {
            cart.addEventListener("click", addProduct);
        });
    
    if (element_transport)
        new Choices(element_transport, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
    if ($('#card-slider').length) {
        new Splide( '#card-slider', {
            type        : 'loop',
            perPage     : 1,
            pauseOnHover: false,
        } ).mount();
    }
});