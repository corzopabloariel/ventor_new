<div class="table-responsive">
    <table class="table table-striped table-borderless mb-0">
        <thead class="thead-dark">
            <tr>
                <th>FECHA</th>
                <th>ESTADO</th>
                <th>PEDIDO</th>
                <th>TIPO</th>
                <th>IP</th>
                <th>DE</th>
                <th>A</th>
                <th>TÍTULO</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($elements AS $email)
                @include('admin.emails.row', ['email' => $email])
            @endforeach
        </tbody>
    </table>
</div>
@include('admin.helper.paginator')