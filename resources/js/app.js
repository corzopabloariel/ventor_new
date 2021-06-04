import Swal from '../../node_modules/sweetalert2/dist/sweetalert2.all'

require('./bootstrap');

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
        window.Ventor.showNotification();
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
            window.Ventor.hideNotification();
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
    confirm: function() {
        let transport = $("#transport").val();
        let obs = $("#obs").val();
        if (transport == '') {
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
            confirmButtonColor: '#009AD6',
            cancelButtonColor: '#f46954',
            confirmButtonText: 'Confirmar'
        }).then(result => {
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
            console.error(error)
            Toast.fire({
                icon: 'error',
                title: 'Error interno'
            });
        });
    },
    createPdfOrder: function(t) {
        this.submit();
        setTimeout(() => {
            location.reload();
        }, 300);
    }
}

$(() => {
    const cart__product__amount = document.querySelectorAll('.cart__product__amount');
    const header__product__amount = document.querySelectorAll('.header__cart__element .price input');
    const button__stock = document.querySelectorAll('.button--stock');
    const btn__back = document.querySelector('#btn--back');
    const btn__confirm = document.querySelector('#btn--confirm');
    const element_client = document.querySelector('#clientList');
    const element_brand = document.querySelector('#brandList');
    const transport = document.querySelector('#transport');
    const create_pdf_order = document.querySelector('#createPdfOrder');

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
    }
    if (transport) {
        new Choices(transport, {
            position: 'bottom',
            itemSelectText: 'Click para seleccionar'
        });
    }
});