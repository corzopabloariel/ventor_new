<script src="{{ asset('js/sorteable.js') }}"></script>
<script>
    const orderFunction = function(t) {
        $("#orderNew").modal("show");
    };

    const orderNewsSubmit = function(t) {
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
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: data.data.message
                });
            }
        }, "post", formData);
    };
    $(() => {
        new Sortable(swapList, {
            handle: '.handle', // handle's class
            swap: true, // Enable swap plugin
            swapClass: 'highlight_new', // The class applied to the hovered swap item
            animation: 150
        });
    });
</script>