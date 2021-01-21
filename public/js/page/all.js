window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};
const darkMode = function(t) {
    axios.post(document.querySelector('meta[name="type"]').content, {
        darkmode: 1,
        status: document.body.classList.contains("dark-mode")
    })
    .then(function (res) {
        console.log(res);
        if (res.data.status) {
            t.innerHTML = '<i class="fas fa-moon"></i>Activar modo oscuro'
            document.body.classList.remove("dark-mode");
        } else {
            t.innerHTML = '<i class="far fa-moon"></i>Desactivar modo oscuro'
            document.body.classList.add("dark-mode");
        }
    });
};