<?php
/**
 * Theme footer.
 *
 * @package a4-remont
 */

?>
	</main>

	<?php
	if ( function_exists( 'a4_remont_render_site_footer' ) ) {
		a4_remont_render_site_footer();
	}
	?>
</div>

<?php
if ( function_exists( 'a4_remont_render_site_popups' ) ) {
	a4_remont_render_site_popups();
}
?>

<?php wp_footer(); ?>

</body>
</html>
