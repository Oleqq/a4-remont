<?php
/**
 * Section rendering helpers.
 *
 * @package a4-remont
 */

/**
 * Build an HTML attribute string.
 *
 * @param array<string, scalar> $attributes Attributes.
 * @return string
 */
function a4_remont_build_html_attributes( $attributes ) {
	$compiled = array();

	foreach ( $attributes as $name => $value ) {
		if ( null === $value || '' === $value || false === $value ) {
			continue;
		}

		$compiled[] = sprintf( '%1$s="%2$s"', esc_attr( $name ), esc_attr( (string) $value ) );
	}

	return implode( ' ', $compiled );
}

/**
 * Render an ACF image field as HTML.
 *
 * @param mixed                $image      ACF image field value.
 * @param string               $size       Image size.
 * @param array<string,string> $attributes Extra attributes.
 * @return string
 */
function a4_remont_get_acf_image_html( $image, $size = 'full', $attributes = array() ) {
	if ( empty( $image ) ) {
		return '';
	}

	if ( is_numeric( $image ) ) {
		return wp_get_attachment_image( (int) $image, $size, false, $attributes );
	}

	if ( is_array( $image ) && ! empty( $image['ID'] ) ) {
		return wp_get_attachment_image( (int) $image['ID'], $size, false, $attributes );
	}

	if ( is_array( $image ) && ! empty( $image['url'] ) ) {
		if ( empty( $attributes['alt'] ) && ! empty( $image['alt'] ) ) {
			$attributes['alt'] = (string) $image['alt'];
		}

		$attributes = array_merge(
			array(
				'src' => (string) $image['url'],
			),
			$attributes
		);

		return sprintf( '<img %s>', a4_remont_build_html_attributes( $attributes ) );
	}

	return '';
}

/**
 * Resolve an ACF image field to a raw URL.
 *
 * @param mixed  $image ACF image field value.
 * @param string $size  Image size.
 * @return string
 */
function a4_remont_get_acf_image_url( $image, $size = 'full' ) {
	if ( empty( $image ) ) {
		return '';
	}

	if ( is_numeric( $image ) ) {
		$image_url = wp_get_attachment_image_url( (int) $image, $size );
		return $image_url ? (string) $image_url : '';
	}

	if ( is_array( $image ) && ! empty( $image['ID'] ) ) {
		$image_url = wp_get_attachment_image_url( (int) $image['ID'], $size );
		return $image_url ? (string) $image_url : '';
	}

	if ( is_array( $image ) && ! empty( $image['sizes'][ $size ] ) ) {
		return (string) $image['sizes'][ $size ];
	}

	if ( is_array( $image ) && ! empty( $image['url'] ) ) {
		return (string) $image['url'];
	}

	return '';
}

/**
 * Render an ACF link field as HTML.
 *
 * @param mixed                $link             ACF link field value.
 * @param string               $class_name       Link CSS class.
 * @param string               $fallback_label   Optional fallback label.
 * @param array<string,string> $extra_attributes Extra attributes.
 * @return string
 */
function a4_remont_get_acf_link_html( $link, $class_name = '', $fallback_label = '', $extra_attributes = array() ) {
	if ( empty( $link ) || ! is_array( $link ) || empty( $link['url'] ) ) {
		return '';
	}

	$attributes = array_merge(
		array(
			'href' => (string) $link['url'],
		),
		$extra_attributes
	);

	if ( $class_name ) {
		$attributes['class'] = $class_name;
	}

	if ( ! empty( $link['target'] ) ) {
		$attributes['target'] = (string) $link['target'];
	}

	$label = ! empty( $link['title'] ) ? (string) $link['title'] : $fallback_label;

	if ( '' === $label ) {
		return '';
	}

	return sprintf(
		'<a %1$s>%2$s</a>',
		a4_remont_build_html_attributes( $attributes ),
		esc_html( $label )
	);
}

/**
 * Normalize an ACF relationship field value to post IDs.
 *
 * @param mixed $items Relationship field value.
 * @return array<int>
 */
