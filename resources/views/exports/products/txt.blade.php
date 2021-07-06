
@foreach($products as $product)
@php
$modelo = $product->modelo_anio ?? '';
$parte = $product->subparte['name'] ?? '';
if (empty($parte))
    $parte = $product->use;
else
    $parte .= " ({$product->use})";
@endphp
"{{$product->stmpdh_art}}";"{{$parte}}";{{$product->precio}}
@endforeach