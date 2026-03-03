<?php
/**
 * Template for 404 pages.
 *
 * @package a4-remont
 */

get_header();
?>
<?php get_template_part( 'template-parts/section/error-404' ); ?>
<?php get_template_part( 'template-parts/section/cta-banner', null, array( 'section_class' => 'cta-banner--light' ) ); ?>

<?php
get_footer();
