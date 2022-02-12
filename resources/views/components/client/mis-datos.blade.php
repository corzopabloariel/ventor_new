@isset($data)
<script>
    const USER_ID = '{{$userId ?? ""}}';
</script>
<section class="section" style="background-color: #eee">
    <div class="section__holder" style="margin-top: 1em">
        <div class="contacto__grid-hero section__holder">
            <div class="contacto__info">
                @php
                $direccion = '-';
                $transporte = '-';
                if (isset($data['address']['direccion'])) {

                    $direccion = $data['address']['direccion'].' ('.$data['address']['codpos'].'). '.$data['address']['provincia'].', '.$data['address']['localidad'];

                }
                if (isset($data['transport']['code'])) {

                    $transporte = $data['transport']['nombre'].' #'.$data['transport']['code'];

                }
                @endphp
                <h2 class="section__title">Mis datos</h2>
                <p class="section__simple --legal__text"><strong style="font-weight: 600">Nro. Cuenta:</strong> {{$data['nroCta']}}</p>
                <p class="section__simple --legal__text"><strong style="font-weight: 600">Nro. Doc:</strong> {{$data['nroDoc']}}</p>
                <p class="section__simple --legal__text"><strong style="font-weight: 600">Email:</strong> {{$data['email']}}</p>
                <p class="section__simple --legal__text"><strong style="font-weight: 600">Razón social:</strong> {{$data['razonSocial']}}</p>
                <p class="section__simple --legal__text"><strong style="font-weight: 600">Responsable:</strong> {{$data['responsable']}}</p>
                <p class="section__simple --legal__text"><strong style="font-weight: 600">Teléfono:</strong> {{$data['phone']}}</p>
                <p class="section__simple --legal__text"><strong style="font-weight: 600">Dirección:</strong> {{$direccion}}</p>
                <p class="section__simple --legal__text"><strong style="font-weight: 600">Transporte:</strong> {{$transporte}}</p>
                <p class="section__simple --legal__text"><strong style="font-weight: 600">Vendedor:</strong> {{$transporte}}</p>
                <hr>
            </div>
            <div class="contacto__form">
                <form novalidate id="datoForm" method="post">
                    <div class="form-item">
                        <label for="responsable">Responsable</label>
                        <input placeholder="Responsable" id="responsable" type="text" name="responsable" class="input" value="{{$data['responsable']}}">
                    </div>
                    <div class="form-item">
                        <label for="razon">Razón Social</label>
                        <input placeholder="Razón Social" type="text" id="razon" name="razon" class="input" value="{{$data['razonSocial']}}">
                    </div>
                    <div class="form-item">
                        <label for="documento">Documento</label>
                        <input placeholder="Documento" type="text" id="documento" name="documento" class="input" value="{{$data['nroDoc']}}">
                    </div>
                    <div class="form-item">
                        <label for="telefono">Teléfono</label>
                        <input placeholder="Teléfono" type="text" id="telefono" name="telefono" class="input" value="{{$data['phone']}}">
                    </div>
                    <div class="form-item">
                        <label for="email">Email</label>
                        <input placeholder="Email" type="email" id="email" name="email" class="input" value="{{$data['email']}}">
                    </div>
                    <div class="form-item">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" rows="5" placeholder="Observaciones" class="textarea"></textarea>
                    </div>
                    <button class="button button--primary-fuchsia" id="datoSubmit">
                        Solicitar cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endisset