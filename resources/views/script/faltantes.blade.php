<script>

    async function search(userId) {

        $('#ventorProducts .overlay').addClass('--active');
        let response = await axios.post('{{ route('ventor.ajax.clientAction')}}', {type: 'faltantes', userId});
        let {data} = response;console.log(data)
        if (!data.error) {

            $('#ventorData').html(
                '<div class="ventorClient__table">' +
                    '<table>' +
                    data.thead +
                    data.tbody +
                    '</table>' +
                '</div>'
            )

        } else {

            $('#ventorData').html(
                '<div class="ventorClient__table">' +
                    '<h2 class="section__title" style="text-align: center">' + data.message + '</h2>' +
                '</div>'
            );

        }
        $('#ventorProducts .overlay').removeClass('--active');

    }
    $(document).ready(async function() {

        let response = await axios.post('{{ route('ventor.ajax.clients')}}', {fromHeader: 1});
        let {data} = response;
        if (data.client !== undefined) {

            let {userId} = data.client.elements[0];
            search(userId);

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
        search(userId);

    });

</script>