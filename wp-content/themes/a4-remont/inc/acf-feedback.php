<?php
/**
 * ACF builders for the feedback content model.
 *
 * @package a4-remont
 */

/**
 * Sync feedback post fields into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_feedback_content_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$review_type_key = a4_remont_acf_key( 'feedback', 'review_type' );

	$field_group = array(
		'key'                   => 'group_a4_remont_feedback_fields',
		'title'                 => 'Поля отзыва',
		'fields'                => array(
			a4_remont_acf_message(
				'feedback',
				'guide',
				'Как заполнять отзыв',
				'<strong>Как работает CPT "Отзывы":</strong><ol><li><strong>Заголовок записи</strong> используйте как имя клиента или короткое название отзыва.</li><li><strong>Формат отзыва</strong> определяет, где запись будет использоваться: текстовые отзывы идут в карточки <code>feedback-showcase</code>, фото- и видео-отзывы выводятся на странице <code>/reviews/</code>.</li><li>Для <strong>текстового</strong> отзыва основной текст записи или excerpt используется как содержимое карточки.</li><li>Для <strong>фото-</strong> и <strong>видео-</strong> отзыва обязательно заполните соответствующие медиа-поля ниже.</li></ol>'
			),
			a4_remont_acf_tab( 'feedback', 'general_tab', 'Основное' ),
			a4_remont_acf_field(
				'feedback',
				'review_type',
				'Формат отзыва',
				'button_group',
				array(
					'choices'       => array(
						'text'  => 'Текстовый отзыв',
						'photo' => 'Фото-отзыв',
						'video' => 'Видео-отзыв',
					),
					'default_value' => 'text',
					'instructions'  => 'Выберите основной формат, в котором отзыв должен выводиться на сайте.',
				)
			),
			a4_remont_acf_message(
				'feedback',
				'type_hint_text',
				'Сейчас выбран текстовый отзыв',
				'Заполните <strong>заголовок</strong>, при необходимости <strong>excerpt</strong> и основной <strong>текст записи</strong>. Этот формат используется для карточек отзывов в секциях showcase.',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $review_type_key,
								'operator' => '==',
								'value'    => 'text',
							),
						),
					),
				)
			),
			a4_remont_acf_message(
				'feedback',
				'type_hint_photo',
				'Сейчас выбран фото-отзыв',
				'Ниже заполните как минимум поле <strong>Фото отзыва</strong>. Эта запись попадет в фотосетку на странице <code>/reviews/</code>.',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $review_type_key,
								'operator' => '==',
								'value'    => 'photo',
							),
						),
					),
				)
			),
			a4_remont_acf_message(
				'feedback',
				'type_hint_video',
				'Сейчас выбран видео-отзыв',
				'Ниже заполните как минимум поле <strong>Ссылка на видео</strong>. Превью желательно добавить, чтобы карточка на странице <code>/reviews/</code> выглядела аккуратно.',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $review_type_key,
								'operator' => '==',
								'value'    => 'video',
							),
						),
					),
				)
			),
			a4_remont_acf_field(
				'feedback',
				'rating',
				'Оценка',
				'number',
				array(
					'default_value' => 5,
					'min'           => 1,
					'max'           => 5,
					'wrapper'       => array( 'width' => 50 ),
				)
			),
			a4_remont_acf_field(
				'feedback',
				'review_date',
				'Дата отзыва',
				'date_picker',
				array(
					'display_format' => 'd.m.Y',
					'return_format'  => 'Y-m-d',
					'wrapper'        => array( 'width' => 50 ),
				)
			),
			a4_remont_acf_tab( 'feedback', 'text_tab', 'Текстовый отзыв' ),
			a4_remont_acf_message(
				'feedback',
				'text_note',
				'Подсказка по текстовому отзыву',
				'Для текстового отзыва используйте содержимое записи WordPress или excerpt. Эти данные автоматически попадут в карточки отзывов на главной, about и других секциях showcase.',
				array(
					'conditional_logic' => array(
						array(
							array(
								'field'    => $review_type_key,
								'operator' => '==',
								'value'    => 'text',
							),
						),
					),
				)
			),
			a4_remont_acf_tab( 'feedback', 'photo_tab', 'Фото-отзыв' ),
			a4_remont_acf_field(
				'feedback',
				'photo_image',
				'Фото отзыва',
				'image',
				array(
					'return_format'     => 'array',
					'preview_size'      => 'medium',
					'instructions'      => 'Основное изображение, которое будет показано в фотосетке на странице /reviews/.',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $review_type_key,
								'operator' => '==',
								'value'    => 'photo',
							),
						),
					),
				)
			),
			a4_remont_acf_field(
				'feedback',
				'photo_full_image',
				'Полноразмерное изображение',
				'image',
				array(
					'return_format'     => 'array',
					'preview_size'      => 'medium',
					'instructions'      => 'Необязательно. Если оставить пустым, по клику откроется то же изображение, что используется в сетке.',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $review_type_key,
								'operator' => '==',
								'value'    => 'photo',
							),
						),
					),
				)
			),
			a4_remont_acf_field(
				'feedback',
				'photo_caption',
				'Подпись к фото-отзыву',
				'textarea',
				array(
					'rows'              => 3,
					'instructions'      => 'Служебное поле для внутреннего описания или будущих подписей. На текущем фронтенде не выводится.',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $review_type_key,
								'operator' => '==',
								'value'    => 'photo',
							),
						),
					),
				)
			),
			a4_remont_acf_tab( 'feedback', 'video_tab', 'Видео-отзыв' ),
			a4_remont_acf_field(
				'feedback',
				'video_url',
				'Ссылка на видео',
				'url',
				array(
					'instructions'      => 'Вставьте прямую ссылку на YouTube, VK Видео, Rutube или другой источник, который должен открываться по клику.',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $review_type_key,
								'operator' => '==',
								'value'    => 'video',
							),
						),
					),
				)
			),
			a4_remont_acf_field(
				'feedback',
				'video_preview_image',
				'Превью видео',
				'image',
				array(
					'return_format'     => 'array',
					'preview_size'      => 'medium',
					'instructions'      => 'Обложка карточки видео-отзыва. Если не заполнить, тема попробует взять featured image записи.',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $review_type_key,
								'operator' => '==',
								'value'    => 'video',
							),
						),
					),
				)
			),
			a4_remont_acf_field(
				'feedback',
				'video_caption',
				'Подпись к видео-отзыву',
				'textarea',
				array(
					'rows'              => 3,
					'instructions'      => 'Служебное поле для внутреннего описания или будущих сценариев вывода. Сейчас на фронтенде не отображается.',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $review_type_key,
								'operator' => '==',
								'value'    => 'video',
							),
						),
					),
				)
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'feedback',
				),
			),
		),
		'position'              => 'acf_after_title',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	);

	$group_id    = a4_remont_get_acf_field_group_post_id( $field_group['key'] );
	$schema_hash = md5( wp_json_encode( $field_group ) );
	$option_key  = 'a4_remont_feedback_acf_schema_hash';

	if ( $group_id ) {
		$field_group['ID'] = $group_id;
	}

	if ( $group_id && get_option( $option_key ) === $schema_hash ) {
		return;
	}

	$imported = acf_import_field_group( $field_group );

	if ( is_array( $imported ) && ! empty( $imported['key'] ) ) {
		update_option( $option_key, $schema_hash, false );
	}
}
add_action( 'acf/init', 'a4_remont_sync_feedback_content_field_group', 90 );

/**
 * Get a human-readable feedback type label.
 *
 * @param string $type Feedback type slug.
 * @return string
 */
