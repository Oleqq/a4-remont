export function initTeamPreview() {
	const root = document.querySelector('[data-team-slider]');
	if (!root) return;

	const prevEl = document.querySelector('.team-preview__btn--prev');
	const nextEl = document.querySelector('.team-preview__btn--next');
	const scrollbarEl = document.querySelector('.team-preview__scrollbar');

	let swiperInstance = null;

	// на десктопе 3 карточки — свайпер нужен (по макету)
	// но делаем безопасно: если слайдов мало, не включаем
	const getSlidesCount = () => {
		const wrapper = root.querySelector('.swiper-wrapper');
		return wrapper ? wrapper.children.length : 0;
	};

	const enable = () => {
		if (swiperInstance) return;

		// если реально нечего листать — не инициалим
		if (getSlidesCount() <= 1) return;

		// eslint-disable-next-line no-undef
		swiperInstance = new Swiper(root, {
			speed: 600,
			spaceBetween: 16,
			watchOverflow: true,

			slidesPerView: 1.12,

			navigation: {
				prevEl,
				nextEl,
				disabledClass: 'is-disabled',
			},

			scrollbar: {
				el: scrollbarEl,
				draggable: false,
				dragClass: 'swiper-scrollbar-drag',
				lockClass: 'is-locked',
			},

			breakpoints: {
				0: { slidesPerView: 1.33, spaceBetween: 10 },
				467: { slidesPerView: 1.33, spaceBetween: 10 },
				650: { slidesPerView: 1.5, spaceBetween: 10 },
				768: { slidesPerView: 1.5, spaceBetween: 10 },
				991: { slidesPerView: 2.15, spaceBetween: 10 },
				992: { slidesPerView: 2.15, spaceBetween: 10 },
				1200: { slidesPerView: 2.15, spaceBetween: 10 },
			},
		});
	};

	const disable = () => {
		if (!swiperInstance) return;
		swiperInstance.destroy(true, true);
		swiperInstance = null;
	};

	// По макету:
	// - на >=992 стрелки сверху справа (они и так в хедере)
	// - на <=991 стрелки absolute снизу, и появляется прогресс (scrollbar)
	// Свайпер можно держать всегда, но делаем переключение как в вашем паттерне:
	const mq = window.matchMedia('(max-width: 991px)');

	const onChange = () => {
		// если хотите держать свайпер и на десктопе — просто enable() всегда
		// но раз у вас паттерн с mq — оставляю так:
		enable(); // свайпер нужен и там, и там (по скрину)
		// если вдруг решите "свайпер только мобилка" — поменяешь на:
		// if (mq.matches) enable(); else disable();
	};

	onChange();
	mq.addEventListener('change', onChange);
}