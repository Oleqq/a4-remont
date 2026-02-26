export function initWorksPortfolio() {
	const root = document.querySelector('[data-works-portfolio]');
	if (!root) return;

	const items = Array.from(root.querySelectorAll('[data-work-item]'));
	const moreBtn = root.querySelector('[data-works-portfolio-more]');

	if (!items.length || !moreBtn) return;

	const getNumber = (value, fallback) => {
		const parsed = Number.parseInt(value, 10);
		return Number.isNaN(parsed) ? fallback : parsed;
	};

	const config = {
		initialDesktop: getNumber(root.dataset.initialDesktop, 6),
		initialTablet: getNumber(root.dataset.initialTablet, 4),
		initialMobile: getNumber(root.dataset.initialMobile, 3),
		loadDesktop: getNumber(root.dataset.loadDesktop, 4),
		loadTablet: getNumber(root.dataset.loadTablet, 3),
		loadMobile: getNumber(root.dataset.loadMobile, 2),
	};

	let visibleCount = 0;

	const isMobile = () => window.matchMedia('(max-width: 650px)').matches;
	const isTablet = () => window.matchMedia('(max-width: 991px)').matches;

	const getCounts = () => {
		if (isMobile()) {
			return { initial: config.initialMobile, load: config.loadMobile };
		}

		if (isTablet()) {
			return { initial: config.initialTablet, load: config.loadTablet };
		}

		return { initial: config.initialDesktop, load: config.loadDesktop };
	};

	const toggleButton = () => {
		const hasMore = visibleCount < items.length;
		moreBtn.hidden = !hasMore;
	};

	const render = () => {
		items.forEach((item, index) => {
			item.hidden = index >= visibleCount;
		});
		toggleButton();
	};

	const applyInitialState = () => {
		const counts = getCounts();
		const minimumVisible = Math.min(counts.initial, items.length);
		visibleCount = Math.max(visibleCount, minimumVisible);
		render();
	};

	const revealChunk = () => {
		if (visibleCount >= items.length) return;

		const counts = getCounts();
		const nextVisible = Math.min(visibleCount + counts.load, items.length);

		for (let index = visibleCount; index < nextVisible; index += 1) {
			const item = items[index];
			item.hidden = false;
			item.classList.remove('is-entering');
			// Force reflow before re-adding animation class.
			void item.offsetWidth;
			item.classList.add('is-entering');
		}

		visibleCount = nextVisible;
		toggleButton();
	};

	const onResize = () => {
		applyInitialState();
	};

	applyInitialState();
	moreBtn.addEventListener('click', revealChunk);
	window.addEventListener('resize', onResize);
}
