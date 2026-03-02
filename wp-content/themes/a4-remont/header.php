<?php
/**
 * Theme header.
 *
 * @package a4-remont
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'a4-remont' ); ?></a>

	<header class="site-header">
		<div class="site-header__inner">
			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<div class="site-branding__logo"><?php the_custom_logo(); ?></div>
				<?php endif; ?>

				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-branding__title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</h1>
				<?php else : ?>
					<p class="site-branding__title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</p>
				<?php endif; ?>
			</div>

			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'container'       => 'nav',
					'container_class' => 'primary-navigation',
					'fallback_cb'     => false,
				)
			);
			?>
		</div>
	</header>

	<main id="primary" class="site-main">
