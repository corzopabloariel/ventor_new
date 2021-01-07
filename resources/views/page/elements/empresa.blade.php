@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/ycodetech/horizontal-timeline-2.0@2/JavaScript/horizontal_timeline.2.0.min.js"></script>
    <script>
    $(() => {
        $('#timeline').horizontalTimeline({
            dateDisplay: "year"
        });
    });
    </script>
@endpush
@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/ycodetech/horizontal-timeline-2.0@2/css/horizontal_timeline.2.0.min.css">
    <link href="{{ asset('css/page/empresa.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
<section>
    <div class="empresa">
        <div class="container">
            <div class="container--empresa">
                <div class="text">{!! $data["content"]["texto"] !!}</div>
                <div class="number">
                    @foreach($data["content"]["numero"] AS $n )
                    <div class="row mt-3 numeros">
                        <div class="col-12 col-md-5 d-flex justify-content-end">{{ number_format( $n["numero"] , 0 , "," , "." ) }}</div>
                        <div class="col-12 col-md">{!! $n["texto"] !!}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if (!empty($data["content"]["anio"]))
        <div class="container container--timeline">
            <div class="horizontal-timeline" id="timeline">
                <div class="events-content">
                    <ol>
                        @for($i = 0; $i < count($data["content"]["anio"]); $i++)
                            <li @if($i == 0) class="selected" @endif data-horizontal-timeline='{"date": "01/01/{{ $data["content"]["anio"][$i]["order"] }}"}'>
                                {!! $data["content"]["anio"][$i]["texto"] !!}
                            </li>
                        @endfor
                    </ol>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>