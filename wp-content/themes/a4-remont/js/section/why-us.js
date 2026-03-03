export function initWhyUs() {
	const root = document.querySelector('[data-why-swiper]');
	if (!root) return;

	let swiperInstance = null;
	const mq = window.matchMedia('(max-width: 991px)');

	const enable = () => {
		if (swiperInstance) return;

		// eslint-disable-next-line no-undef
		swiperInstance = new Swiper(root, {
			slidesPerView: 2.5,
			spaceBetween: 14,
			// speed: 600,
			watchOverflow: true,
			breakpoints: {
				0: { slidesPerView: 1.25 },
				467: { slidesPerView: 1.25 },
				650: { slidesPerView: 2.5 },
				991: { slidesPerView: 2.5 },
			},
			autoplay: true,
		});
	};

	const disable = () => {
		if (!swiperInstance) return;
		swiperInstance.destroy(true, true);
		swiperInstance = null;
	};

	const onChange = () => {
		if (mq.matches) enable();
		else disable();
	};

	onChange();
	mq.addEventListener('change', onChange);
}