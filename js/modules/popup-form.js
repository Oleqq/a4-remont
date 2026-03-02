export function initPopupForm() {
	const popup = document.querySelector('[data-popup-form]');
	if (!popup) return;

	const popupPanel = popup.querySelector('[data-popup-panel]');
	const closeBtn = popup.querySelector('[data-popup-close]');
	const headerButtons = document.querySelectorAll('.header__btn');
	const pageBody = document.body;

	let lastFocusedElement = null;

	const openPopup = () => {
		if (popup.classList.contains('is-open')) return;

		lastFocusedElement = document.activeElement;
		popup.classList.add('is-open');
		popup.setAttribute('aria-hidden', 'false');
		pageBody.classList.add('popup-open');
	};

	const closePopup = () => {
		if (!popup.classList.contains('is-open')) return;

		popup.classList.remove('is-open');
		popup.setAttribute('aria-hidden', 'true');
		pageBody.classList.remove('popup-open');

		if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
			lastFocusedElement.focus();
		}
	};

	// document.addEventListener('click', (event) => {
	// 	const trigger = event.target.closest('.btn');
	// 	if (!trigger) return;
	// 	if (trigger.closest('[data-popup-form]')) return;
	// 	if (trigger.hasAttribute('data-popup-ignore')) return;

	// 	event.preventDefault();
	// 	openPopup();
	// });

	if (closeBtn) {
		closeBtn.addEventListener('click', closePopup);
	}

	headerButtons.forEach((button) => {
		button.addEventListener('click', (event) => {
			event.preventDefault();
			openPopup();
		});
	});

	popup.addEventListener('click', (event) => {
		if (popupPanel && popupPanel.contains(event.target)) return;
		closePopup();
	});

	document.addEventListener('keydown', (event) => {
		if (event.key !== 'Escape') return;
		closePopup();
	});
}
