<?php
/**
 * Global site header helpers and rendering.
 *
 * @package a4-remont
 */

/**
 * Return the shared site shell options page slug.
 *
 * @return string
 */
function a4_remont_get_site_shell_options_slug() {
	return 'a4-remont-site-shell';
}

/**
 * Return the site header options page slug.
 *
 * @return string
 */
function a4_remont_get_site_header_options_slug() {
	return a4_remont_get_site_shell_options_slug();
}

/**
 * Resolve a page URL by slug with a predictable fallback.
 *
 * @param string $slug Page slug.
 * @return string
 */
function a4_remont_get_page_url_by_slug( $slug ) {
	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post ) {
		return (string) get_permalink( $page );
	}

	return home_url( '/' . trim( (string) $slug, '/' ) . '/' );
}

/**
 * Normalize a link-like array.
 *
 * @param mixed  $link           Raw link.
 * @param string $fallback_label Fallback label.
 * @return array<string, string>
 */
function a4_remont_normalize_header_link( $link, $fallback_label = '' ) {
	$normalized = array(
		'url'    => '',
		'title'  => trim( (string) $fallback_label ),
		'target' => '',
	);

	if ( is_array( $link ) ) {
		if ( ! empty( $link['url'] ) ) {
			$normalized['url'] = (string) $link['url'];
		}

		if ( ! empty( $link['title'] ) ) {
			$normalized['title'] = trim( (string) $link['title'] );
		}

		if ( ! empty( $link['target'] ) ) {
			$normalized['target'] = (string) $link['target'];
		}
	}

	return $normalized;
}

/**
 * Build default service dropdown columns from CPT data.
 *
 * @return array<int, array<string, mixed>>
 */
function a4_remont_get_default_service_dropdown_columns() {
	$columns = array();

	if ( ! taxonomy_exists( 'service_category' ) || ! post_type_exists( 'service' ) ) {
		return $columns;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'service_category',
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return $columns;
	}

	foreach ( $terms as $term ) {
		if ( ! $term instanceof WP_Term ) {
			continue;
		}

		$service_ids = get_posts(
			array(
				'post_type'      => 'service',
				'post_status'    => 'publish',
				'posts_per_page' => 6,
				'fields'         => 'ids',
				'orderby'        => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'tax_query'      => array(
					array(
						'taxonomy' => 'service_category',
						'field'    => 'term_id',
						'terms'    => $term->term_id,
					),
				),
			)
		);

		if ( empty( $service_ids ) ) {
			continue;
		}

		$links = array();

		foreach ( $service_ids as $service_id ) {
			$links[] = array(
				'url'    => (string) get_permalink( $service_id ),
				'title'  => (string) get_the_title( $service_id ),
				'target' => '',
			);
		}

		$columns[] = array(
			'title' => $term->name,
			'links' => $links,
		);
	}

	return $columns;
}

/**
 * Build the default site header menu structure.
 *
 * @return array<int, array<string, mixed>>
 */
function a4_remont_get_default_header_menu_items() {
	$services_archive_url = post_type_exists( 'service' ) ? get_post_type_archive_link( 'service' ) : '';
	$works_archive_url    = post_type_exists( 'work' ) ? get_post_type_archive_link( 'work' ) : '';
	$news_archive_url     = post_type_exists( 'news' ) ? get_post_type_archive_link( 'news' ) : '';
	$service_columns      = a4_remont_get_default_service_dropdown_columns();

	return array(
		array(
			'label'   => 'О компании',
			'link'    => array(
				'url'    => a4_remont_get_page_url_by_slug( 'about-us' ),
				'title'  => 'О компании',
				'target' => '',
			),
			'columns' => array(),
		),
		array(
			'label'   => 'Услуги',
			'link'    => array(
				'url'    => $services_archive_url ? (string) $services_archive_url : home_url( '/services/' ),
				'title'  => 'Услуги',
				'target' => '',
			),
			'columns' => $service_columns,
		),
		array(
			'label'   => 'Портфолио',
			'link'    => array(
				'url'    => $works_archive_url ? (string) $works_archive_url : home_url( '/our-works/' ),
				'title'  => 'Портфолио',
				'target' => '',
			),
			'columns' => array(),
		),
		array(
			'label'   => 'Новости',
			'link'    => array(
				'url'    => $news_archive_url ? (string) $news_archive_url : home_url( '/news/' ),
				'title'  => 'Новости',
				'target' => '',
			),
			'columns' => array(),
		),
		array(
			'label'   => 'FAQ',
			'link'    => array(
				'url'    => a4_remont_get_page_url_by_slug( 'faq' ),
				'title'  => 'FAQ',
				'target' => '',
			),
			'columns' => array(),
		),
		array(
			'label'   => 'Контакты',
			'link'    => array(
				'url'    => a4_remont_get_page_url_by_slug( 'contacts' ),
				'title'  => 'Контакты',
				'target' => '',
			),
			'columns' => array(),
		),
	);
}

