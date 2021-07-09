
@foreach($products as $product)
@php
$modelo = $product->modelo_anio ?? '';
$parte = $product->subparte['name'] ?? '';
if (empty($parte))
    $parte = $product->stmpdh_art;
else
    $parte .= " ({$product->stmpdh_art})";
@endphp
"{{$product->stmpdh_art}}";"{{$parte}}";{{$product->precio}}
@endforeach