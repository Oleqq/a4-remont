export function initServiceOrderSteps() {
	const root = document.querySelector('[data-service-order-steps-slider]');
	if (!root) return;

	let swiperInstance = null;

	const shouldEnableSwiper = () => window.matchMedia('(max-width: 991px)').matches;

	const enable = () => {
		if (swiperInstance) return;

		// eslint-disable-next-line no-undef
		swiperInstance = new Swiper(root, {
			speed: 600,
			spaceBetween: 10,
			slidesPerView: 1.33,
			watchOverflow: true,
			breakpoints: {
				651: {
					slidesPerView: 1.55,
					spaceBetween: 10,
				},
			},
		});
	};

	const disable = () => {
		if (!swiperInstance) return;
		swiperInstance.destroy(true, true);
		swiperInstance = null;
	};

	const sync = () => {
		if (shouldEnableSwiper()) enable();
		else disable();
	};

	sync();
	window.addEventListener('resize', sync);
	window.addEventListener('load', sync);
}
