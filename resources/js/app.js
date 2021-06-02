require('./bootstrap');

window.time = new Date().getTime();

window.Ventor = {
    cartPrice: function(t, isHeader = false) {
        const TARGET = this;
        if (TARGET.classList.contains('number--header')) {
            isHeader = true;
        }
        const { id } = TARGET.dataset;//PRODUCTS.data.find(p => p['_id'] === id);
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
        showNotification();
        if (document.querySelector(`#th--${_id}`) && document.querySelector(`#th--${_id}`).classList.contains('bg-dark')) {
            document.querySelector(`#th--${_id}`).classList.remove('bg-dark');
            document.querySelector(`#th--${_id}`).classList.add('bg-success');
        }
        axios.post(document.querySelector('meta[name="cart"]').content, {
            price: 1,
            _id,
            quantity
        })
        .then(function (res) {
            hideNotification();
            if (res.data.error == 0) {
                if (isHeader && document.querySelector(`.cart__product__amount[data-id='${_id}']`))
                    document.querySelector(`.cart__product__amount[data-id='${_id}']`).value = quantity;
                document.querySelector(".btn-cart_product").dataset.total = res.data.elements;
                window.Ventor.cartBody(res.data.cart.html + res.data.cart.totalHtml);
            } else {
                if (document.querySelector(`#th--${_id}`) && document.querySelector(`#th--${_id}`).classList.contains('bg-success')) {
                    document.querySelector(`#th--${_id}`).classList.remove('bg-success');
                    document.querySelector(`#th--${_id}`).classList.add('bg-dark');
                }
            }
        });
    },
    deleteItem: function(_id, isHeader = false) {
        if (document.querySelector(`#th--${_id}`) && document.querySelector(`#th--${_id}`).classList.contains('bg-success')) {
            document.querySelector(`#th--${_id}`).classList.remove('bg-success');
            document.querySelector(`#th--${_id}`).classList.add('bg-dark');
        }
        axios.post(document.querySelector('meta[name="cart"]').content, {
            _id
        })
        .then(function (res) {
            if (res.data.error === 0) {
                if (isHeader && document.querySelector(`.cart__product__amount[data-id='${_id}']`))
                    document.querySelector(`.cart__product__amount[data-id='${_id}']`).value = '0';
                document.querySelector(".btn-cart_product").dataset.total = res.data.elements;
                window.Ventor.cartBody(res.data.cart.html + res.data.cart.totalHtml);
            } else {
                if (document.querySelector(`#th--${_id}`) && document.querySelector(`#th--${_id}`).classList.contains('bg-dark')) {
                    document.querySelector(`#th--${_id}`).classList.remove('bg-dark');
                    document.querySelector(`#th--${_id}`).classList.add('bg-success');
                }
            }
        });
    },
    confirmCart: function() {
        if ($("#clientList").val() == "") {
            Toast.fire({
                icon: 'error',
                title: 'Seleccione un cliente antes de continuar'
            });
            return;
        }
        let url = document.querySelector('meta[name="checkout"]').content;
        location.href = url;
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
    }
}

$(() => {
    const cart__product__amount = document.querySelectorAll('.cart__product__amount');
    const header__product__amount = document.querySelectorAll(".header__cart__element .price input");
    if (cart__product__amount.length > 0) {
        Array.prototype.forEach.call(cart__product__amount, i => i.addEventListener('change', window.Ventor.cartPrice));
    }
    if (header__product__amount.length > 0) {
        document.querySelector('.button__cart.button__cart--clear').addEventListener('click', window.Ventor.clearCart);
        document.querySelector('.button__cart.button__cart--end').addEventListener('click', window.Ventor.confirmCart);
        Array.prototype.forEach.call(header__product__amount, i => i.addEventListener('change', window.Ventor.cartPrice));
    }
});