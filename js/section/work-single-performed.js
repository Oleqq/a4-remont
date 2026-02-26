export function initWorkSinglePerformed() {
	const blocks = document.querySelectorAll('[data-work-performed]');
	if (!blocks.length) return;

	const raf2 = (cb) => requestAnimationFrame(() => requestAnimationFrame(cb));

	blocks.forEach((block) => {
		const slider = block.querySelector('[data-work-performed-slider]');
		if (!slider) return;

		const prevEl = block.querySelector('.work-single-performed__btn--prev');
		const nextEl = block.querySelector('.work-single-performed__btn--next');
		const scrollbarEl = block.querySelector('.work-single-performed__scrollbar');

		// eslint-disable-next-line no-undef
		const swiper = new Swiper(slider, {
			speed: 600,
			watchOverflow: true,
			spaceBetween: 10,
			slidesPerView: 4,
			updateOnWindowResize: true,
			observeParents: true,
			observer: true,

			navigation: {
				prevEl,
				nextEl,
				disabledClass: 'is-disabled',
			},

			scrollbar: {
				el: scrollbarEl,
				draggable: false,
				dragClass: 'swiper-scrollbar-drag',
				lockClass: 'is-locked',
			},

			breakpoints: {
				0: { slidesPerView: 2, spaceBetween: 10 },
				467: { slidesPerView: 3, spaceBetween: 10 },
				650: { slidesPerView: 3, spaceBetween: 10 },
				992: { slidesPerView: 4, spaceBetween: 10 },
			},

			on: {
				init() {
					raf2(() => {
						this.update();
						if (this.scrollbar) this.scrollbar.updateSize();
					});
				},
				afterInit() {
					raf2(() => {
						this.update();
						if (this.scrollbar) this.scrollbar.updateSize();
					});
				},
				breakpoint() {
					raf2(() => {
						this.update();
						if (this.scrollbar) this.scrollbar.updateSize();
					});
				},
				resize() {
					raf2(() => {
						this.update();
						if (this.scrollbar) this.scrollbar.updateSize();
					});
				},
			},
		});

		const force = () => {
			raf2(() => {
				swiper.update();
				if (swiper.scrollbar) swiper.scrollbar.updateSize();
			});
		};

		window.addEventListener('load', force);
		window.addEventListener('resize', force);
	});
}
