<section class="section" style="background-color: #eee">
    <div class="section__holder" style="margin-top: 1em">
        <form action="{{ route('client.datos', ['section' => 'transmision']) }}" novalidate id="transmisionForm" method="post">
            <div class="contacto__grid-hero section__holder">
                <div class="contacto__info">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; grid-gap: .625rem;">
                        <div>
                            <h4 class="section__subtitle">Tipo de transmisión</h4>
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
                        <div>
                            <h4 class="section__subtitle">Tipo de correas</h4>
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
                    <hr>
                    <h4 class="section__subtitle">Complete los valores</h4>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; grid-gap: .625rem; margin-bottom:.625rem">
                        <div class="form-item">
                            <label for="">Potencia HP *</label>
                            <input placeholder="Potencia HP *" required id="potencia" type="text" name="potencia" class="input">
                        </div>
                        <div class="form-item">
                            <label for="">Factor de servicio *</label>
                            <input placeholder="Factor de servicio *" required id="factor" type="text" name="factor" class="input">
                        </div>
                        <div class="form-item">
                            <label for="">RPM polea motor *</label>
                            <input placeholder="RPM polea motor *" required id="poleaMotor" type="text" name="poleaMotor" class="input">
                        </div>
                        <div class="form-item">
                            <label for="">RPM polea conducida *</label>
                            <input placeholder="RPM polea conducida *" required id="poleaConducida" type="text" name="poleaConducida" class="input">
                        </div>
                        <div class="form-item">
                            <label for="">Entre centro Min. (mm) *</label>
                            <input placeholder="Entre centro Min. (mm) *" required id="centroMin" type="text" name="centroMin" class="input">
                        </div>
                        <div class="form-item">
                            <label for="">Entre centro Máx. (mm) *</label>
                            <input placeholder="Entre centro Máx. (mm) *" required id="centroMax" type="text" name="centroMax" class="input">
                        </div>
                    </div>
                    <div class="form-item">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="5" placeholder="Mensaje" class="textarea"></textarea>
                    </div>
                    <hr>
                    <h4 class="section__subtitle">Indicar si tiene preferencia por algún perfil</h4>
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
                </div>
                <div class="">
                    <h4 class="section__subtitle">Datos de contacto</h4>
                    <div class="form-item" style="margin-bottom:.625rem">
                        <label for="nombre">Nombre completo *</label>
                        <input placeholder="Nombre completo *" required id="nombre" type="text" name="nombre" class="input">
                    </div>
                    <div class="form-item" style="margin-bottom:.625rem">
                        <label for="email">Email *</label>
                        <input placeholder="Email *" required type="email" id="email" name="email" class="input">
                    </div>
                    <div class="form-item" style="margin-bottom:.625rem">
                        <label for="telefono">Teléfono</label>
                        <input placeholder="Teléfono" type="phone" id="telefono" name="telefono" class="input">
                    </div>
                    <div class="form-item" style="">
                        <label for="domicilio">Domicilio</label>
                        <textarea id="domicilio" name="domicilio" requir    ed rows="5" placeholder="Domicilio" class="textarea"></textarea>
                    </div>
                    <button class="button button--primary-fuchsia" id="pagosTransmision">
                        Enviar
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>