function a4_remont_get_feedback_type_label( $type ) {
	$labels = array(
		'text'  => 'Текстовый отзыв',
		'photo' => 'Фото-отзыв',
		'video' => 'Видео-отзыв',
	);

	return isset( $labels[ $type ] ) ? $labels[ $type ] : 'Текстовый отзыв';
}

/**
 * Get a compact feedback type badge label.
 *
 * @param string $type Feedback type slug.
 * @return string
 */
function a4_remont_get_feedback_type_badge_label( $type ) {
	$labels = array(
		'text'  => 'Текст',
		'photo' => 'Фото',
		'video' => 'Видео',
	);

	return isset( $labels[ $type ] ) ? $labels[ $type ] : 'Текст';
}

/**
 * Add feedback-specific columns to the admin list table.
 *
 * @param array<string,string> $columns Existing columns.
 * @return array<string,string>
 */
function a4_remont_feedback_admin_columns( $columns ) {
	$date_label = isset( $columns['date'] ) ? $columns['date'] : 'Дата';

	return array(
		'cb'            => isset( $columns['cb'] ) ? $columns['cb'] : '<input type="checkbox" />',
		'title'         => 'Отзыв / клиент',
		'feedback_type' => 'Формат',
		'rating'        => 'Оценка',
		'review_date'   => 'Дата отзыва',
		'date'          => $date_label,
	);
}
add_filter( 'manage_feedback_posts_columns', 'a4_remont_feedback_admin_columns' );

