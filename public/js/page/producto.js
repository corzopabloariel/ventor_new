window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

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

$(() => {
    $(".part--route").click(function(e){
        e.stopPropagation();
    });
});