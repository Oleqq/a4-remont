export function initHeaderDropdown() {
	const item = document.querySelector('[data-header-sub]');
	const btn = document.querySelector('[data-header-sub-btn]');
	const dropdown = document.getElementById('header-services');
	if (!item || !btn || !dropdown) return;

	const open = () => {
		item.classList.add('_open');
		btn.setAttribute('aria-expanded', 'true');
	};

	const close = () => {
		item.classList.remove('_open');
		btn.setAttribute('aria-expanded', 'false');
	};

	btn.addEventListener('click', () => {
		const isOpen = item.classList.contains('_open');
		if (isOpen) close();
		else open();
	});

	document.addEventListener('click', (e) => {
		if (item.contains(e.target)) return;
		close();
	});

	document.addEventListener('keydown', (e) => {
		if (e.key !== 'Escape') return;
		close();
	});
}