export function initHeaderDropdown() {
	const items = Array.from(document.querySelectorAll('[data-header-sub]'));

	if (!items.length) return;

	const closeItem = (item) => {
		item.classList.remove('_open');
		item.querySelectorAll('[data-header-sub-btn]').forEach((button) => {
			button.setAttribute('aria-expanded', 'false');
		});
	};

	const openItem = (item) => {
		items.forEach((otherItem) => {
			if (otherItem !== item) {
				closeItem(otherItem);
			}
		});

		item.classList.add('_open');
		item.querySelectorAll('[data-header-sub-btn]').forEach((button) => {
			button.setAttribute('aria-expanded', 'true');
		});
	};

	items.forEach((item) => {
		item.querySelectorAll('[data-header-sub-btn]').forEach((button) => {
			button.addEventListener('click', (event) => {
				event.preventDefault();
				const isOpen = item.classList.contains('_open');

				if (isOpen) {
					closeItem(item);
					return;
				}

				openItem(item);
			});
		});
	});

	document.addEventListener('click', (event) => {
		if (items.some((item) => item.contains(event.target))) return;
		items.forEach(closeItem);
	});

	document.addEventListener('keydown', (event) => {
		if (event.key !== 'Escape') return;
		items.forEach(closeItem);
	});
}
