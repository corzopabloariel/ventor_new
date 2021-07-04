<?php
header ("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<products>
    @foreach($products AS $product)
    <product>
        <codigo>{{$product->use}}</codigo>
        <descripcion>{{$product->stmpdh_tex}}</descripcion>
        <marca>{{$product->web_marcas}}</marca>
        <modelo>{{$product->modelo_anio}}</modelo>
        <parte>{{$product->subparte['name']}}</parte>
        <precio>{{str_replace('.', ',', $product->precio)}}</precio>
    </product>
    @endforeach
</products>