function a4_remont_normalize_post_ids( $items ) {
	$post_ids = array();

	foreach ( (array) $items as $item ) {
		if ( is_numeric( $item ) ) {
			$post_ids[] = (int) $item;
			continue;
		}

		if ( is_object( $item ) && isset( $item->ID ) ) {
			$post_ids[] = (int) $item->ID;
			continue;
		}

		if ( is_array( $item ) && isset( $item['ID'] ) ) {
			$post_ids[] = (int) $item['ID'];
		}
	}

	return array_values( array_unique( array_filter( $post_ids ) ) );
}

/**
 * Get a trimmed post excerpt.
 *
 * @param int|\WP_Post $post  Post object or ID.
 * @param int          $words Max words.
 * @return string
 */
function a4_remont_get_post_excerpt( $post, $words = 24 ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	$excerpt = has_excerpt( $post ) ? $post->post_excerpt : $post->post_content;
	$excerpt = wp_strip_all_tags( strip_shortcodes( (string) $excerpt ) );

	return wp_trim_words( $excerpt, $words, '...' );
}

/**
 * Resolve term names for a post.
 *
 * @param int         $post_id             Post ID.
 * @param string      $preferred_taxonomy  Preferred taxonomy slug.
 * @param int         $limit               Max terms.
 * @return array<int, string>
 */
function a4_remont_get_post_term_names( $post_id, $preferred_taxonomy = '', $limit = 2 ) {
	$taxonomies = array();

	if ( $preferred_taxonomy && taxonomy_exists( $preferred_taxonomy ) ) {
		$taxonomies[] = $preferred_taxonomy;
	}

	foreach ( get_object_taxonomies( get_post_type( $post_id ), 'names' ) as $taxonomy ) {
		if ( 'post_format' === $taxonomy || in_array( $taxonomy, $taxonomies, true ) ) {
			continue;
		}

		$taxonomies[] = $taxonomy;
	}

	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			continue;
		}

		return array_slice( wp_list_pluck( $terms, 'name' ), 0, $limit );
	}

	return array();
}

/**
 * Get post preview items for an offer tab section.
 *
 * @param string $prefix Field prefix.
 * @return array<WP_Post>
 */
function a4_remont_get_offer_tab_posts( $prefix ) {
	$source_mode  = (string) get_sub_field( "{$prefix}_source_mode" );
	$manual_items = a4_remont_normalize_post_ids( get_sub_field( "{$prefix}_manual_items" ) );
	$limit        = (int) get_sub_field( "{$prefix}_limit" );
	$term_id      = (int) get_sub_field( "{$prefix}_category" );

	if ( $limit < 1 ) {
		$limit = 4;
	}

	if ( 'manual' === $source_mode ) {
		if ( empty( $manual_items ) ) {
			return array();
		}

		return get_posts(
			array(
				'post_type'      => 'service',
				'post_status'    => 'publish',
				'post__in'       => $manual_items,
				'orderby'        => 'post__in',
				'posts_per_page' => count( $manual_items ),
			)
		);
	}

	if ( $term_id < 1 && taxonomy_exists( 'service_category' ) ) {
		$default_term = get_term_by( 'slug', sanitize_title( $prefix ), 'service_category' );

		if ( $default_term && ! is_wp_error( $default_term ) ) {
			$term_id = (int) $default_term->term_id;
		}
	}

	$query_args = array(
		'post_type'      => 'service',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
	);

	if ( $term_id > 0 ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'service_category',
				'field'    => 'term_id',
				'terms'    => array( $term_id ),
			),
		);
	}

	return get_posts( $query_args );
}

/**
 * Get post preview items for a latest news section.
 *
 * @return array<WP_Post>
 */
function a4_remont_get_news_section_posts() {
	$manual_items = a4_remont_normalize_post_ids( get_sub_field( 'manual_posts' ) );
	$source_mode  = (string) get_sub_field( 'source_mode' );
	$limit        = (int) get_sub_field( 'posts_limit' );
	$term_id      = (int) get_sub_field( 'news_category' );

	if ( $limit < 1 ) {
		$limit = 3;
	}

	if ( 'manual' === $source_mode && $manual_items ) {
		return get_posts(
			array(
				'post_type'      => 'news',
				'post_status'    => 'publish',
				'post__in'       => $manual_items,
				'orderby'        => 'post__in',
				'posts_per_page' => count( $manual_items ),
			)
		);
	}

	$query_args = array(
		'post_type'      => 'news',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
	);

	if ( $term_id > 0 ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'news_category',
				'field'    => 'term_id',
				'terms'    => array( $term_id ),
			),
		);
	}

	return get_posts( $query_args );
}

