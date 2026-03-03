<?php
/**
 * Single service why order section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/service-single/service-why-order.html' );
	return;
}

$section_title = (string) get_sub_field( 'section_title' );
$section_lead  = (string) get_sub_field( 'section_lead' );
$items         = get_sub_field( 'items' );
$section_id    = sanitize_title( (string) get_sub_field( 'section_id' ) );
$note_text     = (string) get_sub_field( 'note_text' );
$image         = get_sub_field( 'image' );

if ( '' === trim( $section_title ) && '' === trim( $section_lead ) && empty( $items ) && '' === trim( $note_text ) && empty( $image ) ) {
	a4_remont_render_static_markup( 'section/service-single/service-why-order.html' );
	return;
}
$icons      = array(
		'wallet' => '<svg width="29" height="20" viewBox="0 0 29 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.22222 0C1.44497 0 0 1.45742 0 3.25V16.25C0 18.0426 1.44497 19.5 3.22222 19.5H25.7778C27.555 19.5 29 18.0426 29 16.25V3.25C29 1.45742 27.555 0 25.7778 0H3.22222ZM5.63889 8.125H13.6944C14.1375 8.125 14.5 8.49062 14.5 8.9375C14.5 9.38438 14.1375 9.75 13.6944 9.75H5.63889C5.19583 9.75 4.83333 9.38438 4.83333 8.9375C4.83333 8.49062 5.19583 8.125 5.63889 8.125ZM4.83333 13.8125C4.83333 13.3656 5.19583 13 5.63889 13H23.3611C23.8042 13 24.1667 13.3656 24.1667 13.8125C24.1667 14.2594 23.8042 14.625 23.3611 14.625H5.63889C5.19583 14.625 4.83333 14.2594 4.83333 13.8125ZM18.9306 4.875H22.9583C23.628 4.875 24.1667 5.41836 24.1667 6.09375V8.53125C24.1667 9.20664 23.628 9.75 22.9583 9.75H18.9306C18.2609 9.75 17.7222 9.20664 17.7222 8.53125V6.09375C17.7222 5.41836 18.2609 4.875 18.9306 4.875Z" fill="white"/></svg>',
		'gift'   => '<svg width="25" height="27" viewBox="0 0 25 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.89583 4.62286C3.89573 3.676 4.18757 2.75213 4.73156 1.97714C5.27556 1.20215 6.04527 0.613729 6.93581 0.292049C7.82636 -0.0296301 8.79442 -0.0689218 9.70811 0.179528C10.6218 0.427978 11.4367 0.952086 12.0417 1.68044C12.812 0.743308 13.9211 0.148079 15.1279 0.0241154C16.3347 -0.0998482 17.5416 0.257472 18.4864 1.01842C19.4312 1.77936 20.0375 2.88244 20.1735 4.08791C20.3095 5.29339 19.9643 6.50383 19.2128 7.45619H20.5417C21.0068 7.45619 21.4673 7.5478 21.897 7.72578C22.3267 7.90377 22.7171 8.16464 23.046 8.49352C23.3749 8.82239 23.6358 9.21282 23.8137 9.64252C23.9917 10.0722 24.0833 10.5328 24.0833 10.9979V12.7687C24.0833 13.0505 23.9714 13.3207 23.7721 13.52C23.5729 13.7192 23.3026 13.8312 23.0208 13.8312H13.5292C13.4734 13.8312 13.4181 13.8202 13.3665 13.7988C13.315 13.7775 13.2681 13.7462 13.2286 13.7067C13.1892 13.6672 13.1579 13.6204 13.1365 13.5688C13.1152 13.5173 13.1042 13.462 13.1042 13.4062V8.50452C12.7043 8.24729 12.346 7.93056 12.0417 7.56527C11.7372 7.93006 11.3789 8.2463 10.9792 8.50311V13.4062C10.9792 13.5189 10.9344 13.627 10.8547 13.7067C10.775 13.7864 10.6669 13.8312 10.5542 13.8312H1.0625C0.780708 13.8312 0.510456 13.7192 0.311199 13.52C0.111942 13.3207 0 13.0505 0 12.7687V10.9979C0 10.5328 0.091608 10.0722 0.269593 9.64252C0.447579 9.21282 0.708456 8.82239 1.03733 8.49352C1.3662 8.16464 1.75664 7.90377 2.18633 7.72578C2.61603 7.5478 3.07657 7.45619 3.54167 7.45619H4.8705C4.23782 6.64736 3.89464 5.64974 3.89583 4.62286Z" fill="white"/><path d="M10.9787 16.1691C10.9787 16.0564 10.9339 15.9483 10.8542 15.8686C10.7745 15.7889 10.6664 15.7441 10.5537 15.7441H3.04394C2.76561 15.7436 2.49612 15.8419 2.2834 16.0214C2.07069 16.2009 1.92855 16.45 1.88228 16.7245C1.56789 18.5603 1.56789 20.4363 1.88228 22.2721L2.19961 24.1266C2.30427 24.734 2.60322 25.2911 3.05154 25.7141C3.49987 26.1372 4.07336 26.4033 4.68586 26.4726L6.19461 26.6411C7.64244 26.8026 9.09264 26.9042 10.5452 26.9457C10.6016 26.9469 10.6577 26.9367 10.7101 26.916C10.7626 26.8952 10.8104 26.8642 10.8507 26.8248C10.8911 26.7854 10.9232 26.7383 10.9451 26.6863C10.9671 26.6344 10.9785 26.5786 10.9787 26.5221V16.1691Z" fill="white"/></svg>',
		'shield' => '<svg width="24" height="27" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.064 0.169092C11.5902 -0.0277713 12.1651 -0.053436 12.7067 0.0957593L12.936 0.169092L22.2693 3.66909C22.7426 3.84655 23.1556 4.15483 23.4604 4.55803C23.7652 4.96123 23.9491 5.44273 23.9907 5.94643L24 6.16643V13.3171C23.9999 15.4777 23.4166 17.5981 22.3115 19.4547C21.2064 21.3112 19.6205 22.835 17.7213 23.8651L17.3667 24.0504L12.8947 26.2864C12.6484 26.4094 12.3795 26.4802 12.1046 26.4944C11.8298 26.5086 11.5549 26.466 11.2973 26.3691L11.1053 26.2864L6.63333 24.0504C4.70081 23.0841 3.06503 21.614 1.89867 19.7952C0.73231 17.9765 0.0786407 15.8765 0.00666682 13.7171L0 13.3171V6.16643C7.7534e-06 5.66126 0.143502 5.1665 0.413778 4.73972C0.684054 4.31294 1.06999 3.97172 1.52667 3.75576L1.73067 3.66909L11.064 0.169092ZM16.5773 8.74776L10.4467 14.8784L8.08933 12.5211C7.83915 12.2711 7.49989 12.1307 7.1462 12.1308C6.7925 12.1309 6.45334 12.2716 6.20333 12.5218C5.95332 12.7719 5.81294 13.1112 5.81306 13.4649C5.81319 13.8186 5.95381 14.1577 6.204 14.4078L9.40933 17.6131C9.54554 17.7494 9.70725 17.8575 9.88524 17.9312C10.0632 18.005 10.254 18.0429 10.4467 18.0429C10.6393 18.0429 10.8301 18.005 11.0081 17.9312C11.1861 17.8575 11.3478 17.7494 11.484 17.6131L18.4627 10.6331C18.59 10.5101 18.6916 10.363 18.7615 10.2003C18.8313 10.0376 18.8681 9.86267 18.8697 9.68563C18.8712 9.50859 18.8375 9.33302 18.7704 9.16916C18.7034 9.00529 18.6044 8.85642 18.4792 8.73123C18.354 8.60604 18.2051 8.50704 18.0413 8.44C17.8774 8.37296 17.7018 8.33922 17.5248 8.34076C17.3478 8.3423 17.1728 8.37908 17.0101 8.44896C16.8475 8.51884 16.7003 8.62041 16.5773 8.74776Z" fill="white"/></svg>',
);

?>
<section class="service-why-order"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="service-why-order__container _container">
		<div class="service-why-order__grid">
			<div class="service-why-order__left">
				<?php if ( $section_title ) : ?>
					<h2 class="section__title service-why-order__title"><?php echo wp_kses_post( $section_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $section_lead ) : ?>
					<p class="section__subtitle service-why-order__lead"><?php echo nl2br( esc_html( $section_lead ) ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $items ) ) : ?>
					<div class="service-why-order__list">
						<?php foreach ( (array) $items as $item ) : ?>
							<?php
							if ( ! is_array( $item ) ) {
								continue;
							}

							$item_title = ! empty( $item['item_title'] ) ? (string) $item['item_title'] : '';
							$item_text  = ! empty( $item['item_text'] ) ? (string) $item['item_text'] : '';
							$item_icon  = ! empty( $item['item_icon'] ) ? (string) $item['item_icon'] : 'wallet';

							if ( '' === trim( $item_title ) && '' === trim( $item_text ) ) {
								continue;
							}
							?>
							<article class="service-why-order__item">
								<span class="service-why-order__icon" aria-hidden="true">
									<?php echo wp_kses( $icons[ $item_icon ] ?? $icons['wallet'], array( 'svg' => array( 'width' => true, 'height' => true, 'viewBox' => true, 'fill' => true, 'xmlns' => true ), 'path' => array( 'd' => true, 'fill' => true, 'fill-rule' => true, 'clip-rule' => true ) ) ); ?>
								</span>
								<div class="service-why-order__item-content">
									<?php if ( $item_title ) : ?>
										<h3 class="service-why-order__item-title"><?php echo esc_html( $item_title ); ?></h3>
									<?php endif; ?>

									<?php if ( $item_text ) : ?>
										<p class="service-why-order__item-text"><?php echo nl2br( esc_html( $item_text ) ); ?></p>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="service-why-order__right">
				<div class="service-why-order__media">
					<?php if ( $note_text ) : ?>
						<div class="service-why-order__note">
							<p><?php echo nl2br( esc_html( $note_text ) ); ?></p>
						</div>
					<?php endif; ?>

					<?php if ( $image ) : ?>
						<?php echo a4_remont_get_acf_image_html( $image, 'large', array( 'class' => 'service-why-order__image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
