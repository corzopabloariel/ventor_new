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

$(() => {
    $(".part--route").click(function(e){
        e.stopPropagation();
    });
    const btnFilter = document.querySelector("#btn-filter");
    const btnFilterClose = document.querySelector("#filterClose");
    btnFilter.addEventListener("click", e => visibilityFilter());
    btnFilterClose.addEventListener("click", e => visibilityFilter(0));
    const imgs = document.querySelectorAll(".product--liquidacion__img");
    if (imgs.length) {
        Array.prototype.forEach.call(imgs, img => {
            img.style.filter = colorHSL(img.dataset.color);
        });
    }

    const element = document.querySelector('#brand-filter');
    const choices = new Choices(element);
});