/**
 * Get news posts for the news archive preview section.
 *
 * @param array<string,mixed> $config Optional section config.
 * @return array<WP_Post>
 */
function a4_remont_get_news_archive_posts( $config = array() ) {
	if ( empty( $config ) && function_exists( 'get_sub_field' ) ) {
		$config = array(
			'source_mode'   => get_sub_field( 'source_mode' ),
			'manual_posts'  => get_sub_field( 'manual_posts' ),
			'posts_limit'   => get_sub_field( 'posts_limit' ),
			'news_category' => get_sub_field( 'news_category' ),
		);
	}

	$source_mode  = ! empty( $config['source_mode'] ) ? (string) $config['source_mode'] : 'latest';
	$manual_items = ! empty( $config['manual_posts'] ) ? a4_remont_normalize_post_ids( $config['manual_posts'] ) : array();
	$limit        = ! empty( $config['posts_limit'] ) ? (int) $config['posts_limit'] : 9;
	$term_id      = ! empty( $config['news_category'] ) ? (int) $config['news_category'] : 0;

	if ( $limit < 1 ) {
		$limit = 9;
	}

	if ( 'manual' === $source_mode && $manual_items ) {
		return get_posts(
			array(
				'post_type'      => 'news',
				'post_status'    => 'publish',
				'post__in'       => $manual_items,
				'orderby'        => 'post__in',
				'posts_per_page' => count( $manual_items ),
			)
		);
	}

	$query_args = array(
		'post_type'      => 'news',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
	);

	if ( $term_id > 0 ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'news_category',
				'field'    => 'term_id',
				'terms'    => array( $term_id ),
			),
		);
	}

	return get_posts( $query_args );
}

/**
 * Get the normalized review type for a feedback post.
 *
 * @param int|\WP_Post $post Post object or ID.
 * @return string
 */
function a4_remont_get_feedback_type( $post ) {
	$post_object = get_post( $post );

	if ( ! $post_object instanceof WP_Post ) {
		return 'text';
	}

	$type = function_exists( 'get_field' ) ? (string) get_field( 'review_type', $post_object->ID ) : '';
	$type = sanitize_key( $type );

	if ( ! in_array( $type, array( 'text', 'photo', 'video' ), true ) ) {
		return 'text';
	}

	return $type;
}

/**
 * Get feedback posts filtered by review type.
 *
 * @param string              $type   Review type.
 * @param array<string,mixed> $config Query config.
 * @return array<WP_Post>
 */
function a4_remont_get_feedback_posts_by_type( $type, $config = array() ) {
	$type         = in_array( $type, array( 'text', 'photo', 'video' ), true ) ? $type : 'text';
	$source_mode  = ! empty( $config['source_mode'] ) ? (string) $config['source_mode'] : 'latest';
	$manual_items = ! empty( $config['manual_items'] ) ? a4_remont_normalize_post_ids( $config['manual_items'] ) : array();
	$limit        = ! empty( $config['items_limit'] ) ? (int) $config['items_limit'] : 4;

	if ( $limit < 1 ) {
		$limit = 4;
	}

	if ( 'manual' === $source_mode && $manual_items ) {
		$manual_posts = get_posts(
			array(
				'post_type'      => 'feedback',
				'post_status'    => 'publish',
				'post__in'       => $manual_items,
				'orderby'        => 'post__in',
				'posts_per_page' => count( $manual_items ),
			)
		);

		return array_values(
			array_filter(
				$manual_posts,
				static function ( $feedback_post ) use ( $type ) {
					return a4_remont_get_feedback_type( $feedback_post ) === $type;
				}
			)
		);
	}

	$query_args = array(
		'post_type'      => 'feedback',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
	);

	if ( 'text' === $type ) {
		$query_args['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'     => 'review_type',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'review_type',
				'value'   => 'text',
				'compare' => '=',
			),
		);
	} else {
		$query_args['meta_query'] = array(
			array(
				'key'     => 'review_type',
				'value'   => $type,
				'compare' => '=',
			),
		);
	}

	return get_posts( $query_args );
}

