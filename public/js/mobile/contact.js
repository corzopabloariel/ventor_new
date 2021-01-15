document.addEventListener( 'DOMContentLoaded', function () {
	new Splide( '#card-slider', {
		perPage    : 1,
		breakpoints: {
			600: {
				perPage: 1,
			}
        },
        pagination: false,
	} ).mount();
} );