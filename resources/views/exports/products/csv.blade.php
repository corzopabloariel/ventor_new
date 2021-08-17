<table>
    <thead>
        <tr>
            <th>CÓDIGO</th>
            <th>DESCRIPCIÓN</th>
            <th>PRECIO</th>
        </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{$product->stmpdh_art}}</td>
            <td>{{$product->stmpdh_tex}}</td>
            <td>{{$product->precio}}</td>
        </tr>
    @endforeach
    </tbody>
</table>