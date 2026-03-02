export function initServiceArguments() {
	const root = document.querySelector('[data-service-arguments]');
	if (!root) return;

	let swiperInstance = null;

	const shouldEnableSwiper = () => {
		const isTablet = window.matchMedia('(max-width: 991px)').matches;
		const isMobile = window.matchMedia('(max-width: 650px)').matches;
		return isTablet && !isMobile;
	};

	const enable = () => {
		if (swiperInstance) return;

		// eslint-disable-next-line no-undef
		swiperInstance = new Swiper(root, {
			speed: 600,
			spaceBetween: 14,
			slidesPerView: 1.35,
			watchOverflow: true,
			breakpoints: {
				768: {
					slidesPerView: 1.8,
					spaceBetween: 16,
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
