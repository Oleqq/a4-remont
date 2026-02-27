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

import { initServiceStream } from './section/service-stream.js';

import { initProcessSteps } from './section/process-steps.js';
import { initServiceArguments } from './section/service-arguments.js';
import { initServiceRepairTypes } from './section/service-repair-types.js';
import { initServiceOrderSteps } from './section/service-order-steps.js';
import { initWorksPortfolio } from './section/works-portfolio.js';
import { initNewsPreview } from './section/news-preview.js';
import { initReviewsFeedback } from './section/reviews-feedback.js';
import { initWorkSingleHero } from './section/work-single-hero.js';
import { initWorkSinglePerformed } from './section/work-single-performed.js';
import { initWorkSingleResult } from './section/work-single-result.js';

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

	initServiceStream();
	initProcessSteps();
	initServiceArguments();
	initServiceRepairTypes();
	initServiceOrderSteps();
	initWorksPortfolio();
	initNewsPreview();
	initReviewsFeedback();
	initWorkSingleHero();
	initWorkSinglePerformed();
	initWorkSingleResult();

});
