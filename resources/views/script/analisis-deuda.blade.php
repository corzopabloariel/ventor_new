<script>

    $(document).ready(async function() {

        let response = await axios.post('{{ route('ventor.ajax.clients')}}', {fromHeader: 1});
        let {data} = response;
        if (data.clients.length == 0) {

            console.log("SOS CLIENTE");

        } else {

            let {clients} = data;
            clients.unshift({id: '', text: '', selected: 'selected', search:'', hidden:true });
            $('#ventorClient').html('<select style="width: 100%"></select>');
            $('#ventorClient select').select2({
                data: clients,
                placeholder: {
                    id: '',
                    text: 'Seleccione cliente para traer la información',
                    selected:'selected',
                    search: '',
                    hidden: true
                }
            });

        }
        $('#ventorProducts .overlay').removeClass('--active');

    });
    $(document).on('change', '#ventorClient select', async function (e) {

        let userId = $(this).val();
        let response = await axios.post('{{ route('ventor.ajax.clientAction')}}', {type: 'analisis-deuda', userId});
        let {data} = response;
        if (!data.error) {

        }
        console.log(data)

    })

</script>