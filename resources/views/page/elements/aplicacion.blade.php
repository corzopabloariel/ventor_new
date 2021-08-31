<img src="http://staticbcp.ventor.com.ar/img/parabrisas.jpg" alt="" srcset="" class="w-100">
<div class="wrapper wrapper__application">
    <section>
        <div class="container">
            <h2 class="title">Limpiaparabrisas</h2>
            <div class="row">
                <div class="col-12 col-md">
                    <select name="brand" id="brandList" class="form-control">
                        <option value="">Seleccione marca</option>
                        {!! $data['brandsOptions'] !!}
                    </select>
                </div>
                <div class="col-12 col-md">
                    <select name="model" id="modelList" class="form-control" disabled>
                        <option value="">Seleccione modelo</option>
                    </select>
                </div>
                <div class="col-12 col-md">
                    <select name="year" id="yearList" class="form-control" disabled>
                        <option value="">Seleccione año</option>
                    </select>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-lg btn-primary">Buscar</button>
                </div>
            </div>
        </div>
    </section>
</div>