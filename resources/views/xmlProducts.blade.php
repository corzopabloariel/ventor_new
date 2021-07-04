<?php
header ("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<products>
    @foreach($products AS $product)
        @php
        $precio = str_replace('.', ',', $product->precio);
        $modelo = $product->modelo_anio ?? '';
        $parte = $product->subparte['name'] ?? '';
        @endphp
        <product>
            <codigo>{{$product->use}}</codigo>
            <descripcion>{{$product->stmpdh_tex}}</descripcion>
            <marca>{{$product->web_marcas}}</marca>
            <modelo>{{$modelo}}</modelo>
            <parte>{{$parte}}</parte>
            <precio>{{$precio}}</precio>
        </product>
    @endforeach
</products>