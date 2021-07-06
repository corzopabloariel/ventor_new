
@foreach($products as $product)
@php
$modelo = $product->modelo_anio ?? '';
$parte = $product->subparte['name'] ?? '';
@endphp
"{{$product->stmpdh_art}}";"{{$parte}} ({{$product->use}})";{{$product->precio}}
@endforeach