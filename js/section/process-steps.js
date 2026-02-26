export function initProcessSteps() {
	const blocks = document.querySelectorAll('[data-process-steps]');
	if (!blocks.length) return;

	const raf2 = (cb) => requestAnimationFrame(() => requestAnimationFrame(cb));

	blocks.forEach((block) => {
		const slider = block.querySelector('[data-process-steps-slider]');
		if (!slider) return;

		let swiper = null;

		const mediaQuery = window.matchMedia('(max-width: 650px)');
		const needSwiper = () => mediaQuery.matches;

		const init = () => {
			if (swiper) return;

			const isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

			// eslint-disable-next-line no-undef
			swiper = new Swiper(slider, {
				speed: isReducedMotion ? 0 : 600,
				watchOverflow: true,
				spaceBetween: 16,
				slidesPerView: 1,
				updateOnWindowResize: true,
				observeParents: true,
				observer: true,
				wrapperClass: 'process-steps__wrapper',
				slideClass: 'process-steps__item',

				on: {
					init() {
						raf2(() => this.update());
					},
					afterInit() {
						raf2(() => this.update());
					},
					breakpoint() {
						raf2(() => this.update());
					},
					resize() {
						raf2(() => this.update());
					},
				},
			});
		};

		const destroy = () => {
			if (!swiper) return;
			swiper.destroy(true, true);
			swiper = null;
		};

		const toggle = () => {
			if (needSwiper()) init();
			else destroy();
		};

		toggle();

		const force = () => {
			raf2(() => {
				if (swiper) swiper.update();
			});
		};

		window.addEventListener('load', () => {
			toggle();
			force();
		});

		window.addEventListener('resize', () => {
			toggle();
			force();
		});
	});
}