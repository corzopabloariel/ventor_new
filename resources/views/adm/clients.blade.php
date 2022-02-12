<section class="my-3">
    <div class="container-fluid">
        @isset($data["section"])
            @include('layouts.general.breadcrumb', ['section' => $data["section"]])
        @endisset
        @isset($data["help"])
            {!! $data["help"] !!}
        @endisset
        @include('layouts.general.form', ['buttonADD' => 1, 'form' => 0, 'close' => 1, 'modal' => 1])
        @php
        $arr = [];
        if (isset($data["url_search"]))
            $arr["form"] = [
                "url" => $data["url_search"] ?? "/",
                "placeholder" => "Buscar en " . ($data["placeholder"] ?? "No definido"),
                "search" => isset($data["search"]) ? $data["search"] : null
            ];
        if (isset($data["elements"]) && !isset($data["notPaginate"]))
            $arr["paginate"] = $data["elements"];
        $thead = ["CUENTA", "DOCUMENTO", "RAZÓN SOCIAL", "VENDEDOR", "TELÉFONO", ""];
        $table = $tbody = "";
        $thead = collect($thead)->map(function($item) {
            return "<th>{$item}</th>";
        })->join("");
        $tbody = collect($data["elements"]->toArray()["data"])->map(function($item) {
            $tr = "";
            $tr .= "<tr>";
                $tr .= "<td class='text-center'>" . (isset($item["nrocta"]) ? $item["nrocta"] : $item["id"]) . "</td>";
                $tr .= "<td class='text-center'>" . $item["data"]["nrodoc"] . "</td>";
                $tr .= "<td class='text-center'>" . $item["data"]["respon"] . "</td>";
                $tr .= "<td class='text-left'>" . $item["data"]["vendedor"]["nombre"] . "</td>";
                $tr .= "<td class='text-left'>" . $item["data"]["telefn"] . "</td>";
                $tr .= "<td class='text-left'>";
                    $tr .= "<div class='d-flex justify-content-center'>" .
                        "<button data-toggle='tooltip' data-placement='left' title='blanquear contraseña' style='font-size: 12px;' onclick='passwordFunction(this,".$item['id'].")' class='btn text-center rounded-0 btn-dark'><i class='fas fa-key' aria-hidden='true'></i></button>" .
                        "<button data-toggle='tooltip' data-placement='left' title='ver datos' style='font-size: 12px;' onclick='dataFunction(this,".$item['id'].")' class='btn text-center rounded-0 btn-info'><i class='far fa-eye' aria-hidden='true'></i></button>" .
                        "<button data-toggle='tooltip' data-placement='left' title='ver carrito' style='font-size: 12px;' onclick='cartFunction(this,".$item['id'].")' class='btn text-center rounded-0 btn-warning'><i class='fas fa-shopping-cart' aria-hidden='true'></i></button>" .
                        "<button data-toggle='tooltip' data-placement='left' title='acceder como usuario' style='font-size: 12px;' onclick='accessFunction(this,".$item['id'].")' class='btn text-center rounded-0 btn-danger'><i class='fas fa-user' aria-hidden='true'></i></button>" .
                    "</div>";
                $tr .= "</td>";
            $tr .= "</tr>";
            return $tr;
        })->join("");

        $table .= "<table class='table table-striped table-borderless'>";
            $table .= "<thead class='thead-dark'>{$thead}</thead>";
            $table .= "<tbody>{$tbody}</tbody>";
        $table .= "</table>";
        $arr["tableOnly"] = $table;

        @endphp
        @include('layouts.general.table', $arr)
    </div>
</section>
@push('js')
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/alertify.js') }}"></script>
<script src="{{ asset('js/shortcut.js') }}"></script>

<script src="{{ asset('js/basic.js') }}"></script>
<script>
const sendMail = function(t) {
    axios.post(t.action)
    .then(function (res) {
    });
};
</script>
<script>
const cartFunction = function(t, id) {
    let formData = new FormData();
    let url = url_simple + url_basic + window.pyrus.getObjeto().TABLE + '/cart:' + id;
    $("#notification").removeClass("d-none").addClass("d-flex");
    $("#notification .notification--text").text("En proceso");
    window.pyrus.call(url, response => {
        let {data} = response;
        $("#notification").removeClass("d-flex").addClass("d-none");
        $("#notification .notification--text").text("");
        if (data.error === 0) {

            if (data.showBtn) {

                $('#btnClearCart').show();
                $('#btnClearCart').data('id', id);

            } else {

                $('#btnClearCart').hide();
                $('#btnClearCart').data('id', '');

            }
            window.localStorage.client = JSON.stringify(data.client);
            $('#modalClientCartLabel').text(data.client.razon_social);
            $('#modalClientCart tbody').html(data.data);
            $('#modalClientCart').modal('show');
            $('#modalClientCart p').text('');
            if (data.showBtn) {

                $('#modalClientCart p').text(`Última actualización: ${data.cart.updated_at}`)

            }

        } else {

            $('#btnClearCart').hide();
            Toast.fire({
                icon: 'error',
                title: data.txt
            });

        }
    }, "post", formData);
};
const accessFunction = function(t, id) {
    let formData = new FormData();
    let url = url_simple + url_basic + window.pyrus.getObjeto().TABLE + '/access:' + id
    window.pyrus.call(url, data => {
        if (data.data.error === 0) {
            window.open(url_simple + 'pedido','_blank');
        } else {
            Toast.fire({
                icon: 'error',
                title: data.data.message
            });
        }
    }, "post", formData);
};

