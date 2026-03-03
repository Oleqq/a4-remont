<?php
/**
 * Popup helpers and rendering.
 *
 * @package a4-remont
 */

/**
 * Return the fallback popup key.
 *
 * @return string
 */
function a4_remont_get_default_popup_key() {
	return 'callback-popup';
}

/**
 * Return the default agreement text for the popup form.
 *
 * @return string
 */
function a4_remont_get_default_popup_agreement_text() {
	$privacy_url = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '';
	$privacy_url = $privacy_url ? $privacy_url : home_url( '/privacy-policy/' );

	return sprintf(
		'Я даю согласие на <a href="%1$s">обработку персональных данных</a> и соглашаюсь с <a href="%1$s">политикой конфиденциальности</a>.',
		esc_url( $privacy_url )
	);
}

/**
 * Return the default popup configuration.
 *
 * @return array<string, string>
 */
function a4_remont_get_default_popup_config() {
	return array(
		'key'                 => a4_remont_get_default_popup_key(),
		'admin_label'         => 'Форма обратной связи',
		'trigger_label'       => 'Открыть форму',
		'title'               => "Готовы преобразить\nсвое пространство?",
		'lead'                => 'Давайте обсудим ваш проект. Оставьте контакты в форме ниже, и наша команда свяжется с вами для уточнения деталей.',
		'name_placeholder'    => 'Ваше имя',
		'phone_placeholder'   => '+7 000 000 00 00',
		'email_placeholder'   => 'E-mail',
		'message_placeholder' => 'Сообщение',
		'submit_label'        => 'Отправить',
		'agreement_text'      => a4_remont_get_default_popup_agreement_text(),
		'form_shortcode'      => '',
	);
}

/**
 * Normalize a popup configuration item.
 *
 * @param array<string,mixed> $item  Popup item.
 * @param int                 $index Popup index.
 * @return array<string,mixed>
 */
function a4_remont_normalize_popup_item( $item, $index = 0 ) {
	$defaults = a4_remont_get_default_popup_config();
	$key      = ! empty( $item['popup_key'] ) ? sanitize_key( (string) $item['popup_key'] ) : '';

	if ( '' === $key ) {
		$key = 0 === $index ? $defaults['key'] : 'popup-' . ( $index + 1 );
	}

	$admin_label = ! empty( $item['popup_admin_label'] ) ? trim( (string) $item['popup_admin_label'] ) : '';

	if ( '' === $admin_label ) {
		$admin_label = 0 === $index ? $defaults['admin_label'] : 'Popup ' . ( $index + 1 );
	}

	$trigger_label = ! empty( $item['trigger_label'] ) ? trim( (string) $item['trigger_label'] ) : '';

	if ( '' === $trigger_label ) {
		$trigger_label = $defaults['trigger_label'];
	}

	$title = ! empty( $item['popup_title'] ) ? trim( (string) $item['popup_title'] ) : '';

	if ( '' === $title ) {
		$title = $defaults['title'];
	}

	$lead = ! empty( $item['popup_lead'] ) ? trim( (string) $item['popup_lead'] ) : '';

	if ( '' === $lead ) {
		$lead = $defaults['lead'];
	}

	$name_placeholder = ! empty( $item['name_placeholder'] ) ? (string) $item['name_placeholder'] : $defaults['name_placeholder'];
	$phone_placeholder = ! empty( $item['phone_placeholder'] ) ? (string) $item['phone_placeholder'] : $defaults['phone_placeholder'];
	$email_placeholder = ! empty( $item['email_placeholder'] ) ? (string) $item['email_placeholder'] : $defaults['email_placeholder'];
	$message_placeholder = ! empty( $item['message_placeholder'] ) ? (string) $item['message_placeholder'] : $defaults['message_placeholder'];
	$submit_label        = ! empty( $item['submit_label'] ) ? (string) $item['submit_label'] : $defaults['submit_label'];
	$agreement_text      = ! empty( $item['agreement_text'] ) ? (string) $item['agreement_text'] : $defaults['agreement_text'];
	$form_shortcode      = ! empty( $item['form_shortcode'] ) ? (string) $item['form_shortcode'] : $defaults['form_shortcode'];
	$logo_image          = ! empty( $item['popup_logo'] ) ? $item['popup_logo'] : null;

	return array(
		'key'                 => $key,
		'admin_label'         => $admin_label,
		'trigger_label'       => $trigger_label,
		'title'               => $title,
		'lead'                => $lead,
		'logo_image'          => $logo_image,
		'name_placeholder'    => $name_placeholder,
		'phone_placeholder'   => $phone_placeholder,
		'email_placeholder'   => $email_placeholder,
		'message_placeholder' => $message_placeholder,
		'submit_label'        => $submit_label,
		'agreement_text'      => $agreement_text,
		'form_shortcode'      => $form_shortcode,
	);
}

