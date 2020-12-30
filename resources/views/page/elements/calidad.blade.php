@push('styles')
    <link href="{{ asset('css/page/calidad.css') }}" rel="stylesheet">
@endpush
<div class="wrapper-calidad bg-white py-5">
    <div class="container">
        <h2 class="title">{{ $data["content"]["titulo"] }}</h2>
        <h3 class="title">{{ $data["content"]["subtitulo"] }}</h3>
        <div class="row mt-3">
            <div class="col-12 col-md">{!! $data["content"]["texto"] !!}</div>
            <div class="col-12 col-md frase">{!! $data["content"]["frase"] !!}</div>
        </div>
    </div>
</div>
<div class="wrapper-calidad py-5">
    <div class="container pb-3">
        <div class="row">
            <div class="col-12">
                <div class="list-group list-group-horizontal" role="tablist">
                    <a class="list-group-item list-group-item-action bg-transparent active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">{{ $data["content"]["politica"]["titulo"] }}</a>
                    <a class="list-group-item list-group-item-action bg-transparent" id="list-home-list2" data-toggle="list" href="#list-home2" role="tab" aria-controls="home2">{{ $data["content"]["garantia"]["titulo"] }}</a>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                        {!! $data["content"]["politica"]["texto"] !!}
                    </div>
                    <div class="tab-pane fade" id="list-home2" role="tabpanel" aria-labelledby="list-profile-list">
                        {!! $data["content"]["garantia"]["texto"] !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>