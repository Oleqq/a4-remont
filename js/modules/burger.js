export function initBurger() {
  const burger = document.querySelector('[data-burger]');
  if (!burger) return;

  burger.addEventListener('click', () => {
    const expanded = burger.getAttribute('aria-expanded') === 'true';
    burger.setAttribute('aria-expanded', String(!expanded));
    document.body.classList.toggle('lock');
    burger.classList.toggle('_active');
  });
}
