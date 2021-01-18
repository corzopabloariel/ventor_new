<div class="footer">
    <footer>
        <div class="container-fluid">
            <div class="footer__container">
                <div>
                    <h3 class="footer--title">sitemap</h3>
                    {!! $ventor->sitemap("footer") !!}
                </div>
                <div>
                    <img class="footer--logo" src="{{ asset($ventor->images['logo_footer']['i']) }}" alt="{{ env('APP_NAME') }}" srcset="">
                </div>
                <div>
                    <h3 class="footer--title">{{ env('APP_NAME') }}</h3>
                    <ul class="footer--data mb-2">
                        <li>{!! $ventor->addressPrint() !!}</li>
                        <li>{!! $ventor->phonesPrint() !!}</li>
                        <li>{!! $ventor->emailsPrint() !!}</li>
                    </ul>
                    {!! $ventor->socialPrint() !!}
                </div>
            </div>
        </div>
    </footer>
</div>