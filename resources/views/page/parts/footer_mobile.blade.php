<div class="footer">
    <footer>
        <div class="container-fluid">
            <ul class="footer__data">
                <li class="address">{!! $ventor->addressPrint() !!}</li>
                <li class="phone">{!! $ventor->phonesPrint() !!}</li>
                <li class="email">{!! $ventor->emailsPrint() !!}</li>
            </ul>
            {!! $ventor->socialPrint() !!}
        </div>
    </footer>
</div>