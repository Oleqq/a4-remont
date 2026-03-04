<?php
/**
 * Core plugin helpers.
 *
 * @package A4_Remont_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class A4_Remont_SEO {

	const OPTION_KEY    = 'a4_remont_seo_settings';
	const POST_META_KEY = '_a4_remont_seo_meta';
	const TERM_META_KEY = 'a4_remont_seo_meta';
	const REWRITE_KEY   = 'a4_remont_seo_rewrite_version';

	/**
	 * Singleton instance.
	 *
	 * @var A4_Remont_SEO|null
	 */
	protected static $instance = null;

	/**
	 * Admin module.
	 *
	 * @var A4_Remont_SEO_Admin|null
	 */
	protected $admin = null;

	/**
	 * Frontend module.
	 *
	 * @var A4_Remont_SEO_Frontend|null
	 */
	protected $frontend = null;

	/**
	 * System module.
	 *
	 * @var A4_Remont_SEO_System|null
	 */
	protected $system = null;

	/**
	 * Redirect module.
	 *
	 * @var A4_Remont_SEO_Redirects|null
	 */
	protected $redirects = null;

	/**
	 * Bootstrap plugin.
	 *
	 * @return A4_Remont_SEO
	 */
	public static function boot() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin activation hook.
	 *
	 * @return void
	 */
	public static function activate() {
		if ( ! get_option( self::OPTION_KEY, false ) ) {
			add_option( self::OPTION_KEY, self::get_defaults(), '', false );
		}

		A4_Remont_SEO_System::register_rewrite_rules();
		update_option( self::REWRITE_KEY, A4_REMONT_SEO_VERSION, false );
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation hook.
	 *
	 * @return void
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
		if ( is_admin() ) {
			$this->admin = new A4_Remont_SEO_Admin();
		}

		$this->frontend = new A4_Remont_SEO_Frontend();
		$this->system   = new A4_Remont_SEO_System();
		$this->redirects = new A4_Remont_SEO_Redirects();
	}

	/**
	 * Get plugin default settings.
	 *
	 * @return array<string,mixed>
	 */
	public static function get_defaults() {
		return array(
			'general'      => array(
				'site_name'           => '',
				'title_separator'     => '|',
				'default_description' => '',
				'default_keywords'    => '',
			),
			'social'       => array(
				'default_og_image_id' => 0,
			),
			'verification' => array(
				'google'    => '',
				'yandex'    => '',
				'bing'      => '',
				'pinterest' => '',
			),
			'analytics'    => array(
				'yandex_metrika_id'       => '',
				'yandex_metrika_webvisor' => 1,
				'google_tag_manager_id'   => '',
				'head_code'               => '',
				'body_code'               => '',
				'footer_code'             => '',
			),
			'schema'       => array(
				'organization_name' => '',
				'legal_name'        => '',
				'logo_id'           => 0,
				'phone'             => '',
				'email'             => '',
				'address'           => '',
				'same_as'           => '',
			),
			'tools'        => array(
				'enable_sitemap'         => 1,
				'sitemap_items_per_file' => 500,
				'enable_robots_txt'      => 1,
				'robots_txt_extra'       => '',
				'noindex_paged_archives' => 1,
				'attachment_handling'    => 'redirect_to_parent',
			),
			'redirects'    => array(
				'enabled' => 1,
				'rules'   => array(),
			),
			'archives'     => array(),
			'special'      => array(
				'front_page' => self::get_context_defaults(),
				'posts_page' => self::get_context_defaults(),
				'author'     => array_merge(
					self::get_context_defaults(),
					array(
						'robots_index'  => 0,
						'robots_follow' => 1,
					)
				),
				'date'       => array_merge(
					self::get_context_defaults(),
					array(
						'robots_index'  => 0,
						'robots_follow' => 1,
					)
				),
				'attachment' => array_merge(
					self::get_context_defaults(),
					array(
						'robots_index'  => 0,
						'robots_follow' => 0,
					)
				),
				'search'     => array_merge(
					self::get_context_defaults(),
					array(
						'robots_index'  => 0,
						'robots_follow' => 1,
					)
				),
				'404'        => array_merge(
					self::get_context_defaults(),
					array(
						'robots_index'  => 0,
						'robots_follow' => 0,
					)
				),
			),
		);
	}

	/**
	 * Default field set for singular, term and archive SEO context.
	 *
	 * @return array<string,mixed>
	 */
	public static function get_context_defaults() {
		return array(
			'title'          => '',
			'description'    => '',
			'keywords'       => '',
			'canonical'      => '',
			'og_title'       => '',
			'og_description' => '',
			'og_image_id'    => 0,
			'robots_index'   => 1,
			'robots_follow'  => 1,
			'schema_type'    => 'auto',
		);
	}

	/**
	 * Deep merge user settings with defaults.
	 *
	 * @param array<string,mixed> $value    Current value.
	 * @param array<string,mixed> $defaults Defaults.
	 * @return array<string,mixed>
	 */
	public static function deep_parse_args( $value, $defaults ) {
		$result = $defaults;

		foreach ( $value as $key => $item ) {
			if ( is_array( $item ) && isset( $defaults[ $key ] ) && is_array( $defaults[ $key ] ) ) {
				$result[ $key ] = self::deep_parse_args( $item, $defaults[ $key ] );
			} else {
				$result[ $key ] = $item;
			}
		}

		return $result;
	}

	/**
	 * Get normalized plugin settings.
	 *
	 * @return array<string,mixed>
	 */
	public static function get_settings() {
		$value = get_option( self::OPTION_KEY, array() );

		if ( ! is_array( $value ) ) {
			$value = array();
		}

		return self::deep_parse_args( $value, self::get_defaults() );
	}

	/**
	 * Sanitize settings payload from admin page.
	 *
	 * @param array<string,mixed> $input Raw input.
	 * @return array<string,mixed>
	 */
	public static function sanitize_settings( $input ) {
		$defaults  = self::get_defaults();
		$sanitized = $defaults;

		if ( isset( $input['general'] ) && is_array( $input['general'] ) ) {
			$general                                      = $input['general'];
			$sanitized['general']['site_name']            = sanitize_text_field( $general['site_name'] ?? '' );
			$sanitized['general']['title_separator']      = sanitize_text_field( $general['title_separator'] ?? '|' );
			$sanitized['general']['default_description']  = sanitize_textarea_field( $general['default_description'] ?? '' );
			$sanitized['general']['default_keywords']     = sanitize_text_field( $general['default_keywords'] ?? '' );
		}

		if ( isset( $input['social'] ) && is_array( $input['social'] ) ) {
			$social                                       = $input['social'];
			$sanitized['social']['default_og_image_id']   = absint( $social['default_og_image_id'] ?? 0 );
		}

		if ( isset( $input['verification'] ) && is_array( $input['verification'] ) ) {
			$verification                           = $input['verification'];
			$sanitized['verification']['google']    = sanitize_text_field( $verification['google'] ?? '' );
			$sanitized['verification']['yandex']    = sanitize_text_field( $verification['yandex'] ?? '' );
			$sanitized['verification']['bing']      = sanitize_text_field( $verification['bing'] ?? '' );
			$sanitized['verification']['pinterest'] = sanitize_text_field( $verification['pinterest'] ?? '' );
		}

		if ( isset( $input['analytics'] ) && is_array( $input['analytics'] ) ) {
			$analytics                                  = $input['analytics'];
			$sanitized['analytics']['yandex_metrika_id']       = preg_replace( '/[^0-9]/', '', (string) ( $analytics['yandex_metrika_id'] ?? '' ) );
			$sanitized['analytics']['yandex_metrika_webvisor'] = ! empty( $analytics['yandex_metrika_webvisor'] ) ? 1 : 0;
			$sanitized['analytics']['google_tag_manager_id']   = preg_replace( '/[^A-Z0-9\-]/i', '', (string) ( $analytics['google_tag_manager_id'] ?? '' ) );
			$sanitized['analytics']['head_code']               = self::sanitize_code_snippet( $analytics['head_code'] ?? '' );
			$sanitized['analytics']['body_code']               = self::sanitize_code_snippet( $analytics['body_code'] ?? '' );
			$sanitized['analytics']['footer_code']             = self::sanitize_code_snippet( $analytics['footer_code'] ?? '' );
		}

		if ( isset( $input['schema'] ) && is_array( $input['schema'] ) ) {
			$schema                                  = $input['schema'];
			$sanitized['schema']['organization_name'] = sanitize_text_field( $schema['organization_name'] ?? '' );
			$sanitized['schema']['legal_name']        = sanitize_text_field( $schema['legal_name'] ?? '' );
			$sanitized['schema']['logo_id']           = absint( $schema['logo_id'] ?? 0 );
			$sanitized['schema']['phone']             = sanitize_text_field( $schema['phone'] ?? '' );
			$sanitized['schema']['email']             = sanitize_email( $schema['email'] ?? '' );
			$sanitized['schema']['address']           = sanitize_textarea_field( $schema['address'] ?? '' );
			$sanitized['schema']['same_as']           = sanitize_textarea_field( $schema['same_as'] ?? '' );
		}

		if ( isset( $input['tools'] ) && is_array( $input['tools'] ) ) {
			$tools                                        = $input['tools'];
			$sanitized['tools']['enable_sitemap']         = ! empty( $tools['enable_sitemap'] ) ? 1 : 0;
			$sanitized['tools']['sitemap_items_per_file'] = max( 50, min( 2000, absint( $tools['sitemap_items_per_file'] ?? 500 ) ) );
			$sanitized['tools']['enable_robots_txt']      = ! empty( $tools['enable_robots_txt'] ) ? 1 : 0;
			$sanitized['tools']['robots_txt_extra']       = sanitize_textarea_field( $tools['robots_txt_extra'] ?? '' );
			$sanitized['tools']['noindex_paged_archives'] = ! empty( $tools['noindex_paged_archives'] ) ? 1 : 0;
			$sanitized['tools']['attachment_handling']    = sanitize_key( $tools['attachment_handling'] ?? 'redirect_to_parent' );

			if ( ! array_key_exists( $sanitized['tools']['attachment_handling'], self::get_attachment_handling_options() ) ) {
				$sanitized['tools']['attachment_handling'] = 'redirect_to_parent';
			}
		}

		if ( isset( $input['redirects'] ) && is_array( $input['redirects'] ) ) {
			$redirects = $input['redirects'];

			$sanitized['redirects']['enabled'] = ! empty( $redirects['enabled'] ) ? 1 : 0;
			$sanitized['redirects']['rules']   = array();

			if ( isset( $redirects['rules'] ) && is_array( $redirects['rules'] ) ) {
				foreach ( $redirects['rules'] as $rule ) {
					if ( ! is_array( $rule ) ) {
						continue;
					}

					$prepared_rule = self::sanitize_redirect_rule( $rule );

					if ( ! empty( $prepared_rule ) ) {
						$sanitized['redirects']['rules'][] = $prepared_rule;
					}
				}
			}
		}

		if ( isset( $input['archives'] ) && is_array( $input['archives'] ) ) {
			foreach ( $input['archives'] as $archive_key => $archive_settings ) {
				if ( is_array( $archive_settings ) ) {
					$sanitized['archives'][ sanitize_key( $archive_key ) ] = self::sanitize_context_settings( $archive_settings );
				}
			}
		}

		if ( isset( $input['special'] ) && is_array( $input['special'] ) ) {
			foreach ( $input['special'] as $special_key => $special_settings ) {
				if ( is_array( $special_settings ) ) {
					$sanitized['special'][ sanitize_key( $special_key ) ] = self::sanitize_context_settings( $special_settings );
				}
			}
		}

		if ( isset( $input['pages'] ) && is_array( $input['pages'] ) ) {
			foreach ( $input['pages'] as $page_id => $page_settings ) {
				$page_id = absint( $page_id );

				if ( ! $page_id || ! is_array( $page_settings ) || 'page' !== get_post_type( $page_id ) ) {
					continue;
				}

				self::save_post_meta_bundle( $page_id, $page_settings );
			}
		}

		return $sanitized;
	}

	/**
	 * Sanitize SEO field bundle.
	 *
	 * @param array<string,mixed> $input Raw bundle.
	 * @return array<string,mixed>
	 */
	public static function sanitize_context_settings( $input ) {
		$defaults = self::get_context_defaults();

		return array(
			'title'          => sanitize_text_field( $input['title'] ?? $defaults['title'] ),
			'description'    => sanitize_textarea_field( $input['description'] ?? $defaults['description'] ),
			'keywords'       => sanitize_text_field( $input['keywords'] ?? $defaults['keywords'] ),
			'canonical'      => esc_url_raw( $input['canonical'] ?? $defaults['canonical'] ),
			'og_title'       => sanitize_text_field( $input['og_title'] ?? $defaults['og_title'] ),
			'og_description' => sanitize_textarea_field( $input['og_description'] ?? $defaults['og_description'] ),
			'og_image_id'    => absint( $input['og_image_id'] ?? $defaults['og_image_id'] ),
			'robots_index'   => ! empty( $input['robots_index'] ) ? 1 : 0,
			'robots_follow'  => ! empty( $input['robots_follow'] ) ? 1 : 0,
			'schema_type'    => sanitize_key( $input['schema_type'] ?? $defaults['schema_type'] ),
		);
	}

	/**
	 * Sanitize redirect rule.
	 *
	 * @param array<string,mixed> $rule Raw rule.
	 * @return array<string,mixed>
	 */
	public static function sanitize_redirect_rule( $rule ) {
		$source     = self::normalize_redirect_source( $rule['source'] ?? '' );
		$match_type = sanitize_key( $rule['match_type'] ?? 'exact' );
		$target     = self::normalize_redirect_target( $rule['target'] ?? '' );
		$code       = absint( $rule['code'] ?? 301 );
		$note       = sanitize_text_field( $rule['note'] ?? '' );
		$enabled    = ! empty( $rule['enabled'] ) ? 1 : 0;

		if ( '' === $source || '' === $target ) {
			return array();
		}

		if ( ! array_key_exists( $match_type, self::get_redirect_match_options() ) ) {
			$match_type = 'exact';
		}

		if ( ! array_key_exists( $code, self::get_redirect_code_options() ) ) {
			$code = 301;
		}

		return array(
			'source'     => $source,
			'match_type' => $match_type,
			'target'     => $target,
			'code'       => $code,
			'note'       => $note,
			'enabled'    => $enabled,
		);
	}

	/**
	 * Sanitize analytics code snippet.
	 *
	 * @param string $code Raw code.
	 * @return string
	 */
	public static function sanitize_code_snippet( $code ) {
		$code = trim( (string) wp_unslash( $code ) );
		$code = str_replace( array( '<?php', '<?', '?>' ), '', $code );

		return $code;
	}

	/**
	 * Save post meta bundle.
	 *
	 * @param int                 $post_id Post ID.
	 * @param array<string,mixed> $input   Raw input.
	 * @return void
	 */
	public static function save_post_meta_bundle( $post_id, $input ) {
		update_post_meta( $post_id, self::POST_META_KEY, self::sanitize_context_settings( $input ) );
	}

	/**
	 * Read post meta bundle.
	 *
	 * @param int $post_id Post ID.
	 * @return array<string,mixed>
	 */
	public static function get_post_meta_bundle( $post_id ) {
		$value = get_post_meta( $post_id, self::POST_META_KEY, true );

		if ( ! is_array( $value ) ) {
			$value = array();
		}

		return self::deep_parse_args( $value, self::get_context_defaults() );
	}

	/**
	 * Save term meta bundle.
	 *
	 * @param int                 $term_id Term ID.
	 * @param array<string,mixed> $input   Raw input.
	 * @return void
	 */
	public static function save_term_meta_bundle( $term_id, $input ) {
		update_term_meta( $term_id, self::TERM_META_KEY, self::sanitize_context_settings( $input ) );
	}

	/**
	 * Read term meta bundle.
	 *
	 * @param int $term_id Term ID.
	 * @return array<string,mixed>
	 */
	public static function get_term_meta_bundle( $term_id ) {
		$value = get_term_meta( $term_id, self::TERM_META_KEY, true );

		if ( ! is_array( $value ) ) {
			$value = array();
		}

		return self::deep_parse_args( $value, self::get_context_defaults() );
	}

	/**
	 * Get supported schema type options.
	 *
	 * @return array<string,string>
	 */
	public static function get_schema_type_options() {
		return array(
			'auto'         => 'Автоматически',
			'webpage'      => 'WebPage',
			'article'      => 'Article',
			'newsarticle'  => 'NewsArticle',
			'collection'   => 'CollectionPage',
			'service'      => 'Service',
			'creativework' => 'CreativeWork',
			'review'       => 'Review',
			'none'         => 'Не выводить schema для этого объекта',
		);
	}

	/**
	 * Get public editable post types for meta boxes.
	 *
	 * @return array<string,WP_Post_Type>
	 */
	public static function get_supported_post_types() {
		$post_types = get_post_types(
			array(
				'public'  => true,
				'show_ui' => true,
			),
			'objects'
		);

		unset( $post_types['attachment'] );

		return $post_types;
	}

	/**
	 * Get public post types that should be in sitemaps.
	 *
	 * @return array<string,WP_Post_Type>
	 */
	public static function get_sitemap_post_types() {
		return self::get_supported_post_types();
	}

	/**
	 * Get post types that have archives.
	 *
	 * @return array<string,WP_Post_Type>
	 */
	public static function get_archive_post_types() {
		return array_filter(
			self::get_supported_post_types(),
			static function ( $post_type ) {
				return ! empty( $post_type->has_archive );
			}
		);
	}

	/**
	 * Get public editable taxonomies.
	 *
	 * @return array<string,WP_Taxonomy>
	 */
	public static function get_supported_taxonomies() {
		$taxonomies = get_taxonomies(
			array(
				'public'  => true,
				'show_ui' => true,
			),
			'objects'
		);

		unset( $taxonomies['post_format'] );

		return $taxonomies;
	}

	/**
	 * Get public taxonomies that should be in sitemaps.
	 *
	 * @return array<string,WP_Taxonomy>
	 */
	public static function get_sitemap_taxonomies() {
		return self::get_supported_taxonomies();
	}

	/**
	 * Return archive/system contexts for plugin settings.
	 *
	 * @return array<string,array<string,string>>
	 */
	public static function get_archive_contexts() {
		$contexts = array(
			'front_page' => array(
				'label'       => 'Главная без статической страницы',
				'description' => 'Используется, если сайт выводит последние записи на главной, а не отдельную статическую страницу.',
			),
			'posts_page' => array(
				'label'       => 'Лента записей',
				'description' => 'Работает как fallback для страницы записей и обычного блога.',
			),
			'author'     => array(
				'label'       => 'Архив автора',
				'description' => 'По умолчанию рекомендуем noindex, follow, если авторские архивы не являются отдельными SEO-страницами.',
			),
			'date'       => array(
				'label'       => 'Архив по дате',
				'description' => 'По умолчанию рекомендуем noindex, follow, если архивы дат не продвигаются отдельно.',
			),
			'attachment' => array(
				'label'       => 'Страница вложения',
				'description' => 'Используется только если вложения не перенаправляются и остаются отдельными страницами.',
			),
			'search'     => array(
				'label'       => 'Результаты поиска',
				'description' => 'По умолчанию рекомендуем noindex, follow.',
			),
			'404'        => array(
				'label'       => 'Страница 404',
				'description' => 'По умолчанию рекомендуем noindex, nofollow.',
			),
		);

		foreach ( self::get_archive_post_types() as $post_type => $object ) {
			$contexts[ $post_type ] = array(
				'label'       => sprintf( 'Архив: %s', $object->labels->name ),
				'description' => sprintf( 'SEO-настройки для архивной страницы post type "%s".', $post_type ),
			);
		}

		if ( (int) get_option( 'page_on_front' ) ) {
			unset( $contexts['front_page'] );
		}

		if ( (int) get_option( 'page_for_posts' ) ) {
			unset( $contexts['posts_page'] );
		}

		return $contexts;
	}

	/**
	 * Return published site pages for central SEO settings.
	 *
	 * @return array<int,array<string,string>>
	 */
	public static function get_page_contexts() {
		$page_contexts = array();
		$front_page_id = (int) get_option( 'page_on_front' );
		$posts_page_id = (int) get_option( 'page_for_posts' );
		$pages         = get_pages(
			array(
				'sort_column' => 'menu_order,post_title',
				'sort_order'  => 'ASC',
			)
		);

		foreach ( $pages as $page ) {
			if ( ! $page instanceof WP_Post ) {
				continue;
			}

			$page_id    = (int) $page->ID;
			$page_title = get_the_title( $page_id );
			$flags      = array();

			if ( $front_page_id === $page_id ) {
				$flags[] = 'Главная';
			}

			if ( $posts_page_id === $page_id ) {
				$flags[] = 'Лента записей';
			}

			if ( ! empty( $flags ) ) {
				$page_title .= ' (' . implode( ', ', $flags ) . ')';
			}

			$page_contexts[ $page_id ] = array(
				'label'       => $page_title,
				'description' => sprintf( 'Точная SEO-настройка для страницы "%s". Эти значения будут записаны прямо в SEO-мета этой страницы.', get_the_title( $page_id ) ),
				'edit_link'   => (string) get_edit_post_link( $page_id, '' ),
				'permalink'   => (string) get_permalink( $page_id ),
			);
		}

		return $page_contexts;
	}

	/**
	 * Attachment handling options.
	 *
	 * @return array<string,string>
	 */
	public static function get_attachment_handling_options() {
		return array(
			'redirect_to_parent' => 'Перенаправлять на родительскую запись',
			'redirect_to_file'   => 'Перенаправлять на файл',
			'noindex'            => 'Оставлять страницу вложения, но закрывать от индексации',
			'keep'               => 'Ничего не менять',
		);
	}

	/**
	 * Get default redirect rule payload.
	 *
	 * @return array<string,mixed>
	 */
	public static function get_redirect_rule_defaults() {
		return array(
			'source'     => '',
			'match_type' => 'exact',
			'target'     => '',
			'code'       => 301,
			'note'       => '',
			'enabled'    => 1,
		);
	}

	/**
	 * Supported redirect match types.
	 *
	 * @return array<string,string>
	 */
	public static function get_redirect_match_options() {
		return array(
			'exact'  => 'Точное совпадение',
			'prefix' => 'По префиксу',
		);
	}

	/**
	 * Supported redirect codes.
	 *
	 * @return array<int,string>
	 */
	public static function get_redirect_code_options() {
		return array(
			301 => '301 Permanent Redirect',
			302 => '302 Temporary Redirect',
			307 => '307 Temporary Redirect',
		);
	}

	/**
	 * Normalize redirect source path.
	 *
	 * @param string $source Raw source.
	 * @return string
	 */
	public static function normalize_redirect_source( $source ) {
		$source = trim( wp_unslash( (string) $source ) );

		if ( '' === $source ) {
			return '';
		}

		$source_path = wp_parse_url( $source, PHP_URL_PATH );

		if ( ! is_string( $source_path ) || '' === $source_path ) {
			$source_path = $source;
		}

		$source_path = '/' . ltrim( rawurldecode( $source_path ), '/' );
		$source_path = preg_replace( '#/+#', '/', $source_path );

		if ( '/' !== $source_path ) {
			$source_path = untrailingslashit( $source_path );
		}

		return (string) $source_path;
	}

	/**
	 * Normalize redirect target URL.
	 *
	 * @param string $target Raw target.
	 * @return string
	 */
	public static function normalize_redirect_target( $target ) {
		$target = trim( wp_unslash( (string) $target ) );

		if ( '' === $target ) {
			return '';
		}

		if ( '/' === substr( $target, 0, 1 ) ) {
			return esc_url_raw( home_url( $target ) );
		}

		return esc_url_raw( $target );
	}

	/**
	 * Normalize request path for redirect and sitemap checks.
	 *
	 * @param string $path Raw path.
	 * @return string
	 */
	public static function normalize_path( $path ) {
		$path = trim( (string) $path );

		if ( '' === $path ) {
			return '/';
		}

		$path = '/' . ltrim( rawurldecode( $path ), '/' );
		$path = preg_replace( '#/+#', '/', $path );

		if ( '/' !== $path ) {
			$path = untrailingslashit( $path );
		}

		return (string) $path;
	}

	/**
	 * Check whether two URLs point to the same location.
	 *
	 * @param string $left  First URL.
	 * @param string $right Second URL.
	 * @return bool
	 */
	public static function are_same_url( $left, $right ) {
		$left_host  = (string) wp_parse_url( $left, PHP_URL_HOST );
		$right_host = (string) wp_parse_url( $right, PHP_URL_HOST );
		$left_path  = self::normalize_path( (string) wp_parse_url( $left, PHP_URL_PATH ) );
		$right_path = self::normalize_path( (string) wp_parse_url( $right, PHP_URL_PATH ) );
		$left_query = (string) wp_parse_url( $left, PHP_URL_QUERY );
		$right_query = (string) wp_parse_url( $right, PHP_URL_QUERY );

		return $left_host === $right_host && $left_path === $right_path && $left_query === $right_query;
	}

	/**
	 * Preferred public sitemap URL.
	 *
	 * @return string
	 */
	public static function get_sitemap_url() {
		return home_url( '/wp-sitemap.xml' );
	}

	/**
	 * Get image URL from attachment ID.
	 *
	 * @param int    $attachment_id Attachment ID.
	 * @param string $size          Image size.
	 * @return string
	 */
	public static function get_image_url( $attachment_id, $size = 'full' ) {
		$attachment_id = absint( $attachment_id );

		if ( ! $attachment_id ) {
			return '';
		}

		$image = wp_get_attachment_image_url( $attachment_id, $size );

		return $image ? (string) $image : '';
	}

	/**
	 * Get image alt text.
	 *
	 * @param int $attachment_id Attachment ID.
	 * @return string
	 */
	public static function get_image_alt( $attachment_id ) {
		$attachment_id = absint( $attachment_id );

		if ( ! $attachment_id ) {
			return '';
		}

		return trim( (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) );
	}

	/**
	 * Get site name for SEO output.
	 *
	 * @return string
	 */
	public static function get_site_name() {
		$settings = self::get_settings();
		$custom   = trim( (string) $settings['general']['site_name'] );

		if ( '' !== $custom ) {
			return $custom;
		}

		return (string) get_bloginfo( 'name' );
	}

	/**
	 * Build final title with separator and site name.
	 *
	 * @param string $main_title Main SEO title.
	 * @return string
	 */
	public static function build_title( $main_title ) {
		$main_title = trim( wp_strip_all_tags( (string) $main_title ) );
		$site_name  = trim( self::get_site_name() );
		$settings   = self::get_settings();
		$separator  = trim( (string) $settings['general']['title_separator'] );

		if ( '' === $main_title ) {
			return $site_name;
		}

		if ( '' === $site_name ) {
			return $main_title;
		}

		if ( '' === $separator ) {
			$separator = '|';
		}

		return $main_title . ' ' . $separator . ' ' . $site_name;
	}

	/**
	 * Build trimmed description from raw text.
	 *
	 * @param string $text  Raw text.
	 * @param int    $limit Character limit.
	 * @return string
	 */
	public static function trim_description( $text, $limit = 160 ) {
		$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $text ) ) );

		if ( '' === $text ) {
			return '';
		}

		return wp_html_excerpt( $text, $limit, '...' );
	}

	/**
	 * Build keywords from array of values.
	 *
	 * @param array<int,string> $values Keyword parts.
	 * @return string
	 */
	public static function build_keywords( $values ) {
		$values = array_filter(
			array_map(
				static function ( $value ) {
					return trim( sanitize_text_field( (string) $value ) );
				},
				$values
			)
		);

		$values = array_values( array_unique( $values ) );

		return implode( ', ', $values );
	}

	/**
	 * Build current request URL.
	 *
	 * @return string
	 */
	public static function get_current_request_url() {
		global $wp;

		if ( isset( $wp->request ) ) {
			$path = (string) $wp->request;
			$url  = home_url( '/' . ltrim( $path, '/' ) );

			if ( is_search() ) {
				$url = get_search_link( get_search_query() );
			}

			if ( is_paged() ) {
				$paged = max( 1, (int) get_query_var( 'paged' ) );
				$url   = get_pagenum_link( $paged );
			}

			return (string) $url;
		}

		return (string) home_url( '/' );
	}

	/**
	 * Check whether a post is indexable.
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	public static function is_post_indexable( $post_id ) {
		$post_id = absint( $post_id );

		if ( ! $post_id || 'publish' !== get_post_status( $post_id ) ) {
			return false;
		}

		$meta = self::get_post_meta_bundle( $post_id );

		return ! empty( $meta['robots_index'] );
	}

	/**
	 * Check whether a term is indexable.
	 *
	 * @param int $term_id Term ID.
	 * @return bool
	 */
	public static function is_term_indexable( $term_id ) {
		$term_id = absint( $term_id );

		if ( ! $term_id ) {
			return false;
		}

		$meta = self::get_term_meta_bundle( $term_id );

		return ! empty( $meta['robots_index'] );
	}
}
