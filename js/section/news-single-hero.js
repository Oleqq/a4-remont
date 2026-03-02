const copyWithFallback = (value) => {
	const textarea = document.createElement('textarea');
	textarea.value = value;
	textarea.setAttribute('readonly', '');
	textarea.style.position = 'fixed';
	textarea.style.opacity = '0';
	textarea.style.pointerEvents = 'none';

	document.body.append(textarea);
	textarea.select();
	textarea.setSelectionRange(0, textarea.value.length);

	const isCopied = document.execCommand('copy');
	textarea.remove();

	if (!isCopied) {
		throw new Error('Copy command failed');
	}
};

const copyToClipboard = async (value) => {
	if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
		await navigator.clipboard.writeText(value);
		return;
	}

	copyWithFallback(value);
};

export function initNewsSingleHero() {
	const sections = document.querySelectorAll('.news-single-hero');
	if (!sections.length) return;

	sections.forEach((section, index) => {
		const shareBox = section.querySelector('[data-news-share]');
		const trigger = shareBox?.querySelector('[data-news-share-trigger]');
		const tooltip = shareBox?.querySelector('[data-news-share-tooltip]');
		const status = shareBox?.querySelector('[data-share-status]');
		const copyButton = shareBox?.querySelector('[data-share-copy]');
		const shareLinks = shareBox ? Array.from(shareBox.querySelectorAll('[data-share-link]')) : [];

		if (!shareBox || !trigger || !tooltip) return;

		const title = section.querySelector('.news-single-hero__title')?.textContent?.trim() || document.title;
		const pageUrl = window.location.href;
		const encodedTitle = encodeURIComponent(title);
		const encodedUrl = encodeURIComponent(pageUrl);
		const defaultStatus = status?.textContent?.trim() || '';
		let statusTimer = null;

		tooltip.id = tooltip.id || `news-single-share-tooltip-${index + 1}`;
		trigger.setAttribute('aria-controls', tooltip.id);

		const shareUrlByNetwork = {
			telegram: `https://t.me/share/url?url=${encodedUrl}&text=${encodedTitle}`,
			whatsapp: `https://api.whatsapp.com/send?text=${encodedTitle}%20${encodedUrl}`,
			vk: `https://vk.com/share.php?url=${encodedUrl}&title=${encodedTitle}`,
		};

		shareLinks.forEach((link) => {
			const network = link.dataset.shareLink;
			if (!network || !shareUrlByNetwork[network]) return;
			link.href = shareUrlByNetwork[network];
		});

		const setStatus = (message, isCopied = false) => {
			if (!status) return;
			status.textContent = message;
			shareBox.classList.toggle('is-copied', isCopied);
		};

		const resetStatus = () => {
			if (statusTimer) {
				window.clearTimeout(statusTimer);
				statusTimer = null;
			}

			setStatus(defaultStatus, false);
		};

		const open = () => {
			shareBox.classList.add('is-open');
			trigger.setAttribute('aria-expanded', 'true');
			tooltip.setAttribute('aria-hidden', 'false');
		};

		const close = () => {
			shareBox.classList.remove('is-open');
			trigger.setAttribute('aria-expanded', 'false');
			tooltip.setAttribute('aria-hidden', 'true');
			resetStatus();
		};

		trigger.addEventListener('click', () => {
			if (shareBox.classList.contains('is-open')) {
				close();
				return;
			}

			open();
		});

		copyButton?.addEventListener('click', async () => {
			try {
				await copyToClipboard(pageUrl);
				setStatus('Ссылка скопирована', true);

				if (statusTimer) {
					window.clearTimeout(statusTimer);
				}

				statusTimer = window.setTimeout(() => {
					setStatus(defaultStatus, false);
					statusTimer = null;
				}, 2200);
			} catch (error) {
				setStatus('Не удалось скопировать ссылку');
			}
		});

		shareLinks.forEach((link) => {
			link.addEventListener('click', () => {
				close();
			});
		});

		document.addEventListener('click', (event) => {
			if (shareBox.contains(event.target)) return;
			close();
		});

		document.addEventListener('keydown', (event) => {
			if (event.key !== 'Escape' || !shareBox.classList.contains('is-open')) return;
			close();
			trigger.focus();
		});

		shareBox.addEventListener('focusout', () => {
			window.setTimeout(() => {
				if (shareBox.contains(document.activeElement)) return;
				close();
			}, 0);
		});
	});
}
