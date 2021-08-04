<table>
    <thead style="font-size: 10px; font-family: Arial;">
        <tr>
            <th style="text-align: center;">CÓDIGO</th>
            <th style="text-align: center;">DESCRIPCIÓN</th>
            <th style="text-align: center;">MARCA</th>
            <th style="text-align: center;">MODELO / AÑO</th>
            <th style="text-align: center;">PARTE</th>
            <th style="text-align: center;">PRECIO</th>
        </tr>
    </thead>
    <tbody style="font-size: 11px; font-family: Calibri;">
    @foreach($products as $product)
        @php
        $precio = str_replace('.', ',', $product->precio);
        $modelo = $product->modelo_anio ?? '';
        $parte = $product->subparte['name'] ?? '';
        @endphp
        @foreach($product->web_marcas AS $marca)
        <tr>
            <td>{{$product->stmpdh_art}}</td>
            <td>{{$product->stmpdh_tex}}</td>
            <td>{{$marca['brand']}}</td>
            <td>{{$modelo}}</td>
            <td>{{$parte}}</td>
            <td>{{$precio}}</td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>