/**
 * Return popup configurations.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_popup_items() {
	$default_popup = a4_remont_normalize_popup_item( array(), 0 );

	if ( ! function_exists( 'get_field' ) ) {
		return array( $default_popup );
	}

	$raw_items = get_field( 'site_popups', 'option' );

	if ( empty( $raw_items ) || ! is_array( $raw_items ) ) {
		return array( $default_popup );
	}

	$items = array();

	foreach ( array_values( $raw_items ) as $index => $raw_item ) {
		if ( ! is_array( $raw_item ) ) {
			continue;
		}

		$items[] = a4_remont_normalize_popup_item( $raw_item, (int) $index );
	}

	if ( empty( $items ) ) {
		return array( $default_popup );
	}

	return $items;
}

/**
 * Return popup choices for ACF fields.
 *
 * @return array<string, string>
 */
function a4_remont_get_popup_choices() {
	$choices = array();

	foreach ( a4_remont_get_popup_items() as $popup ) {
		if ( empty( $popup['key'] ) ) {
			continue;
		}

		$choices[ $popup['key'] ] = ! empty( $popup['admin_label'] ) ? (string) $popup['admin_label'] : (string) $popup['key'];
	}

	if ( empty( $choices ) ) {
		$default_key            = a4_remont_get_default_popup_key();
		$choices[ $default_key ] = 'Форма обратной связи';
	}

	return $choices;
}

/**
 * Return a popup config by key.
 *
 * @param string $popup_key Popup key.
 * @return array<string,mixed>
 */
function a4_remont_get_popup_config( $popup_key = '' ) {
	$popup_key = sanitize_key( (string) $popup_key );
	$items     = a4_remont_get_popup_items();

	if ( '' !== $popup_key ) {
		foreach ( $items as $popup ) {
			if ( isset( $popup['key'] ) && $popup_key === $popup['key'] ) {
				return $popup;
			}
		}
	}

	return ! empty( $items[0] ) ? $items[0] : a4_remont_normalize_popup_item( array(), 0 );
}

/**
 * Return fallback button label for a popup.
 *
 * @param string $popup_key Popup key.
 * @return string
 */
function a4_remont_get_popup_trigger_fallback_label( $popup_key = '' ) {
	$popup = a4_remont_get_popup_config( $popup_key );

	if ( ! empty( $popup['trigger_label'] ) ) {
		return (string) $popup['trigger_label'];
	}

	if ( ! empty( $popup['admin_label'] ) ) {
		return (string) $popup['admin_label'];
	}

	return 'Открыть форму';
}

/**
 * Render a popup trigger button.
 *
 * @param string               $popup_key         Popup key.
 * @param string               $label             Trigger label.
 * @param string               $class_name        CSS classes.
 * @param array<string,string> $extra_attributes  Extra attributes.
 * @return string
 */
function a4_remont_get_popup_trigger_html( $popup_key, $label, $class_name = '', $extra_attributes = array() ) {
	$label = trim( (string) $label );

	if ( '' === $label ) {
		$label = a4_remont_get_popup_trigger_fallback_label( $popup_key );
	}

	$popup_key = sanitize_key( (string) $popup_key );

	if ( '' === $popup_key ) {
		$popup_key = a4_remont_get_default_popup_key();
	}

	$attributes = array_merge(
		array(
			'type'             => 'button',
			'data-popup-open'  => $popup_key,
			'data-popup-source'=> $label,
		),
		$extra_attributes
	);

	if ( $class_name ) {
		$attributes['class'] = $class_name;
	}

	return sprintf(
		'<button %1$s>%2$s</button>',
		a4_remont_build_html_attributes( $attributes ),
		esc_html( $label )
	);
}

/**
 * Return whether a sub field button has displayable content.
 *
 * @param string $field_name Base field name.
 * @param mixed  $link_value Optional preloaded link value.
 * @return bool
 */
function a4_remont_has_sub_field_action_button( $field_name, $link_value = null ) {
	if ( ! function_exists( 'get_sub_field' ) ) {
		return false;
	}

	$action = sanitize_key( (string) get_sub_field( "{$field_name}_action" ) );

	if ( 'popup' === $action ) {
		$popup_key = sanitize_key( (string) get_sub_field( "{$field_name}_popup_target" ) );
		$label     = trim( (string) get_sub_field( "{$field_name}_popup_label" ) );

		if ( '' === $label ) {
			$link_source = null !== $link_value ? $link_value : get_sub_field( $field_name );

			if ( is_array( $link_source ) && ! empty( $link_source['title'] ) ) {
				$label = trim( (string) $link_source['title'] );
			}
		}

		if ( '' === $label ) {
			$label = a4_remont_get_popup_trigger_fallback_label( $popup_key );
		}

		return '' !== $label;
	}

	$link_source = null !== $link_value ? $link_value : get_sub_field( $field_name );

	return is_array( $link_source ) && ( ! empty( $link_source['url'] ) || ! empty( $link_source['title'] ) );
}