/**
 * Render custom feedback admin columns.
 *
 * @param string $column  Column name.
 * @param int    $post_id Post ID.
 * @return void
 */
function a4_remont_render_feedback_admin_column( $column, $post_id ) {
	if ( 'feedback_type' === $column ) {
		$type = function_exists( 'a4_remont_get_feedback_type' ) ? a4_remont_get_feedback_type( $post_id ) : 'text';
		printf(
			'<span class="a4-feedback-badge a4-feedback-badge--%1$s">%2$s</span>',
			esc_attr( $type ),
			esc_html( a4_remont_get_feedback_type_badge_label( $type ) )
		);
		return;
	}

	if ( 'rating' === $column ) {
		$rating = function_exists( 'a4_remont_get_feedback_rating' ) ? a4_remont_get_feedback_rating( $post_id ) : 5;
		echo esc_html( sprintf( '%d/5', (int) $rating ) );
		return;
	}

	if ( 'review_date' === $column ) {
		$review_date = function_exists( 'a4_remont_get_feedback_display_date' ) ? a4_remont_get_feedback_display_date( $post_id ) : '';
		echo esc_html( $review_date ? $review_date : '—' );
	}
}
add_action( 'manage_feedback_posts_custom_column', 'a4_remont_render_feedback_admin_column', 10, 2 );

/**
 * Make feedback admin columns sortable.
 *
 * @param array<string,string> $sortable_columns Sortable columns.
 * @return array<string,string>
 */
function a4_remont_feedback_sortable_columns( $sortable_columns ) {
	$sortable_columns['feedback_type'] = 'feedback_type';
	$sortable_columns['review_date']   = 'review_date';

	return $sortable_columns;
}
add_filter( 'manage_edit-feedback_sortable_columns', 'a4_remont_feedback_sortable_columns' );

/**
 * Add a type filter to the feedback admin list.
 *
 * @param string $post_type Current post type.
 * @return void
 */
function a4_remont_feedback_admin_type_filter( $post_type ) {
	if ( 'feedback' !== $post_type ) {
		return;
	}

	$current_value = isset( $_GET['feedback_type_filter'] ) ? sanitize_key( wp_unslash( $_GET['feedback_type_filter'] ) ) : '';
	?>
	<select name="feedback_type_filter" id="feedback-type-filter">
		<option value="">Все форматы</option>
		<option value="text" <?php selected( $current_value, 'text' ); ?>>Текстовые отзывы</option>
		<option value="photo" <?php selected( $current_value, 'photo' ); ?>>Фото-отзывы</option>
		<option value="video" <?php selected( $current_value, 'video' ); ?>>Видео-отзывы</option>
	</select>
	<?php
}
add_action( 'restrict_manage_posts', 'a4_remont_feedback_admin_type_filter' );

/**
 * Apply sorting and filtering to the feedback admin query.
 *
 * @param WP_Query $query Current admin query.
 * @return void
 */
function a4_remont_adjust_feedback_admin_query( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$post_type = $query->get( 'post_type' );

	if ( 'feedback' !== $post_type ) {
		return;
	}

	$filter_type = isset( $_GET['feedback_type_filter'] ) ? sanitize_key( wp_unslash( $_GET['feedback_type_filter'] ) ) : '';

	if ( in_array( $filter_type, array( 'text', 'photo', 'video' ), true ) ) {
		if ( 'text' === $filter_type ) {
			$query->set(
				'meta_query',
				array(
					'relation' => 'OR',
					array(
						'key'     => 'review_type',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'review_type',
						'value'   => 'text',
						'compare' => '=',
					),
				)
			);
		} else {
			$query->set(
				'meta_query',
				array(
					array(
						'key'     => 'review_type',
						'value'   => $filter_type,
						'compare' => '=',
					),
				)
			);
		}
	}

	$orderby = $query->get( 'orderby' );

	if ( 'feedback_type' === $orderby ) {
		$query->set( 'meta_key', 'review_type' );
		$query->set( 'orderby', 'meta_value' );
	}

	if ( 'review_date' === $orderby ) {
		$query->set( 'meta_key', 'review_date' );
		$query->set( 'orderby', 'meta_value' );
	}
}
add_action( 'pre_get_posts', 'a4_remont_adjust_feedback_admin_query' );

/**
 * Add compact post states for feedback rows in the admin list.
 *
 * @param array<int,string> $post_states Existing states.
 * @param WP_Post           $post        Current post.
 * @return array<int,string>
 */
