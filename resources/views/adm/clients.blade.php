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
        $thead = ["CUENTA", "DOCUMENTO", "RAZÓN SOCIAL", "VENDEDOR", "TELÉFONO", ""];
        $table = $tbody = "";
        $thead = collect($thead)->map(function($item) {
            return "<th>{$item}</th>";
        })->join("");
        $tbody = collect($data["elements"]->toArray()["data"])->map(function($item) {
            $tr = "";
            $tr .= "<tr>";
                $tr .= "<td class='text-center'>" . (isset($item["nrocta"]) ? $item["nrocta"] : $item["id"]) . "</td>";
                $tr .= "<td class='text-center'>" . $item["data"]["nrodoc"] . "</td>";
                $tr .= "<td class='text-center'>" . $item["data"]["respon"] . "</td>";
                $tr .= "<td class='text-left'>" . $item["data"]["vendedor"]["nombre"] . "</td>";
                $tr .= "<td class='text-left'>" . $item["data"]["telefn"] . "</td>";
                $tr .= "<td class='text-left'>";
                    $tr .= "<div class='d-flex justify-content-center'>" .
                        "<button data-toggle='tooltip' data-placement='left' title='blanquear contraseña' style='font-size: 12px;' onclick='passwordFunction(this,".$item['id'].")' class='btn text-center rounded-0 btn-dark'><i class='fas fa-key' aria-hidden='true'></i></button>" .
                        "<button data-toggle='tooltip' data-placement='left' title='ver datos' style='font-size: 12px;' onclick='dataFunction(this,".$item['id'].")' class='btn text-center rounded-0 btn-info'><i class='far fa-eye' aria-hidden='true'></i></button>" .
                        "<button data-toggle='tooltip' data-placement='left' title='ver carrito' style='font-size: 12px;' onclick='cartFunction(this,".$item['id'].")' class='btn text-center rounded-0 btn-warning'><i class='fas fa-shopping-cart' aria-hidden='true'></i></button>" .
                        "<button data-toggle='tooltip' data-placement='left' title='acceder como usuario' style='font-size: 12px;' onclick='accessFunction(this,".$item['id'].")' class='btn text-center rounded-0 btn-danger'><i class='fas fa-user' aria-hidden='true'></i></button>" .
                        "<button data-toggle='tooltip' data-placement='left' title='historial de cambios' style='font-size: 12px;' onclick='historyFunction(this,".$item['id'].")' class='btn text-center rounded-0 btn-dark'><i class='fas fa-history' aria-hidden='true'></i></button>" .
                    "</div>";
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