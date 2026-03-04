(function ($) {
	'use strict';

	function bindTabs(scope) {
		var $scope = $(scope);
		var $buttons = $scope.find('[data-a4seo-target]');

		$buttons.on('click', function () {
			var target = $(this).data('a4seo-target');

			$buttons.removeClass('is-active');
			$(this).addClass('is-active');
			$scope.find('[data-a4seo-panel]').removeClass('is-active');
			$scope.find('[data-a4seo-panel="' + target + '"]').addClass('is-active');
		});
	}

	function bindCounters(scope) {
		$(scope).find('[data-a4seo-counter]').each(function () {
			var $field = $(this);
			var type = $field.data('a4seo-counter');
			var $counter = $field.closest('.a4seo-field').find('[data-a4seo-counter-value="' + type + '"]');

			function update() {
				var length = ($field.val() || '').length;

				$counter.text(length + ' симв.');
				$counter.removeClass('is-ok is-warn is-bad');

				if (length <= 0) {
					return;
				}

				if (length <= 160) {
					$counter.addClass('is-ok');
					return;
				}

				if (length <= 220) {
					$counter.addClass('is-warn');
					return;
				}

				$counter.addClass('is-bad');
			}

			$field.on('input change', update);
			update();
		});
	}

	function buildPreviewTitle(rawTitle, fallbackTitle, siteName, separator) {
		var title = $.trim(rawTitle || '');

		if (!title) {
			return fallbackTitle;
		}

		if (!siteName) {
			return title;
		}

		return title + ' ' + (separator || '|') + ' ' + siteName;
	}

	function bindPreview(scope) {
		$(scope).find('.a4seo-metabox').each(function () {
			var $box = $(this);
			var $titleField = $box.find('[name="a4_remont_seo_meta[title]"]');
			var $descriptionField = $box.find('[name="a4_remont_seo_meta[description]"]');
			var $titlePreview = $box.find('[data-a4seo-preview="title"]');
			var $descriptionPreview = $box.find('[data-a4seo-preview="description"]');

			if (!$titleField.length || !$descriptionField.length) {
				return;
			}

			function update() {
				var nextTitle = buildPreviewTitle(
					$titleField.val(),
					$box.data('a4seo-default-title'),
					$box.data('a4seo-site-name'),
					$box.data('a4seo-separator')
				);
				var nextDescription = $.trim($descriptionField.val() || '') || $box.data('a4seo-default-description');

				$titlePreview.text(nextTitle);
				$descriptionPreview.text(nextDescription);
			}

			$titleField.on('input change', update);
			$descriptionField.on('input change', update);
			update();
		});
	}

	function bindMedia(scope) {
		$(scope).find('.a4seo-image-field').each(function () {
			var $field = $(this);
			var $value = $field.parent().find('.a4seo-image-field__value');
			var frame;

			$field.find('.a4seo-image-field__open').on('click', function (event) {
				event.preventDefault();

				if (frame) {
					frame.open();
					return;
				}

				frame = wp.media({
					title: a4SeoAdmin.mediaTitle,
					button: {
						text: a4SeoAdmin.mediaButton
					},
					multiple: false
				});

				frame.on('select', function () {
					var attachment = frame.state().get('selection').first().toJSON();

					$value.val(attachment.id).trigger('change');
					$field.find('.a4seo-image-field__preview').html('<img src="' + attachment.url + '" alt="">');
				});

				frame.open();
			});

			$field.find('.a4seo-image-field__clear').on('click', function (event) {
				event.preventDefault();
				$value.val('');
				$field.find('.a4seo-image-field__preview').html('<span>' + a4SeoAdmin.emptyImageLabel + '</span>');
			});
		});
	}

	function hydrateScores(scope) {
		$(scope).find('.a4seo-score').each(function () {
			var $score = $(this);
			var value = parseInt($score.text(), 10);

			if (isNaN(value)) {
				return;
			}

			$score.attr('title', 'SEO score: ' + value + '%');
		});
	}

	function toggleRepeaterEmptyState($repeater) {
		var $list = $repeater.find('[data-a4seo-repeater-list]');
		var $items = $list.children('[data-a4seo-repeater-item]').not('.is-template');
		var $empty = $list.find('[data-a4seo-repeater-empty]');

		if ($items.length) {
			$empty.remove();
			return;
		}

		if (!$empty.length) {
			$list.append('<p class="a4seo-redirects__empty" data-a4seo-repeater-empty>Пока нет ни одного правила. Добавьте первое правило редиректа.</p>');
		}
	}

	function bindRepeaters(scope) {
		$(scope).find('[data-a4seo-repeater]').each(function () {
			var $repeater = $(this);
			var $list = $repeater.find('[data-a4seo-repeater-list]');
			var templateHtml = $.trim($repeater.find('[data-a4seo-repeater-template]').html() || '');

			$repeater.on('click', '[data-a4seo-repeater-add]', function (event) {
				var nextIndex = parseInt($repeater.attr('data-a4seo-next-index'), 10);
				var markup;

				event.preventDefault();

				if (!templateHtml) {
					return;
				}

				if (isNaN(nextIndex)) {
					nextIndex = 0;
				}

				markup = templateHtml.replace(/__index__/g, String(nextIndex));
				$repeater.attr('data-a4seo-next-index', nextIndex + 1);
				$list.find('[data-a4seo-repeater-empty]').remove();
				$list.append(markup);
				$list.children('[data-a4seo-repeater-item]').last().hide().slideDown(180);
			});

			$repeater.on('click', '[data-a4seo-repeater-remove]', function (event) {
				var $item = $(this).closest('[data-a4seo-repeater-item]');

				event.preventDefault();

				$item.slideUp(180, function () {
					$item.remove();
					toggleRepeaterEmptyState($repeater);
				});
			});

			toggleRepeaterEmptyState($repeater);
		});
	}

	function bindContextSwitchers(scope) {
		$(scope).find('[data-a4seo-context-switcher]').each(function () {
			var $switcher = $(this);
			var $select = $switcher.find('[data-a4seo-context-select]');
			var $cards = $switcher.find('[data-a4seo-context-card]');

			if (!$select.length || !$cards.length) {
				return;
			}

			function update() {
				var value = $select.val();

				$cards.removeClass('is-active');
				$cards.filter('[data-a4seo-context-card="' + value + '"]').addClass('is-active');
			}

			$select.on('change', update);
			update();
		});
	}

	$(function () {
		$('[data-a4seo-tabs]').each(function () {
			bindTabs(this);
		});

		bindCounters(document);
		bindPreview(document);
		bindMedia(document);
		bindRepeaters(document);
		bindContextSwitchers(document);
		hydrateScores(document);
	});
}(jQuery));
