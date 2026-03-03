<?php
/**
 * Global site footer helpers and rendering.
 *
 * @package a4-remont
 */

/**
 * Return a display label for a footer social item.
 *
 * @param string $network Social network slug.
 * @return string
 */
function a4_remont_get_footer_social_network_label( $network ) {
	$labels = array(
		'telegram' => 'Telegram',
		'vk'       => 'VK',
		'reviews'  => 'Отзывы',
	);

	return isset( $labels[ $network ] ) ? $labels[ $network ] : 'Соцсеть';
}

/**
 * Return the footer phone icon markup.
 *
 * @return string
 */
function a4_remont_get_footer_phone_icon() {
	return '<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="28" height="28" rx="14" fill="white" /><path d="M20.5359 18.9687C20.4478 19.3636 20.2294 19.7173 19.9159 19.973C19.5734 20.3204 19.1653 20.5962 18.7152 20.7845C18.2652 20.9728 17.7822 21.0698 17.2944 21.0699H17.0665C16.0426 21.0143 15.04 20.7551 14.1175 20.3075H14.1105C13.8966 20.208 13.6757 20.1084 13.4618 19.987C12.3848 19.3832 11.3971 18.6327 10.5268 17.7569C9.39736 16.696 8.47055 15.4384 7.79168 14.0456C7.43089 13.2822 7.19008 12.4678 7.07775 11.631C6.91256 10.755 7.01193 9.84975 7.36318 9.0305C7.58493 8.6609 7.85576 8.32307 8.16828 8.02623C8.3089 7.86721 8.48083 7.73891 8.6733 7.64938C8.86577 7.55984 9.07465 7.51098 9.28687 7.50586C9.51767 7.53137 9.74111 7.60246 9.9442 7.71503C10.1473 7.8276 10.326 7.97938 10.47 8.16158C10.7905 8.51013 11.1818 8.87341 11.503 9.20864L11.9448 9.64345C12.2043 9.87348 12.3649 10.1947 12.3937 10.5411C12.3937 10.8588 12.2765 11.1653 12.0655 11.403C11.9287 11.5693 11.7836 11.7285 11.6306 11.8799L11.4883 12.0293C11.4055 12.1064 11.3424 12.2018 11.3031 12.3077C11.2681 12.412 11.254 12.5188 11.2611 12.6282C11.374 12.9305 11.5514 13.2054 11.78 13.4333C12.137 13.9172 12.4932 14.338 12.8565 14.7939C13.5136 15.5567 14.2934 16.2045 15.1638 16.7105C15.2529 16.7758 15.3553 16.8192 15.464 16.8382C15.558 16.8452 15.6533 16.8312 15.7417 16.7961C15.9907 16.6495 16.2116 16.4616 16.3967 16.24C16.6457 15.935 17.0032 15.7385 17.394 15.6915C17.7701 15.6967 18.1289 15.8505 18.392 16.1193C18.5343 16.24 18.6907 16.4041 18.8408 16.5612L19.054 16.7814L19.2749 16.9953L19.6529 17.3796C19.8783 17.5797 20.0896 17.7936 20.2869 18.0213C20.4847 18.2948 20.5737 18.6335 20.5359 18.9687ZM17.9789 14.0883C17.8376 14.0869 17.7025 14.0301 17.6026 13.9302C17.5027 13.8303 17.4459 13.6952 17.4445 13.5539C17.4452 13.1505 17.3663 12.7509 17.2123 12.3781C17.0582 12.0053 16.8321 11.6665 16.5469 11.3812C16.2616 11.096 15.9228 10.8699 15.55 10.7158C15.1772 10.5618 14.7776 10.4829 14.3742 10.4836C14.2324 10.4836 14.0965 10.4273 13.9963 10.3271C13.8961 10.2269 13.8398 10.0909 13.8398 9.94922C13.8398 9.80749 13.8961 9.67156 13.9963 9.57134C14.0965 9.47112 14.2324 9.41482 14.3742 9.41482C14.9179 9.41399 15.4566 9.52048 15.9591 9.7282C16.4617 9.93592 16.9183 10.2408 17.3028 10.6253C17.6873 11.0098 17.9922 11.4664 18.1999 11.969C18.4076 12.4715 18.5141 13.0102 18.5133 13.5539C18.5135 13.6242 18.4998 13.6937 18.473 13.7587C18.4462 13.8236 18.4068 13.8826 18.3572 13.9322C18.3075 13.9819 18.2485 14.0212 18.1836 14.048C18.1187 14.0748 18.0491 14.0885 17.9789 14.0883Z" fill="#C09B57" /><path d="M20.4519 13.8388C20.3107 13.8374 20.1757 13.7807 20.0759 13.6809C19.976 13.5812 19.9191 13.4463 19.9175 13.3051C19.9186 12.6178 19.7841 11.9369 19.5218 11.3016C19.2596 10.6663 18.8746 10.0889 18.3889 9.60249C17.9032 9.1161 17.3264 8.73025 16.6914 8.46702C16.0565 8.20379 15.3759 8.06835 14.6885 8.06844C14.6168 8.07104 14.5452 8.05916 14.4781 8.03349C14.4111 8.00782 14.3499 7.9689 14.2982 7.91906C14.2465 7.86921 14.2054 7.80946 14.1773 7.74337C14.1492 7.67728 14.1348 7.6062 14.1348 7.5344C14.1348 7.46259 14.1492 7.39152 14.1773 7.32543C14.2054 7.25934 14.2465 7.19959 14.2982 7.14974C14.3499 7.09989 14.4111 7.06097 14.4781 7.0353C14.5452 7.00964 14.6168 6.99775 14.6885 7.00035C15.5161 7.00035 16.3356 7.16348 17.1002 7.48041C17.8647 7.79734 18.5593 8.26185 19.1442 8.8474C19.7291 9.43294 20.1928 10.128 20.5089 10.8929C20.825 11.6578 20.9872 12.4775 20.9863 13.3051C20.9865 13.3753 20.9728 13.4449 20.946 13.5098C20.9192 13.5748 20.8798 13.6337 20.8302 13.6834C20.7805 13.7331 20.7215 13.7724 20.6566 13.7992C20.5917 13.826 20.5221 13.839 20.4519 13.8388Z" fill="#C09B57" /></svg>';
}

