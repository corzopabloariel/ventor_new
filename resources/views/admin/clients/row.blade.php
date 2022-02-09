<tr>
    <td>{{$client['nroCta']}}</td>
    <td>{{$client['nroDoc']}}</td>
    <td>{{$client['razonSocial']}}</td>
    <td>@isset($client['address']){{$client['address']['direccion']}}.<br/>{{$client['address']['provincia']}}, {{$client['address']['localidad']}} ({{$client['address']['codpos']}})@endisset</td>
    <td>#{{$client['seller']['code']}} - {{$client['seller']['nombre']}}</td>
    <td>@isset($client['transport']) #{{$client['transport']['code']}} - {{$client['transport']['nombre']}} @endisset</td>
    <td>{{$client['phone']}}</td>
    <td>
        <div class="d-flex justify-content-center">
            <button data-toggle="tooltip" data-placement="left" title="Blanquear contraseña" style="font-size: 12px;" onclick="passwordFunction(this, {{$client['userId']}})" class="btn text-center rounded-0 btn-dark"><i class="fas fa-key" aria-hidden="true"></i></button>
            <button data-toggle="tooltip" data-placement="left" title="Ver carrito" style="font-size: 12px;" onclick="cartFunction(this, {{$client['userId']}})" class="btn text-center rounded-0 btn-warning"><i class="fas fa-shopping-cart" aria-hidden="true"></i></button>
            <button data-toggle="tooltip" data-placement="left" title="Acceder como {{$client['razonSocial']}}" style="font-size: 12px;" onclick="accessFunction(this, {{$client['userId']}})" class="btn text-center rounded-0 btn-danger"><i class="fas fa-user" aria-hidden="true"></i></button>
        </div>
    </td>
</tr>