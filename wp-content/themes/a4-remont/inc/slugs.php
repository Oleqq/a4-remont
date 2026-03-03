<?php
/**
 * Slug normalization helpers.
 *
 * @package a4-remont
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Transliterate Cyrillic symbols to Latin before WordPress sanitizes a slug.
 *
 * @param string $value Raw value that will be converted to a slug.
 * @return string
 */
function a4_remont_transliterate_slug_value( $value ) {
	$map = array(
		"\u{0410}" => 'A',
		"\u{0430}" => 'a',
		"\u{0411}" => 'B',
		"\u{0431}" => 'b',
		"\u{0412}" => 'V',
		"\u{0432}" => 'v',
		"\u{0413}" => 'G',
		"\u{0433}" => 'g',
		"\u{0414}" => 'D',
		"\u{0434}" => 'd',
		"\u{0415}" => 'E',
		"\u{0435}" => 'e',
		"\u{0401}" => 'Yo',
		"\u{0451}" => 'yo',
		"\u{0416}" => 'Zh',
		"\u{0436}" => 'zh',
		"\u{0417}" => 'Z',
		"\u{0437}" => 'z',
		"\u{0418}" => 'I',
		"\u{0438}" => 'i',
		"\u{0419}" => 'Y',
		"\u{0439}" => 'y',
		"\u{041A}" => 'K',
		"\u{043A}" => 'k',
		"\u{041B}" => 'L',
		"\u{043B}" => 'l',
		"\u{041C}" => 'M',
		"\u{043C}" => 'm',
		"\u{041D}" => 'N',
		"\u{043D}" => 'n',
		"\u{041E}" => 'O',
		"\u{043E}" => 'o',
		"\u{041F}" => 'P',
		"\u{043F}" => 'p',
		"\u{0420}" => 'R',
		"\u{0440}" => 'r',
		"\u{0421}" => 'S',
		"\u{0441}" => 's',
		"\u{0422}" => 'T',
		"\u{0442}" => 't',
		"\u{0423}" => 'U',
		"\u{0443}" => 'u',
		"\u{0424}" => 'F',
		"\u{0444}" => 'f',
		"\u{0425}" => 'Kh',
		"\u{0445}" => 'kh',
		"\u{0426}" => 'Ts',
		"\u{0446}" => 'ts',
		"\u{0427}" => 'Ch',
		"\u{0447}" => 'ch',
		"\u{0428}" => 'Sh',
		"\u{0448}" => 'sh',
		"\u{0429}" => 'Shch',
		"\u{0449}" => 'shch',
		"\u{042A}" => '',
		"\u{044A}" => '',
		"\u{042B}" => 'Y',
		"\u{044B}" => 'y',
		"\u{042C}" => '',
		"\u{044C}" => '',
		"\u{042D}" => 'E',
		"\u{044D}" => 'e',
		"\u{042E}" => 'Yu',
		"\u{044E}" => 'yu',
		"\u{042F}" => 'Ya',
		"\u{044F}" => 'ya',
	);

	return strtr( (string) $value, $map );
}

/**
 * Force Latin slug generation for posts, pages, terms and CPT items.
 *
 * @param string $title     Sanitized title candidate.
 * @param string $raw_title Original unsanitized value.
 * @param string $context   Sanitization context.
 * @return string
 */
function a4_remont_use_latin_slugs( $title, $raw_title, $context ) {
	if ( 'save' !== $context ) {
		return $title;
	}

	$source = '' !== (string) $title ? $title : $raw_title;

	return a4_remont_transliterate_slug_value( $source );
}
add_filter( 'sanitize_title', 'a4_remont_use_latin_slugs', 9, 3 );

/**
 * Return fixed slugs for core site pages that the theme relies on.
 *
 * @return array<string, string>
 */
function a4_remont_get_template_slug_map() {
	return array(
		'page-templates/about-us.php'              => 'about-us',
		'page-templates/contacts-page.php'         => 'contacts',
		'page-templates/faq-page.php'              => 'faq',
		'page-templates/payment-delivery-page.php' => 'payment-delivery',
		'page-templates/privacy-policy-page.php'   => 'privacy-policy',
	);
}

/**
 * Keep template-driven service pages on predictable English slugs.
 *
 * @param array $data                Sanitized post data.
 * @param array $postarr             Sanitized original post array.
 * @param array $unsanitized_postarr Unsanitized original post array.
 * @param bool  $update              Whether this is an existing post update.
 * @return array
 */
function a4_remont_force_template_page_slugs( $data, $postarr, $unsanitized_postarr, $update ) {
	if ( empty( $data['post_type'] ) || 'page' !== $data['post_type'] ) {
		return $data;
	}

	if ( ! empty( $postarr['post_status'] ) && 'auto-draft' === $postarr['post_status'] ) {
		return $data;
	}

	$template = '';

	if ( ! empty( $unsanitized_postarr['page_template'] ) ) {
		$template = (string) $unsanitized_postarr['page_template'];
	} elseif ( ! empty( $postarr['page_template'] ) ) {
		$template = (string) $postarr['page_template'];
	} elseif ( $update && ! empty( $postarr['ID'] ) ) {
		$template = (string) get_page_template_slug( (int) $postarr['ID'] );
	}

	if ( ! $template ) {
		return $data;
	}

	$template_slugs = a4_remont_get_template_slug_map();

	if ( isset( $template_slugs[ $template ] ) ) {
		$data['post_name'] = $template_slugs[ $template ];
	}

	return $data;
}
add_filter( 'wp_insert_post_data', 'a4_remont_force_template_page_slugs', 20, 4 );