/**
 * Return a footer social icon markup by network.
 *
 * @param string $network Social network slug.
 * @return string
 */
function a4_remont_get_footer_social_icon( $network ) {
	switch ( $network ) {
		case 'telegram':
			return '<svg width="28" height="23" viewBox="0 0 28 23" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.9436 0.259703L0.892222 9.58241C-0.0756482 10.0166 -0.403008 10.886 0.658287 11.3578L6.8285 13.3288L21.7473 4.06103C22.5619 3.47922 23.3958 3.63436 22.6782 4.27439L9.86498 15.9359L9.46249 20.871C9.83529 21.633 10.5179 21.6365 10.9533 21.2578L14.4983 17.8861L20.5696 22.456C21.9797 23.2951 22.747 22.7536 23.0504 21.2156L27.0327 2.26167C27.4461 0.368505 26.741 -0.465642 24.9436 0.259703Z" fill="white" /></svg>';
		case 'vk':
			return '<svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_footer_vk)"><path fill-rule="evenodd" clip-rule="evenodd" d="M32.3708 8.21098C32.5999 7.45727 32.3708 6.90234 31.2733 6.90234H27.6498C26.7276 6.90234 26.3025 7.38135 26.0719 7.91005C26.0719 7.91005 24.2291 12.3219 21.6187 15.1876C20.7739 16.0186 20.3901 16.2823 19.9291 16.2823C19.6986 16.2823 19.3521 16.0186 19.3521 15.2635V8.21098C19.3521 7.30543 19.0981 6.90234 18.3306 6.90234H12.6322C12.0566 6.90234 11.7101 7.32199 11.7101 7.72093C11.7101 8.57817 13.016 8.77695 13.1499 11.1899V16.4327C13.1499 17.5826 12.9386 17.7911 12.4776 17.7911C11.249 17.7911 8.26042 13.3586 6.48658 8.2869C6.14286 7.2999 5.79499 6.90234 4.86873 6.90234H1.24238C0.207063 6.90234 0 7.38135 0 7.91005C0 8.85149 1.22857 13.5283 5.72183 19.714C8.71733 23.9381 12.9345 26.2282 16.7762 26.2282C19.0801 26.2282 19.3645 25.7202 19.3645 24.8436V21.6507C19.3645 20.6333 19.5826 20.4304 20.3128 20.4304C20.8512 20.4304 21.7719 20.6955 23.9226 22.7316C26.3798 25.1459 26.7842 26.2282 28.1674 26.2282H31.791C32.8263 26.2282 33.3454 25.7202 33.0472 24.7152C32.7186 23.7158 31.5453 22.265 29.9896 20.5436C29.1447 19.5635 27.8775 18.5075 27.4924 17.9788C26.9554 17.301 27.1086 16.9987 27.4924 16.3955C27.4924 16.3955 31.9097 10.2857 32.3694 8.21098" fill="white" /></g><defs><clipPath id="clip0_footer_vk"><rect width="33.13" height="33.13" fill="white" /></clipPath></defs></svg>';
		case 'reviews':
			return '<svg width="26" height="21" viewBox="0 0 26 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.0498 9.5424H5.37591V4.6326H18.0498C18.7921 4.6326 19.3069 4.7586 19.5644 4.9812C19.8218 5.2038 19.9848 5.6112 19.9848 6.2076V7.9716C19.9848 8.6016 19.8261 9.009 19.5644 9.2316C19.3069 9.45 18.7921 9.5424 18.0498 9.5424ZM18.9208 0H0V21H5.37591V14.1666H15.2825L19.9805 21H26L20.8172 14.1372C22.7264 13.86 23.5845 13.2846 24.2924 12.3438C25.0003 11.3988 25.3564 9.8868 25.3564 7.875V6.3C25.3564 5.103 25.2277 4.158 25.0003 3.4356C24.7729 2.7132 24.3911 2.0832 23.8419 1.5162C23.2627 0.9786 22.6191 0.6048 21.8469 0.3528C21.0746 0.126 20.1092 0 18.9208 0Z" fill="white" /></svg>';
		default:
			return '';
	}
}

