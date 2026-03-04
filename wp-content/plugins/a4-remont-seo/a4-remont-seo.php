<?php
/**
 * Plugin Name: A4 Remont SEO
 * Description: Кастомный SEO-плагин для проекта A4 Remont: метаданные, Open Graph, canonical, robots, schema.org, sitemap, редиректы и аналитика.
 * Version: 0.4.0
 * Author: DS-Art
 * Text Domain: a4-remont-seo
 *
 * @package A4_Remont_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'A4_REMONT_SEO_VERSION', '0.4.0' );
define( 'A4_REMONT_SEO_FILE', __FILE__ );
define( 'A4_REMONT_SEO_PATH', plugin_dir_path( __FILE__ ) );
define( 'A4_REMONT_SEO_URL', plugin_dir_url( __FILE__ ) );

require_once A4_REMONT_SEO_PATH . 'includes/class-a4-remont-seo.php';
require_once A4_REMONT_SEO_PATH . 'includes/class-a4-remont-seo-admin.php';
require_once A4_REMONT_SEO_PATH . 'includes/class-a4-remont-seo-frontend.php';
require_once A4_REMONT_SEO_PATH . 'includes/class-a4-remont-seo-system.php';
require_once A4_REMONT_SEO_PATH . 'includes/class-a4-remont-seo-redirects.php';

register_activation_hook( __FILE__, array( 'A4_Remont_SEO', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'A4_Remont_SEO', 'deactivate' ) );

add_action(
	'plugins_loaded',
	static function () {
		A4_Remont_SEO::boot();
	}
);
