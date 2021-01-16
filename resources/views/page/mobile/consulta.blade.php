@push('styles')
    <link href="{{ asset('css/mobile/contact.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
<section>
    <div class="contact">
        <div class="container-fluid">
            <div class="shadow-sm contact_container">
                <h3 class="contact__title text-center">Consulta general</h3>
                <p class="contact__title contact__title--secondary text-center">Contáctanos y te brindaremos toda la información que necesites</p>
            </div>
        </div>
    </div>
    <div class="contact contact__white">
        <div class="container-fluid">
            <form class="contact__form" action="{{ route('client.datos', ['section' => 'consulta']) }}" novalidate id="form" onsubmit="event.preventDefault(); enviar(this);" method="post">
            @csrf
                <div class="form-group mb-0">
                    <label for="nombre">Nombre <span class="text-danger">*</span></label>
                    <input id="nombre" required="true" name="nombre" class="form-control" type="text" placeholder="Razón Social o Nombre">
                </div>
                <div class="form-group mb-0">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input id="email" required="true" name="email" class="form-control" type="email" placeholder="Email">
                </div>
                <div class="form-group mb-0">
                    <label for="telefono">Teléfono</label>
                    <input id="telefono" name="telefono" class="form-control" type="phone" placeholder="Teléfono">
                </div>
                <div class="form-group mb-0">
                    <label for="localidad">Localidad</label>
                    <input id="localidad" name="localidad" class="form-control" type="text" placeholder="Localidad">
                </div>
                <div class="form-group mb-0">
                    <label for="mensaje">Mensaje <span class="text-danger">*</span></label>
                    <textarea id="mensaje" data-sample-short="" required="true" name="mensaje" class="form-control" placeholder="Mensaje"></textarea>
                </div>
                <button type="submit" class="btn btn-primary text-uppercase d-block mx-auto text-white px-5">enviar</button>
            </form>
        </div>
    </div>
</section>