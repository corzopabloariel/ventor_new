<div class="footer">
    <footer>
        <div class="container-fluid">
            <div class="container__footer">
                <div class="element">
                    <h3 class="footer--title">sitemap</h3>
                    {!! $ventor->sitemap("footer") !!}
                </div>
                <div>
                    <img class="footer--logo" src="{{ asset($ventor->images['logo_footer']['i']) }}" alt="{{ config('app.name') }}" srcset="">
                </div>
                <div class="element">
                    <h3 class="footer--title">{{ config('app.name') }}</h3>
                    <ul class="footer--data">
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