/**
 * Build default legal links.
 *
 * @return array<int, array<string, string>>
 */
function a4_remont_get_default_footer_legal_links() {
	$privacy_url = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '';

	if ( '' === $privacy_url ) {
		$privacy_url = a4_remont_get_page_url_by_slug( 'privacy-policy' );
	}

	return array(
		array(
			'url'    => (string) $privacy_url,
			'title'  => 'Политика конфиденциальности',
			'target' => '',
		),
	);
}

/**
 * Flatten header items into footer navigation links.
 *
 * @return array<int, array<string, string>>
 */
function a4_remont_get_default_footer_navigation_links() {
	$links = array();

	foreach ( a4_remont_get_header_menu_items() as $item ) {
		$link = isset( $item['link'] ) && is_array( $item['link'] ) ? $item['link'] : array();

		if ( ! empty( $link['url'] ) && ! empty( $link['title'] ) ) {
			$links[] = a4_remont_normalize_header_link( $link );
		}
	}

	$unique_links = array();

	foreach ( $links as $link ) {
		$key = md5( wp_json_encode( $link ) );
		$unique_links[ $key ] = $link;
	}

	return array_values( $unique_links );
}

/**
 * Split links into footer navigation columns.
 *
 * @param array<int, array<string, string>> $links Navigation links.
 * @param int                               $columns_count Desired column count.
 * @return array<int, array<string, mixed>>
 */
function a4_remont_split_footer_links_into_columns( $links, $columns_count = 2 ) {
	$links = array_values( array_filter( $links ) );

	if ( empty( $links ) ) {
		return array();
	}

	$columns_count = max( 1, (int) $columns_count );
	$per_column    = (int) ceil( count( $links ) / $columns_count );
	$chunks        = array_chunk( $links, $per_column );
	$columns       = array();

	foreach ( $chunks as $chunk ) {
		$columns[] = array(
			'title' => '',
			'links' => $chunk,
		);
	}

	return $columns;
}

/**
 * Normalize footer navigation columns from ACF.
 *
 * @param mixed $raw_columns Raw ACF columns.
 * @return array<int, array<string, mixed>>
 */
