export function initReviewsFeedback() {
	const root = document.querySelector('[data-reviews-photos]');
	if (!root) return;

	const photoItems = Array.from(root.querySelectorAll('[data-review-photo-item]'));
	const moreBtn = root.querySelector('[data-reviews-photos-more]');

	const getNumber = (value, fallback) => {
		const parsed = Number.parseInt(value, 10);
		return Number.isNaN(parsed) ? fallback : parsed;
	};

	if (photoItems.length && moreBtn) {
		const config = {
			initialDesktop: getNumber(root.dataset.initialDesktop, 8),
			initialTablet: getNumber(root.dataset.initialTablet, 6),
			initialMobile: getNumber(root.dataset.initialMobile, 2),
			loadDesktop: getNumber(root.dataset.loadDesktop, 4),
			loadTablet: getNumber(root.dataset.loadTablet, 3),
			loadMobile: getNumber(root.dataset.loadMobile, 2),
		};

		let visibleCount = 0;
		let hasInteracted = false;

		const isMobile = () => window.matchMedia('(max-width: 650px)').matches;
		const isTablet = () => window.matchMedia('(max-width: 991px)').matches;

		const getCounts = () => {
			if (isMobile()) return { initial: config.initialMobile, load: config.loadMobile };
			if (isTablet()) return { initial: config.initialTablet, load: config.loadTablet };
			return { initial: config.initialDesktop, load: config.loadDesktop };
		};

		const toggleButton = () => {
			moreBtn.hidden = visibleCount >= photoItems.length;
		};

		const render = () => {
			photoItems.forEach((item, index) => {
				const isHidden = index >= visibleCount;
				item.classList.toggle('is-hidden', isHidden);
				item.classList.toggle('is-visible', !isHidden);
				if (isHidden) {
					item.classList.remove('is-entering');
					item.style.removeProperty('--reveal-delay');
				}
			});
			toggleButton();
		};

		const animateRange = (from, to) => {
			let delayIndex = 0;

			for (let index = from; index < to; index += 1) {
				const item = photoItems[index];
				if (!item || item.classList.contains('is-hidden')) continue;

				item.classList.remove('is-entering');
				item.style.setProperty('--reveal-delay', `${delayIndex * 90}ms`);
				void item.offsetWidth;
				item.classList.add('is-entering');
				delayIndex += 1;
			}
		};

		const syncVisibleByViewport = (withAnimation = false) => {
			const counts = getCounts();
			const baseVisible = Math.min(counts.initial, photoItems.length);
			const previousVisible = visibleCount;

			visibleCount = hasInteracted ? Math.max(visibleCount, baseVisible) : baseVisible;
			visibleCount = Math.min(visibleCount, photoItems.length);

			render();

			if (withAnimation && visibleCount > 0) {
				const start = previousVisible === 0 ? 0 : previousVisible;
				animateRange(start, visibleCount);
			}
		};

		const revealChunk = () => {
			if (visibleCount >= photoItems.length) return;

			const counts = getCounts();
			const startVisible = visibleCount;
			const nextVisible = Math.min(visibleCount + counts.load, photoItems.length);

			hasInteracted = true;
			visibleCount = nextVisible;
			render();
			animateRange(startVisible, nextVisible);
		};

		syncVisibleByViewport(true);
		root.classList.add('is-ready');
		moreBtn.addEventListener('click', revealChunk);
		window.addEventListener('resize', () => syncVisibleByViewport(false));
	}

	const videoRoot = document.querySelector('[data-reviews-video]');
	if (!videoRoot) return;

	const slider = videoRoot.querySelector('[data-reviews-video-slider]');
	if (!slider) return;

	const prevEl = videoRoot.querySelector('.reviews-feedback__video-btn--prev');
	const nextEl = videoRoot.querySelector('.reviews-feedback__video-btn--next');
	const scrollbarEl = videoRoot.querySelector('.reviews-feedback__video-scrollbar');

	const raf2 = (cb) => requestAnimationFrame(() => requestAnimationFrame(cb));

	// eslint-disable-next-line no-undef
	const swiper = new Swiper(slider, {
		speed: 600,
		watchOverflow: true,
		loop: true,
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
}
