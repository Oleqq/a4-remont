<?php
/**
 * Technical SEO layer: sitemap, robots.txt, verification codes, attachment handling.
 *
 * @package A4_Remont_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class A4_Remont_SEO_System {

	const QUERY_SITEMAP_KIND = 'a4seo_sitemap';
	const QUERY_SITEMAP_NAME = 'a4seo_sitemap_name';
	const QUERY_SITEMAP_PAGE = 'a4seo_sitemap_page';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'maybe_render_sitemap_from_request' ), 0 );
		add_action( 'init', array( __CLASS__, 'register_rewrite_rules' ), 1 );
		add_action( 'init', array( $this, 'maybe_flush_rewrite_rules' ), 99 );
		add_filter( 'query_vars', array( $this, 'register_query_vars' ) );
		add_filter( 'rewrite_rules_array', array( $this, 'override_rewrite_rules' ), 1000 );
		add_filter( 'pre_handle_404', array( $this, 'maybe_handle_legacy_sitemap_request' ), 20, 2 );
		add_filter( 'wp_sitemaps_enabled', '__return_false', 20 );
		add_filter( 'redirect_canonical', array( $this, 'filter_canonical_redirect' ), 20, 2 );
		add_action( 'template_redirect', array( $this, 'maybe_render_sitemap' ), 0 );
		add_action( 'template_redirect', array( $this, 'handle_attachment_request' ), 1 );
		add_filter( 'robots_txt', array( $this, 'filter_robots_txt' ), 20, 2 );
		add_action( 'wp_head', array( $this, 'render_verification_tags' ), 0 );
		add_action( 'wp_head', array( $this, 'render_tracking_head' ), 99 );
		add_action( 'wp_body_open', array( $this, 'render_tracking_body' ), 5 );
		add_action( 'wp_footer', array( $this, 'render_tracking_footer' ), 99 );
	}

	/**
	 * Register sitemap rewrite rules.
	 *
	 * @return void
	 */
	public static function register_rewrite_rules() {
		add_rewrite_rule(
			'^sitemap\.xml$',
			'index.php?' . self::QUERY_SITEMAP_KIND . '=index',
			'top'
		);

		add_rewrite_rule(
			'^wp-sitemap\.xml$',
			'index.php?' . self::QUERY_SITEMAP_KIND . '=index',
			'top'
		);

		add_rewrite_rule(
			'^sitemap-posttype-([a-z0-9_-]+)-([0-9]+)\.xml$',
			'index.php?' . self::QUERY_SITEMAP_KIND . '=posttype&' . self::QUERY_SITEMAP_NAME . '=$matches[1]&' . self::QUERY_SITEMAP_PAGE . '=$matches[2]',
			'top'
		);

		add_rewrite_rule(
			'^sitemap-taxonomy-([a-z0-9_-]+)-([0-9]+)\.xml$',
			'index.php?' . self::QUERY_SITEMAP_KIND . '=taxonomy&' . self::QUERY_SITEMAP_NAME . '=$matches[1]&' . self::QUERY_SITEMAP_PAGE . '=$matches[2]',
			'top'
		);
	}

	/**
	 * Flush rewrite rules after plugin updates.
	 *
	 * @return void
	 */
	public function maybe_flush_rewrite_rules() {
		$stored_version = (string) get_option( A4_Remont_SEO::REWRITE_KEY, '' );

		if ( A4_REMONT_SEO_VERSION === $stored_version ) {
			return;
		}

		self::register_rewrite_rules();
		update_option( A4_Remont_SEO::REWRITE_KEY, A4_REMONT_SEO_VERSION, false );
		flush_rewrite_rules( false );
	}

	/**
	 * Register custom query vars.
	 *
	 * @param array<int,string> $vars Existing vars.
	 * @return array<int,string>
	 */
	public function register_query_vars( $vars ) {
		$vars[] = self::QUERY_SITEMAP_KIND;
		$vars[] = self::QUERY_SITEMAP_NAME;
		$vars[] = self::QUERY_SITEMAP_PAGE;

		return $vars;
	}

	/**
	 * Ensure custom sitemap rewrite rules win over core sitemap rewrites.
	 *
	 * @param array<string,string> $rules Rewrite rules.
	 * @return array<string,string>
	 */
	public function override_rewrite_rules( $rules ) {
		$rules['sitemap\.xml$'] = 'index.php?' . self::QUERY_SITEMAP_KIND . '=index';
		$rules['wp-sitemap\.xml$'] = 'index.php?' . self::QUERY_SITEMAP_KIND . '=index';

		return $rules;
	}

	/**
	 * Intercept legacy sitemap.xml request before WordPress turns it into a 404.
	 *
	 * @param bool     $bypass Current bypass flag.
	 * @param WP_Query $query  Query object.
	 * @return bool
	 */
	public function maybe_handle_legacy_sitemap_request( $bypass, $query ) {
		$settings = A4_Remont_SEO::get_settings();

		if ( ! empty( $bypass ) || empty( $settings['tools']['enable_sitemap'] ) ) {
			return (bool) $bypass;
		}

		$path = '';

		if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$path = '/' . ltrim( (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ), '/' );
		}

		if (
			in_array( $path, array( '/sitemap.xml', '/wp-sitemap.xml' ), true ) &&
			(
				'sitemap-xml' === $query->get( 'pagename' ) ||
				'sitemap-xml' === $query->get( 'name' ) ||
				'index' === $query->get( 'sitemap' )
			)
		) {
			$this->send_xml_headers();
			$this->render_sitemap_index();
			exit;
		}

		return false;
	}

	/**
	 * Disable canonical redirects for custom sitemap endpoints.
	 *
	 * @param string|false $redirect_url Redirect URL.
	 * @param string       $requested_url Requested URL.
	 * @return string|false
	 */
	public function filter_canonical_redirect( $redirect_url, $requested_url ) {
		$request_path = wp_parse_url( $requested_url, PHP_URL_PATH );

		if ( is_string( $request_path ) && preg_match( '#/((wp-)?sitemap(\.xml)?|sitemap-(posttype|taxonomy)-[a-z0-9_-]+-[0-9]+\.xml)$#i', $request_path ) ) {
			return false;
		}

		if ( get_query_var( self::QUERY_SITEMAP_KIND ) ) {
			return false;
		}

		return $redirect_url;
	}

	/**
	 * Render custom sitemap by raw request path before core redirects kick in.
	 *
	 * @return void
	 */
	public function maybe_render_sitemap_from_request() {
		$settings = A4_Remont_SEO::get_settings();

		if ( empty( $settings['tools']['enable_sitemap'] ) || empty( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}

		$request_path = (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH );
		$request_path = '/' . ltrim( $request_path, '/' );

		if ( in_array( $request_path, array( '/sitemap.xml', '/wp-sitemap.xml' ), true ) ) {
			$this->send_xml_headers();
			$this->render_sitemap_index();
			exit;
		}

		if ( preg_match( '#^/sitemap-posttype-([a-z0-9_-]+)-([0-9]+)\.xml$#i', $request_path, $matches ) ) {
			$this->send_xml_headers();
			$this->render_post_type_sitemap( sanitize_key( $matches[1] ), max( 1, absint( $matches[2] ) ) );
			exit;
		}

		if ( preg_match( '#^/sitemap-taxonomy-([a-z0-9_-]+)-([0-9]+)\.xml$#i', $request_path, $matches ) ) {
			$this->send_xml_headers();
			$this->render_taxonomy_sitemap( sanitize_key( $matches[1] ), max( 1, absint( $matches[2] ) ) );
			exit;
		}
	}

	/**
	 * Render sitemap response if current request matches the custom endpoint.
	 *
	 * @return void
	 */
	public function maybe_render_sitemap() {
		$settings = A4_Remont_SEO::get_settings();

		if ( empty( $settings['tools']['enable_sitemap'] ) ) {
			return;
		}

		$kind = sanitize_key( (string) get_query_var( self::QUERY_SITEMAP_KIND ) );

		if ( '' === $kind ) {
			$core_sitemap = sanitize_key( (string) get_query_var( 'sitemap' ) );

			if ( 'index' === $core_sitemap && ! empty( $_SERVER['REQUEST_URI'] ) ) {
				$request_path = (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH );
				$request_path = '/' . ltrim( $request_path, '/' );

				if ( in_array( $request_path, array( '/sitemap.xml', '/wp-sitemap.xml' ), true ) ) {
					$this->send_xml_headers();
					$this->render_sitemap_index();
					exit;
				}
			}

			return;
		}

		status_header( 200 );
		nocache_headers();
		header( 'Content-Type: application/xml; charset=UTF-8' );

		switch ( $kind ) {
			case 'index':
				$this->render_sitemap_index();
				break;

			case 'posttype':
				$this->render_post_type_sitemap(
					sanitize_key( (string) get_query_var( self::QUERY_SITEMAP_NAME ) ),
					max( 1, absint( get_query_var( self::QUERY_SITEMAP_PAGE ) ) )
				);
				break;

			case 'taxonomy':
				$this->render_taxonomy_sitemap(
					sanitize_key( (string) get_query_var( self::QUERY_SITEMAP_NAME ) ),
					max( 1, absint( get_query_var( self::QUERY_SITEMAP_PAGE ) ) )
				);
				break;

			default:
				status_header( 404 );
				echo $this->get_empty_sitemap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		exit;
	}

	/**
	 * Send XML response headers for sitemap output.
	 *
	 * @return void
	 */
	protected function send_xml_headers() {
		status_header( 200 );
		nocache_headers();
		header( 'Content-Type: application/xml; charset=UTF-8' );
	}

	/**
	 * Redirect or constrain attachment pages.
	 *
	 * @return void
	 */
	public function handle_attachment_request() {
		if ( ! is_attachment() || is_admin() || wp_doing_ajax() ) {
			return;
		}

		$settings = A4_Remont_SEO::get_settings();
		$mode     = $settings['tools']['attachment_handling'] ?? 'redirect_to_parent';
		$post_id  = get_queried_object_id();

		if ( ! $post_id ) {
			return;
		}

		if ( 'redirect_to_parent' === $mode ) {
			$parent_id = (int) wp_get_post_parent_id( $post_id );
			$target    = $parent_id ? get_permalink( $parent_id ) : wp_get_attachment_url( $post_id );

			if ( $target ) {
				wp_safe_redirect( $target, 301 );
				exit;
			}
		}

		if ( 'redirect_to_file' === $mode ) {
			$file_url = wp_get_attachment_url( $post_id );

			if ( $file_url ) {
				wp_safe_redirect( $file_url, 301 );
				exit;
			}
		}
	}

	/**
	 * Override virtual robots.txt.
	 *
	 * @param string $output Current content.
	 * @param bool   $public Site visibility flag.
	 * @return string
	 */
	public function filter_robots_txt( $output, $public ) {
		$settings = A4_Remont_SEO::get_settings();

		if ( empty( $settings['tools']['enable_robots_txt'] ) ) {
			return $output;
		}

		$lines   = array();
		$lines[] = 'User-agent: *';

		if ( ! $public ) {
			$lines[] = 'Disallow: /';
		} else {
			$lines[] = 'Allow: /';
			$lines[] = 'Disallow: /wp-admin/';
			$lines[] = 'Allow: /wp-admin/admin-ajax.php';
		}

		$extra_lines = preg_split( '/\r\n|\r|\n/', (string) $settings['tools']['robots_txt_extra'] );

		if ( is_array( $extra_lines ) ) {
			foreach ( $extra_lines as $extra_line ) {
				$extra_line = trim( (string) $extra_line );

				if ( '' !== $extra_line ) {
					$lines[] = $extra_line;
				}
			}
		}

		if ( ! empty( $settings['tools']['enable_sitemap'] ) ) {
			$lines[] = 'Sitemap: ' . esc_url_raw( A4_Remont_SEO::get_sitemap_url() );
		}

		return implode( "\n", array_values( array_unique( $lines ) ) ) . "\n";
	}

	/**
	 * Print search-engine verification tags.
	 *
	 * @return void
	 */
	public function render_verification_tags() {
		if ( is_admin() || is_feed() || wp_is_json_request() ) {
			return;
		}

		$settings = A4_Remont_SEO::get_settings();
		$codes    = $settings['verification'];

		$map = array(
			'google'    => 'google-site-verification',
			'yandex'    => 'yandex-verification',
			'bing'      => 'msvalidate.01',
			'pinterest' => 'p:domain_verify',
		);

		foreach ( $map as $key => $meta_name ) {
			if ( empty( $codes[ $key ] ) ) {
				continue;
			}

			echo '<meta name="' . esc_attr( $meta_name ) . '" content="' . esc_attr( $codes[ $key ] ) . '">' . "\n";
		}
	}

	/**
	 * Render tracking snippets in head.
	 *
	 * @return void
	 */
	public function render_tracking_head() {
		if ( is_admin() || is_feed() || wp_is_json_request() ) {
			return;
		}

		$analytics = A4_Remont_SEO::get_settings()['analytics'];

		if ( ! empty( $analytics['google_tag_manager_id'] ) ) {
			$container_id = $analytics['google_tag_manager_id'];
			?>
			<!-- A4 Remont SEO: Google Tag Manager -->
			<script>
				(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','<?php echo esc_js( $container_id ); ?>');
			</script>
			<?php
		}

		if ( ! empty( $analytics['yandex_metrika_id'] ) ) {
			$counter_id = $analytics['yandex_metrika_id'];
			$webvisor   = ! empty( $analytics['yandex_metrika_webvisor'] ) ? 'true' : 'false';
			?>
			<!-- A4 Remont SEO: Yandex Metrika -->
			<script>
				(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
				m[i].l=1*new Date();for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
				k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a);})
				(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

				ym(<?php echo esc_js( $counter_id ); ?>, "init", {
					clickmap:true,
					trackLinks:true,
					accurateTrackBounce:true,
					webvisor:<?php echo esc_html( $webvisor ); ?>
				});
			</script>
			<?php
		}

		if ( ! empty( $analytics['head_code'] ) ) {
			echo "\n" . $analytics['head_code'] . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Render tracking snippets after body open.
	 *
	 * @return void
	 */
	public function render_tracking_body() {
		if ( is_admin() || is_feed() || wp_is_json_request() ) {
			return;
		}

		$analytics = A4_Remont_SEO::get_settings()['analytics'];

		if ( ! empty( $analytics['google_tag_manager_id'] ) ) {
			$container_id = $analytics['google_tag_manager_id'];
			?>
			<!-- A4 Remont SEO: Google Tag Manager (noscript) -->
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $container_id ); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<?php
		}

		if ( ! empty( $analytics['yandex_metrika_id'] ) ) {
			$counter_id = $analytics['yandex_metrika_id'];
			?>
			<!-- A4 Remont SEO: Yandex Metrika (noscript) -->
			<noscript><div><img src="https://mc.yandex.ru/watch/<?php echo esc_attr( $counter_id ); ?>" style="position:absolute; left:-9999px;" alt=""></div></noscript>
			<?php
		}

		if ( ! empty( $analytics['body_code'] ) ) {
			echo "\n" . $analytics['body_code'] . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Render tracking snippets in footer.
	 *
	 * @return void
	 */
	public function render_tracking_footer() {
		if ( is_admin() || is_feed() || wp_is_json_request() ) {
			return;
		}

		$analytics = A4_Remont_SEO::get_settings()['analytics'];

		if ( ! empty( $analytics['footer_code'] ) ) {
			echo "\n" . $analytics['footer_code'] . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Render sitemap index XML.
	 *
	 * @return void
	 */
	protected function render_sitemap_index() {
		$items = array();
		$limit = $this->get_sitemap_limit();

		foreach ( A4_Remont_SEO::get_sitemap_post_types() as $post_type => $object ) {
			$count = (int) wp_count_posts( $post_type )->publish;

			if ( $count < 1 ) {
				continue;
			}

			$pages = max( 1, (int) ceil( $count / $limit ) );

			for ( $page = 1; $page <= $pages; $page++ ) {
				$items[] = array(
					'loc' => home_url( '/sitemap-posttype-' . $post_type . '-' . $page . '.xml' ),
				);
			}
		}

		foreach ( A4_Remont_SEO::get_sitemap_taxonomies() as $taxonomy => $object ) {
			$count = (int) wp_count_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				)
			);

			if ( $count < 1 ) {
				continue;
			}

			$pages = max( 1, (int) ceil( $count / $limit ) );

			for ( $page = 1; $page <= $pages; $page++ ) {
				$items[] = array(
					'loc' => home_url( '/sitemap-taxonomy-' . $taxonomy . '-' . $page . '.xml' ),
				);
			}
		}

		echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		foreach ( $items as $item ) {
			echo "\t" . '<sitemap><loc>' . $this->escape_xml( $item['loc'] ) . '</loc></sitemap>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</sitemapindex>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render post type sitemap.
	 *
	 * @param string $post_type Post type.
	 * @param int    $page      Page number.
	 * @return void
	 */
	protected function render_post_type_sitemap( $post_type, $page ) {
		$post_types = A4_Remont_SEO::get_sitemap_post_types();

		if ( empty( $post_types[ $post_type ] ) ) {
			status_header( 404 );
			echo $this->get_empty_sitemap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		$query = new WP_Query(
			array(
				'post_type'              => $post_type,
				'post_status'            => 'publish',
				'posts_per_page'         => $this->get_sitemap_limit(),
				'paged'                  => max( 1, $page ),
				'orderby'                => 'modified',
				'order'                  => 'DESC',
				'fields'                 => 'ids',
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);

		$items = array();

		foreach ( $query->posts as $post_id ) {
			if ( ! A4_Remont_SEO::is_post_indexable( $post_id ) ) {
				continue;
			}

			$items[] = array(
				'loc'     => get_permalink( $post_id ),
				'lastmod' => get_post_modified_time( 'c', true, $post_id ),
			);
		}

		$this->render_urlset( $items );
	}

	/**
	 * Render taxonomy sitemap.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @param int    $page     Page number.
	 * @return void
	 */
	protected function render_taxonomy_sitemap( $taxonomy, $page ) {
		$taxonomies = A4_Remont_SEO::get_sitemap_taxonomies();

		if ( empty( $taxonomies[ $taxonomy ] ) ) {
			status_header( 404 );
			echo $this->get_empty_sitemap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'number'     => $this->get_sitemap_limit(),
				'offset'     => ( max( 1, $page ) - 1 ) * $this->get_sitemap_limit(),
			)
		);

		if ( is_wp_error( $terms ) ) {
			status_header( 404 );
			echo $this->get_empty_sitemap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		$items = array();

		foreach ( $terms as $term ) {
			if ( ! $term instanceof WP_Term || ! A4_Remont_SEO::is_term_indexable( $term->term_id ) ) {
				continue;
			}

			$url = get_term_link( $term );

			if ( is_wp_error( $url ) ) {
				continue;
			}

			$items[] = array(
				'loc' => $url,
			);
		}

		$this->render_urlset( $items );
	}

	/**
	 * Render urlset XML.
	 *
	 * @param array<int,array<string,string>> $items Items.
	 * @return void
	 */
	protected function render_urlset( $items ) {
		echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		foreach ( $items as $item ) {
			echo "\t<url>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo "\t\t<loc>" . $this->escape_xml( $item['loc'] ) . "</loc>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( ! empty( $item['lastmod'] ) ) {
				echo "\t\t<lastmod>" . $this->escape_xml( $item['lastmod'] ) . "</lastmod>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo "\t</url>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</urlset>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get empty valid sitemap response.
	 *
	 * @return string
	 */
	protected function get_empty_sitemap() {
		return '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>';
	}

	/**
	 * Get entries per sitemap file.
	 *
	 * @return int
	 */
	protected function get_sitemap_limit() {
		$settings = A4_Remont_SEO::get_settings();

		return max( 50, min( 2000, absint( $settings['tools']['sitemap_items_per_file'] ) ) );
	}

	/**
	 * Escape XML value.
	 *
	 * @param string $value Raw value.
	 * @return string
	 */
	protected function escape_xml( $value ) {
		return htmlspecialchars( (string) $value, ENT_XML1 | ENT_COMPAT, 'UTF-8' );
	}
}
