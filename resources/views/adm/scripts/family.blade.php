<script src="{{ asset('js/sorteable.js') }}"></script>
<script>
const partsFunction = function(t) {
    $("#parts").modal("show");
};
const partCategoriesSubmit = function(t) {
    let formData = new FormData(t);
    Toast.fire({
        icon: 'warning',
        title: 'Espere'
    });
    window.pyrus.call(t.action, data => {
        'use strict'
        if (data.data.error === 0) {
            Toast.fire({
                icon: 'success',
                title: data.data.message
            });
        } else {
            Toast.fire({
                icon: 'error',
                title: data.data.message
            });
        }
    }, "post", formData);
};
const orderFunction = function(t) {
    $("#orderCategory").modal("show");
};
const orderCategoriesSubmit = function(t) {
    let formData = new FormData(t);
    Toast.fire({
        icon: 'warning',
        title: 'Espere'
    });
    window.pyrus.call(t.action, data => {
        'use strict'
        if (data.data.error === 0) {
            Toast.fire({
                icon: 'success',
                title: data.data.txt
            });
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            Toast.fire({
                icon: 'error',
                title: data.data.txt
            });
        }
    }, "post", formData);
};
$(() => {
    new Sortable(swapList_category, {
        handle: '.handle', // handle's class
        swap: true, // Enable swap plugin
        swapClass: 'highlight_order', // The class applied to the hovered swap item
        animation: 150
    });
});
</script>