<button class="btn btn-light border-0" data-toggle="modal" data-target="#partesModal" id="btnPartesModal">PARTES</button>
@isset($data["clients"])
<div class="search search--one">
    <select id="clientList" class="form-control selectpicker" multiple data-max-options="1" data-container="body" data-header="Seleccione cliente" data-live-search="true" data-style="btn-white" data-width="100%" title="Seleccione cliente" onchange="selectClient(this);">
        @foreach($data["clients"] AS $client)
        @php
        $selected = "";
        if (session()->has('nrocta_client') && session()->get('nrocta_client') == $client->nrocta)
            $selected = "selected=true";
        @endphp
        <option {{ $selected }} data-subtext="{{ $client->nrocta }}" value="{{ $client->nrocta }}">{{ $client->razon_social }} @if(!empty($client->direml))({{ $client->direml }})@endif</option>
        @endforeach
    </select>
</div>
@endisset