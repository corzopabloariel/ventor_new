<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/alertify.js') }}"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-start',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    const downloadPDF = async function(t, orderId) {

        var slug = '{{ route("ventor.ajax.order.pdf", ":order") }}';
        slug = slug.replace(':order', orderId);
        var response = await axios({
            url: slug,
            method: 'POST',
            data: {slug},
            responseType: 'blob',
        });
        var {data} = response;
        var file = new Blob([data], {type: 'application/pdf'});
        var fileURL = URL.createObjectURL(file);
        window.open(fileURL);

    };
    const forwardFunction = async function(t, orderId) {

        var dataMailGMX = {
            id: orderId,
            is_test: false,
            type: 'order'
        };
        var responseMailGMX = await axios.post('{{ route('ventor.ajax.mail')}}', dataMailGMX);
        var dataGMX = responseMailGMX.data;
        Toast.fire({
            icon: dataGMX.error ? 'error' : 'success',
            title: dataGMX.message
        });

    };
    const downloadXLS = async function(t, orderId) {

        var url = '{{ route("api.order.export", ":orderId") }}';
        url = url.replace(':orderId', orderId);
        var response = await axios({
            url,
            method: 'POST',
            data,
            responseType: 'blob',
        });
        var a = $("<a style='display: none;'/>");
        var {data} = response;
        if (data.size > 0) {

            var file = new Blob([data], {type: 'application/vnd.ms-excel'});
            var fileURL = URL.createObjectURL(file);
            a.attr('href', fileURL);
            a.attr('download', 'PEDIDO-'+orderId+'.xls');
            $('body').append(a);
            a[0].click();
            window.URL.revokeObjectURL(fileURL);
            a.remove();

        }

    };
</script>