function a4_remont_feedback_post_states( $post_states, $post ) {
	if ( ! $post instanceof WP_Post || 'feedback' !== $post->post_type ) {
		return $post_states;
	}

	$type          = function_exists( 'a4_remont_get_feedback_type' ) ? a4_remont_get_feedback_type( $post ) : 'text';
	$post_states[] = a4_remont_get_feedback_type_label( $type );

	return $post_states;
}
add_filter( 'display_post_states', 'a4_remont_feedback_post_states', 10, 2 );

/**
 * Improve the feedback post title placeholder.
 *
 * @param string  $placeholder Current placeholder.
 * @param WP_Post $post        Current post.
 * @return string
 */
function a4_remont_feedback_title_placeholder( $placeholder, $post ) {
	if ( $post instanceof WP_Post && 'feedback' === $post->post_type ) {
		return 'Например: Анна Петрова';
	}

	return $placeholder;
}
add_filter( 'enter_title_here', 'a4_remont_feedback_title_placeholder', 10, 2 );

/**
 * Print feedback admin styles for list table and edit screen.
 *
 * @return void
 */
function a4_remont_feedback_admin_styles() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'feedback' !== $screen->post_type ) {
		return;
	}
	?>
	<style>
		.column-feedback_type,
		.column-rating,
		.column-review_date {
			width: 120px;
		}

		.a4-feedback-badge {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-width: 68px;
			padding: 4px 10px;
			border-radius: 999px;
			font-size: 12px;
			font-weight: 600;
			line-height: 1.2;
		}

		.a4-feedback-badge--text {
			background: #e9eef5;
			color: #1d3557;
		}

		.a4-feedback-badge--photo {
			background: #f7ead0;
			color: #8a5a00;
		}

		.a4-feedback-badge--video {
			background: #efe1e8;
			color: #7c284f;
		}

		.post-type-feedback .acf-field[data-name="review_type"] .acf-button-group {
			display: grid;
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 8px;
		}

		.post-type-feedback .acf-field[data-name="review_type"] .acf-button-group label {
			border-radius: 10px;
			border: 1px solid #d4d8dd;
			padding: 10px 12px;
			text-align: center;
			font-weight: 600;
			background: #fff;
		}

		.post-type-feedback .acf-field[data-name="review_type"] .acf-button-group label.selected {
			border-color: #1d2327;
			box-shadow: inset 0 0 0 1px #1d2327;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'a4_remont_feedback_admin_styles' );

/**
 * Auto-switch feedback ACF tabs when the review type changes.
 *
 * @return void
 */
function a4_remont_feedback_admin_tab_switcher() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'feedback' !== $screen->post_type ) {
		return;
	}
	?>
	<script>
		(function () {
			const tabMap = {
				text: 'field_a4_remont_feedback_text_tab',
				photo: 'field_a4_remont_feedback_photo_tab',
				video: 'field_a4_remont_feedback_video_tab',
			};

			function activateTab(type) {
				const tabKey = tabMap[type];
				if (!tabKey) return;

				const tabButton = document.querySelector(
					'.acf-tab-wrap.-top a[data-key="' + tabKey + '"], .acf-tab-wrap.-left a[data-key="' + tabKey + '"]'
				);

				if (tabButton) {
					tabButton.click();
				}
			}

			function bindSwitcher() {
				const field = document.querySelector('.post-type-feedback .acf-field[data-name="review_type"]');
				if (!field) return;

				field.querySelectorAll('input[type="radio"]').forEach((input) => {
					if (input.dataset.a4FeedbackTabBound === '1') return;
					input.dataset.a4FeedbackTabBound = '1';

					input.addEventListener('change', function () {
						if (!this.checked) return;

						window.requestAnimationFrame(() => {
							activateTab(this.value);
						});
					});
				});

				field.querySelectorAll('.acf-button-group label').forEach((label) => {
					if (label.dataset.a4FeedbackClickBound === '1') return;
					label.dataset.a4FeedbackClickBound = '1';

					label.addEventListener('click', function () {
						const input = this.querySelector('input[type="radio"]');
						if (!input) return;

						window.requestAnimationFrame(() => {
							activateTab(input.value);
						});
					});
				});
			}

			if (window.acf && typeof window.acf.addAction === 'function') {
				window.acf.addAction('ready', bindSwitcher);
				window.acf.addAction('append', bindSwitcher);
			} else {
				document.addEventListener('DOMContentLoaded', bindSwitcher);
			}
		})();
	</script>
	<?php
}
add_action( 'admin_footer', 'a4_remont_feedback_admin_tab_switcher' );

