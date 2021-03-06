<section class="my-3">
    <div class="container-fluid">
        @isset($data["section"])
            @include('layouts.general.breadcrumb', ['section' => $data["section"]])
        @endisset
        @isset($data["help"])
            {!! $data["help"] !!}
        @endisset
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
        $thead = ["#", "FECHA", "CLIENTE", "VENDEDOR", "TRANSPORTE", "PRODUCTOS", ""];
        $table = $tbody = "";
        $thead = collect($thead)->map(function($item) {
            return "<th>{$item}</th>";
        })->join("");
        $route = [
            route('order.pdf'),
            route('order.send'),
            route('order.xls')
        ];
        $tbody = collect($data["elements"]->toArray()["data"])->map(function($item) use ($route) {
            $tr = "";
            $tr .= "<tr>";
                $tr .= "<td class='text-center'>" . (isset($item["uid"]) ? $item["uid"] : $item["_id"]) . "</td>";
                $tr .= "<td class='text-center'>" . date("d/m/Y H:i", strtotime($item["created_at"])) . "</td>";
                $tr .= "<td data-column='client'>";
                    if (isset($item["client"])) {
                        $tr .= "<p>{$item["client"]["razon_social"]} ({$item["client"]["nrocta"]})</p>";
                        if (isset($item["client"]["direml"])) {
                            $tr .= "<p>{$item["client"]["direml"]}</p>";
                        }
                        if (isset($item["client"]["telefn"])) {
                            $tr .= "<p>{$item["client"]["telefn"]}</p><hr/>";
                        }
                        if (isset($item["client"]["nrodoc"])) {
                            $tr .= "<p>{$item["client"]["nrodoc"]}</p>";
                        }
                    }
                $tr .= "</td>";
                $tr .= "<td data-column='seller'>";
                    if (isset($item["seller"])) {
                        $attr = isset($item["seller"]["code"]) ? "code" : "cod";
                        $tr .= "<p>{$item["seller"]["nombre"]} ({$item["seller"][$attr]})</p>";
                        if (isset($item["seller"]["email"])) {
                            $tr .= "<p>{$item["seller"]["email"]}</p>";
                        }
                        if (isset($item["seller"]["telefono"])) {
                            $tr .= "<p>{$item["seller"]["telefono"]}</p>";
                        }
                    }
                $tr .= "</td>";
                $tr .= "<td data-column='transport'>";
                    if (isset($item["transport"])) {
                        if (isset($item["transport"]["description"]) && isset($item["transport"]["code"])) {
                            $tr .= "<p>{$item["transport"]["description"]} ({$item["transport"]["code"]})</p>";
                        }
                        if (isset($item["transport"]["address"])) {
                            $tr .= "<p>{$item["transport"]["address"]}</p>";
                        }
                    }
                $tr .= "</td>";
                $tr .= "<td class='text-center'>" . count($item["products"]) . "</td>";
                $tr .= "<td style='width: 90px;'>";
                    $tr .= "<div class='d-flex'>";
                        $tr .= "<form action='{$route[0]}' target='_blank' method='post'>";
                            $tr .= '<input type="hidden" name="_token" value="' . csrf_token() . '" />';
                            $tr .= '<input type="hidden" name="order_id__pedidos" value="' . $item["_id"] . '">';
                            $tr .= "<button title='Descargar pdf' class='btn btn-danger'><i class='fas fa-file-pdf'></i></button>";
                        $tr .= "</form>";
                        $tr .= "<form action='{$route[2]}' target='_blank' method='post'>";
                            $tr .= '<input type="hidden" name="_token" value="' . csrf_token() . '" />';
                            $tr .= '<input type="hidden" name="order_id__pedidos" value="' . $item["_id"] . '">';
                            $tr .= "<button title='Descargar excel' class='btn btn-dark'><i class='fas fa-file-excel'></i></button>";
                        $tr .= "</form>";
                    $tr .= "</div>";
                $tr .= "</td>";
            $tr .= "</tr>";
            return $tr;
        })->join("");

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
const sendMail = function(t) {
    axios.post(t.action)
    .then(function (res) {
    });
}
</script>
@endpush