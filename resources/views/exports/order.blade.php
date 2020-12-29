<table>
    <thead>
    <tr>
        <th>exp_1</th>
        <th>exp_2</th>
        <th>cod</th>
        <th>exp_4</th>
        <th>cnt</th>
        <th>precio</th>
        <th>bonif1</th>
        <th>bonif2</th>
        <th>observ</th>
        <th>cliente</th>
        <th>destrp</th>
        <th>dirtrp</th>
        <th>idpedido</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order['exp_1'] }} </td>
            <td>{{ $order['exp_2'] }} </td>
            <td>{{ $order['cod'] }} </td>
            <td>{{ $order['exp_4'] }} </td>
            <td>{{ $order['cnt'] }} </td>
            <td>{{ $order['precio'] }} </td>
            <td>{{ $order['bonif1'] }} </td>
            <td>{{ $order['bonif2'] }} </td>
            <td>{{ $order['observ'] }} </td>
            <td>{{ $order['cliente'] }} </td>
            <td>{{ $order['destrp'] }} </td>
            <td>{{ $order['dirtrp'] }} </td>
            <td>{{ $order['idpedido'] }} </td>
        </tr>
    @endforeach
    </tbody>
</table>