const passwordFunction = (t, id) => {
    $('[data-toggle="tooltip"]').tooltip('hide');
    let pos = $(t).closest("tr").index();
    let client = window.data.elements.data[pos];
    let data = [
        `<p><strong>Cuenta:</strong> ${client.nrocta}</p>`,
        client.razon_social !== undefined ? `<p><strong>Razón social:</strong> ${client.razon_social}</p>` : '',
        client.nrodoc !== undefined ? `<p><strong>Documento:</strong> ${client.nrodoc}</p>` : '',
        client.telefn !== undefined ? `<p><strong>Télefono:</strong> ${client.telefn}</p>` : '',
        client.direml !== undefined ? `<p><strong>Email:</strong> <a target="blank" class="text-primary" href="mailto: ${client.direml}">${client.direml}</a><i class="fas fa-external-link-alt ml-1"></i></p>` : '',
        `<p><strong>Dirección:</strong> ${client.address.direccion} (${client.address.codpos}). ${client.address.provincia}, ${client.address.localidad}</p>`
    ];
    $("#modalClientPass form").attr("action", url_simple + url_basic + "clients/" + client._id);
    $("#modalClientPass .modal-body-data").html(data.join(""));
    $("#modalClientPass").modal("show");
};
const dataFunction = (t, id) => {
    $('[data-toggle="tooltip"]').tooltip('hide');
    let pos = $(t).closest("tr").index();
    let client = window.data.elements.data[pos];
    let data = [
        `<p><strong>Cuenta:</strong> ${client.nrocta}</p>`,
        client.razon_social !== undefined ? `<p><strong>Razón social:</strong> ${client.razon_social}</p>` : '',
        client.nrodoc !== undefined ? `<p><strong>Documento:</strong> ${client.nrodoc}</p>` : '',
        client.telefn !== undefined ? `<p><strong>Télefono:</strong> ${client.telefn}</p>` : '',
        client.direml !== undefined ? `<p><strong>Email:</strong> <a target="blank" class="text-primary" href="mailto: ${client.direml}">${client.direml}</a><i class="fas fa-external-link-alt ml-1"></i></p>` : '',
        `<p><strong>Dirección:</strong> ${client.address.direccion} (${client.address.codpos}). ${client.address.provincia}, ${client.address.localidad}</p>`
    ];
    if (client.vendedor !== undefined) {
        data.push(
            `<hr/>`,
            `<h4 class="text-center mb-2">Vendedor</h4>`,
            `<p><strong>Nombre:</strong> ${client.vendedor.nombre} (${client.vendedor.cod !== undefined ? client.vendedor.cod : client.vendedor.code})</p>`,
            client.vendedor.email !== undefined && client.vendedor.email !== null ? `<p><strong>Email:</strong> <a target="blank" class="text-primary" href="mailto: ${client.vendedor.email}">${client.vendedor.email}</a><i class="fas fa-external-link-alt ml-1"></i></p>` : '',
            client.vendedor.telefono !== undefined && client.vendedor.telefono !== null ? `<p><strong>Teléfono:</strong> ${client.vendedor.telefono}</p>` : '',
        );
    } else {
        data.push(
            `<hr/>`,
            `<h4 class="">Sin vendedor</h4>`
        );
    }
    if (client.transportista !== undefined) {
        data.push(
            `<hr/>`,
            `<h4 class="text-center mb-2">Transportista</h4>`,
            `<p><strong>Nombre:</strong> ${client.transportista.nombre} (${client.transportista.cod !== undefined ? client.transportista.cod : client.transportista.code})</p>`
        );
    } else {
        data.push(
            `<hr/>`,
            `<h4 class="">Sin transporte</h4>`
        );
    }
    $("#modalClient .modal-body").html(data.join(""));
    $("#modalClient").modal("show");
};
const passwordSubmit = t => {
    let formData = new FormData(t);
    Toast.fire({
        icon: 'warning',
        title: 'Espere'
    });
    $("#input-pass, #input-notice").prop("readonly", true);
    window.pyrus.call(t.action, data => {
        'use strict'

        $("#input-pass, #input-notice").prop("readonly", false);
        if (data.data.error === 0) {
            Toast.fire({
                icon: 'success',
                title: data.data.message
            });
            $("#modalClientPass").modal("hide");
        } else {
            Toast.fire({
                icon: 'error',
                title: data.data.message
            });
        }
    }, "post", formData);
};

$('#modalClientPass').on('hidden.bs.modal', function (e) {
    $("#modalClientPass form").attr("action", "");
    $("#modalClientPass .modal-body-data").html("");
    $("#input-pass").val("");
    $("#input-notice").prop("checked", false)
});

$('#btnClearCart').on('click', function() {
    $('#modalClientCart').modal('hide');
    Swal.fire({
        title: "Atención!",
        html: "¿Limpiar el carrito de "+$('#modalClientCartLabel').text()+"?<br/><small>Esta acción se verá reflejada cuando el cliente se loguee o actualice su navegador</small>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            let formData = new FormData();
            let client = JSON.parse(window.localStorage.client);
            formData.set('username', client.nrodoc);
            formData.set('empty', 1);
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            $("#notification").removeClass("d-none").addClass("d-flex");
            $("#notification .notification--text").text("En proceso");
            Connect.post(`${url_simple}pedido/checkout`, formData, data => {
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
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
            });
        }
    });
});
</script>
@endpush