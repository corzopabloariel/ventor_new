@php
$button = $button ?? '<button class="btn btn-success button--form px-5 text-uppercase btn-lg"><i class="fas fa-save"></i></button>';
$required = $required ?? '<p class="form-required">Datos necesarios</p>';
@endphp

@if($buttonADD)
<button id="btnADD" onclick="add(this)" class="btn position-fixed rounded-circle btn-primary text-uppercase" type="button"><i class="fas fa-plus"></i></button>
@endif
@if(isset($data["buttons"]))
<div class="my-2 justify-content-between d-flex">
    <div>
    @isset($buttons)
        @foreach($buttons AS $d)
        <button @if(!isset($d['f'])) disabled @else onclick="{{ $d['f'] }}Function(this);" @endif class="btn {{ $d[ 'b' ] }} text-center button--form">
            <i class="{{ $d[ 'i' ] }}" aria-hidden="true"></i> {{ $d[ 't' ] }}
        </button>
        @endforeach
    @endisset
    @foreach($data["buttons"] AS $d)
    <button @if(!isset($d['f'])) disabled @else onclick="{{ $d['f'] }}Function(this);" @endif class="btn {{ $d[ 'b' ] }} text-center button--form">
        <i class="{{ $d[ 'i' ] }}" aria-hidden="true"></i> {{ $d[ 't' ] }}
    </button>
    @endforeach
    </div>
    @isset($data["total"])
    <div class="d-flex align-items-center">
        Registros: {{ $data["total"] }}
    </div>
    @endisset
</div>
@endif
@if(!$modal)
<div @if(!$form) style="display: none;" @endif id="wrapper-form" class="mt-3">
    <div class="card border-0">
        <div class="card-body">
            @if($close)
            <button onclick="remove(this)" type="button" class="close close-form position-absolute" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times"></i></span>
            </button>
            @endif
            <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" class="pt-2" action="@isset( $url ) {{ $url }} @endisset" @if( $buttonADD ) method="post" @else method="put" @endif enctype="multipart/form-data">
                @csrf
                {!! $button !!}
                {!! $required !!}
                <div class="container-form pyrus--form py-3 my-3"></div>
                <div class="d-flex justify-content-end">
                    {!! $button !!}
                </div>
            </form>
        </div>
    </div>
</div>
@else
@push('modal')
<div class="modal fade bd-example-modal-lg" id="formModal" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="formModalLabel"></h5>
                <button type="button" class="close" onclick="remove( this );">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" action="@isset( $url ) {{ $url }} @endisset" @if( $buttonADD ) method="post" @else method="put" @endif enctype="multipart/form-data">
                @csrf
                <p class="form-required shadow-sm">Datos necesarios</p>
                <div class="modal-body pyrus--form"></div>
                <div class="modal-footer bg-light">
                    <button type="button" onclick="remove( this );" class="btn button--form btn-danger px-5 text-uppercase">cerrar</button>
                    <button class="btn button--form btn-success px-5 text-uppercase"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endif