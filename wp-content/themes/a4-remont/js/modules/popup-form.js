export function initPopupForm() {
	const popups = Array.from(document.querySelectorAll('[data-popup-form]'));
	if (!popups.length) return;

	const pageBody = document.body;
	let activePopup = null;
	let lastFocusedElement = null;

	const escapeSelector = (value) => {
		if (window.CSS && typeof window.CSS.escape === 'function') {
			return window.CSS.escape(value);
		}

		return String(value).replace(/["\\]/g, '\\$&');
	};

	const getPopupByKey = (popupKey) => {
		if (!popupKey) {
			return popups[0] || null;
		}

		return document.querySelector(`[data-popup-form="${escapeSelector(popupKey)}"]`) || popups[0] || null;
	};

	const syncPopupSource = (popup, trigger) => {
		if (!popup) return;

		const sourceText =
			trigger?.getAttribute('data-popup-source')?.trim() ||
			trigger?.textContent?.trim() ||
			'';

		popup.querySelectorAll('[data-popup-source-input]').forEach((input) => {
			input.value = sourceText;
		});
	};

	const focusPopup = (popup) => {
		if (!popup) return;

		const focusTarget =
			popup.querySelector(
				'input:not([type="hidden"]):not([disabled]), textarea:not([disabled]), select:not([disabled]), button:not([disabled]), [href], [tabindex]:not([tabindex="-1"])'
			) || popup.querySelector('[data-popup-panel]');

		if (focusTarget && typeof focusTarget.focus === 'function') {
			focusTarget.focus({ preventScroll: true });
		}
	};

	const openPopup = (popup, trigger = null) => {
		if (!popup) return;

		if (activePopup && activePopup !== popup) {
			activePopup.classList.remove('is-open');
			activePopup.setAttribute('aria-hidden', 'true');
		}

		if (activePopup === popup && popup.classList.contains('is-open')) {
			return;
		}

		lastFocusedElement = trigger || document.activeElement;
		activePopup = popup;

		syncPopupSource(popup, trigger);

		popup.classList.add('is-open');
		popup.setAttribute('aria-hidden', 'false');
		pageBody.classList.add('popup-open');

		focusPopup(popup);
	};

	const closePopup = (popup = activePopup, restoreFocus = true) => {
		if (!popup || !popup.classList.contains('is-open')) return;

		popup.classList.remove('is-open');
		popup.setAttribute('aria-hidden', 'true');

		if (popup === activePopup) {
			activePopup = null;
		}

		if (!document.querySelector('[data-popup-form].is-open')) {
			pageBody.classList.remove('popup-open');
		}

		if (
			restoreFocus &&
			lastFocusedElement &&
			typeof lastFocusedElement.focus === 'function'
		) {
			lastFocusedElement.focus();
		}
	};

	document.addEventListener('click', (event) => {
		const trigger = event.target.closest('[data-popup-open]');

		if (trigger) {
			event.preventDefault();
			openPopup(getPopupByKey(trigger.getAttribute('data-popup-open') || ''), trigger);
			return;
		}

		const closeButton = event.target.closest('[data-popup-close]');

		if (closeButton) {
			event.preventDefault();
			closePopup(closeButton.closest('[data-popup-form]'));
			return;
		}

		if (activePopup && event.target === activePopup) {
			closePopup(activePopup);
		}
	});

	document.addEventListener('keydown', (event) => {
		if (event.key !== 'Escape' || !activePopup) return;
		closePopup(activePopup);
	});
}
