import { initBurger } from './modules/burger.js';
import { initHeaderDropdown } from './modules/header-dropdown.js';

import { initSectionAnimation } from './modules/section-animation.js';

import { initHero } from './section/hero.js';
import { initBenefits } from './section/benefits.js';
import { initOfferTabs, initOfferSliders } from './section/offer-tabs.js';
import { initWhyUs } from './section/why-us.js';
import { initFaq } from './section/faq.js';

import { initTeamPreview } from './section/team-preview.js';

import { initFeedbackShowcase } from './section/feedback-showcase.js';

document.addEventListener('DOMContentLoaded', () => {
	initBurger();
	initHeaderDropdown();

	initSectionAnimation();

	initHero();
	initBenefits();
	initOfferTabs();
	initOfferSliders();
	initWhyUs();
	initFaq();

	initTeamPreview();

	initFeedbackShowcase();

});