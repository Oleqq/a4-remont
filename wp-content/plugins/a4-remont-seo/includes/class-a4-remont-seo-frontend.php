<?php
/**
 * Frontend SEO output.
 *
 * @package A4_Remont_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class A4_Remont_SEO_Frontend {

	/**
	 * Cached resolved context.
	 *
	 * @var array<string,mixed>|null
	 */
	protected $context = null;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp', array( $this, 'prepare_frontend' ) );
		add_filter( 'pre_get_document_title', array( $this, 'filter_document_title' ), 20 );
		add_filter( 'wp_robots', array( $this, 'disable_core_robots' ) );
		add_action( 'wp_head', array( $this, 'render_meta_tags' ), 1 );
	}

	/**
	 * Prepare frontend hooks.
	 *
	 * @return void
	 */
	public function prepare_frontend() {
		remove_action( 'wp_head', 'rel_canonical' );
	}

	/**
	 * Disable default robots output.
	 *
	 * @return array<string,mixed>
	 */
	public function disable_core_robots() {
		return array();
	}

	/**
	 * Override document title.
	 *
	 * @param string $title Default title.
	 * @return string
	 */
	public function filter_document_title( $title ) {
		$context = $this->get_context();

		return ! empty( $context['title'] ) ? $context['title'] : $title;
	}

	/**
	 * Render SEO meta tags.
	 *
	 * @return void
	 */
	public function render_meta_tags() {
		if ( is_admin() || is_feed() || wp_is_json_request() ) {
			return;
		}

		$context  = $this->get_context();

		if ( ! empty( $context['description'] ) ) {
			echo '<meta name="description" content="' . esc_attr( $context['description'] ) . '">' . "\n";
		}

		if ( ! empty( $context['keywords'] ) ) {
			echo '<meta name="keywords" content="' . esc_attr( $context['keywords'] ) . '">' . "\n";
		}

		if ( ! empty( $context['canonical'] ) ) {
			echo '<link rel="canonical" href="' . esc_url( $context['canonical'] ) . '">' . "\n";
		}

		if ( ! empty( $context['author_name'] ) ) {
			echo '<meta name="author" content="' . esc_attr( $context['author_name'] ) . '">' . "\n";
		}

		echo '<meta name="robots" content="' . esc_attr( $context['robots'] ) . '">' . "\n";
		echo '<meta property="og:locale" content="' . esc_attr( $context['locale'] ) . '">' . "\n";
		echo '<meta property="og:type" content="' . esc_attr( $context['og_type'] ) . '">' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( $context['og_title'] ) . '">' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( $context['og_description'] ) . '">' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( A4_Remont_SEO::get_site_name() ) . '">' . "\n";

		if ( ! empty( $context['canonical'] ) ) {
			echo '<meta property="og:url" content="' . esc_url( $context['canonical'] ) . '">' . "\n";
		}

		if ( ! empty( $context['og_image'] ) ) {
			echo '<meta property="og:image" content="' . esc_url( $context['og_image'] ) . '">' . "\n";

			if ( ! empty( $context['og_image_alt'] ) ) {
				echo '<meta property="og:image:alt" content="' . esc_attr( $context['og_image_alt'] ) . '">' . "\n";
			}
		}

		if ( ! empty( $context['published_time'] ) ) {
			echo '<meta property="article:published_time" content="' . esc_attr( $context['published_time'] ) . '">' . "\n";
		}

		if ( ! empty( $context['modified_time'] ) ) {
			echo '<meta property="article:modified_time" content="' . esc_attr( $context['modified_time'] ) . '">' . "\n";
			echo '<meta property="og:updated_time" content="' . esc_attr( $context['modified_time'] ) . '">' . "\n";
		}

		if ( ! empty( $context['author_name'] ) ) {
			echo '<meta property="article:author" content="' . esc_attr( $context['author_name'] ) . '">' . "\n";
		}

		$schema = $this->build_schema_graph( $context );

		if ( ! empty( $schema ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Resolve context data for current request.
	 *
	 * @return array<string,mixed>
	 */
	protected function get_context() {
		if ( null !== $this->context ) {
			return $this->context;
		}

		$settings = A4_Remont_SEO::get_settings();
		$context  = array(
			'title'          => '',
			'description'    => '',
			'keywords'       => '',
			'canonical'      => '',
			'og_title'       => '',
			'og_description' => '',
			'og_image'       => '',
			'og_image_alt'   => '',
			'og_type'        => 'website',
			'robots'         => 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1',
			'locale'         => str_replace( '_', '-', get_locale() ),
			'schema_type'    => 'webpage',
			'published_time' => '',
			'modified_time'  => '',
			'author_name'    => '',
			'object_type'    => '',
		);

		if ( is_attachment() ) {
			$context = $this->resolve_attachment_context( $context );
		} elseif ( is_singular() ) {
			$context = $this->resolve_singular( $context );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$context = $this->resolve_term_archive( $context );
		} elseif ( is_home() ) {
			$context = $this->resolve_posts_page( $context );
		} elseif ( is_front_page() ) {
			$context = $this->resolve_special_context( 'front_page', $context, 'Главная страница' );
		} elseif ( is_post_type_archive() ) {
			$context = $this->resolve_post_type_archive( $context );
		} elseif ( is_author() ) {
			$context = $this->resolve_author_archive( $context );
		} elseif ( is_date() ) {
			$context = $this->resolve_date_archive( $context );
		} elseif ( is_search() ) {
			$context = $this->resolve_special_context( 'search', $context, 'Результаты поиска: ' . get_search_query() );
		} elseif ( is_404() ) {
			$context = $this->resolve_special_context( '404', $context, 'Страница не найдена' );
		}

		if ( empty( $context['description'] ) ) {
			$context['description'] = A4_Remont_SEO::trim_description( $settings['general']['default_description'] );
		}

		if ( empty( $context['keywords'] ) ) {
			$context['keywords'] = (string) $settings['general']['default_keywords'];
		}

		if ( empty( $context['og_title'] ) ) {
			$context['og_title'] = wp_strip_all_tags( $context['title'] );
		}

		if ( empty( $context['og_description'] ) ) {
			$context['og_description'] = $context['description'];
		}

		if ( empty( $context['og_image'] ) ) {
			$context['og_image']     = A4_Remont_SEO::get_image_url( (int) $settings['social']['default_og_image_id'] );
			$context['og_image_alt'] = A4_Remont_SEO::get_image_alt( (int) $settings['social']['default_og_image_id'] );
		}

		if ( empty( $context['canonical'] ) && ! is_404() ) {
			$context['canonical'] = A4_Remont_SEO::get_current_request_url();
		}

		if ( is_paged() ) {
			$context['canonical'] = A4_Remont_SEO::get_current_request_url();

			if ( ! is_singular() && ! empty( $settings['tools']['noindex_paged_archives'] ) ) {
				$context['robots'] = $this->build_robots_string( false, true );
			}
		}

		$this->context           = $context;

		return $this->context;
	}

	/**
	 * Resolve singular object context.
	 *
	 * @param array<string,mixed> $context Current context.
	 * @return array<string,mixed>
	 */
	protected function resolve_singular( $context ) {
		$post_id   = get_queried_object_id();
		$post      = get_post( $post_id );
		$post_meta = A4_Remont_SEO::get_post_meta_bundle( $post_id );
		$post_type = get_post_type( $post_id );
		$title     = $post_meta['title'] ? $post_meta['title'] : get_the_title( $post_id );
		$excerpt   = $post && $post->post_excerpt ? $post->post_excerpt : ( $post ? $post->post_content : '' );

		$context['title']          = A4_Remont_SEO::build_title( $title );
		$context['description']    = $post_meta['description'] ? $post_meta['description'] : A4_Remont_SEO::trim_description( $excerpt );
		$context['keywords']       = $post_meta['keywords'] ? $post_meta['keywords'] : $this->build_post_keywords( $post_id );
		$context['canonical']      = $post_meta['canonical'] ? $post_meta['canonical'] : get_permalink( $post_id );
		$context['og_title']       = $post_meta['og_title'] ? $post_meta['og_title'] : $title;
		$context['og_description'] = $post_meta['og_description'] ? $post_meta['og_description'] : $context['description'];
		$context['robots']         = $this->build_robots_string( ! empty( $post_meta['robots_index'] ), ! empty( $post_meta['robots_follow'] ) );
		$context['schema_type']    = $this->resolve_schema_type( $post_meta['schema_type'], 'singular', $post_type );
		$context['published_time'] = get_the_date( 'c', $post_id );
		$context['modified_time']  = get_the_modified_date( 'c', $post_id );
		$context['author_name']    = $post ? get_the_author_meta( 'display_name', (int) $post->post_author ) : '';
		$context['object_type']    = $post_type;
		$context['og_type']        = 'page' === $post_type ? 'website' : 'article';

		$this->hydrate_post_image_context( $context, $post_id, (int) $post_meta['og_image_id'] );

		return $context;
	}

	/**
	 * Resolve attachment page context.
	 *
	 * @param array<string,mixed> $context Current context.
	 * @return array<string,mixed>
	 */
	protected function resolve_attachment_context( $context ) {
		$attachment_id = get_queried_object_id();
		$attachment    = get_post( $attachment_id );
		$settings      = A4_Remont_SEO::get_settings();
		$data          = $settings['special']['attachment'] ?? A4_Remont_SEO::get_context_defaults();
		$title         = ! empty( $data['title'] ) ? $data['title'] : get_the_title( $attachment_id );
		$description   = $attachment ? ( $attachment->post_excerpt ?: $attachment->post_content ) : '';

		$context['title']          = A4_Remont_SEO::build_title( $title );
		$context['description']    = ! empty( $data['description'] ) ? $data['description'] : A4_Remont_SEO::trim_description( $description );
		$context['keywords']       = ! empty( $data['keywords'] ) ? $data['keywords'] : '';
		$context['canonical']      = ! empty( $data['canonical'] ) ? $data['canonical'] : get_attachment_link( $attachment_id );
		$context['og_title']       = ! empty( $data['og_title'] ) ? $data['og_title'] : $title;
		$context['og_description'] = ! empty( $data['og_description'] ) ? $data['og_description'] : $context['description'];
		$context['robots']         = $this->build_robots_string( ! empty( $data['robots_index'] ), ! empty( $data['robots_follow'] ) );
		$context['schema_type']    = $this->resolve_schema_type( $data['schema_type'], 'special', 'attachment' );
		$context['object_type']    = 'attachment';

		$context['og_image']     = wp_get_attachment_url( $attachment_id );
		$context['og_image_alt'] = A4_Remont_SEO::get_image_alt( $attachment_id );

		if ( 'noindex' === ( $settings['tools']['attachment_handling'] ?? 'redirect_to_parent' ) ) {
			$context['robots'] = $this->build_robots_string( false, false );
		}

		return $context;
	}

	/**
	 * Resolve taxonomy archive context.
	 *
	 * @param array<string,mixed> $context Current context.
	 * @return array<string,mixed>
	 */
	protected function resolve_term_archive( $context ) {
		$term = get_queried_object();

		if ( ! $term instanceof WP_Term ) {
			return $context;
		}

		$term_meta                  = A4_Remont_SEO::get_term_meta_bundle( $term->term_id );
		$title                      = $term_meta['title'] ? $term_meta['title'] : single_term_title( '', false );
		$context['title']           = A4_Remont_SEO::build_title( $title );
		$context['description']     = $term_meta['description'] ? $term_meta['description'] : A4_Remont_SEO::trim_description( term_description( $term, $term->taxonomy ) );
		$context['keywords']        = $term_meta['keywords'] ? $term_meta['keywords'] : A4_Remont_SEO::build_keywords( array( $term->name, $term->taxonomy ) );
		$context['canonical']       = $term_meta['canonical'] ? $term_meta['canonical'] : get_term_link( $term );
		$context['og_title']        = $term_meta['og_title'] ? $term_meta['og_title'] : $title;
		$context['og_description']  = $term_meta['og_description'] ? $term_meta['og_description'] : $context['description'];
		$context['robots']          = $this->build_robots_string( ! empty( $term_meta['robots_index'] ), ! empty( $term_meta['robots_follow'] ) );
		$context['schema_type']     = $this->resolve_schema_type( $term_meta['schema_type'], 'term', $term->taxonomy );
		$context['object_type']     = $term->taxonomy;

		if ( ! empty( $term_meta['og_image_id'] ) ) {
			$context['og_image']     = A4_Remont_SEO::get_image_url( (int) $term_meta['og_image_id'] );
			$context['og_image_alt'] = A4_Remont_SEO::get_image_alt( (int) $term_meta['og_image_id'] );
		}

		return $context;
	}

	/**
	 * Resolve posts page context.
	 *
	 * @param array<string,mixed> $context Current context.
	 * @return array<string,mixed>
	 */
	protected function resolve_posts_page( $context ) {
		$page_for_posts = (int) get_option( 'page_for_posts' );

		if ( $page_for_posts ) {
			$page_meta = A4_Remont_SEO::get_post_meta_bundle( $page_for_posts );
			$title     = $page_meta['title'] ? $page_meta['title'] : get_the_title( $page_for_posts );

			$context['title']          = A4_Remont_SEO::build_title( $title );
			$context['description']    = $page_meta['description'] ? $page_meta['description'] : A4_Remont_SEO::trim_description( get_post_field( 'post_excerpt', $page_for_posts ) );
			$context['keywords']       = $page_meta['keywords'];
			$context['canonical']      = $page_meta['canonical'] ? $page_meta['canonical'] : get_permalink( $page_for_posts );
			$context['og_title']       = $page_meta['og_title'] ? $page_meta['og_title'] : $title;
			$context['og_description'] = $page_meta['og_description'] ? $page_meta['og_description'] : $context['description'];
			$context['robots']         = $this->build_robots_string( ! empty( $page_meta['robots_index'] ), ! empty( $page_meta['robots_follow'] ) );
			$context['schema_type']    = $this->resolve_schema_type( $page_meta['schema_type'], 'special', 'posts_page' );
			$context['object_type']    = 'posts_page';

			$this->hydrate_post_image_context( $context, $page_for_posts, (int) $page_meta['og_image_id'] );

			return $context;
		}

		return $this->resolve_special_context( 'posts_page', $context, 'Лента записей' );
	}

	/**
	 * Resolve post type archive context.
	 *
	 * @param array<string,mixed> $context Current context.
	 * @return array<string,mixed>
	 */
	protected function resolve_post_type_archive( $context ) {
		$post_type = get_query_var( 'post_type' );

		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}

		$post_type = sanitize_key( (string) $post_type );

		if ( ! $post_type ) {
			return $context;
		}

		$settings = A4_Remont_SEO::get_settings();
		$archive  = $settings['archives'][ $post_type ] ?? A4_Remont_SEO::get_context_defaults();
		$object   = get_post_type_object( $post_type );
		$title    = ! empty( $archive['title'] ) ? $archive['title'] : post_type_archive_title( '', false );

		$context['title']          = A4_Remont_SEO::build_title( $title );
		$context['description']    = ! empty( $archive['description'] ) ? $archive['description'] : A4_Remont_SEO::trim_description( $object ? $object->description : '' );
		$context['keywords']       = ! empty( $archive['keywords'] ) ? $archive['keywords'] : A4_Remont_SEO::build_keywords( array( $object ? $object->labels->name : $post_type ) );
		$context['canonical']      = ! empty( $archive['canonical'] ) ? $archive['canonical'] : get_post_type_archive_link( $post_type );
		$context['og_title']       = ! empty( $archive['og_title'] ) ? $archive['og_title'] : $title;
		$context['og_description'] = ! empty( $archive['og_description'] ) ? $archive['og_description'] : $context['description'];
		$context['robots']         = $this->build_robots_string( ! empty( $archive['robots_index'] ), ! empty( $archive['robots_follow'] ) );
		$context['schema_type']    = $this->resolve_schema_type( $archive['schema_type'], 'archive', $post_type );
		$context['object_type']    = $post_type;

		if ( ! empty( $archive['og_image_id'] ) ) {
			$context['og_image']     = A4_Remont_SEO::get_image_url( (int) $archive['og_image_id'] );
			$context['og_image_alt'] = A4_Remont_SEO::get_image_alt( (int) $archive['og_image_id'] );
		}

		return $context;
	}

	/**
	 * Resolve author archive context.
	 *
	 * @param array<string,mixed> $context Current context.
	 * @return array<string,mixed>
	 */
	protected function resolve_author_archive( $context ) {
		$author = get_queried_object();
		$title  = $author instanceof WP_User ? 'Публикации автора: ' . $author->display_name : 'Архив автора';

		$context = $this->resolve_special_context( 'author', $context, $title );

		if ( $author instanceof WP_User ) {
			$context['author_name'] = $author->display_name;

			if ( empty( $context['description'] ) ) {
				$context['description'] = A4_Remont_SEO::trim_description( get_the_author_meta( 'description', $author->ID ) );
			}

			if ( empty( $context['keywords'] ) ) {
				$context['keywords'] = A4_Remont_SEO::build_keywords( array( $author->display_name ) );
			}
		}

		return $context;
	}

	/**
	 * Resolve date archive context.
	 *
	 * @param array<string,mixed> $context Current context.
	 * @return array<string,mixed>
	 */
	protected function resolve_date_archive( $context ) {
		return $this->resolve_special_context( 'date', $context, 'Архив за ' . wp_strip_all_tags( get_the_archive_title() ) );
	}

	/**
	 * Resolve generic special context.
	 *
	 * @param string              $key           Context key.
	 * @param array<string,mixed> $context       Current context.
	 * @param string              $default_title Default title.
	 * @return array<string,mixed>
	 */
	protected function resolve_special_context( $key, $context, $default_title ) {
		$settings = A4_Remont_SEO::get_settings();
		$data     = $settings['special'][ $key ] ?? A4_Remont_SEO::get_context_defaults();
		$title    = ! empty( $data['title'] ) ? $data['title'] : $default_title;

		$context['title']          = A4_Remont_SEO::build_title( $title );
		$context['description']    = ! empty( $data['description'] ) ? $data['description'] : '';
		$context['keywords']       = ! empty( $data['keywords'] ) ? $data['keywords'] : '';
		$context['canonical']      = ! empty( $data['canonical'] ) ? $data['canonical'] : ( '404' === $key ? '' : A4_Remont_SEO::get_current_request_url() );
		$context['og_title']       = ! empty( $data['og_title'] ) ? $data['og_title'] : $title;
		$context['og_description'] = ! empty( $data['og_description'] ) ? $data['og_description'] : $context['description'];
		$context['robots']         = $this->build_robots_string( ! empty( $data['robots_index'] ), ! empty( $data['robots_follow'] ) );
		$context['schema_type']    = $this->resolve_schema_type( $data['schema_type'], 'special', $key );
		$context['object_type']    = $key;

		if ( ! empty( $data['og_image_id'] ) ) {
			$context['og_image']     = A4_Remont_SEO::get_image_url( (int) $data['og_image_id'] );
			$context['og_image_alt'] = A4_Remont_SEO::get_image_alt( (int) $data['og_image_id'] );
		}

		return $context;
	}

	/**
	 * Build keyword string for a post.
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	protected function build_post_keywords( $post_id ) {
		$values     = array();
		$taxonomies = get_object_taxonomies( get_post_type( $post_id ), 'names' );

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( $post_id, $taxonomy );

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				continue;
			}

			foreach ( $terms as $term ) {
				$values[] = $term->name;
			}
		}

		return A4_Remont_SEO::build_keywords( $values );
	}

	/**
	 * Build robots string.
	 *
	 * @param bool $index  Index flag.
	 * @param bool $follow Follow flag.
	 * @return string
	 */
	protected function build_robots_string( $index, $follow ) {
		return implode(
			',',
			array(
				$index ? 'index' : 'noindex',
				$follow ? 'follow' : 'nofollow',
				'max-image-preview:large',
				'max-snippet:-1',
				'max-video-preview:-1',
			)
		);
	}

	/**
	 * Resolve schema type.
	 *
	 * @param string $requested Requested schema type.
	 * @param string $context   Context type.
	 * @param string $object    Object type.
	 * @return string
	 */
	protected function resolve_schema_type( $requested, $context, $object ) {
		$requested = sanitize_key( (string) $requested );

		if ( $requested && 'auto' !== $requested ) {
			return $requested;
		}

		if ( 'special' === $context && '404' === $object ) {
			return 'none';
		}

		if ( 'special' === $context && 'front_page' === $object ) {
			return 'webpage';
		}

		if ( 'special' === $context && 'search' === $object ) {
			return 'collection';
		}

		if ( 'term' === $context || 'archive' === $context || 'special' === $context ) {
			return 'collection';
		}

		if ( 'service' === $object ) {
			return 'service';
		}

		if ( 'work' === $object ) {
			return 'creativework';
		}

		if ( 'feedback' === $object ) {
			return 'review';
		}

		if ( 'news' === $object ) {
			return 'newsarticle';
		}

		if ( 'post' === $object ) {
			return 'article';
		}

		return 'webpage';
	}

	/**
	 * Hydrate OG image from attachment or featured image.
	 *
	 * @param array<string,mixed> $context        Context array.
	 * @param int                 $post_id        Post ID.
	 * @param int                 $og_attachment  Explicit OG attachment ID.
	 * @return void
	 */
	protected function hydrate_post_image_context( &$context, $post_id, $og_attachment = 0 ) {
		$attachment_id = absint( $og_attachment );

		if ( ! $attachment_id ) {
			$attachment_id = get_post_thumbnail_id( $post_id );
		}

		if ( ! $attachment_id ) {
			return;
		}

		$context['og_image']     = A4_Remont_SEO::get_image_url( $attachment_id );
		$context['og_image_alt'] = A4_Remont_SEO::get_image_alt( $attachment_id );
	}

	/**
	 * Build schema graph.
	 *
	 * @param array<string,mixed> $context SEO context.
	 * @return array<string,mixed>
	 */
	protected function build_schema_graph( $context ) {
		$settings  = A4_Remont_SEO::get_settings();
		$graph     = array();
		$site_url  = home_url( '/' );
		$site_name = A4_Remont_SEO::get_site_name();
		$website   = array(
			'@type'           => 'WebSite',
			'@id'             => trailingslashit( $site_url ) . '#website',
			'url'             => $site_url,
			'name'            => $site_name,
			'inLanguage'      => get_locale(),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		);
		$graph[] = $website;

		$organization = null;

		if ( ! empty( $settings['schema']['organization_name'] ) ) {
			$organization = array(
				'@type'       => 'Organization',
				'@id'         => trailingslashit( $site_url ) . '#organization',
				'url'         => $site_url,
				'name'        => $settings['schema']['organization_name'],
				'legalName'   => $settings['schema']['legal_name'] ? $settings['schema']['legal_name'] : $settings['schema']['organization_name'],
				'telephone'   => $settings['schema']['phone'],
				'email'       => $settings['schema']['email'],
				'description' => $settings['general']['default_description'],
			);

			$logo_url = A4_Remont_SEO::get_image_url( (int) $settings['schema']['logo_id'] );

			if ( $logo_url ) {
				$organization['logo'] = $logo_url;
			}

			if ( $settings['schema']['address'] ) {
				$organization['address'] = $settings['schema']['address'];
			}

			$same_as = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $settings['schema']['same_as'] ) ) );

			if ( ! empty( $same_as ) ) {
				$organization['sameAs'] = array_values( $same_as );
			}

			$graph[] = $organization;
		}

		if ( 'none' !== $context['schema_type'] ) {
			$page_schema = $this->build_context_schema( $context, $organization, $site_url );

			if ( ! empty( $page_schema ) ) {
				$graph[] = $page_schema;
			}
		}

		$breadcrumbs = $this->build_breadcrumb_schema();

		if ( $breadcrumbs ) {
			$graph[] = $breadcrumbs;
		}

		return array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		);
	}

	/**
	 * Build page/entity schema.
	 *
	 * @param array<string,mixed>      $context      Context.
	 * @param array<string,mixed>|null $organization Organization schema.
	 * @param string                   $site_url     Site URL.
	 * @return array<string,mixed>
	 */
	protected function build_context_schema( $context, $organization, $site_url ) {
		$type   = $this->map_schema_type( $context['schema_type'] );
		$schema = array(
			'@type'       => $type,
			'@id'         => $context['canonical'] ? trailingslashit( $context['canonical'] ) . '#entity' : trailingslashit( $site_url ) . '#entity',
			'url'         => $context['canonical'],
			'name'        => wp_strip_all_tags( $context['og_title'] ),
			'description' => $context['description'],
			'inLanguage'  => get_locale(),
		);

		if ( ! empty( $context['og_image'] ) ) {
			$schema['image'] = $context['og_image'];
		}

		if ( ! empty( $context['published_time'] ) ) {
			$schema['datePublished'] = $context['published_time'];
		}

		if ( ! empty( $context['modified_time'] ) ) {
			$schema['dateModified'] = $context['modified_time'];
		}

		if ( $organization ) {
			$schema['publisher'] = array(
				'@id' => $organization['@id'],
			);
			$schema['provider']  = array(
				'@id' => $organization['@id'],
			);
		}

		if ( in_array( $type, array( 'Article', 'NewsArticle' ), true ) ) {
			$schema['headline'] = wp_strip_all_tags( $context['og_title'] );

			if ( ! empty( $context['author_name'] ) ) {
				$schema['author'] = array(
					'@type' => 'Person',
					'name'  => $context['author_name'],
				);
			}

			if ( ! empty( $context['canonical'] ) ) {
				$schema['mainEntityOfPage'] = $context['canonical'];
			}
		}

		if ( 'Review' === $type ) {
			$schema['reviewBody'] = $context['description'];

			if ( ! empty( $context['author_name'] ) ) {
				$schema['author'] = array(
					'@type' => 'Person',
					'name'  => $context['author_name'],
				);
			}

			$schema['itemReviewed'] = array(
				'@type' => 'Thing',
				'name'  => A4_Remont_SEO::get_site_name(),
			);
		}

		if ( 'Service' === $type ) {
			$schema['serviceType'] = wp_strip_all_tags( $context['og_title'] );
		}

		return $schema;
	}

	/**
	 * Map internal schema keyword to schema.org type.
	 *
	 * @param string $type Internal type.
	 * @return string
	 */
	protected function map_schema_type( $type ) {
		$map = array(
			'webpage'      => 'WebPage',
			'article'      => 'Article',
			'newsarticle'  => 'NewsArticle',
			'collection'   => 'CollectionPage',
			'service'      => 'Service',
			'creativework' => 'CreativeWork',
			'review'       => 'Review',
		);

		return $map[ $type ] ?? 'WebPage';
	}

	/**
	 * Build breadcrumb schema.
	 *
	 * @return array<string,mixed>|null
	 */
	protected function build_breadcrumb_schema() {
		if ( is_front_page() || is_404() ) {
			return null;
		}

		$items   = array();
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => 'Главная',
			'item'     => home_url( '/' ),
		);
		$position = 2;

		if ( is_singular() ) {
			$post_id          = get_queried_object_id();
			$post_type        = get_post_type( $post_id );
			$post_type_object = get_post_type_object( $post_type );

			if ( $post_type_object && ! empty( $post_type_object->has_archive ) ) {
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => $position++,
					'name'     => $post_type_object->labels->name,
					'item'     => get_post_type_archive_link( $post_type ),
				);
			}

			if ( 'page' === $post_type ) {
				$ancestors = array_reverse( get_post_ancestors( $post_id ) );

				foreach ( $ancestors as $ancestor_id ) {
					$items[] = array(
						'@type'    => 'ListItem',
						'position' => $position++,
						'name'     => get_the_title( $ancestor_id ),
						'item'     => get_permalink( $ancestor_id ),
					);
				}
			}

			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => get_the_title( $post_id ),
				'item'     => get_permalink( $post_id ),
			);
		} elseif ( is_post_type_archive() ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => post_type_archive_title( '', false ),
				'item'     => A4_Remont_SEO::get_current_request_url(),
			);
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();

			if ( $term instanceof WP_Term ) {
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => $position,
					'name'     => $term->name,
					'item'     => get_term_link( $term ),
				);
			}
		} elseif ( is_author() ) {
			$author = get_queried_object();

			if ( $author instanceof WP_User ) {
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => $position,
					'name'     => $author->display_name,
					'item'     => A4_Remont_SEO::get_current_request_url(),
				);
			}
		} elseif ( is_date() || is_search() ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => wp_strip_all_tags( get_the_archive_title() ? get_the_archive_title() : 'Поиск' ),
				'item'     => A4_Remont_SEO::get_current_request_url(),
			);
		}

		return array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		);
	}
}
