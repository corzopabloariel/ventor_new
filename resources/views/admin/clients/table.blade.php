<div class="table-responsive">
    <table class="table table-striped table-borderless mb-0">
        <thead class="thead-dark">
            <tr>
                <th>CUENTA</th>
                <th>DOCUMENTO</th>
                <th>RAZÓN SOCIAL</th>
                <th>DIRECCIÓN</th>
                <th>VENDEDOR</th>
                <th>TRANSPORTE</th>
                <th>TELÉFONO</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($elements AS $client)
                @include('admin.clients.row', ['client' => $client])
            @endforeach
        </tbody>
    </table>
</div>
@include('admin.helper.paginator')