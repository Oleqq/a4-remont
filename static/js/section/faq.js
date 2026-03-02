export function initFaq() {
	const root = document.querySelector('[data-faq]');
	if (!root) return;

	const items = Array.from(root.querySelectorAll('.faq-item'));
	if (!items.length) return;

	const closeAll = () => {
		items.forEach((item) => {
			item.classList.remove('_active');
			const btn = item.querySelector('[data-faq-btn]');
			if (btn) btn.setAttribute('aria-expanded', 'false');
		});
	};

	items.forEach((item) => {
		const btn = item.querySelector('[data-faq-btn]');
		if (!btn) return;

		btn.addEventListener('click', () => {
			const isActive = item.classList.contains('_active');
			closeAll();

			if (!isActive) {
				item.classList.add('_active');
				btn.setAttribute('aria-expanded', 'true');
			}
		});
	});

	// close on Escape
	document.addEventListener('keydown', (e) => {
		if (e.key !== 'Escape') return;
		closeAll();
	});
}