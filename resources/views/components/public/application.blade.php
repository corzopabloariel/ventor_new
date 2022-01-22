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
        'markup'    => $markup,
        'cart'      => $dataCartProducts ? collect($dataCartProducts['element'])->firstWhere('product', $application['A']['path']) : null,
        'tag'       => 'Pasajero'
    ])
@endisset
@isset($application['C'])
    @includeIf('components.public.product', [
        'product'   => $application['C'],
        'markup'    => $markup,
        'cart'      => $dataCartProducts ? collect($dataCartProducts['element'])->firstWhere('product', $application['C']['path']) : null,
        'tag'       => 'Conductor'
    ])
@endisset
@isset($application['T'])
    @includeIf('components.public.product', [
        'product'   => $application['T'],
        'markup'    => $markup,
        'cart'      => $dataCartProducts ? collect($dataCartProducts['element'])->firstWhere('product', $application['T']['path']) : null,
        'tag'       => 'Luneta'
    ])
@endisset