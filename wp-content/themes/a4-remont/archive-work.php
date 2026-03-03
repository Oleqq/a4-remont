<?php
/**
 * Works archive template.
 *
 * @package a4-remont
 */

get_header();

if ( function_exists( 'a4_remont_render_work_archive_content' ) ) {
	a4_remont_render_work_archive_content();
}

get_footer();