/**
 * Build a feedback meta query for relationship pickers.
 *
 * @param string $type Feedback type slug.
 * @return array<int|string, array<string,string>>
 */
function a4_remont_get_feedback_relationship_meta_query( $type ) {
	$type = sanitize_key( $type );

	if ( 'text' === $type ) {
		return array(
			'relation' => 'OR',
			array(
				'key'     => 'review_type',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'review_type',
				'value'   => 'text',
				'compare' => '=',
			),
		);
	}

	if ( ! in_array( $type, array( 'photo', 'video' ), true ) ) {
		$type = 'text';
	}

	return array(
		array(
			'key'     => 'review_type',
			'value'   => $type,
			'compare' => '=',
		),
	);
}

/**
 * Apply a feedback type constraint to an ACF relationship field query.
 *
 * @param array<string,mixed> $args  Relationship query args.
 * @param string              $type  Feedback type slug.
 * @return array<string,mixed>
 */
function a4_remont_filter_feedback_relationship_query( $args, $type ) {
	$args['post_type']      = array( 'feedback' );
	$args['post_status']    = array( 'publish' );
	$args['posts_per_page'] = 40;
	$args['meta_query']     = a4_remont_get_feedback_relationship_meta_query( $type );

	return $args;
}

/**
 * Limit manual text review pickers to text feedback only.
 *
 * @param array<string,mixed> $args    Relationship query args.
 * @param array<string,mixed> $field   Field config.
 * @param int|string          $post_id Current post ID.
 * @return array<string,mixed>
 */
function a4_remont_filter_text_feedback_relationship_query( $args, $field, $post_id ) {
	unset( $field, $post_id );

	return a4_remont_filter_feedback_relationship_query( $args, 'text' );
}
add_filter( 'acf/fields/relationship/query/name=manual_feedback_items', 'a4_remont_filter_text_feedback_relationship_query', 10, 3 );

/**
 * Limit manual photo review pickers to photo feedback only.
 *
 * @param array<string,mixed> $args    Relationship query args.
 * @param array<string,mixed> $field   Field config.
 * @param int|string          $post_id Current post ID.
 * @return array<string,mixed>
 */
function a4_remont_filter_photo_feedback_relationship_query( $args, $field, $post_id ) {
	unset( $field, $post_id );

	return a4_remont_filter_feedback_relationship_query( $args, 'photo' );
}
add_filter( 'acf/fields/relationship/query/name=manual_photo_items', 'a4_remont_filter_photo_feedback_relationship_query', 10, 3 );

/**
 * Limit manual video review pickers to video feedback only.
 *
 * @param array<string,mixed> $args    Relationship query args.
 * @param array<string,mixed> $field   Field config.
 * @param int|string          $post_id Current post ID.
 * @return array<string,mixed>
 */
function a4_remont_filter_video_feedback_relationship_query( $args, $field, $post_id ) {
	unset( $field, $post_id );

	return a4_remont_filter_feedback_relationship_query( $args, 'video' );
}
add_filter( 'acf/fields/relationship/query/name=manual_video_items', 'a4_remont_filter_video_feedback_relationship_query', 10, 3 );

/**
 * Decorate feedback relationship picker results with a type marker.
 *
 * @param string              $title  Current result title.
 * @param WP_Post             $post   Related post object.
 * @param array<string,mixed> $field  Field config.
 * @param int|string          $post_id Current post ID.
 * @return string
 */
function a4_remont_feedback_relationship_result_label( $title, $post, $field, $post_id ) {
	unset( $field, $post_id );

	if ( ! $post instanceof WP_Post || 'feedback' !== $post->post_type ) {
		return $title;
	}

	$type = function_exists( 'a4_remont_get_feedback_type' ) ? a4_remont_get_feedback_type( $post ) : 'text';

	return sprintf(
		'%1$s [%2$s]',
		$title,
		a4_remont_get_feedback_type_badge_label( $type )
	);
}
add_filter( 'acf/fields/relationship/result/name=manual_feedback_items', 'a4_remont_feedback_relationship_result_label', 10, 4 );
add_filter( 'acf/fields/relationship/result/name=manual_photo_items', 'a4_remont_feedback_relationship_result_label', 10, 4 );
add_filter( 'acf/fields/relationship/result/name=manual_video_items', 'a4_remont_feedback_relationship_result_label', 10, 4 );
