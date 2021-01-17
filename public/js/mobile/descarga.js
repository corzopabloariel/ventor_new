const download = function(t, id) {
    let index = t.selectedIndex - 1;
    let file = t.item(index).text;
    let txt = t.dataset.name + ` [${file}]`;
    let link = $(t).next().children()[index];
    let value = $(t).val();
    if (value == "") {
        swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
            buttons: {
                cerrar: true,
            },
        });
        return;
    }
    downloadTrack(t, id, link);
};
const notFile = function(t) {
    let txt = t.dataset.name;
    swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
        buttons: {
            cerrar: true,
        },
    });
};
const downloadTrack = function(t, id, link = null) {
    let txt = t.dataset.name
    let flag = false;
    if (link === null) {
        flag = true;
        link = document.createElement("a");
        link.href = t.dataset.href;
        link.download = t.dataset.name;
    }
    axios.get(document.querySelector('meta[name="url"]').content + "/track_download/" + id)
    .then(function (res) {
        if (res.data.error === 0) {
            link.click();
            if (flag)
                link.remove();
        } else {
            swal("Atención!", res.data.msg, "error",{
                buttons: {
                    cerrar: true,
                },
            });
        }
    })
    .catch(err => {
        swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
            buttons: {
                cerrar: true,
            },
        });
    });
};

document.addEventListener( 'DOMContentLoaded', function () {
    if ($('#card-slider-PUBL').length) {
        new Splide( '#card-slider-PUBL', {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }
    if ($('#card-slider-CATA').length) {
        new Splide( '#card-slider-CATA', {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }if ($('#card-slider-PREC').length) {
        new Splide( '#card-slider-PREC', {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }if ($('#card-slider-OTRA').length) {
        new Splide( '#card-slider-OTRA', {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }
});