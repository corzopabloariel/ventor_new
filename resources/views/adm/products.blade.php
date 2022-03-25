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
        $thead = ["CÓDIGO", "NOMBRE", "PRECIO", "PARTE", "SUBPARTE", "INGRESO"];
        $table = $tbody = "";
        $thead = collect($thead)->map(function($item) {
            return "<th>{$item}</th>";
        })->join("");
        foreach($data["elements"] AS $item) {

            $tbody .= "<tr>";
                $tbody .= "<td class='text-left'>" . $item["stmpdh_art"] . "</td>";
                $tbody .= "<td class='text-left'>" . $item["stmpdh_tex"] . "</td>";
                $tbody .= "<td class='text-right'>" . $item["precio"] . "</td>";
                $tbody .= "<td class='text-left'>" . $item->part->name . "</td>";
                $tbody .= "<td class='text-left'>" . $item->subpart->name . "</td>";
                $tbody .= "<td class='text-center'>" . $item["fecha_ingr"] . "</td>";
            $tbody .= "</tr>";

        }

        $table .= "<table class='table table-striped table-borderless'>";
            $table .= "<thead class='thead-dark'>{$thead}</thead>";
            $table .= "<tbody>{$tbody}</tbody>";
        $table .= "</table>";
        $arr["tableOnly"] = $table;

        @endphp
        @include('layouts.general.table', $arr)
    </div>
</section>
@push('js')
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/alertify.js') }}"></script>
<script src="{{ asset('js/shortcut.js') }}"></script>

<script src="{{ asset('js/basic.js') }}"></script>
<script>
    const categoriesFunction = function(t) {

        let url = url_simple + url_basic + "products/categories";
        window.location.href = url;

    };
    const sendMail = function(t) {
        axios.post(t.action)
        .then(function (res) {
        });
    }
</script>
@endpush