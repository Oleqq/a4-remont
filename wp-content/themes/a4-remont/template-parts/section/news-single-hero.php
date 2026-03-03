<?php
/**
 * Single news hero section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/news-single/news-single-hero.html' );
	return;
}

$news_post = get_post();
$title     = (string) get_sub_field( 'title' );
$lead      = (string) get_sub_field( 'lead' );
$image     = get_sub_field( 'image' );

if ( $news_post instanceof WP_Post ) {
	if ( '' === trim( $title ) ) {
		$title = get_the_title( $news_post );
	}

	if ( '' === trim( $lead ) ) {
		$lead = has_excerpt( $news_post ) ? (string) $news_post->post_excerpt : a4_remont_get_post_excerpt( $news_post, 40 );
	}

	if ( empty( $image ) && has_post_thumbnail( $news_post ) ) {
		$image = get_post_thumbnail_id( $news_post );
	}
}

$badge_prefix        = (string) get_sub_field( 'badge_prefix' );
$share_button_label  = (string) get_sub_field( 'share_button_label' );
$share_tooltip_title = (string) get_sub_field( 'share_tooltip_title' );

if ( '' === trim( $badge_prefix ) ) {
	$badge_prefix = 'Дата публикации:';
}

if ( '' === trim( $share_button_label ) ) {
	$share_button_label = 'Поделиться';
}

if ( '' === trim( $share_tooltip_title ) ) {
	$share_tooltip_title = 'Поделиться';
}

if ( '' === trim( $title ) && '' === trim( $lead ) && empty( $image ) && ! ( $news_post instanceof WP_Post ) ) {
	a4_remont_render_static_markup( 'section/news-single/news-single-hero.html' );
	return;
}

$section_id  = sanitize_title( (string) get_sub_field( 'section_id' ) );
$publish_badge = $news_post instanceof WP_Post ? trim( $badge_prefix . ' ' . get_the_date( 'd.m.y', $news_post ) ) : '';
?>
<section class="news-single-hero"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="news-single-hero__container _container">
		<div class="news-single-hero__card">
			<div class="news-single-hero__content">
				<div class="news-single-hero__meta">
					<?php if ( $publish_badge ) : ?>
						<span class="news-single-hero__badge"><?php echo esc_html( $publish_badge ); ?></span>
					<?php endif; ?>

					<div class="news-single-hero__share-box" data-news-share="data-news-share">
						<button class="news-single-hero__share" type="button" aria-expanded="false" aria-haspopup="true" data-news-share-trigger="data-news-share-trigger">
							<span class="news-single-hero__share-text"><?php echo esc_html( $share_button_label ); ?></span>
							<span class="news-single-hero__share-trigger-icon" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
									<path d="M6.66667 12.5002C6.43056 12.5002 6.23264 12.4203 6.07292 12.2606C5.91319 12.1009 5.83333 11.9029 5.83333 11.6668V7.50016C5.83333 7.04183 5.99653 6.64947 6.32292 6.32308C6.64931 5.99669 7.04167 5.8335 7.5 5.8335H14.2917L12.7292 4.271C12.5625 4.10433 12.4792 3.90641 12.4792 3.67725C12.4792 3.44808 12.5625 3.25016 12.7292 3.0835C12.8958 2.91683 13.0938 2.8335 13.3229 2.8335C13.5521 2.8335 13.75 2.91683 13.9167 3.0835L16.9167 6.0835C17.0833 6.25016 17.1667 6.44461 17.1667 6.66683C17.1667 6.88905 17.0833 7.0835 16.9167 7.25016L13.9167 10.2502C13.7639 10.4029 13.5729 10.4828 13.3438 10.4897C13.1146 10.4967 12.9167 10.4168 12.75 10.2502C12.5833 10.0835 12.4965 9.88558 12.4896 9.65641C12.4826 9.42725 12.5625 9.22933 12.7292 9.06266L14.2917 7.50016H7.5V11.6668C7.5 11.9029 7.42014 12.1009 7.26042 12.2606C7.10069 12.4203 6.90278 12.5002 6.66667 12.5002ZM4.16667 17.5002C3.70833 17.5002 3.31597 17.337 2.98958 17.0106C2.66319 16.6842 2.5 16.2918 2.5 15.8335V4.16683C2.5 3.93072 2.57986 3.7328 2.73958 3.57308C2.89931 3.41336 3.09722 3.3335 3.33333 3.3335C3.56944 3.3335 3.76736 3.41336 3.92708 3.57308C4.08681 3.7328 4.16667 3.93072 4.16667 4.16683V15.8335H14.1667V13.3335C14.1667 13.0974 14.2465 12.8995 14.4062 12.7397C14.566 12.58 14.7639 12.5002 15 12.5002C15.2361 12.5002 15.434 12.58 15.5938 12.7397C15.7535 12.8995 15.8333 13.0974 15.8333 13.3335V15.8335C15.8333 16.2918 15.6701 16.6842 15.3438 17.0106C15.0174 17.337 14.625 17.5002 14.1667 17.5002H4.16667Z" fill="#C09B57"></path>
								</svg>
							</span>
						</button>

						<div class="news-single-hero__share-tooltip" aria-hidden="true" data-news-share-tooltip="data-news-share-tooltip">
							<p class="news-single-hero__share-tooltip-title"><?php echo esc_html( $share_tooltip_title ); ?></p>
							<div class="news-single-hero__share-actions">
								<a class="news-single-hero__share-action" href="#" target="_blank" rel="noopener noreferrer" data-share-link="telegram" aria-label="Поделиться в Telegram">
									<span class="news-single-hero__share-icon news-single-hero__share-icon--telegram" aria-hidden="true">
										<svg xmlns="http://www.w3.org/2000/svg" width="9" height="8" viewBox="0 0 9 8" fill="none"><path d="M8.26937 0.0909246L0.295792 3.35489C-0.0250791 3.50689 -0.133606 3.81128 0.218237 3.97648L2.2638 4.66654L7.20972 1.42181C7.47978 1.21811 7.75624 1.27242 7.51834 1.49651L3.27047 5.5793L3.13703 7.30712C3.26062 7.5739 3.48692 7.57514 3.63127 7.44254L4.80651 6.2621L6.81929 7.86204C7.28678 8.15583 7.54116 7.96624 7.64174 7.42777L8.96194 0.791831C9.09902 0.129017 8.86526 -0.163026 8.26937 0.0909246Z" fill="white"></path></svg>
									</span>
								</a>
								<a class="news-single-hero__share-action" href="#" target="_blank" rel="noopener noreferrer" data-share-link="vk" aria-label="Поделиться во ВКонтакте">
									<span class="news-single-hero__share-icon news-single-hero__share-icon--vk" aria-hidden="true">
										<svg xmlns="http://www.w3.org/2000/svg" width="11" height="7" viewBox="0 0 11 7" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.7479 0.4345C10.824 0.18425 10.7479 0 10.3835 0H9.18042C8.87425 0 8.73309 0.159042 8.65654 0.334583C8.65654 0.334583 8.04467 1.79942 7.17796 2.75092C6.89746 3.02683 6.77004 3.11437 6.61696 3.11437C6.54042 3.11437 6.42538 3.02683 6.42538 2.77612V0.4345C6.42538 0.133833 6.34104 0 6.08621 0H4.19421C4.00308 0 3.88804 0.139333 3.88804 0.271792C3.88804 0.556417 4.32163 0.622417 4.36608 1.42358V3.16433C4.36608 3.54612 4.29596 3.61533 4.14288 3.61533C3.73496 3.61533 2.74267 2.14362 2.15371 0.459708C2.03958 0.132 1.92408 0 1.61654 0H0.4125C0.06875 0 0 0.159042 0 0.334583C0 0.647167 0.407917 2.2 1.89979 4.25379C2.89438 5.65629 4.29458 6.41667 5.57013 6.41667C6.33508 6.41667 6.4295 6.248 6.4295 5.95696V4.89683C6.4295 4.55904 6.50192 4.49167 6.74438 4.49167C6.92313 4.49167 7.22884 4.57967 7.94292 5.25571C8.75875 6.05733 8.89304 6.41667 9.35229 6.41667H10.5554C10.8992 6.41667 11.0715 6.248 10.9725 5.91433C10.8634 5.5825 10.4738 5.10079 9.95729 4.52925C9.6768 4.20383 9.25604 3.85321 9.12817 3.67767C8.94988 3.45262 9.00075 3.35225 9.12817 3.15196C9.12817 3.15196 10.5948 1.12337 10.7475 0.4345" fill="white"></path></svg>
									</span>
								</a>
								<button class="news-single-hero__share-action news-single-hero__share-action--copy" type="button" data-share-copy="data-share-copy" aria-label="Скопировать ссылку">
									<span class="news-single-hero__share-icon news-single-hero__share-icon--link" aria-hidden="true">
										<svg xmlns="http://www.w3.org/2000/svg" width="15" height="13" viewBox="0 0 15 13" fill="none"><path d="M6.57618 10.0463C6.6461 10.116 6.70157 10.1988 6.73943 10.29C6.77728 10.3811 6.79677 10.4789 6.79677 10.5776C6.79677 10.6763 6.77728 10.774 6.73943 10.8652C6.70157 10.9564 6.6461 11.0392 6.57618 11.1088L6.25618 11.4288C5.91649 11.7698 5.51258 12.04 5.0678 12.2239C4.62303 12.4078 4.14621 12.5016 3.66493 12.5001C2.94013 12.5003 2.23156 12.2856 1.62884 11.883C1.02612 11.4804 0.556351 10.9082 0.278954 10.2386C0.00155643 9.56894 -0.071 8.8321 0.0704639 8.12125C0.211928 7.4104 0.561055 6.75747 1.07368 6.24509L3.24555 4.07321C3.70751 3.61132 4.28453 3.2813 4.91687 3.11731C5.54921 2.95332 6.2139 2.96132 6.84211 3.14049C7.47032 3.31966 8.03922 3.66348 8.48992 4.13637C8.94063 4.60925 9.25674 5.194 9.40555 5.83009C9.4302 5.9268 9.43529 6.02746 9.42054 6.12617C9.40578 6.22488 9.37147 6.31965 9.31962 6.40493C9.26777 6.49021 9.19942 6.56428 9.11858 6.62281C9.03773 6.68133 8.94602 6.72313 8.84881 6.74576C8.75161 6.76839 8.65086 6.77139 8.55248 6.75458C8.4541 6.73777 8.36007 6.7015 8.27589 6.64789C8.1917 6.59427 8.11907 6.52439 8.06224 6.44235C8.00541 6.3603 7.96553 6.26774 7.94493 6.17009C7.85662 5.79461 7.66964 5.44952 7.4033 5.17051C7.13696 4.8915 6.80093 4.68869 6.42996 4.58304C6.05899 4.4774 5.66653 4.47275 5.29315 4.56958C4.91978 4.66641 4.57905 4.86121 4.30618 5.13384L2.1343 7.30571C1.83156 7.60821 1.62532 7.99369 1.54167 8.41341C1.45802 8.83312 1.50071 9.26822 1.66435 9.66367C1.82799 10.0591 2.10522 10.3972 2.46099 10.635C2.81676 10.8729 3.23508 11 3.66305 11.0001C3.94742 11.0009 4.22912 10.9453 4.49186 10.8366C4.75459 10.7278 4.99313 10.5679 5.19368 10.3663L5.51305 10.0463C5.58274 9.97632 5.66558 9.92076 5.7568 9.88286C5.84802 9.84495 5.94583 9.82543 6.04461 9.82543C6.1434 9.82543 6.24121 9.84495 6.33243 9.88286C6.42365 9.92076 6.50649 9.97632 6.57618 10.0463ZM13.4293 1.07321C12.7419 0.386033 11.8097 0 10.8377 0C9.86576 0 8.93358 0.386033 8.24618 1.07321L7.9268 1.39259C7.78591 1.53348 7.70675 1.72458 7.70675 1.92384C7.70675 2.12309 7.78591 2.31419 7.9268 2.45509C8.0677 2.59598 8.25879 2.67514 8.45805 2.67514C8.65731 2.67514 8.84841 2.59598 8.9893 2.45509L9.3093 2.13509C9.71542 1.72897 10.2662 1.50082 10.8406 1.50082C11.4149 1.50082 11.9657 1.72897 12.3718 2.13509C12.7779 2.5412 13.0061 3.09201 13.0061 3.66634C13.0061 4.24067 12.7779 4.79147 12.3718 5.19759L10.1962 7.36634C9.99564 7.56795 9.75709 7.72777 9.49436 7.83655C9.23162 7.94534 8.94992 8.00092 8.66555 8.00009C8.17774 7.99974 7.70434 7.83461 7.32214 7.53149C6.93994 7.22836 6.67135 6.80501 6.55993 6.33009C6.51484 6.13631 6.39462 5.96839 6.22572 5.86325C6.05682 5.75811 5.85308 5.72437 5.6593 5.76946C5.46553 5.81455 5.2976 5.93476 5.19246 6.10366C5.08733 6.27256 5.05359 6.47631 5.09868 6.67009C5.28687 7.47438 5.74124 8.19152 6.38811 8.7052C7.03498 9.21889 7.83641 9.49899 8.66243 9.50009H8.66555C9.14706 9.5014 9.62405 9.40724 10.0689 9.22304C10.5138 9.03885 10.9178 8.76827 11.2574 8.42696L13.4293 6.25509C13.7696 5.91487 14.0395 5.51094 14.2237 5.06638C14.4079 4.62183 14.5027 4.14534 14.5027 3.66415C14.5027 3.18295 14.4079 2.70647 14.2237 2.26191C14.0395 1.81736 13.7696 1.41343 13.4293 1.07321Z" fill="#C09B57"></path></svg>
									</span>
								</button>
							</div>
						</div>
					</div>

					<?php if ( $title ) : ?>
						<h1 class="news-single-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
					<?php endif; ?>

					<?php if ( $lead ) : ?>
						<p class="news-single-hero__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
					<?php endif; ?>
				</div>

				
			</div>
			<?php if ( $image ) : ?>
				<figure class="news-single-hero__media">
					<?php echo a4_remont_get_acf_image_html( $image, 'full', array( 'class' => 'news-single-hero__image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</figure>
			<?php endif; ?>
		</div>
	</section>
