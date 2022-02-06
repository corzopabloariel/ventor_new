<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/alertify.js') }}"></script>
<script>
    const passwordSubmit = async (t) => {

        var response = await axios.post(t.action, {
            password: $('#input-pass').val()
        });
        console.log(response)

    };
    const passwordFunction = (t, id) => {

        $('[data-toggle="tooltip"]').tooltip('hide');
        let tr = $(t).closest("tr");
        var [nroCta, nroDoc, razonSocial, , , , telefono, ] = tr.find('td');
        let data = [
            `<p><strong>Cuenta:</strong> ${nroCta.textContent}</p>`,
            `<p><strong>Documento:</strong> ${nroDoc.textContent}</p>`,
            `<p><strong>Télefono:</strong> ${telefono.textContent}</p>`,
            `<p><strong>Razón social:</strong> ${razonSocial.textContent}</p>`
        ];
        $("#modalClientPass form").attr("action", "{{URL::to('adm/clients')}}/"+id);
        $("#modalClientPass .modal-body-data").html(data.join(""));
        $("#modalClientPass").modal("show");

    };
</script>