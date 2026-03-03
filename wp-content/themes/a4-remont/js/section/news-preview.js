export function initNewsPreview() {
	const root = document.querySelector('[data-news-preview]');
	if (!root) return;

	const items = Array.from(root.querySelectorAll('.news-card'));
	const moreBtn = root.querySelector('[data-news-more]');

	if (!items.length || !moreBtn) return;

	const getNumber = (value, fallback) => {
		const parsed = Number.parseInt(value, 10);
		return Number.isNaN(parsed) ? fallback : parsed;
	};

	const config = {
		initialDesktop: getNumber(root.dataset.initialDesktop, 3),
		initialTablet: getNumber(root.dataset.initialTablet, 3),
		initialMobile: getNumber(root.dataset.initialMobile, 3),
		loadDesktop: getNumber(root.dataset.loadDesktop, 3),
		loadTablet: getNumber(root.dataset.loadTablet, 3),
		loadMobile: getNumber(root.dataset.loadMobile, 3),
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
		moreBtn.hidden = visibleCount >= items.length;
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
			void item.offsetWidth;
			item.classList.add('is-entering');
		}

		visibleCount = nextVisible;
		toggleButton();
	};

	applyInitialState();
	root.classList.add('is-ready');
	moreBtn.addEventListener('click', revealChunk);
	window.addEventListener('resize', applyInitialState);
}