/**
 * Normalize manual menu columns from ACF repeater.
 *
 * @param mixed $raw_columns Raw ACF columns.
 * @return array<int, array<string, mixed>>
 */
function a4_remont_normalize_header_menu_columns( $raw_columns ) {
	$columns = array();

	foreach ( (array) $raw_columns as $raw_column ) {
		if ( ! is_array( $raw_column ) ) {
			continue;
		}

		$links = array();

		foreach ( (array) ( $raw_column['column_links'] ?? array() ) as $raw_link_row ) {
			if ( ! is_array( $raw_link_row ) || empty( $raw_link_row['column_link'] ) ) {
				continue;
			}

			$link = a4_remont_normalize_header_link( $raw_link_row['column_link'] );

			if ( '' === $link['url'] || '' === $link['title'] ) {
				continue;
			}

			$links[] = $link;
		}

		if ( empty( $links ) ) {
			continue;
		}

		$columns[] = array(
			'title' => trim( (string) ( $raw_column['column_title'] ?? '' ) ),
			'links' => $links,
		);
	}

	return $columns;
}

/**
 * Return the header menu items.
 *
 * @return array<int, array<string, mixed>>
 */
function a4_remont_get_header_menu_items() {
	if ( ! function_exists( 'get_field' ) ) {
		return a4_remont_get_default_header_menu_items();
	}

	$raw_items = get_field( 'menu_items', 'option' );

	if ( empty( $raw_items ) || ! is_array( $raw_items ) ) {
		return a4_remont_get_default_header_menu_items();
	}

	$items = array();

	foreach ( $raw_items as $raw_item ) {
		if ( ! is_array( $raw_item ) ) {
			continue;
		}

		$link   = a4_remont_normalize_header_link( $raw_item['item_link'] ?? array(), (string) ( $raw_item['item_label'] ?? '' ) );
		$label  = trim( (string) ( $raw_item['item_label'] ?? $link['title'] ) );
		$type   = sanitize_key( (string) ( $raw_item['item_type'] ?? 'link' ) );
		$columns = 'dropdown' === $type ? a4_remont_normalize_header_menu_columns( $raw_item['dropdown_columns'] ?? array() ) : array();

		if ( '' === $label ) {
			continue;
		}

		if ( '' === $link['title'] ) {
			$link['title'] = $label;
		}

		$items[] = array(
			'label'   => $label,
			'link'    => $link,
			'columns' => $columns,
		);
	}

	if ( empty( $items ) ) {
		return a4_remont_get_default_header_menu_items();
	}

	return $items;
}

/**
 * Build a comparable request path.
 *
 * @param string $url Raw URL.
 * @return string
 */
function a4_remont_get_comparable_path( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url || str_starts_with( $url, '#' ) || str_starts_with( $url, 'tel:' ) || str_starts_with( $url, 'mailto:' ) ) {
		return '';
	}

	$target_host = wp_parse_url( $url, PHP_URL_HOST );
	$site_host   = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

	if ( $target_host && $site_host && strtolower( $target_host ) !== strtolower( $site_host ) ) {
		return '';
	}

	$path = (string) wp_parse_url( $url, PHP_URL_PATH );

	if ( '' === $path ) {
		$path = '/';
	}

	$path = '/' . ltrim( $path, '/' );

	return '/' === $path ? '/' : untrailingslashit( $path );
}

/**
 * Check whether a URL matches the current request.
 *
 * @param string $url Target URL.
 * @return bool
 */
function a4_remont_is_current_header_url( $url ) {
	$target_path = a4_remont_get_comparable_path( $url );

	if ( '' === $target_path ) {
		return false;
	}

	$current_path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	$current_path = strtok( $current_path, '?' );
	$current_path = $current_path ? '/' . ltrim( $current_path, '/' ) : '/';
	$current_path = '/' === $current_path ? '/' : untrailingslashit( $current_path );

	return $current_path === $target_path;
}

