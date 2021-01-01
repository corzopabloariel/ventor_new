@push('styles')
    <link href="{{ asset('css/page/contacto.css') . '?t=' . time() }}" rel="stylesheet">
    <link href="{{ asset('css/page/form.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/page/datos.js') . '?t=' . time() }}"></script>
@endpush
<div class="wrapper-form wrapper-atencion py-5 bg-white">
    <div class="container">
        <h2 class="title text-uppercase">atención al cliente</h2>
        <h4>Análisis de transmisión</h4>

        <form action="{{ route('client.datos', ['section' => 'transmision']) }}" novalidate method="post" id="form" onsubmit="event.preventDefault(); enviar(this)" class="formulario wrapper-formulario border-top-0 bg-white" enctype="multipart/form-data">
            @method("post")
            {{ csrf_field() }}
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 my-2">
                    <fieldset class="border p-3">
                        <legend class="p-0 bg-transparent border-0 mb-0 d-inline-block title" style="width: auto">Datos básicos</legend>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6 my-2">
                                <input value="" required="true" name="nombre" class="form-control" type="text" placeholder="Nombre y Apellido">
                            </div>
                            <div class="col-12 col-md-6 my-2">
                                <input value="" name="telefono" class="form-control" type="phone" placeholder="Teléfono">
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-12 col-md-6 my-2">
                                <input value="" required="true" name="domicilio" class="form-control" type="text" placeholder="Domicilio">
                            </div>
                            <div class="col-12 col-md-6 my-2">
                                <input value="" required="true" name="localidad" class="form-control" type="text" placeholder="Localidad">
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-12">
                                <input value="" required="true" name="email" class="form-control" type="email" placeholder="Email">
                            </div>
                        </div>
                    </fieldset>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <p class="title">Tipo de transmisión</p>
                            <div class="form-check">
                                <input checked class="form-check-input" type="radio" value="Transmisión nueva" name="transmision" id="transmisionNueva">
                                <label class="form-check-label" for="transmisionNueva">
                                    Transmisión Nueva
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Transmisión existente" name="transmision" id="transmisionExistente">
                                <label class="form-check-label" for="transmisionExistente">
                                    Transmisión Existente
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <p class="title">Tipo de correas</p>
                            <div class="form-check">
                                <input checked class="form-check-input" type="radio" value="Correa en V" name="correa" id="correaV">
                                <label class="form-check-label" for="correaV">
                                    Correas en V
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Correas Sincrónicas" name="correa" id="correaSincronica">
                                <label class="form-check-label" for="correaSincronica">
                                    Correas Sincrónicas
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 my-2">
                    <div class="row">
                        <div class="col-12">
                            <fieldset class="border p-3">
                                <legend class="p-0 bg-transparent border-0 mb-0 d-inline-block title" style="width: auto">Complete los valores</legend>
                                <div class="row">
                                    <div class="col-md-6 col-12 my-3">
                                        <input required type="text" name="potencia" placeholder="Potencia HP" class="form-control"/>
                                    </div>
                                    <div class="col-md-6 col-12 my-3">
                                        <input required type="text" name="factor" placeholder="Factor de servicio" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 col-12 my-3">
                                        <input required type="text" name="poleaMotor" placeholder="RPM polea motor" class="form-control"/>
                                    </div>
                                    <div class="col-md-6 col-12 my-3">
                                        <input required type="text" name="poleaConducida" placeholder="RPM polea conducida" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 col-12 my-3">
                                        <input required type="text" name="centroMin" placeholder="Entre centro Min. (mm)" class="form-control"/>
                                    </div>
                                    <div class="col-md-6 col-12 my-3">
                                        <input required type="text" name="centroMax" placeholder="Entre centro Max. (mm)" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <textarea required name="mensaje" placeholder="Mensaje" class="form-control"></textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="mt-4 perfiles">
                                <legend class="p-0 mb-4 bg-transparent border-0 title">Indicar si tiene preferencia por algún perfil</legend>
                                <div class="" style="column-count: 3">
                                    <div class="form-check">
                                        <input checked class="form-check-input" type="radio" value="AX" name="perfil" id="perfilAX">
                                        <label class="form-check-label" for="perfilAX">
                                            AX
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="5VX" name="perfil" id="perfil5VX">
                                        <label class="form-check-label" for="perfil5VX">
                                            5VX
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="DP" name="perfil" id="perfilDP">
                                        <label class="form-check-label" for="perfilDP">
                                            DP
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="B" name="perfil" id="perfilB">
                                        <label class="form-check-label" for="perfilB">
                                            B
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="BX" name="perfil" id="perfilBX">
                                        <label class="form-check-label" for="perfilBX">
                                            BX
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="8VX" name="perfil" id="perfil8VX">
                                        <label class="form-check-label" for="perfil8VX">
                                            8VX
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="3V" name="perfil" id="perfil3V">
                                        <label class="form-check-label" for="perfil3V">
                                            3V
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="C" name="perfil" id="perfilC">
                                        <label class="form-check-label" for="perfilC">
                                            C
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="CX" name="perfil" id="perfilCX">
                                        <label class="form-check-label" for="perfilCX">
                                            CX
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="AP" name="perfil" id="perfilAP">
                                        <label class="form-check-label" for="perfilAP">
                                            AP
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="5V" name="perfil" id="perfil5V">
                                        <label class="form-check-label" for="perfil5V">
                                            5V
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="3VX" name="perfil" id="perfil3VX">
                                        <label class="form-check-label" for="perfil3VX">
                                            3VX
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="CP" name="perfil" id="perfilCP">
                                        <label class="form-check-label" for="perfilCP">
                                            CP
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="A" name="perfil" id="perfilA">
                                        <label class="form-check-label" for="perfilA">
                                            A
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary text-white px-5 text-uppercase rounded-pill">enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>