/**
 * Get the preview image URL for a photo review.
 *
 * @param int|\WP_Post $post Post object or ID.
 * @return string
 */
function a4_remont_get_feedback_photo_preview_url( $post ) {
	$post_object = get_post( $post );

	if ( ! $post_object instanceof WP_Post ) {
		return '';
	}

	$image = function_exists( 'get_field' ) ? get_field( 'photo_image', $post_object->ID ) : null;
	$url   = a4_remont_get_acf_image_url( $image, 'large' );

	if ( $url ) {
		return $url;
	}

	$thumbnail_url = get_the_post_thumbnail_url( $post_object, 'large' );

	return $thumbnail_url ? (string) $thumbnail_url : '';
}

/**
 * Get the full image URL for a photo review.
 *
 * @param int|\WP_Post $post Post object or ID.
 * @return string
 */
function a4_remont_get_feedback_photo_full_url( $post ) {
	$post_object = get_post( $post );

	if ( ! $post_object instanceof WP_Post ) {
		return '';
	}

	$full_image = function_exists( 'get_field' ) ? get_field( 'photo_full_image', $post_object->ID ) : null;
	$url        = a4_remont_get_acf_image_url( $full_image, 'full' );

	if ( $url ) {
		return $url;
	}

	$photo_image = function_exists( 'get_field' ) ? get_field( 'photo_image', $post_object->ID ) : null;
	$url         = a4_remont_get_acf_image_url( $photo_image, 'full' );

	if ( $url ) {
		return $url;
	}

	$thumbnail_url = get_the_post_thumbnail_url( $post_object, 'full' );

	return $thumbnail_url ? (string) $thumbnail_url : '';
}

/**
 * Get the preview image URL for a video review.
 *
 * @param int|\WP_Post $post Post object or ID.
 * @return string
 */
function a4_remont_get_feedback_video_preview_url( $post ) {
	$post_object = get_post( $post );

	if ( ! $post_object instanceof WP_Post ) {
		return '';
	}

	$image = function_exists( 'get_field' ) ? get_field( 'video_preview_image', $post_object->ID ) : null;
	$url   = a4_remont_get_acf_image_url( $image, 'large' );

	if ( $url ) {
		return $url;
	}

	$thumbnail_url = get_the_post_thumbnail_url( $post_object, 'large' );

	return $thumbnail_url ? (string) $thumbnail_url : '';
}

/**
 * Get the destination URL for a video review.
 *
 * @param int|\WP_Post $post Post object or ID.
 * @return string
 */
function a4_remont_get_feedback_video_url( $post ) {
	$post_object = get_post( $post );

	if ( ! $post_object instanceof WP_Post || ! function_exists( 'get_field' ) ) {
		return '';
	}

	$video_url = (string) get_field( 'video_url', $post_object->ID );

	return $video_url ? esc_url_raw( $video_url ) : '';
}

/**
 * Get feedback posts for a showcase section.
 *
 * @return array<WP_Post>
 */
function a4_remont_get_feedback_showcase_posts() {
	return a4_remont_get_feedback_posts_by_type(
		'text',
		array(
			'source_mode'  => get_sub_field( 'source_mode' ),
			'manual_items' => get_sub_field( 'manual_feedback_items' ),
			'items_limit'  => get_sub_field( 'items_limit' ),
		)
	);
}

/**
 * Get a numeric rating for a feedback post.
 *
 * @param int|\WP_Post $post Post object or ID.
 * @return int
 */
function a4_remont_get_feedback_rating( $post ) {
	$post_id = get_post( $post ) ? (int) get_post( $post )->ID : 0;

	if ( $post_id < 1 ) {
		return 5;
	}

	$rating = function_exists( 'get_field' ) ? get_field( 'rating', $post_id ) : 5;

	return max( 1, min( 5, (int) $rating ) );
}

/**
 * Get a display date for a feedback post.
 *
 * @param int|\WP_Post $post Post object or ID.
 * @return string
 */
function a4_remont_get_feedback_display_date( $post ) {
	$post_object = get_post( $post );

	if ( ! $post_object instanceof WP_Post ) {
		return '';
	}

	$date_value = function_exists( 'get_field' ) ? (string) get_field( 'review_date', $post_object->ID ) : '';

	if ( $date_value ) {
		$timestamp = strtotime( $date_value );

		if ( $timestamp ) {
			return wp_date( 'd.m.Y', $timestamp );
		}
	}

	return get_the_date( 'd.m.Y', $post_object );
}

