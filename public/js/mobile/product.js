window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};
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


$(() => {
    const imgs = document.querySelectorAll(".product--liquidacion__img");
    if (imgs.length) {
        Array.prototype.forEach.call(imgs, img => {
            img.style.filter = colorHSL(img.dataset.color);
        });
    }
});