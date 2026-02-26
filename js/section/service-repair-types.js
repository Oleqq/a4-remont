export function initServiceRepairTypes() {
	const root = document.querySelector('[data-service-repair-slider]');
	if (!root) return;

	let swiperInstance = null;

	const shouldEnableSwiper = () => window.matchMedia('(max-width: 991px)').matches;

	const enable = () => {
		if (swiperInstance) return;

		// eslint-disable-next-line no-undef
		swiperInstance = new Swiper(root, {
			speed: 600,
			spaceBetween: 10,
			slidesPerView: 1.8,
			watchOverflow: true,
			breakpoints: {
				0: {
					slidesPerView: 1.08,
					spaceBetween: 10,
				},
				651: {
					slidesPerView: 1.8,
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