/**
 * Render a sub field button that can be either a link or a popup trigger.
 *
 * @param string               $field_name        Base field name.
 * @param string               $class_name        CSS classes.
 * @param string               $fallback_label    Fallback label.
 * @param array<string,string> $extra_attributes  Extra attributes.
 * @return string
 */
function a4_remont_get_sub_field_action_button_html( $field_name, $class_name = '', $fallback_label = '', $extra_attributes = array() ) {
	if ( ! function_exists( 'get_sub_field' ) ) {
		return '';
	}

	$action = sanitize_key( (string) get_sub_field( "{$field_name}_action" ) );
	$link   = get_sub_field( $field_name );

	if ( 'popup' === $action ) {
		$popup_key = sanitize_key( (string) get_sub_field( "{$field_name}_popup_target" ) );
		$label     = trim( (string) get_sub_field( "{$field_name}_popup_label" ) );

		if ( '' === $label && is_array( $link ) && ! empty( $link['title'] ) ) {
			$label = trim( (string) $link['title'] );
		}

		if ( '' === $label ) {
			$label = $fallback_label;
		}

		return a4_remont_get_popup_trigger_html( $popup_key, $label, $class_name, $extra_attributes );
	}

	return a4_remont_get_acf_link_html( $link, $class_name, $fallback_label, $extra_attributes );
}

/**
 * Check whether an option-level action button has displayable content.
 *
 * @param string              $field_name Base field name.
 * @param string              $context    Field context.
 * @param array<string,mixed> $fallback   Fallback values.
 * @return bool
 */
function a4_remont_has_option_action_button( $field_name, $context = 'option', $fallback = array() ) {
	if ( ! function_exists( 'get_field' ) ) {
		$action = ! empty( $fallback['action'] ) ? sanitize_key( (string) $fallback['action'] ) : '';

		if ( 'popup' === $action ) {
			return ! empty( $fallback['popup_target'] ) || ! empty( $fallback['popup_label'] );
		}

		return ! empty( $fallback['link']['url'] );
	}

	$action = sanitize_key( (string) get_field( "{$field_name}_action", $context ) );

	if ( '' === $action && ! empty( $fallback['action'] ) ) {
		$action = sanitize_key( (string) $fallback['action'] );
	}

	if ( 'popup' === $action ) {
		$popup_key = sanitize_key( (string) get_field( "{$field_name}_popup_target", $context ) );
		$label     = trim( (string) get_field( "{$field_name}_popup_label", $context ) );

		if ( '' === $popup_key && ! empty( $fallback['popup_target'] ) ) {
			$popup_key = sanitize_key( (string) $fallback['popup_target'] );
		}

		if ( '' === $label && ! empty( $fallback['popup_label'] ) ) {
			$label = trim( (string) $fallback['popup_label'] );
		}

		return '' !== $popup_key || '' !== $label;
	}

	$link = get_field( $field_name, $context );

	if ( empty( $link ) && ! empty( $fallback['link'] ) ) {
		$link = $fallback['link'];
	}

	return is_array( $link ) && ( ! empty( $link['url'] ) || ! empty( $link['title'] ) );
}

/**
 * Render an option-level action button.
 *
 * @param string               $field_name       Base field name.
 * @param string               $class_name       CSS classes.
 * @param string               $context          Field context.
 * @param array<string,mixed>  $fallback         Fallback values.
 * @param array<string,string> $extra_attributes Extra attributes.
 * @return string
 */
function a4_remont_get_option_action_button_html( $field_name, $class_name = '', $context = 'option', $fallback = array(), $extra_attributes = array() ) {
	$action = function_exists( 'get_field' ) ? sanitize_key( (string) get_field( "{$field_name}_action", $context ) ) : '';

	if ( '' === $action && ! empty( $fallback['action'] ) ) {
		$action = sanitize_key( (string) $fallback['action'] );
	}

	if ( 'popup' === $action ) {
		$popup_key = function_exists( 'get_field' ) ? sanitize_key( (string) get_field( "{$field_name}_popup_target", $context ) ) : '';
		$label     = function_exists( 'get_field' ) ? trim( (string) get_field( "{$field_name}_popup_label", $context ) ) : '';

		if ( '' === $popup_key && ! empty( $fallback['popup_target'] ) ) {
			$popup_key = sanitize_key( (string) $fallback['popup_target'] );
		}

		if ( '' === $label && ! empty( $fallback['popup_label'] ) ) {
			$label = trim( (string) $fallback['popup_label'] );
		}

		if ( '' === $label ) {
			$label = a4_remont_get_popup_trigger_fallback_label( $popup_key );
		}

		if ( '' === $label ) {
			return '';
		}

		return a4_remont_get_popup_trigger_html( $popup_key, $label, $class_name, $extra_attributes );
	}

	$link = function_exists( 'get_field' ) ? get_field( $field_name, $context ) : array();

	if ( empty( $link ) && ! empty( $fallback['link'] ) ) {
		$link = $fallback['link'];
	}

	return a4_remont_get_acf_link_html( $link, $class_name, '', $extra_attributes );
}

