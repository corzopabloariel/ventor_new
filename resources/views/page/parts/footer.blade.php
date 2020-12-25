<div class="footer">
    <footer>
        <div class="container">
            <div class="container--footer">
                <div>
                    <h3 class="footer--title">sitemap</h3>
                    {!! $ventor->sitemap("footer") !!}
                </div>
                <div>
                    <img class="footer--logo" src="{{ asset($ventor->images['logo_footer']['i']) }}" alt="{{ env('APP_NAME') }}" srcset="">
                </div>
                <div>
                    <h3 class="footer--title">{{ env('APP_NAME') }}</h3>
                    <ul class="footer--data">
                        <li>{!! $ventor->addressString() !!}</li>
                        <li>{!! $ventor->phonesString() !!}</li>
                        <li>{!! $ventor->emailsString() !!}</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>