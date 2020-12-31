@isset($form)
<div class="card mt-2 border-0">
    <div class="card-body">
        <form action="{{ $form['url'] }}" method="get">
            <div class="d-flex">
                <input aria-label="Search" oninvalid="this.setCustomValidity('Ingrese una palabra mayor a 2 caracteres')"  oninput="setCustomValidity('')" required pattern=".{2,}" @if(!empty($form["search"])) value="{{ $form["search"] }}" @endif placeholder="{{ $form['placeholder'] }}" type="search" class="form-control form-control-lg border-left-0 border-top-0 border-rigth-0 rounded-0" name="search"/>
                <a href="{{ $form['url'] }}" class="btn rounded-0 btn-info btn-lg border-0"><i class="fas fa-undo"></i></a>
                <button class="btn btn-success btn-lg border-0 rounded-0"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
</div>
@endisset
<div class="card border-0 mt-2">
    <div class="card-body p-0" id="wrapper-tabla">
        @isset($table)
        {!! $tableOnly !!}
        @endisset
    </div>
    @isset( $paginate )
    <div class="card-footer d-flex justify-content-center">
        @if(!empty($form["search"]))
        {{ $paginate->appends(["search" => $form["search"]])->links() }}
        @else
        {{ $paginate->links() }}
        @endif
    </div>
    @endisset
</div>