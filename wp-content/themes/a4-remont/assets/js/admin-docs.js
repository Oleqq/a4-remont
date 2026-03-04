document.addEventListener('DOMContentLoaded', () => {
	const root = document.querySelector('[data-a4-docs]');

	if (!root) {
		return;
	}

	const tabs = Array.from(root.querySelectorAll('[data-a4-docs-tab]'));
	const panels = Array.from(root.querySelectorAll('[data-a4-docs-panel]'));
	const storageKey = 'a4-remont-docs-active-tab';

	const activateTab = (target) => {
		if (!target) {
			return;
		}

		tabs.forEach((tab) => {
			const isActive = tab.dataset.a4DocsTab === target;
			tab.classList.toggle('is-active', isActive);
			tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
		});

		panels.forEach((panel) => {
			panel.classList.toggle('is-active', panel.dataset.a4DocsPanel === target);
		});

		try {
			window.sessionStorage.setItem(storageKey, target);
		} catch (error) {
			// Ignore storage errors in locked-down admin environments.
		}
	};

	let initialTab = 'user';

	if (window.location.hash === '#technical') {
		initialTab = 'technical';
	} else if (window.location.hash === '#user') {
		initialTab = 'user';
	} else {
		try {
			initialTab = window.sessionStorage.getItem(storageKey) || initialTab;
		} catch (error) {
			// Ignore storage errors in locked-down admin environments.
		}
	}

	activateTab(initialTab);

	tabs.forEach((tab) => {
		tab.addEventListener('click', () => {
			const target = tab.dataset.a4DocsTab;

			activateTab(target);

			if (history.replaceState) {
				history.replaceState(null, '', `#${target}`);
			}
		});
	});
});
