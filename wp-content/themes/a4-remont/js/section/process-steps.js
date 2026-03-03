export function initProcessSteps() {
	const sliders = document.querySelectorAll(
		'[data-process-steps-slider], .process-steps__slider[data-process-steps]'
	);
	if (!sliders.length) return;

	const raf2 = (cb) => requestAnimationFrame(() => requestAnimationFrame(cb));

	sliders.forEach((slider) => {
		let swiper = null;

		const needSwiper = () => window.matchMedia('(max-width: 650px)').matches;

		const init = () => {
			if (swiper) return;

			// eslint-disable-next-line no-undef
			swiper = new Swiper(slider, {
				speed: 600,
				watchOverflow: true,
				spaceBetween: 16,
				slidesPerView: 1.5,
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
