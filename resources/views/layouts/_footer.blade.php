<footer class="footer">
    <div class="footer__top-layout"></div>
    <div class="footer__holder">
        <div class="footer_info">
            <img src="http://staticbcp.ventor.com.ar/img/logo.png" alt="{{config('app.name')}}">
        </div>
        <ul class="footer__nav">
            <li class="">
                <a href="{{\url::to('empresa')}}">Empresa</a>
            </li>
            <li class="">
                <a href="{{\url::to('descargas')}}">Descargas</a>
            </li>
            <li class="">
                <a href="{{\url::to('productos')}}">Productos</a>
            </li>
            <li class="">
                <a href="{{\url::to('aplicacion')}}">Aplicación</a>
            </li>
            <li class="">
                <a href="{{\url::to('calidad')}}">Calidad</a>
            </li>
            <li class="">
                <a href="{{\url::to('contacto')}}">Contacto</a>
            </li>
        </ul>
        <ul class="footer__nav">
            <li class="">
                <a href="{{\url::to('atencion/transmision')}}">Análisis de transmisión</a>
            </li>
            <li class="">
                <a href="{{\url::to('atencion/pagos')}}">Información sobre pagos</a>
            </li>
            <li class="">
                <a href="{{\url::to('atencion/consulta')}}">Consulta general</a>
            </li>
        </ul>
        <ul class="footer__contact-nav">
            <ul class="footer--data">
                <li>{!! $ventor->addressPrint() !!}</li>
                <li>{!! $ventor->phonesPrint() !!}</li>
                <li>{!! $ventor->emailsPrint() !!}</li>
            </ul>
        </ul>
        <div class="footer__nav">
            <ul class="footer--data footer__social-nav">
                {!! $ventor->socialFooter() !!}
            </ul>
        </div>
    </div>
</footer>