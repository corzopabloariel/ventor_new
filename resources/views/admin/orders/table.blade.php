<div class="table-responsive">
    <table class="table table-striped table-borderless mb-0">
        <thead class="thead-dark">
            <tr>
                <th>FECHA</th>
                <th>TÍTULO</th>
                <th>CLIENTE</th>
                <th>TRANSPORTE</th>
                <th>VENDEDOR</th>
                <th>ENVIADO</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($elements AS $order)
                @include('admin.orders.row', ['order' => $order])
            @endforeach
        </tbody>
    </table>
</div>
@include('admin.helper.paginator')