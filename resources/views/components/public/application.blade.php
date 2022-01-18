<div class="card-app">

    <div class="card-app__content">
        <p class="card-app__title">{{$application['title']}}</p>
    </div>
    <div class="card-app__content">
        <img alt="{{$application['title']}}" class="card-app__image" loading="lazy" src="{{$application['image']}}">
    </div>

</div>

@isset($application['A'])
    @includeIf('components.public.product', [
        'product'   => $application['A'],
        'markup'    => session()->has('markup') ? session()->get('markup') : 'costo',
        'cart'      => $dataCartProducts ? collect($dataCartProducts['element'])->firstWhere('product', $product['path']) : null,
        'tag'       => 'Pasajero'
    ])
@endisset
@isset($application['C'])
    @includeIf('components.public.product', [
        'product'   => $application['C'],
        'markup'    => session()->has('markup') ? session()->get('markup') : 'costo',
        'cart'      => $dataCartProducts ? collect($dataCartProducts['element'])->firstWhere('product', $product['path']) : null,
        'tag'       => 'Conductor'
    ])
@endisset
@isset($application['T'])
    @includeIf('components.public.product', [
        'product'   => $application['T'],
        'markup'    => session()->has('markup') ? session()->get('markup') : 'costo',
        'cart'      => $dataCartProducts ? collect($dataCartProducts['element'])->firstWhere('product', $product['path']) : null,
        'tag'       => 'Luneta'
    ])
@endisset