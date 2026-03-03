<?php
/**
 * Media handling helpers.
 *
 * @package a4-remont
 */

/**
 * Determine whether the current user is allowed to upload SVG files.
 *
 * SVG is intentionally limited to trusted users because the format can contain
 * executable markup if it is not sanitized server-side.
 *
 * @return bool
 */
function a4_remont_current_user_can_upload_svg() {
	return current_user_can( 'manage_options' ) || current_user_can( 'unfiltered_html' );
}

/**
 * Allow SVG uploads for trusted users.
 *
 * @param array<string, string> $mimes Allowed mime types.
 * @return array<string, string>
 */
function a4_remont_allow_svg_uploads( $mimes ) {
	if ( ! a4_remont_current_user_can_upload_svg() ) {
		return $mimes;
	}

	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}
add_filter( 'upload_mimes', 'a4_remont_allow_svg_uploads' );

/**
 * Fix SVG filetype detection during upload validation.
 *
 * @param array<string, mixed> $data                Filetype data.
 * @param string               $file                Full path to the file.
 * @param string               $filename            Original filename.
 * @param array<string, string> $mimes              Allowed mime types.
 * @param string|false         $real_mime           Real mime type if known.
 * @return array<string, mixed>
 */
function a4_remont_fix_svg_filetype_check( $data, $file, $filename, $mimes, $real_mime ) {
	unset( $file, $mimes, $real_mime );

	if ( ! a4_remont_current_user_can_upload_svg() ) {
		return $data;
	}

	$file_extension = strtolower( (string) pathinfo( $filename, PATHINFO_EXTENSION ) );

	if ( 'svg' !== $file_extension ) {
		return $data;
	}

	$data['ext']             = 'svg';
	$data['type']            = 'image/svg+xml';
	$data['proper_filename'] = $filename;

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'a4_remont_fix_svg_filetype_check', 10, 5 );

/**
 * Make SVG files behave like images in the media library UI.
 *
 * @param bool   $result Whether the file is displayable.
 * @param string $path   Absolute file path.
 * @return bool
 */
function a4_remont_mark_svg_as_displayable_image( $result, $path ) {
	if ( $result ) {
		return $result;
	}

	return 'svg' === strtolower( (string) pathinfo( $path, PATHINFO_EXTENSION ) );
}
add_filter( 'file_is_displayable_image', 'a4_remont_mark_svg_as_displayable_image', 10, 2 );

/**
 * Normalize SVG preview data for the media modal.
 *
 * @param array<string, mixed> $response Attachment response.
 * @param WP_Post              $attachment Attachment object.
 * @param array<string, mixed>|false $meta Attachment meta.
 * @return array<string, mixed>
 */
function a4_remont_prepare_svg_for_media_modal( $response, $attachment, $meta ) {
	if ( 'image/svg+xml' !== get_post_mime_type( $attachment ) ) {
		return $response;
	}

	$width  = 0;
	$height = 0;

	if ( is_array( $meta ) ) {
		$width  = ! empty( $meta['width'] ) ? (int) $meta['width'] : 0;
		$height = ! empty( $meta['height'] ) ? (int) $meta['height'] : 0;
	}

	if ( $width < 1 ) {
		$width = 512;
	}

	if ( $height < 1 ) {
		$height = 512;
	}

	$response['type'] = 'image';

	if ( empty( $response['sizes'] ) || ! is_array( $response['sizes'] ) ) {
		$response['sizes'] = array();
	}

	$response['sizes']['full'] = array(
		'url'         => $response['url'],
		'width'       => $width,
		'height'      => $height,
		'orientation' => $width > $height ? 'landscape' : 'portrait',
	);

	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'a4_remont_prepare_svg_for_media_modal', 10, 3 );

/**
 * Keep SVG previews tidy inside the media library.
 *
 * @return void
 */
function a4_remont_admin_svg_preview_styles() {
	?>
	<style>
		.media-icon img[src$=".svg"],
		.thumbnail img[src$=".svg"],
		img.attachment-post-thumbnail[src$=".svg"] {
			width: 100% !important;
			height: auto !important;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'a4_remont_admin_svg_preview_styles' );
