<?php
/**
 * 404 error section.
 *
 * @package a4-remont
 */

$home_url     = home_url( '/' );
$services_url = post_type_exists( 'service' ) ? get_post_type_archive_link( 'service' ) : '';

if ( ! $services_url ) {
	$services_url = home_url( '/services/' );
}
?>
<section class="error-404">
	<div class="error-404__container _container">
		<div class="error-404__inner">
			<img
				class="error-404__image"
				src="<?php echo esc_url( get_theme_file_uri( '/images/404.svg' ) ); ?>"
				alt="<?php echo esc_attr__( 'Ошибка 404', 'a4-remont' ); ?>"
				width="430"
				height="320"
				loading="lazy"
			>
			<h1 class="error-404__title">Извините, такой<br>страницы не существует</h1>
			<p class="error-404__text">Воспользуйтесь навигационным меню для перехода на другие страницы или вернитесь к услугам компании.</p>
			<div class="error-404__actions">
				<a class="btn btn--primary error-404__btn" href="<?php echo esc_url( $home_url ); ?>">Вернуться на главную</a>
				<a class="btn btn--grey error-404__btn" href="<?php echo esc_url( $services_url ); ?>">Ознакомиться с услугами</a>
			</div>
		</div>
	</div>
</section>
