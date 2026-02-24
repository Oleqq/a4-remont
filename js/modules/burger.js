export function initBurger() {
	const burger = document.querySelector('[data-burger]');
	const mob = document.querySelector('[data-header-mob]');
	const close = document.querySelector('[data-header-close]');

	if (!burger || !mob) return;

  const openMenu = () => {
    burger.setAttribute('aria-expanded', 'true');
    mob.classList.add('_open');
    mob.setAttribute('aria-hidden', 'false');
    document.body.classList.add('lock');
    document.body.classList.add('_menu-open');
  };

  const closeMenu = () => {
    burger.setAttribute('aria-expanded', 'false');
    mob.classList.remove('_open');
    mob.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('lock');
    document.body.classList.remove('_menu-open');
  };

	burger.addEventListener('click', openMenu);
	if (close) close.addEventListener('click', closeMenu);

	// close on overlay click (если клик по пустому месту)
	mob.addEventListener('click', (e) => {
		if (e.target === mob) closeMenu();
	});

	// close on Escape
	document.addEventListener('keydown', (e) => {
		if (e.key !== 'Escape') return;
		if (!mob.classList.contains('_open')) return;
		closeMenu();
	});

	// mobile submenu
	mob.querySelectorAll('[data-mob-sub]').forEach((item) => {
		const btn = item.querySelector('[data-mob-sub-btn]');
		if (!btn) return;

		btn.addEventListener('click', () => {
			const isOpen = item.classList.contains('_open');
			mob.querySelectorAll('[data-mob-sub]').forEach((i) => {
				i.classList.remove('_open');
				const b = i.querySelector('[data-mob-sub-btn]');
				if (b) b.setAttribute('aria-expanded', 'false');
			});
			if (!isOpen) {
				item.classList.add('_open');
				btn.setAttribute('aria-expanded', 'true');
			}
		});
	});
}