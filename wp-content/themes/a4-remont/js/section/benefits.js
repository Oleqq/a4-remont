export function initBenefits() {
	const root = document.querySelector('[data-benefits-swiper]');
	if (!root) return;

	let swiperInstance = null;

	const enable = () => {
		if (swiperInstance) return;

		// eslint-disable-next-line no-undef
		swiperInstance = new Swiper(root, {
			slidesPerView: 1.33,
			spaceBetween: 14,
			speed: 600,
			loop: false,
			pagination: {
				el: root.querySelector('[data-benefits-pagination]'),
				clickable: true,
			},
		});
	};

	const disable = () => {
		if (!swiperInstance) return;
		swiperInstance.destroy(true, true);
		swiperInstance = null;
	};

	const mq = window.matchMedia('(max-width: 650px)');

	const onChange = () => {
		if (mq.matches) enable();
		else disable();
	};

	onChange();
	mq.addEventListener('change', onChange);
}