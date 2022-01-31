<section class="section">
    <div class="section__holder" style="margin-top: 1em">
        <div class="contacto__grid-hero section__holder">
            <div class="contacto__info">
                <h2 class="section__title">Atención al cliente</h2>
                <h4 class="section__subtitle">Información sobre pagos</h4>
                <div class="section__simple --legal__text">{!! $banco->value !!}</div>
                <hr>
                <div class="section__simple --legal__text">{!! $pagos->value !!}</div>
            </div>
            <div class="contacto__form">
                <form action="{{ route('client.datos', ['section' => 'contacto']) }}" novalidate id="contactoForm" method="post">
                    <div class="form-item">
                        <label for="nrocliente">Nro. Cliente *</label>
                        <input placeholder="Nro. Cliente *" required id="nrocliente" type="text" name="nrocliente" class="input">
                    </div>
                    <div class="form-item">
                        <label for="razon">Razón Social *</label>
                        <input placeholder="Razón Social *" required type="text" id="razon" name="razon" class="input">
                    </div>
                    <div class="form-item">
                        <label for="fecha">Fecha *</label>
                        <input placeholder="Fecha *" required type="date" id="fecha" name="fecha" class="input">
                    </div>
                    <div class="form-item">
                        <label for="importe">Importe *</label>
                        <input placeholder="Importe *" required type="text" id="importe" name="importe" class="input">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; grid-gap: 10px">
                        <div class="form-item">
                            <label for="banco">Banco *</label>
                            <input placeholder="Banco *" required type="text" id="banco" name="banco" class="input">
                        </div>
                        <div class="form-item">
                            <label for="sucursal">Sucursal *</label>
                            <input placeholder="Sucursal *" required type="text" id="sucursal" name="sucursal" class="input">
                        </div>
                    </div>
                    <div class="form-item">
                        <label for="facturas">Facturas canceladas *</label>
                        <textarea style="height: 3.25rem" id="facturas" name="facturas" required rows="5" placeholder="Facturas canceladas *" class="textarea"></textarea>
                    </div>
                    <div class="form-item">
                        <label for="descuento">Descuento efectuado</label>
                        <textarea style="height: 3.25rem" id="descuento" name="descuento" rows="5" placeholder="Descuento efectuado" class="textarea"></textarea>
                    </div>
                    <div class="form-item">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" rows="5" placeholder="Observaciones" class="textarea"></textarea>
                    </div>
                    <button class="button button--primary-fuchsia" id="contactoSubmit">
                        Enviar consulta
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>