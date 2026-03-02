export function initOfferTabs() {
	const root = document.querySelector('[data-offer-tabs]');
	if (!root) return;

	const tabs = Array.from(root.querySelectorAll('[data-offer-tab]'));
	const panels = Array.from(root.querySelectorAll('[data-offer-panel]'));

	const getPanel = (name) => panels.find((p) => p.dataset.offerPanel === name);

	const setActive = (name) => {
		tabs.forEach((tab) => {
			const isActive = tab.dataset.offerTab === name;
			tab.setAttribute('aria-selected', String(isActive));
			tab.tabIndex = isActive ? 0 : -1;
		});

		panels.forEach((panel) => {
			const isActive = panel.dataset.offerPanel === name;
			panel.classList.toggle('is-hidden', !isActive);
		});
	};

	// keyboard
	root.addEventListener('keydown', (e) => {
		if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(e.key)) return;
		const current = tabs.findIndex((t) => t.getAttribute('aria-selected') === 'true');
		if (current < 0) return;

		let next = current;
		if (e.key === 'ArrowRight') next = (current + 1) % tabs.length;
		if (e.key === 'ArrowLeft') next = (current - 1 + tabs.length) % tabs.length;
		if (e.key === 'Home') next = 0;
		if (e.key === 'End') next = tabs.length - 1;

		tabs[next].focus();
		tabs[next].click();
	});

	tabs.forEach((tab) => {
		tab.addEventListener('click', () => {
			const name = tab.dataset.offerTab;
			setActive(name);
			root.dispatchEvent(new CustomEvent('offerTabs:change', { detail: { name } }));
		});
	});

	setActive('repair');
}

export function initOfferSliders() {
	const roots = document.querySelectorAll('[data-offer-swiper]');
	if (!roots.length) return;

	roots.forEach((el) => {
		const slides = el.querySelectorAll('.swiper-slide');
		if (slides.length <= 1) return;

		const name = el.dataset.offerSwiper;

		const prev = document.querySelector(`[data-offer-prev="${name}"]`);
		const next = document.querySelector(`[data-offer-next="${name}"]`);

		// eslint-disable-next-line no-undef
		new Swiper(el, {
			slidesPerView: 3,
			spaceBetween: 18,
			speed: 600,
			watchOverflow: true,
			loop: true,
			navigation: { prevEl: prev, nextEl: next },
			breakpoints: {
				0: { slidesPerView: 1, spaceBetween: 10 },
				650: { slidesPerView: 2, spaceBetween: 10 },
				767: { slidesPerView: 3, spaceBetween: 10 },
				991: { slidesPerView: 3, spaceBetween: 10 },
			},
		});
	});
}