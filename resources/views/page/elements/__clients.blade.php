@isset($data["clients"])
<div class="search search--one">
    <select id="clientList" class="form-control">
        <option value="">Seleccione cliente</option>
        @foreach($data["clients"] AS $client)
        @php
        $selected = "";
        if (session()->has('nrocta_client') && session()->get('nrocta_client') == $client->nrocta)
            $selected = "selected=true";
        @endphp
        <option {{ $selected }} value="{{ $client->nrocta }}">{{ $client->nrocta }} | {{ $client->razon_social }} @if(!empty($client->direml))({{ $client->direml }})@endif</option>
        @endforeach
    </select>
</div>
@endisset