import { initBurger } from './modules/burger.js';
import { initHero } from './section/hero.js';
import { initBenefits } from './section/benefits.js';
import { initOfferTabs, initOfferSliders } from './section/offer-tabs.js';
import { initWhyUs } from './section/why-us.js';
import { initFaq } from './section/faq.js';

document.addEventListener('DOMContentLoaded', () => {
	initBurger();
	initHero();
	initBenefits();
	initOfferTabs();
	initOfferSliders();
	initWhyUs();
	initFaq();
});