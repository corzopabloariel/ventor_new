require('./bootstrap');

window.time = new Date().getTime();

window.Ventor = {
    cartPrice: function cartPrice(t) {
        const TARGET = this;
        const { id } = TARGET.dataset;
        const { cantminvta, stock_mini, priceNumberStd } = PRODUCTS.data.find(p => p['_id'] === id);
        const value = TARGET.value;
        window.Ventor.confirmProduct(id, priceNumberStd, value);
    },
    confirmProduct: function(_id, price, quantity) {
        showNotification();
        axios.post(document.querySelector('meta[name="cart"]').content, {
            price,
            _id,
            quantity
        })
        .then(function (res) {
            hideNotification();
            if (res.data.error == 0) {
                document.querySelector(".btn-cart_product").dataset.total = res.data.total;
                document.querySelector(`#th--${_id}`).classList.remove('bg-dark');
                document.querySelector(`#th--${_id}`).classList.add('bg-success');
            }
        });
    }
}

$(() => {
    const cart__product__amount = document.querySelectorAll('.cart__product__amount');
    if (cart__product__amount.length > 0) {
        Array.prototype.forEach.call(cart__product__amount, i => i.addEventListener('change', window.Ventor.cartPrice));
    }
});