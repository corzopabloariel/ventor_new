@push('modal')
<form action="{{ route('ventor.number.order') }}" method="post" onsubmit="event.preventDefault(); bodyMailsSubmit(this);">
    <div class="modal fade bd-example-modal-lg" id="bodyMail" tabindex="-1" role="dialog" aria-labelledby="bodyMailLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="bodyMailLabel">Cuerpo del mensaje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endpush
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
        $thead = ["FECHA", "ESTADO", "PEDIDO", "TIPO", "IP", "DE", "TÍTULO", ""];
        $table = $tbody = "";
        $thead = collect($thead)->map(function($item) {
            return "<th>{$item}</th>";
        })->join("");
        foreach($data['elements'] AS $mail) {
            $tr = "";
            $to = $mail->to;
            $tr .= "<tr>";
                $tr .= "<td class='text-center'>" . date("d/m/Y H:i", strtotime($mail->updated_at)) . "</td>";
                $tr .= "<td class='text-center'>" . ($mail->sent && !$mail->error ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>') . "</td>";
                $tr .= "<td class=''>" . ($mail->is_order ? 'SI' : 'NO') . "</td>";
                $tr .= "<td class=''>" . $mail->type . "</td>";
                $tr .= "<td class=''>" . $mail->ip . "</td>";
                $tr .= "<td class=''>" . $mail->from . "</td>";
                $tr .= "<td class=''>" . $mail->subject . "</td>";
                $tr .= "<td style='width: 90px;'>";
                    $tr .= "<button title='Ver cuerpo' data-id='{$mail->id}' class='btn btn-dark seeMessage'><i class='fas fa-eye'></i></button>";
                $tr .= "</td>";
            $tr .= "</tr>";
            $tbody .= $tr;
        }

        $table .= "<table class='table table-striped table-borderless'>";
            $table .= "<thead class='thead-dark'>{$thead}</thead>";
            $table .= "<tbody>{$tbody}</tbody>";
        $table .= "</table>";
        $arr["tableOnly"] = $table;
        $arr["addForm"] = "<div class='d-flex'>";
            $arr["addForm"] .= "<div class='form-group'>";
                $arr["addForm"] .= "<label>Con error</label>";
                $arr["addForm"] .= "<select name='error' class='form-control'>";
                    $arr["addForm"] .= "<option value=''>- Indistinto -</option>";
                    $arr["addForm"] .= "<option ". (isset($data["error"]) && $data["error"] == "1" ? "selected" : "") ." value='1'>SI</option>";
                    $arr["addForm"] .= "<option ". (isset($data["error"]) && $data["error"] == "0" ? "selected" : "") ." value='0'>NO</option>";
                $arr["addForm"] .= "</select>";
            $arr["addForm"] .= "</div>";
            $arr["addForm"] .= "<div class='form-group ml-2'>";
                $arr["addForm"] .= "<label>Pedido</label>";
                $arr["addForm"] .= "<select name='order' class='form-control'>";
                    $arr["addForm"] .= "<option value=''>- Indistinto -</option>";
                    $arr["addForm"] .= "<option ". (isset($data["order"]) && $data["order"] == "1" ? "selected" : "") ." value='1'>SI</option>";
                    $arr["addForm"] .= "<option ". (isset($data["order"]) && $data["order"] == "0" ? "selected" : "") ." value='0'>NO</option>";
                $arr["addForm"] .= "</select>";
            $arr["addForm"] .= "</div>";
        $arr["addForm"] .= "</div>";
        $arr["addAppend"] = [];
        if (isset($data["error"]) && $data["error"] != '')
            $arr["addAppend"]["error"] = $data["error"];
        if (isset($data["order"]) && $data["order"] != '')
            $arr["addAppend"]["order"] = $data["order"];
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
const mailBody = function(evt) {
    const {id} = this.dataset;
    let url = url_simple + url_basic + "emails";
    let formData = new FormData();
    let entity = Array.isArray(window.pyrus) ? window.pyrus[0].entidad : window.pyrus;
    formData.append("id", id);
    axios({
        method: "post",
        url: url,
        data: formData,
        responseType: 'json'
    })
    .then(res => {
        let {data} = res;
        if (data.error) {
            alertify.error(data.message);
            return;
        }
        if (data.email.mongo.body !== undefined) {
            document.querySelector('#bodyMailLabel').innerText = data.email.mongo.subject;
            document.querySelector('#bodyMail .modal-body').innerHTML = data.email.mongo.body;
            $('#bodyMail').modal('show');
            return;
        }
        alertify.error("Sin cuerpo en el mail");
    })
    .catch(err => {
        console.error(err);
        alertify.error("Error. Ver consola");
    });
}
const seeMessage = document.querySelectorAll('.seeMessage');
Array.prototype.forEach.call(seeMessage, q => {
    q.addEventListener("click", mailBody);
});
</script>
@endpush