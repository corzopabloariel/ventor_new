document.addEventListener( 'DOMContentLoaded', function () {
	new Splide( '#card-slider', {
		perPage    : 2,
		breakpoints: {
			'425': {
				perPage: 1,
			}
        },
        pagination: false,
	} ).mount();
} );