/**
 * Get service posts for a configured archive stream group.
 *
 * @param array<string,mixed> $group Stream group config.
 * @return array<WP_Post>
 */
function a4_remont_get_service_stream_group_posts( $group ) {
	$source_mode  = ! empty( $group['source_mode'] ) ? (string) $group['source_mode'] : 'auto';
	$manual_items = ! empty( $group['manual_items'] ) ? a4_remont_normalize_post_ids( $group['manual_items'] ) : array();
	$limit        = ! empty( $group['limit'] ) ? (int) $group['limit'] : 4;
	$term_id      = ! empty( $group['category'] ) ? (int) $group['category'] : 0;

	if ( $limit < 1 ) {
		$limit = 4;
	}

	if ( 'manual' === $source_mode && $manual_items ) {
		return get_posts(
			array(
				'post_type'      => 'service',
				'post_status'    => 'publish',
				'post__in'       => $manual_items,
				'orderby'        => 'post__in',
				'posts_per_page' => count( $manual_items ),
			)
		);
	}

	$query_args = array(
		'post_type'      => 'service',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
	);

	if ( $term_id > 0 ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'service_category',
				'field'    => 'term_id',
				'terms'    => array( $term_id ),
			),
		);
	}

	return get_posts( $query_args );
}

/**
 * Resolve a service category term for a stream group.
 *
 * @param array<string,mixed> $group Stream group config.
 * @return WP_Term|null
 */
function a4_remont_get_service_stream_group_term( $group ) {
	$term_id = ! empty( $group['category'] ) ? (int) $group['category'] : 0;

	if ( $term_id < 1 || ! taxonomy_exists( 'service_category' ) ) {
		return null;
	}

	$term = get_term( $term_id, 'service_category' );

	return ( $term instanceof WP_Term && ! is_wp_error( $term ) ) ? $term : null;
}

/**
 * Get work posts for the works archive portfolio section.
 *
 * @param array<string,mixed> $config Optional section config.
 * @return array<WP_Post>
 */
function a4_remont_get_work_archive_posts( $config = array() ) {
	if ( empty( $config ) && function_exists( 'get_sub_field' ) ) {
		$config = array(
			'source_mode' => get_sub_field( 'source_mode' ),
			'manual_items' => get_sub_field( 'manual_items' ),
			'items_limit' => get_sub_field( 'items_limit' ),
			'work_category' => get_sub_field( 'work_category' ),
		);
	}

	$source_mode  = ! empty( $config['source_mode'] ) ? (string) $config['source_mode'] : 'latest';
	$manual_items = ! empty( $config['manual_items'] ) ? a4_remont_normalize_post_ids( $config['manual_items'] ) : array();
	$limit        = ! empty( $config['items_limit'] ) ? (int) $config['items_limit'] : 10;
	$term_id      = ! empty( $config['work_category'] ) ? (int) $config['work_category'] : 0;

	if ( $limit < 1 ) {
		$limit = 10;
	}

	if ( 'manual' === $source_mode && $manual_items ) {
		return get_posts(
			array(
				'post_type'      => 'work',
				'post_status'    => 'publish',
				'post__in'       => $manual_items,
				'orderby'        => 'post__in',
				'posts_per_page' => count( $manual_items ),
			)
		);
	}

	$query_args = array(
		'post_type'      => 'work',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
	);

	if ( $term_id > 0 ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'work_category',
				'field'    => 'term_id',
				'terms'    => array( $term_id ),
			),
		);
	}

	return get_posts( $query_args );
}

/**
 * Render rating stars for reviews.
 *
 * @param int $rating Rating value.
 * @return string
 */
function a4_remont_render_rating_stars( $rating ) {
	$rating = max( 1, min( 5, (int) $rating ) );

	return sprintf(
		'<div class="review-card__stars" aria-label="%1$d out of 5">%2$s</div>',
		$rating,
		str_repeat( '<span class="review-card__star" aria-hidden="true">&#9733;</span>', $rating )
	);
}
