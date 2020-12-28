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
    axios.post(document.querySelector('meta[name="cart"]').content, {
        price: window.price,
        _id: window.id,
        quantity: document.querySelector("#cart--total").value
    })
    .then(function (res) {
        if (res.data.error == 0) {
            Toast.fire({
                icon: 'success',
                title: res.data.msg
            });
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
    axios.post(document.querySelector('meta[name="type"]').content, {
        type,
        "markup": 1
    })
    .then(function (res) {
        if (res.data.error == 0)
            location.reload();
    });
};
const typeProduct = function(t, filter) {
    axios.post(document.querySelector('meta[name="type"]').content, {
        filter
    })
    .then(function (res) {
        if (res.data.error == 0)
            location.reload();
    });
};
const verificarStock = function(t, use, stock = null) {
    $(t).attr("disabled", true);
    Toast.fire({
        icon: 'warning',
        title: `Verificando STOCK`
    });
    axios.post(document.querySelector('meta[name="soap"]').content, {
        use
    })
    .then(function (res) {
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
                            icon: 'wrror',
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
            if (window.btnAddCart === undefined || document.querySelectorAll(".addCart").length == window.btnAddCart)
                window.btnAddCart = 0;
            activeElement[window.btnAddCart].focus();
            window.btnAddCart ++;
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
    axios.post(document.querySelector('meta[name="cart-show"]').content)
    .then(function (res) {
        $("#menu-cart--confirm").prop("disabled", false);
        $(".background").removeClass("d-none");
        $(".menu-cart").addClass("expanded");
        $(".menu-cart .menu-cart-list").html(res.data.html);
        $(".menu-cart-price").text(formatter.format(res.data.total));
        if (res.data.total == 0)
            $("#menu-cart--confirm").prop("disabled", true);
    });
};
const updateCart = function() {
    let target = $(this);
    let id = target.data("id");
    let price = target.data("price");
    let quantity = target.val();
    target.parent().find("span").text(formatter.format(parseFloat(price) * parseInt(quantity)));
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
            $(".menu-cart-price").text(formatter.format(res.data.total));
            if (res.data.total == 0)
                $("#menu-cart--confirm").prop("disabled", true);
        }
    });
};
const confirmCart = function() {
    let url = document.querySelector('meta[name="checkout"]').content;
    location.href = url;
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
            axios.post(document.querySelector('meta[name="checkout"]').content, {
                transport,
                obs
            })
            .then(function (res) {
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

var body = document.querySelector('body');
body.addEventListener('keyup', checkTabPress);
$(() => {
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
    $("#btn--confirm").click(confirm);
    $("#menu-cart--confirm").click(confirmCart);
    $("#cart--confirm").click(confirmProduct);
    $(".btn-cart_product").click(showCart);
    $("body").on("change", ".quantity-cart", updateCart);
});