export function initHero() {
	const sliderEl = document.querySelector('[data-hero-swiper]');
	if (!sliderEl) return;

	const slides = sliderEl.querySelectorAll('.swiper-slide');
	if (slides.length <= 1) return;

	// Swiper подключён локально глобальным скриптом
	// eslint-disable-next-line no-undef
	new Swiper(sliderEl, {
		slidesPerView: 1,
		speed: 600,
		loop: true,
		watchOverflow: true,
		pagination: {
			el: sliderEl.querySelector('[data-hero-pagination]'),
			clickable: true,
		},
		navigation: {
			prevEl: sliderEl.querySelector('[data-hero-prev]'),
			nextEl: sliderEl.querySelector('[data-hero-next]'),
		},
	});
}