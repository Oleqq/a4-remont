export function initFeedbackShowcase() {
	const root = document.querySelector('[data-reviews-swiper]');
	if (!root) return;

	let swiperInstance = null;
	const mq = window.matchMedia('(max-width: 650px)');

	const enable = () => {
		if (swiperInstance) return;

		// eslint-disable-next-line no-undef
		swiperInstance = new Swiper(root, {
			slidesPerView: 1.05,
			spaceBetween: 10,
			speed: 600,
			watchOverflow: true,
			breakpoints: {
				0: { slidesPerView: 1.5, spaceBetween: 10 },
				467: { slidesPerView: 1.5, spaceBetween: 10 },
				650: { slidesPerView: 1.66, spaceBetween: 10 },
				991: { slidesPerView: 2.1, spaceBetween: 10 },
			},
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