/**
 * Check whether a header menu item is current.
 *
 * @param array<string, mixed> $item Menu item.
 * @return bool
 */
function a4_remont_is_current_header_item( $item ) {
	if ( ! empty( $item['link']['url'] ) && a4_remont_is_current_header_url( (string) $item['link']['url'] ) ) {
		return true;
	}

	foreach ( (array) ( $item['columns'] ?? array() ) as $column ) {
		foreach ( (array) ( $column['links'] ?? array() ) as $link ) {
			if ( ! empty( $link['url'] ) && a4_remont_is_current_header_url( (string) $link['url'] ) ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Return normalized site header settings.
 *
 * @return array<string, mixed>
 */
function a4_remont_get_site_header_settings() {
	$defaults = array(
		'logo_image'        => null,
		'mobile_logo_image' => null,
		'logo_alt'          => get_bloginfo( 'name' ),
		'phone_label'       => '+7 000 000 00 00',
		'cta_button'        => array(
			'action'      => 'popup',
			'popup_target'=> a4_remont_get_default_popup_key(),
			'popup_label' => 'Связаться с нами',
		),
	);

	if ( ! function_exists( 'get_field' ) ) {
		$defaults['menu_items'] = a4_remont_get_default_header_menu_items();
		return $defaults;
	}

	$logo_image        = get_field( 'logo_image', 'option' );
	$mobile_logo_image = get_field( 'mobile_logo_image', 'option' );
	$logo_alt          = trim( (string) get_field( 'logo_alt', 'option' ) );
	$phone_label       = trim( (string) get_field( 'phone_label', 'option' ) );

	if ( '' === $logo_alt ) {
		$logo_alt = $defaults['logo_alt'];
	}

	if ( '' === $phone_label ) {
		$phone_label = $defaults['phone_label'];
	}

	return array(
		'logo_image'        => $logo_image,
		'mobile_logo_image' => $mobile_logo_image,
		'logo_alt'          => $logo_alt,
		'phone_label'       => $phone_label,
		'cta_button'        => array(
			'action'       => sanitize_key( (string) get_field( 'cta_button_action', 'option' ) ),
			'link'         => get_field( 'cta_button', 'option' ),
			'popup_target' => sanitize_key( (string) get_field( 'cta_button_popup_target', 'option' ) ),
			'popup_label'  => trim( (string) get_field( 'cta_button_popup_label', 'option' ) ),
		),
		'menu_items'        => a4_remont_get_header_menu_items(),
	);
}

/**
 * Render the desktop or mobile site logo.
 *
 * @param string $variant Logo variant.
 * @return string
 */
function a4_remont_get_site_header_logo_html( $variant = 'desktop' ) {
	$settings = a4_remont_get_site_header_settings();
	$image    = 'mobile' === $variant && ! empty( $settings['mobile_logo_image'] ) ? $settings['mobile_logo_image'] : $settings['logo_image'];
	$classes  = 'desktop' === $variant ? 'header__logo-image' : 'header-mob__logo-image';
	$alt      = ! empty( $settings['logo_alt'] ) ? (string) $settings['logo_alt'] : get_bloginfo( 'name' );

	$image_html = a4_remont_get_acf_image_html(
		$image,
		'full',
		array(
			'class'   => $classes,
			'alt'     => $alt,
			'loading' => 'eager',
		)
	);

	if ( '' !== $image_html ) {
		return $image_html;
	}

	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$custom_logo_id = (int) get_theme_mod( 'custom_logo' );
		$custom_logo    = wp_get_attachment_image(
			$custom_logo_id,
			'full',
			false,
			array(
				'class'   => $classes,
				'alt'     => $alt,
				'loading' => 'eager',
			)
		);

		if ( $custom_logo ) {
			return $custom_logo;
		}
	}

	$text_class = 'desktop' === $variant ? 'header__logo-text' : 'header-mob__logo-text';

	return sprintf(
		'<span class="%1$s">%2$s</span>',
		esc_attr( $text_class ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Build the header CTA button HTML.
 *
 * @param string $class_name CSS class.
 * @return string
 */
function a4_remont_get_site_header_cta_button_html( $class_name ) {
	$settings = a4_remont_get_site_header_settings();
	$button   = $settings['cta_button'];

	return a4_remont_get_option_action_button_html(
		'cta_button',
		$class_name,
		'option',
		array(
			'action'       => ! empty( $button['action'] ) ? $button['action'] : 'popup',
			'link'         => ! empty( $button['link'] ) ? $button['link'] : array(),
			'popup_target' => ! empty( $button['popup_target'] ) ? $button['popup_target'] : a4_remont_get_default_popup_key(),
			'popup_label'  => ! empty( $button['popup_label'] ) ? $button['popup_label'] : 'Связаться с нами',
		)
	);
}

/**
 * Return the phone href for the site header.
 *
 * @return string
 */
function a4_remont_get_site_header_phone_href() {
	$settings    = a4_remont_get_site_header_settings();
	$digits_only = preg_replace( '/[^0-9+]/', '', (string) $settings['phone_label'] );

	if ( '' === $digits_only ) {
		return '';
	}

	return 'tel:' . $digits_only;
}

/**
 * Render HTML attributes for header links.
 *
 * @param array<string, string> $link      Link data.
 * @param string                $class_name CSS class.
 * @param bool                  $is_current Whether current.
 * @return string
 */
function a4_remont_get_header_link_attributes( $link, $class_name, $is_current = false ) {
	$attributes = array(
		'href'  => (string) $link['url'],
		'class' => trim( $class_name . ( $is_current ? ' is-current' : '' ) ),
	);

	if ( ! empty( $link['target'] ) ) {
		$attributes['target'] = (string) $link['target'];

		if ( '_blank' === $link['target'] ) {
			$attributes['rel'] = 'noopener noreferrer';
		}
	}

	if ( $is_current ) {
		$attributes['aria-current'] = 'page';
	}

	return a4_remont_build_html_attributes( $attributes );
}

/**
 * Return shared phone icon markup.
 *
 * @return string
 */
function a4_remont_get_header_phone_icon() {
	return '<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="36" rx="18" fill="#F3ECE6"/><path d="M26.4032 24.3885C26.29 24.8962 26.0092 25.351 25.6062 25.6797C25.1658 26.1263 24.6411 26.481 24.0624 26.7231C23.4838 26.9652 22.8629 27.0899 22.2357 27.0899H21.9426C20.6262 27.0185 19.3372 26.6853 18.151 26.1098H18.142C17.867 25.9818 17.583 25.8537 17.308 25.6977C15.9234 24.9214 14.6534 23.9564 13.5344 22.8304C12.0823 21.4665 10.8907 19.8495 10.0179 18.0587C9.554 17.0773 9.24438 16.0301 9.09996 14.9542C8.88758 13.828 9.01533 12.6641 9.46694 11.6108C9.75205 11.1356 10.1003 10.7012 10.5021 10.3196C10.6829 10.1151 10.9039 9.95015 11.1514 9.83503C11.3988 9.71991 11.6674 9.6571 11.9403 9.65051C12.237 9.68331 12.5243 9.77472 12.7854 9.91945C13.0465 10.0642 13.2763 10.2593 13.4614 10.4936C13.8735 10.9417 14.3766 11.4088 14.7896 11.8398L15.3576 12.3988C15.6913 12.6946 15.8977 13.1076 15.9347 13.553C15.9347 13.9615 15.7841 14.3555 15.5127 14.6612C15.337 14.8749 15.1504 15.0796 14.9537 15.2743L14.7706 15.4664C14.6642 15.5655 14.5831 15.6882 14.5326 15.8243C14.4875 15.9584 14.4695 16.0957 14.4785 16.2364C14.6237 16.625 14.8518 16.9785 15.1457 17.2715C15.6047 17.8937 16.0627 18.4347 16.5298 19.0208C17.3747 20.0016 18.3773 20.8345 19.4964 21.4851C19.6109 21.5689 19.7425 21.6248 19.8823 21.6492C20.0031 21.6582 20.1257 21.6402 20.2393 21.5951C20.5594 21.4066 20.8435 21.165 21.0815 20.8801C21.4016 20.488 21.8612 20.2353 22.3637 20.1749C22.8473 20.1816 23.3086 20.3793 23.6468 20.725C23.8298 20.8801 24.0309 21.0911 24.2239 21.293L24.498 21.5762L24.782 21.8512L25.268 22.3453C25.5578 22.6026 25.8295 22.8776 26.0831 23.1703C26.3374 23.522 26.4519 23.9575 26.4032 24.3885Z" fill="#171717"/></svg>';
}

/**
 * Return the shared submenu caret icon.
 *
 * @return string
 */
function a4_remont_get_header_caret_icon() {
	return '<svg width="12" height="6" viewBox="0 0 12 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.71027 5.74688L11.7062 1.46825C11.7993 1.38847 11.8732 1.29375 11.9236 1.1895C11.974 1.08525 12 0.973522 12 0.860687C12 0.632806 11.8943 0.414258 11.7062 0.253122C11.613 0.173335 11.5024 0.110045 11.3807 0.0668649C11.2589 0.0236848 11.1285 0.00146075 10.9967 0.00146075C10.7307 0.00146073 10.4755 0.0919858 10.2873 0.253122L6.00085 3.93274L1.71438 0.253122C1.6215 0.172916 1.51099 0.109255 1.38923 0.0658109C1.26747 0.0223669 1.13687 -4.74842e-07 1.00497 -4.80608e-07C0.873067 -4.86374e-07 0.742469 0.0223669 0.620709 0.0658109C0.498951 0.109255 0.388442 0.172916 0.295555 0.253122C0.201904 0.332672 0.127569 0.427316 0.0768423 0.531594C0.0261154 0.635872 -3.26839e-08 0.747721 -3.76218e-08 0.860686C-4.25597e-08 0.973652 0.0261154 1.0855 0.0768423 1.18978C0.127569 1.29406 0.201904 1.3887 0.295555 1.46825L5.29144 5.74688C5.38432 5.82708 5.49483 5.89075 5.61659 5.93419C5.73835 5.97763 5.86895 6 6.00085 6C6.13276 6 6.26335 5.97763 6.38511 5.93419C6.50687 5.89075 6.61738 5.82708 6.71027 5.74688Z" fill="#C09B57"/></svg>';
}

/**
 * Return the shared sublink arrow icon markup.
 *
 * @return string
 */
function a4_remont_get_header_sublink_icon() {
	return '';
}

/**
 * Return the burger icon markup.
 *
 * @return string
 */
function a4_remont_get_header_burger_icon() {
	return '<svg xmlns="http://www.w3.org/2000/svg" width="38" height="24" viewBox="0 0 38 24" fill="none"><rect width="38" height="4" rx="2" fill="white"/><rect y="10" width="38" height="4" rx="2" fill="white"/><rect y="20" width="38" height="4" rx="2" fill="white"/></svg>';
}

/**
 * Return the mobile close icon markup.
 *
 * @return string
 */
function a4_remont_get_header_close_icon() {
	return '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="20" fill="#EDE4D2"/><path fill-rule="evenodd" clip-rule="evenodd" d="M28.8388 11.3388C29.1201 11.6201 29.2782 12.0017 29.2782 12.3995C29.2782 12.7973 29.1201 13.1789 28.8388 13.4602L22.1213 20.1777L28.8388 26.8952C29.1201 27.1765 29.2782 27.558 29.2782 27.9558C29.2782 28.3537 29.1201 28.7352 28.8388 29.0165C28.5575 29.2978 28.176 29.4558 27.7782 29.4558C27.3803 29.4558 26.9988 29.2978 26.7175 29.0165L20 22.299L13.2825 29.0165C13.0012 29.2978 12.6197 29.4558 12.2218 29.4558C11.824 29.4558 11.4425 29.2978 11.1612 29.0165C10.8799 28.7352 10.7218 28.3537 10.7218 27.9558C10.7218 27.558 10.8799 27.1765 11.1612 26.8952L17.8787 20.1777L11.1612 13.4602C10.8799 13.1789 10.7218 12.7973 10.7218 12.3995C10.7218 12.0017 10.8799 11.6201 11.1612 11.3388C11.4425 11.0575 11.824 10.8995 12.2218 10.8995C12.6197 10.8995 13.0012 11.0575 13.2825 11.3388L20 18.0563L26.7175 11.3388C26.9988 11.0575 27.3803 10.8995 27.7782 10.8995C28.176 10.8995 28.5575 11.0575 28.8388 11.3388Z" fill="#171717"/></svg>';
}

/**
 * Render one desktop menu item.
 *
 * @param array<string, mixed> $item  Menu item.
 * @param int                  $index Index.
 * @return void
 */
function a4_remont_render_desktop_header_menu_item( $item, $index ) {
	$columns       = (array) ( $item['columns'] ?? array() );
	$link          = (array) ( $item['link'] ?? array() );
	$label         = (string) ( $item['label'] ?? '' );
	$is_current    = a4_remont_is_current_header_item( $item );
	$dropdown_id   = 'header-dropdown-' . ( $index + 1 );
	$item_classes  = 'header__item';

	if ( ! empty( $columns ) ) {
		$item_classes .= ' header__item--has-sub';
	}

	if ( $is_current ) {
		$item_classes .= ' _current';
	}
	?>
	<li class="<?php echo esc_attr( $item_classes ); ?>"<?php echo ! empty( $columns ) ? ' data-header-sub' : ''; ?>>
		<?php if ( empty( $columns ) ) : ?>
			<?php if ( ! empty( $link['url'] ) ) : ?>
				<a <?php echo a4_remont_get_header_link_attributes( $link, 'header__link', $is_current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $label ); ?></a>
			<?php else : ?>
				<span class="header__link<?php echo $is_current ? ' is-current' : ''; ?>"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>
		<?php else : ?>
			<div class="header__item-head">
				<?php if ( ! empty( $link['url'] ) ) : ?>
					<a <?php echo a4_remont_get_header_link_attributes( $link, 'header__link', $is_current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $label ); ?></a>
					<button class="header__sub-toggle" type="button" aria-label="<?php echo esc_attr( 'Открыть подменю: ' . $label ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $dropdown_id ); ?>" data-header-sub-btn>
						<span class="header__caret" aria-hidden="true"><?php echo a4_remont_get_header_caret_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
				<?php else : ?>
					<button class="header__link header__link--btn" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $dropdown_id ); ?>" data-header-sub-btn>
						<span><?php echo esc_html( $label ); ?></span>
						<span class="header__caret" aria-hidden="true"><?php echo a4_remont_get_header_caret_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
				<?php endif; ?>
			</div>
			<div class="header__dropdown" id="<?php echo esc_attr( $dropdown_id ); ?>" role="region" aria-label="<?php echo esc_attr( $label ); ?>">
				<div class="header__dropdown-inner">
					<?php foreach ( $columns as $column ) : ?>
						<div class="header__dropdown-col">
							<?php if ( ! empty( $column['title'] ) ) : ?>
								<p class="header__dropdown-title"><?php echo esc_html( (string) $column['title'] ); ?></p>
							<?php endif; ?>
							<ul class="header__dropdown-list">
								<?php foreach ( (array) $column['links'] as $column_link ) : ?>
									<?php $link_is_current = ! empty( $column_link['url'] ) && a4_remont_is_current_header_url( (string) $column_link['url'] ); ?>
									<li>
										<a <?php echo a4_remont_get_header_link_attributes( $column_link, 'header__dropdown-link', $link_is_current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
											<span><?php echo esc_html( (string) $column_link['title'] ); ?></span>
											<span class="header__dropdown-ic" aria-hidden="true"></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</li>
	<?php
}

/**
 * Render one mobile menu item.
 *
 * @param array<string, mixed> $item Menu item.
 * @return void
 */
function a4_remont_render_mobile_header_menu_item( $item ) {
	$columns      = (array) ( $item['columns'] ?? array() );
	$link         = (array) ( $item['link'] ?? array() );
	$label        = (string) ( $item['label'] ?? '' );
	$is_current   = a4_remont_is_current_header_item( $item );
	$item_classes = 'header-mob__item';

	if ( ! empty( $columns ) ) {
		$item_classes .= ' header-mob__item--has-sub';
	}

	if ( $is_current ) {
		$item_classes .= ' _current';
	}
	?>
	<li class="<?php echo esc_attr( $item_classes ); ?>"<?php echo ! empty( $columns ) ? ' data-mob-sub' : ''; ?>>
		<?php if ( empty( $columns ) ) : ?>
			<?php if ( ! empty( $link['url'] ) ) : ?>
				<a <?php echo a4_remont_get_header_link_attributes( $link, 'header-mob__link', $is_current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $label ); ?></a>
			<?php else : ?>
				<span class="header-mob__link<?php echo $is_current ? ' is-current' : ''; ?>"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>
		<?php else : ?>
			<div class="header-mob__item-head">
				<?php if ( ! empty( $link['url'] ) ) : ?>
					<a <?php echo a4_remont_get_header_link_attributes( $link, 'header-mob__link', $is_current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $label ); ?></a>
					<button class="header-mob__sub-toggle" type="button" aria-label="<?php echo esc_attr( 'Открыть подменю: ' . $label ); ?>" aria-expanded="false" data-mob-sub-btn>
						<span class="header-mob__caret" aria-hidden="true"><?php echo a4_remont_get_header_caret_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
				<?php else : ?>
					<button class="header-mob__link header-mob__link--btn" type="button" aria-expanded="false" data-mob-sub-btn>
						<span><?php echo esc_html( $label ); ?></span>
						<span class="header-mob__caret" aria-hidden="true"><?php echo a4_remont_get_header_caret_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
				<?php endif; ?>
			</div>
			<div class="header-mob__dropdown">
				<div class="header-mob__cols">
					<?php foreach ( $columns as $column ) : ?>
						<div class="header-mob__col">
							<?php if ( ! empty( $column['title'] ) ) : ?>
								<p class="header-mob__title"><?php echo esc_html( (string) $column['title'] ); ?></p>
							<?php endif; ?>
							<ul class="header-mob__list">
								<?php foreach ( (array) $column['links'] as $column_link ) : ?>
									<?php $link_is_current = ! empty( $column_link['url'] ) && a4_remont_is_current_header_url( (string) $column_link['url'] ); ?>
									<li>
										<a <?php echo a4_remont_get_header_link_attributes( $column_link, 'header-mob__sublink', $link_is_current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
											<span><?php echo esc_html( (string) $column_link['title'] ); ?></span>
											<span class="header-mob__ic" aria-hidden="true"></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</li>
	<?php
}

/**
 * Render the global site header.
 *
 * @return void
 */
function a4_remont_render_site_header() {
	$settings          = a4_remont_get_site_header_settings();
	$menu_items        = (array) $settings['menu_items'];
	$phone_href        = a4_remont_get_site_header_phone_href();
	$desktop_cta_html  = a4_remont_get_site_header_cta_button_html( 'header__btn' );
	$mobile_cta_html   = a4_remont_get_site_header_cta_button_html( 'header-mob__btn' );
	$desktop_logo_html = a4_remont_get_site_header_logo_html( 'desktop' );
	$mobile_logo_html  = a4_remont_get_site_header_logo_html( 'mobile' );
	?>
	<header class="header" data-header role="banner">
		<div class="header__container _container">
			<div class="header__inner">
				<a class="header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php echo $desktop_logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>

				<nav class="header__nav" aria-label="Основная навигация">
					<ul class="header__menu">
						<?php foreach ( array_values( $menu_items ) as $index => $item ) : ?>
							<?php a4_remont_render_desktop_header_menu_item( $item, (int) $index ); ?>
						<?php endforeach; ?>
					</ul>
				</nav>

				<div class="header__right">
					<?php if ( $phone_href ) : ?>
						<a class="header__phone" href="<?php echo esc_url( $phone_href ); ?>">
							<span class="header__phone-ic" aria-hidden="true"><?php echo a4_remont_get_header_phone_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="header__phone-txt"><?php echo esc_html( (string) $settings['phone_label'] ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( $desktop_cta_html ) : ?>
						<?php echo $desktop_cta_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>

					<button class="header__burger" type="button" aria-label="Открыть меню" aria-expanded="false" data-burger>
						<?php echo a4_remont_get_header_burger_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				</div>
			</div>
		</div>
	</header>

	<div class="header-mob" data-header-mob aria-hidden="true">
		<div class="header-mob__bar">
			<a class="header-mob__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php echo $mobile_logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>

			<button class="header-mob__close" type="button" aria-label="Закрыть меню" data-header-close>
				<?php echo a4_remont_get_header_close_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>

		<nav class="header-mob__nav" aria-label="Мобильная навигация">
			<ul class="header-mob__menu">
				<?php foreach ( $menu_items as $item ) : ?>
					<?php a4_remont_render_mobile_header_menu_item( $item ); ?>
				<?php endforeach; ?>
			</ul>
		</nav>

		<div class="header-mob__bottom">
			<?php if ( $phone_href ) : ?>
				<a class="header-mob__phone" href="<?php echo esc_url( $phone_href ); ?>">
					<span class="header-mob__phone-ic" aria-hidden="true"><?php echo a4_remont_get_header_phone_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span><?php echo esc_html( (string) $settings['phone_label'] ); ?></span>
				</a>
			<?php endif; ?>

			<?php if ( $mobile_cta_html ) : ?>
				<?php echo $mobile_cta_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
