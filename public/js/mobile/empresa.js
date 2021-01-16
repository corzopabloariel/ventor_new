document.addEventListener( 'DOMContentLoaded', function () {
    const slider = new Splide( '#splide', {
        type        : 'loop',
        perPage     : 1,
        autoplay    : true,
        pauseOnHover: false,
        arrows      : false
    } ).mount();
	const anios = new Splide( '#card-slider', {
		perPage    : 1,
		breakpoints: {
			600: {
				perPage: 1,
			}
        },
        autoHeight : true
	} ).mount();
});