<?php
/**
 * Redirect manager.
 *
 * @package A4_Remont_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class A4_Remont_SEO_Redirects {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'maybe_redirect' ), 2 );
	}

	/**
	 * Process redirect rules.
	 *
	 * @return void
	 */
	public function maybe_redirect() {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || wp_is_json_request() || is_feed() || is_preview() ) {
			return;
		}

		$method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';

		if ( ! in_array( $method, array( 'GET', 'HEAD' ), true ) ) {
			return;
		}

		$settings = A4_Remont_SEO::get_settings();
		$rules    = $settings['redirects']['rules'] ?? array();

		if ( empty( $settings['redirects']['enabled'] ) || empty( $rules ) || ! is_array( $rules ) ) {
			return;
		}

		$request_uri = ! empty( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
		$request_path = A4_Remont_SEO::normalize_path( (string) wp_parse_url( $request_uri, PHP_URL_PATH ) );

		if ( $this->is_protected_path( $request_path ) ) {
			return;
		}

		$current_url = home_url( $request_uri );

		foreach ( $rules as $rule ) {
			if ( ! is_array( $rule ) || empty( $rule['enabled'] ) ) {
				continue;
			}

			$target_url = $this->match_rule( $rule, $request_path, $request_uri );

			if ( '' === $target_url || A4_Remont_SEO::are_same_url( $current_url, $target_url ) ) {
				continue;
			}

			wp_redirect( $target_url, absint( $rule['code'] ), 'A4 Remont SEO' );
			exit;
		}
	}

	/**
	 * Check whether current request path must not be redirected by plugin rules.
	 *
	 * @param string $request_path Current path.
	 * @return bool
	 */
	protected function is_protected_path( $request_path ) {
		if ( preg_match( '#^/(wp-admin|wp-login\.php|wp-json|xmlrpc\.php)(/|$)#i', $request_path ) ) {
			return true;
		}

		if ( preg_match( '#^/((wp-)?sitemap\.xml|sitemap-(posttype|taxonomy)-[a-z0-9_-]+-[0-9]+\.xml)$#i', $request_path ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Match redirect rule against current request.
	 *
	 * @param array<string,mixed> $rule         Redirect rule.
	 * @param string              $request_path Current path.
	 * @param string              $request_uri  Current request URI.
	 * @return string
	 */
	protected function match_rule( $rule, $request_path, $request_uri ) {
		$source     = A4_Remont_SEO::normalize_redirect_source( $rule['source'] ?? '' );
		$match_type = sanitize_key( $rule['match_type'] ?? 'exact' );
		$target     = (string) ( $rule['target'] ?? '' );

		if ( '' === $source || '' === $target ) {
			return '';
		}

		if ( 'prefix' === $match_type ) {
			return $this->match_prefix_rule( $source, $target, $request_path, $request_uri );
		}

		if ( A4_Remont_SEO::normalize_path( $source ) !== $request_path ) {
			return '';
		}

		return $target;
	}

	/**
	 * Match prefix redirect rule and preserve trailing path.
	 *
	 * @param string $source       Source path.
	 * @param string $target       Target URL.
	 * @param string $request_path Current path.
	 * @param string $request_uri  Current request URI.
	 * @return string
	 */
	protected function match_prefix_rule( $source, $target, $request_path, $request_uri ) {
		$source = A4_Remont_SEO::normalize_path( $source );

		if ( '/' === $source ) {
			return '';
		}

		$source_prefix = trailingslashit( $source );
		$path_matches  = $request_path === $source || str_starts_with( trailingslashit( $request_path ), $source_prefix );

		if ( ! $path_matches ) {
			return '';
		}

		$remainder = ltrim( substr( $request_path, strlen( $source ) ), '/' );

		if ( '' === $remainder ) {
			return $target;
		}

		$target_scheme = (string) wp_parse_url( $target, PHP_URL_SCHEME );
		$target_host   = (string) wp_parse_url( $target, PHP_URL_HOST );
		$target_port   = (string) wp_parse_url( $target, PHP_URL_PORT );
		$target_user   = (string) wp_parse_url( $target, PHP_URL_USER );
		$target_pass   = (string) wp_parse_url( $target, PHP_URL_PASS );
		$target_path   = (string) wp_parse_url( $target, PHP_URL_PATH );
		$target_query  = (string) wp_parse_url( $target, PHP_URL_QUERY );
		$target_base   = rtrim( home_url(), '/' );

		if ( $target_scheme && $target_host ) {
			$target_base = $target_scheme . '://';

			if ( $target_user ) {
				$target_base .= $target_user;

				if ( $target_pass ) {
					$target_base .= ':' . $target_pass;
				}

				$target_base .= '@';
			}

			$target_base .= $target_host;

			if ( $target_port ) {
				$target_base .= ':' . $target_port;
			}
		}

		$final_path = trailingslashit( A4_Remont_SEO::normalize_path( $target_path ? $target_path : '/' ) ) . $remainder;
		$final_url  = $target_base . $final_path;

		if ( '' !== $target_query ) {
			$final_url .= '?' . $target_query;
		}

		return $final_url;
	}
}