/**
 * Render all configured site popups.
 *
 * @return void
 */
function a4_remont_render_site_popups() {
	$popup_items = a4_remont_get_popup_items();

	if ( empty( $popup_items ) ) {
		return;
	}

	foreach ( $popup_items as $popup ) {
		$popup_key  = ! empty( $popup['key'] ) ? sanitize_key( (string) $popup['key'] ) : a4_remont_get_default_popup_key();
		$title_id   = 'popup-form-title-' . $popup_key;
		$title      = ! empty( $popup['title'] ) ? (string) $popup['title'] : '';
		$lead       = ! empty( $popup['lead'] ) ? (string) $popup['lead'] : '';
		$shortcode  = ! empty( $popup['form_shortcode'] ) ? (string) $popup['form_shortcode'] : '';
		$agreement  = ! empty( $popup['agreement_text'] ) ? (string) $popup['agreement_text'] : a4_remont_get_default_popup_agreement_text();
		$logo_image = ! empty( $popup['logo_image'] ) ? $popup['logo_image'] : null;
		?>
		<div class="popup-form" data-popup-form="<?php echo esc_attr( $popup_key ); ?>" aria-hidden="true">
			<div class="popup-form__dialog" data-popup-panel role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
				<button class="popup-form__close" type="button" data-popup-close aria-label="Закрыть">&times;</button>
				<div class="popup-form__grid">
					<div class="popup-form__info">
						<?php if ( $title ) : ?>
							<h2 class="popup-form__title" id="<?php echo esc_attr( $title_id ); ?>"><?php echo nl2br( esc_html( $title ) ); ?></h2>
						<?php endif; ?>

						<?php if ( $lead ) : ?>
							<p class="popup-form__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
						<?php endif; ?>

						<?php
						if ( $logo_image ) {
							echo a4_remont_get_acf_image_html(
								$logo_image,
								'full',
								array(
									'class'   => 'popup-form__logo',
									'loading' => 'lazy',
								)
							); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							?>
							<img
								class="popup-form__logo"
								src="<?php echo esc_url( get_theme_file_uri( '/images/popup-logo.svg' ) ); ?>"
								alt="<?php echo esc_attr__( 'Логотип A4 Ремонт', 'a4-remont' ); ?>"
								width="319"
								height="170"
								loading="lazy"
							>
							<?php
						}
						?>
					</div>

					<?php if ( '' !== trim( $shortcode ) ) : ?>
						<div class="popup-form__form">
							<?php echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php else : ?>
						<form class="popup-form__form cta-form__form" action="#" method="post">
							<input type="hidden" name="popup_key" value="<?php echo esc_attr( $popup_key ); ?>">
							<input type="hidden" name="popup_source" value="" data-popup-source-input>

							<label class="cta-form__field">
								<span class="visually-hidden">Ваше имя</span>
								<input class="cta-form__input" type="text" name="name" placeholder="<?php echo esc_attr( $popup['name_placeholder'] ); ?>" autocomplete="name">
							</label>

							<label class="cta-form__field">
								<span class="visually-hidden">Телефон</span>
								<input class="cta-form__input" type="tel" name="phone" placeholder="<?php echo esc_attr( $popup['phone_placeholder'] ); ?>" inputmode="tel" autocomplete="tel">
							</label>

							<label class="cta-form__field">
								<span class="visually-hidden">E-mail</span>
								<input class="cta-form__input" type="email" name="email" placeholder="<?php echo esc_attr( $popup['email_placeholder'] ); ?>" autocomplete="email">
							</label>

							<label class="cta-form__field">
								<span class="visually-hidden">Сообщение</span>
								<textarea class="cta-form__textarea" name="message" rows="5" placeholder="<?php echo esc_attr( $popup['message_placeholder'] ); ?>"></textarea>
							</label>

							<label class="cta-form__agree">
								<input class="cta-form__checkbox" type="checkbox" name="agree" checked>
								<span class="cta-form__agree-ui" aria-hidden="true"></span>
								<span class="cta-form__agree-text"><?php echo wp_kses_post( $agreement ); ?></span>
							</label>

							<div class="popup-form__actions">
								<button class="btn btn--grey cta-form__submit" type="submit"><?php echo esc_html( $popup['submit_label'] ); ?></button>
							</div>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