function a4_remont_normalize_footer_nav_columns( $raw_columns ) {
	$columns = array();

	foreach ( (array) $raw_columns as $raw_column ) {
		if ( ! is_array( $raw_column ) ) {
			continue;
		}

		$links = array();

		foreach ( (array) ( $raw_column['column_links'] ?? array() ) as $raw_row ) {
			if ( ! is_array( $raw_row ) || empty( $raw_row['column_link'] ) ) {
				continue;
			}

			$link = a4_remont_normalize_header_link( $raw_row['column_link'] );

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
 * Return normalized footer social items.
 *
 * @return array<int, array<string, string>>
 */
function a4_remont_get_footer_social_items() {
	$items = array();

	if ( function_exists( 'get_field' ) ) {
		foreach ( (array) get_field( 'footer_socials', 'option' ) as $raw_item ) {
			if ( ! is_array( $raw_item ) || empty( $raw_item['social_link'] ) ) {
				continue;
			}

			$network = sanitize_key( (string) ( $raw_item['social_network'] ?? '' ) );
			$link    = a4_remont_normalize_header_link( $raw_item['social_link'] ?? array() );

			if ( '' === $link['url'] || '' === $network ) {
				continue;
			}

			$label = trim( (string) ( $raw_item['social_label'] ?? '' ) );

			if ( '' === $label ) {
				$label = '' !== $link['title'] ? $link['title'] : a4_remont_get_footer_social_network_label( $network );
			}

			$items[] = array(
				'network' => $network,
				'label'   => $label,
				'url'     => $link['url'],
				'target'  => $link['target'],
			);
		}
	}

	if ( ! empty( $items ) ) {
		return $items;
	}

	$reviews_url = post_type_exists( 'feedback' ) ? get_post_type_archive_link( 'feedback' ) : '';

	if ( ! $reviews_url ) {
		$reviews_url = home_url( '/reviews/' );
	}

	return array(
		array(
			'network' => 'reviews',
			'label'   => 'Отзывы',
			'url'     => (string) $reviews_url,
			'target'  => '',
		),
	);
}

/**
 * Return footer navigation columns.
 *
 * @return array<int, array<string, mixed>>
 */
function a4_remont_get_footer_navigation_columns() {
	$source = function_exists( 'get_field' ) ? sanitize_key( (string) get_field( 'footer_nav_source', 'option' ) ) : '';

	if ( 'custom' === $source ) {
		$columns = a4_remont_normalize_footer_nav_columns( function_exists( 'get_field' ) ? get_field( 'footer_nav_columns', 'option' ) : array() );

		if ( ! empty( $columns ) ) {
			return $columns;
		}
	}

	return a4_remont_split_footer_links_into_columns( a4_remont_get_default_footer_navigation_links(), 2 );
}

/**
 * Return footer legal links.
 *
 * @return array<int, array<string, string>>
 */
function a4_remont_get_footer_legal_links() {
	$links = array();

	if ( function_exists( 'get_field' ) ) {
		foreach ( (array) get_field( 'footer_legal_links', 'option' ) as $raw_item ) {
			if ( ! is_array( $raw_item ) || empty( $raw_item['legal_link'] ) ) {
				continue;
			}

			$link = a4_remont_normalize_header_link( $raw_item['legal_link'] ?? array() );

			if ( '' === $link['url'] || '' === $link['title'] ) {
				continue;
			}

			$links[] = $link;
		}
	}

	return ! empty( $links ) ? $links : a4_remont_get_default_footer_legal_links();
}

/**
 * Return normalized footer settings.
 *
 * @return array<string, mixed>
 */
function a4_remont_get_site_footer_settings() {
	$header_settings = a4_remont_get_site_header_settings();
	$current_year    = gmdate( 'Y' );
	$defaults        = array(
		'logo_image'      => null,
		'logo_alt'        => get_bloginfo( 'name' ),
		'phone_label'     => (string) $header_settings['phone_label'],
		'email'           => 'a4remont@yandex.ru',
		'address'         => "г. Москва, Каширский\nпроезд, д. 7",
		'company_name'    => 'ООО ______',
		'inn'             => '0000000000',
		'kpp'             => '0000000000000',
		'copyright_text'  => sprintf( '©%1$s - Официальный сайт «%2$s»', $current_year, get_bloginfo( 'name' ) ),
		'developer_text'  => 'Сайт разработан компанией DS-ART',
		'developer_link'  => array(),
		'developer_logo'  => null,
	);

	$settings = array(
		'logo_image'     => function_exists( 'get_field' ) ? get_field( 'footer_logo_image', 'option' ) : null,
		'logo_alt'       => function_exists( 'get_field' ) ? trim( (string) get_field( 'footer_logo_alt', 'option' ) ) : '',
		'phone_label'    => function_exists( 'get_field' ) ? trim( (string) get_field( 'footer_phone_label', 'option' ) ) : '',
		'email'          => function_exists( 'get_field' ) ? sanitize_email( (string) get_field( 'footer_email', 'option' ) ) : '',
		'address'        => function_exists( 'get_field' ) ? trim( (string) get_field( 'footer_address', 'option' ) ) : '',
		'company_name'   => function_exists( 'get_field' ) ? trim( (string) get_field( 'footer_company_name', 'option' ) ) : '',
		'inn'            => function_exists( 'get_field' ) ? trim( (string) get_field( 'footer_inn', 'option' ) ) : '',
		'kpp'            => function_exists( 'get_field' ) ? trim( (string) get_field( 'footer_kpp', 'option' ) ) : '',
		'copyright_text' => function_exists( 'get_field' ) ? trim( (string) get_field( 'footer_copyright_text', 'option' ) ) : '',
		'developer_text' => function_exists( 'get_field' ) ? trim( (string) get_field( 'footer_developer_text', 'option' ) ) : '',
		'developer_link' => function_exists( 'get_field' ) ? get_field( 'footer_developer_link', 'option' ) : array(),
		'developer_logo' => function_exists( 'get_field' ) ? get_field( 'footer_developer_logo', 'option' ) : null,
		'social_items'   => a4_remont_get_footer_social_items(),
		'nav_columns'    => a4_remont_get_footer_navigation_columns(),
		'legal_links'    => a4_remont_get_footer_legal_links(),
	);

	foreach ( $defaults as $key => $default_value ) {
		if ( empty( $settings[ $key ] ) ) {
			$settings[ $key ] = $default_value;
		}
	}

	if ( empty( $settings['logo_image'] ) ) {
		$settings['logo_image'] = $header_settings['logo_image'];
	}

	if ( '' === $settings['logo_alt'] ) {
		$settings['logo_alt'] = $defaults['logo_alt'];
	}

	return $settings;
}

/**
 * Return footer phone href.
 *
 * @return string
 */
function a4_remont_get_site_footer_phone_href() {
	$settings = a4_remont_get_site_footer_settings();
	$digits   = preg_replace( '/[^0-9\+]/', '', (string) $settings['phone_label'] );

	return $digits ? 'tel:' . $digits : '';
}

/**
 * Return footer logo HTML.
 *
 * @return string
 */
function a4_remont_get_site_footer_logo_html() {
	$settings = a4_remont_get_site_footer_settings();
	$logo_alt = ! empty( $settings['logo_alt'] ) ? (string) $settings['logo_alt'] : get_bloginfo( 'name' );
	$image     = a4_remont_get_acf_image_html(
		$settings['logo_image'],
		'full',
		array(
			'class'   => 'footer__logo-image',
			'alt'     => $logo_alt,
			'loading' => 'lazy',
		)
	);

	if ( $image ) {
		return $image;
	}

	if ( has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );

		if ( $logo_id ) {
			return wp_get_attachment_image(
				$logo_id,
				'full',
				false,
				array(
					'class'   => 'footer__logo-image',
					'alt'     => $logo_alt,
					'loading' => 'lazy',
				)
			);
		}
	}

	return sprintf( '<span class="footer__logo-text">%s</span>', esc_html( get_bloginfo( 'name' ) ) );
}

/**
 * Render the global site footer.
 *
 * @return void
 */
function a4_remont_render_site_footer() {
	$settings       = a4_remont_get_site_footer_settings();
	$phone_href     = a4_remont_get_site_footer_phone_href();
	$developer_link = a4_remont_normalize_header_link( $settings['developer_link'] ?? array(), $settings['developer_text'] ?? '' );
	$developer_logo = '';

	if ( ! empty( $settings['developer_logo'] ) ) {
		$developer_logo = a4_remont_get_acf_image_html(
			$settings['developer_logo'],
			'full',
			array(
				'class'   => 'footer__dev-logo',
				'alt'     => '',
				'loading' => 'lazy',
			)
		);
	}

	if ( '' === $developer_logo ) {
		$developer_logo = sprintf(
			'<img class="footer__dev-logo" src="%s" alt="" loading="lazy">',
			esc_url( get_theme_file_uri( '/images/Group 17428.png' ) )
		);
	}
	?>
	<footer class="footer" role="contentinfo">
		<div class="footer__container _container">
			<div class="footer__top">
				<div class="footer__brand">
					<a class="footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
						<?php echo a4_remont_get_site_footer_logo_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>

					<?php if ( $phone_href ) : ?>
						<a class="footer__phone" href="<?php echo esc_url( $phone_href ); ?>">
							<span class="footer__phone-ic" aria-hidden="true"><?php echo a4_remont_get_footer_phone_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span><?php echo esc_html( (string) $settings['phone_label'] ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( ! empty( $settings['social_items'] ) ) : ?>
						<div class="footer__social">
							<?php foreach ( (array) $settings['social_items'] as $social_item ) : ?>
								<?php
								$icon = a4_remont_get_footer_social_icon( (string) $social_item['network'] );

								if ( '' === $icon || empty( $social_item['url'] ) ) {
									continue;
								}
								?>
								<a
									class="footer__social-link"
									href="<?php echo esc_url( (string) $social_item['url'] ); ?>"
									aria-label="<?php echo esc_attr( (string) $social_item['label'] ); ?>"
									<?php echo ! empty( $social_item['target'] ) ? ' target="' . esc_attr( (string) $social_item['target'] ) . '"' : ''; ?>
								>
									<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $settings['nav_columns'] ) ) : ?>
					<nav class="footer__nav" aria-label="Навигация в подвале сайта">
						<?php foreach ( (array) $settings['nav_columns'] as $column ) : ?>
							<div class="footer__nav-col">
								<?php foreach ( (array) $column['links'] as $column_link ) : ?>
									<?php $is_current = ! empty( $column_link['url'] ) && a4_remont_is_current_header_url( (string) $column_link['url'] ); ?>
									<a <?php echo a4_remont_get_header_link_attributes( $column_link, 'footer__nav-link', $is_current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
										<?php echo esc_html( (string) $column_link['title'] ); ?>
									</a>
								<?php endforeach; ?>
							</div>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>

				<?php if ( ! empty( $settings['legal_links'] ) ) : ?>
					<div class="footer__policy">
						<?php foreach ( (array) $settings['legal_links'] as $legal_link ) : ?>
							<?php $is_current = ! empty( $legal_link['url'] ) && a4_remont_is_current_header_url( (string) $legal_link['url'] ); ?>
							<a <?php echo a4_remont_get_header_link_attributes( $legal_link, 'footer__policy-link', $is_current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php echo esc_html( (string) $legal_link['title'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="footer__requisites">
					<div class="footer__req-layout">
						<?php if ( ! empty( $settings['address'] ) ) : ?>
							<div class="footer__req-col footer__req-col--address">
								<p class="footer__req-title">Адрес:</p>
								<p class="footer__req-text footer__req-text--address"><?php echo nl2br( esc_html( (string) $settings['address'] ) ); ?></p>
							</div>
						<?php endif; ?>

						<div class="footer__req-col footer__req-col--details">
							<p class="footer__req-title">Реквизиты:</p>
							<div class="footer__req-grid">
								<div class="footer__req-group">
									<?php if ( ! empty( $settings['company_name'] ) ) : ?>
										<p class="footer__req-text"><?php echo esc_html( (string) $settings['company_name'] ); ?></p>
									<?php endif; ?>
									<?php if ( ! empty( $settings['inn'] ) ) : ?>
										<p class="footer__req-text">ИНН: <?php echo esc_html( (string) $settings['inn'] ); ?></p>
									<?php endif; ?>
								</div>
								<div class="footer__req-group">
									<?php if ( ! empty( $settings['kpp'] ) ) : ?>
										<p class="footer__req-text">КПП: <?php echo esc_html( (string) $settings['kpp'] ); ?></p>
									<?php endif; ?>
									<?php if ( ! empty( $settings['email'] ) ) : ?>
										<p class="footer__req-text">Электронная почта: <a href="<?php echo esc_url( 'mailto:' . sanitize_email( (string) $settings['email'] ) ); ?>"><?php echo esc_html( (string) $settings['email'] ); ?></a></p>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="footer__bottom">
				<?php if ( ! empty( $settings['copyright_text'] ) ) : ?>
					<p class="footer__copy"><?php echo esc_html( (string) $settings['copyright_text'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $settings['developer_text'] ) ) : ?>
					<?php if ( ! empty( $developer_link['url'] ) ) : ?>
						<a <?php echo a4_remont_get_header_link_attributes( $developer_link, 'footer__dev' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<span class="footer__dev-ic" aria-hidden="true"><?php echo $developer_logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span><?php echo esc_html( (string) $settings['developer_text'] ); ?></span>
						</a>
					<?php else : ?>
						<span class="footer__dev">
							<span class="footer__dev-ic" aria-hidden="true"><?php echo $developer_logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span><?php echo esc_html( (string) $settings['developer_text'] ); ?></span>
						</span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</footer>
	<?php
}
