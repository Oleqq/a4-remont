export function initWorkSingleHero() {
	const section = document.querySelector('.work-single-hero');
	if (!section) return;

	const text = section.querySelector('[data-work-single-text]');
	const btn = section.querySelector('[data-work-single-more]');

	if (!text || !btn) return;

	const mobileQuery = window.matchMedia('(max-width: 650px)');

	const syncState = () => {
		if (mobileQuery.matches) {
			btn.hidden = text.classList.contains('is-expanded');
			return;
		}

		text.classList.remove('is-expanded');
		btn.hidden = true;
	};

	btn.addEventListener('click', () => {
		text.classList.add('is-expanded');
		btn.hidden = true;
	});

	if (typeof mobileQuery.addEventListener === 'function') {
		mobileQuery.addEventListener('change', syncState);
	} else if (typeof mobileQuery.addListener === 'function') {
		mobileQuery.addListener(syncState);
	}

	syncState();
}
