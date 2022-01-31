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
        $price = str_replace('.', ',', $product->precio);
        $brands = $product->brands;
        $models = $product->models->map(function($item) {

            return $model->name;

        })->join(', ');
        $subpart = $product->subpart->name ?? '';
        @endphp
        @foreach($brands AS $brand)
        <tr>
            <td>{{$product->stmpdh_art}}</td>
            <td>{{$product->stmpdh_tex}}</td>
            <td>{{$brand->name}}</td>
            <td>{{$models}}</td>
            <td>{{$subpart}}</td>
            <td>{{$price}}</td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>