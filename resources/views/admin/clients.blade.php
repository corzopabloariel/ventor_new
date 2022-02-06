<div class="container-fluid">
    <div id="wrapper-form" class="mt-3">
        <div class="card mt-2 border-0">
            <div class="card-body">
                <form action="{{ $form['url'] }}" method="get">
                    <div class="d-flex">
                        <input aria-label="Search" required pattern=".{1,}" @if(!empty($form["search"])) value="{{ $form["search"] }}" @endif placeholder="{{ $form['placeholder'] }}" type="search" class="form-control form-control-lg border-left-0 border-top-0 border-rigth-0 rounded-0" name="search"/>
                        <a href="{{ $form['url'] }}" class="btn rounded-0 btn-info btn-lg border-0"><i class="fas fa-undo"></i></a>
                        <button class="btn btn-success btn-lg border-0 rounded-0"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card border-0 mt-2">
        <div class="card-body p-0" id="wrapper-tabla">
            {!! $table !!}
        </div>
    </div>
</div>