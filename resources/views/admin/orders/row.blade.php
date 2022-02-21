<tr>
    <td>{{date("d/m/Y H:i", strtotime($order->created_at))}}</td>
    <td>{{$order->title}}</td>
    <td>{{$order->client->nrocta ?? '-'}}</td>
    <td>{{$order->transport->description ?? '-'}}</td>
    <td>{{$order->seller->name ?? '-'}}</td>
    <td>
        {!!($order->sent ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>')!!}
        {{ implode(', ', $order->email ?? array()) }}
    </td>
    <td>
        <div class="d-flex justify-content-center">
            <button data-toggle="tooltip" data-placement="left" title="Descargar XLS" style="font-size: 12px;" onclick="downloadXLS(this, {{$order->id}})" class="btn text-center rounded-0 btn-success"><i class="fas fa-file-excel"></i></button>
            <button data-toggle="tooltip" data-placement="left" title="Descargar PDF" style="font-size: 12px;" onclick="downloadPDF(this, {{$order->id}})" class="btn text-center rounded-0 btn-danger"><i class="fas fa-file-pdf"></i></button>
            <button data-toggle="tooltip" data-placement="left" title="Reenvio del PEDIDO a GMX" style="font-size: 12px;" onclick="forwardFunction(this, {{$order->id}})" class="btn text-center rounded-0 btn-primary"><i class="fas fa-reply"></i></button>
        </div>
    </td>
</tr>