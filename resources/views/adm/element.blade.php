@push('styles')
@includeIf('adm.styles.' . $data["entity"])
@endpush
@push('modal')
@includeIf('adm.modal.' . $data["entity"])
@endpush
<section class="my-3">
    <div class="container-fluid">
        @isset($data["section"])
            @include('layouts.general.breadcrumb', ['section' => $data["section"]])
        @endisset
        @isset($data["help"])
            {!! $data["help"] !!}
        @endisset
        @include('layouts.general.form', ['buttonADD' => 1, 'form' => 0, 'close' => 1, 'modal' => 1])
        @php
        $arr = [];
        if (isset($data["url_search"]))
            $arr["form"] = [
                "url" => $data["url_search"] ?? "/",
                "placeholder" => "Buscar en " . ($data["placeholder"] ?? "No definido"),
                "search" => isset($data["search"]) ? $data["search"] : null
            ];
        if (isset($data["elements"]) && !isset($data["notPaginate"]))
            $arr["paginate"] = $data["elements"];
        @endphp
        @include('layouts.general.table', $arr)
    </div>
</section>
@push('js')
<script src="//cdn.ckeditor.com/4.13.1/full/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/color.js') }}"></script>
<script src="{{ asset('js/solver.js') }}"></script>
<script src="{{ asset('js/alertify.js') }}"></script>
<script src="{{ asset('js/shortcut.js') }}"></script>

<script src="{{ asset('js/pyrus.js') }}"></script>
<script src="{{ asset('js/basic.js') }}"></script>
<script src="{{ asset('js/declarations.js') }}"></script>

@includeIf('adm.scripts.' . $data["entity"])
<script>
    if (window.pyrus === undefined)
        window.pyrus = new Pyrus(entity);

    /** -------------------------------------
     *      INICIO
     ** ------------------------------------- */
    init(r => {
        if (data.values_form !== undefined) {
            data.values_form.forEach(x => {
                const e = document.querySelector(`#${x.id}`);
                if (e)
                    e.value = x.value;
            });
        }

        if (!(Array.isArray(window.pyrus) ? window.pyrus[0].entidad.getObjeto().ADD : window.pyrus.getObjeto().ADD)) {
            const b = document.querySelector('#btnADD');
            if (b)
                b.remove();
        }

        if (data.search) {
            data.searchIn.forEach(c => {
                Array.prototype.forEach.call(document.querySelectorAll(`td[data-column="${c}"]`), td => {
                    Array.prototype.forEach.call(td.querySelectorAll(".highlight"), h => {
                        $(h).highlight(data.search, "text-warning");
                    });
                });
            });
        }
    },
        true,
        true,
        "table",
        true,
        Array.isArray(window.pyrus) ? window.pyrus[0].entidad.getObjeto().BTN : window.pyrus.getObjeto().BTN,
        data.buttons === undefined ?
            null:
            data.buttons.map(x => {
                if (x.f === undefined) return x;
            }).filter(e => e),
        Array.isArray(window.pyrus) ? window.pyrus[0].entidad.getObjeto().REFRESH : window.pyrus.getObjeto().REFRESH);
</script>
@endpush