<?php
/**
 * ACF builders for single work pages.
 *
 * @package a4-remont
 */

/**
 * Return the layout map for the single work builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_work_single_section_map() {
	return array(
		'work_single_hero'      => 'work-single-hero',
		'work_single_performed' => 'work-single-performed',
		'work_single_result'    => 'work-single-result',
		'work_single_cta_form'  => array(
			'template' => 'cta-form',
			'args'     => array(
				'fallback_partial' => 'section/work-single/cta-form-2.html',
			),
		),
	);
}

/**
 * Return ACF layouts for single work pages.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_work_single_layouts() {
	return array(
		a4_remont_acf_layout(
			'work_single_hero',
			'Первый экран проекта',
			array(
				a4_remont_acf_message(
					'work_single_hero',
					'guide',
					'Как работает секция',
					'Если оставить заголовок, лид или главное изображение пустыми, секция сможет использовать данные самой записи: заголовок работы, краткое описание и миниатюру записи.'
				),
				a4_remont_acf_tab( 'work_single_hero', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'work_single_hero', 'title', 'Заголовок H1', 'textarea', array( 'rows' => 2, 'instructions' => 'Можно оставить пустым, тогда будет использован заголовок записи работы.' ) ),
				a4_remont_acf_field( 'work_single_hero', 'lead', 'Подводка', 'textarea', array( 'rows' => 4, 'instructions' => 'Если оставить пустым, будет использовано краткое описание записи.' ) ),
				a4_remont_acf_tab( 'work_single_hero', 'facts_tab', 'Факты проекта' ),
				a4_remont_acf_field(
					'work_single_hero',
					'facts',
					'Факты проекта',
					'repeater',
					array(
						'button_label' => 'Добавить факт',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'work_single_hero', 'fact_label' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'work_single_hero', 'fact_label', 'Название факта', 'text', array( 'wrapper' => array( 'width' => 40 ) ) ),
							a4_remont_acf_field( 'work_single_hero', 'fact_value', 'Значение', 'text', array( 'wrapper' => array( 'width' => 60 ) ) ),
						),
					)
				),
				a4_remont_acf_tab( 'work_single_hero', 'details_tab', 'Описание проекта' ),
				a4_remont_acf_field( 'work_single_hero', 'info_title', 'Заголовок блока с описанием', 'text', array( 'default_value' => 'Общая информация о проекте' ) ),
				a4_remont_acf_field(
					'work_single_hero',
					'info_content',
					'Текст о проекте',
					'wysiwyg',
					array(
						'tabs'         => 'visual',
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'instructions' => 'Основной развернутый текст о проекте. На мобильных он сворачивается и раскрывается кнопкой "...ещё".',
					)
				),
				a4_remont_acf_tab( 'work_single_hero', 'media_tab', 'Медиа' ),
				a4_remont_acf_field( 'work_single_hero', 'image', 'Главное изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'Если не заполнять, будет использована миниатюра записи работы.' ) ),
				a4_remont_acf_tab( 'work_single_hero', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'work_single_hero', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'work_single_performed',
			'Выполненные работы',
			array(
				a4_remont_acf_tab( 'work_single_performed', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'work_single_performed', 'section_title', 'Заголовок секции', 'text', array( 'default_value' => 'Какие работы были выполнены' ) ),
				a4_remont_acf_field( 'work_single_performed', 'section_text', 'Основной текст', 'textarea', array( 'rows' => 6 ) ),
				a4_remont_acf_field( 'work_single_performed', 'section_text_strong', 'Акцентный абзац', 'textarea', array( 'rows' => 5, 'instructions' => 'Выделенный абзац в правой колонке.' ) ),
				a4_remont_acf_tab( 'work_single_performed', 'list_tab', 'Список работ' ),
				a4_remont_acf_field( 'work_single_performed', 'list_title', 'Заголовок списка', 'text' ),
				a4_remont_acf_field(
					'work_single_performed',
					'items',
					'Пункты списка',
					'repeater',
					array(
						'button_label' => 'Добавить пункт',
						'layout'       => 'table',
						'collapsed'    => a4_remont_acf_key( 'work_single_performed', 'item_text' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'work_single_performed', 'item_text', 'Текст пункта', 'text' ),
						),
					)
				),
				a4_remont_acf_tab( 'work_single_performed', 'media_tab', 'Изображения и галерея' ),
				a4_remont_acf_field( 'work_single_performed', 'main_image', 'Главное изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'work_single_performed', 'secondary_image', 'Второе изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'work_single_performed', 'gallery', 'Галерея слайдера', 'gallery', array( 'preview_size' => 'medium', 'instructions' => 'Миниатюры в нижнем слайдере. Лучше использовать 4-8 изображений.' ) ),
				a4_remont_acf_tab( 'work_single_performed', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'work_single_performed', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'work_single_result',
			'Результат работ',
			array(
				a4_remont_acf_message(
					'work_single_result',
					'guide',
					'Как работает секция',
					'Фоновое изображение лучше загружать отдельно для десктопа и мобильного. Если мобильное изображение не задано, на всех устройствах используется основное.'
				),
				a4_remont_acf_tab( 'work_single_result', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'work_single_result', 'title', 'Заголовок секции', 'text', array( 'default_value' => 'Результат работ' ) ),
				a4_remont_acf_field(
					'work_single_result',
					'content',
					'Текст результата',
					'wysiwyg',
					array(
						'tabs'         => 'visual',
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'instructions' => 'Основной текст блока. На мобильных он раскрывается кнопкой "...ещё".',
					)
				),
				a4_remont_acf_tab( 'work_single_result', 'brand_tab', 'Бренд' ),
				a4_remont_acf_field( 'work_single_result', 'brand_image', 'Логотип', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'work_single_result', 'brand_text', 'Текстовый логотип', 'text', array( 'wrapper' => array( 'width' => 50 ), 'instructions' => 'Используется, если не задан логотип изображением.' ) ),
				a4_remont_acf_tab( 'work_single_result', 'media_tab', 'Фон' ),
				a4_remont_acf_field( 'work_single_result', 'background_image', 'Фоновое изображение (десктоп)', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'work_single_result', 'background_image_mobile', 'Фоновое изображение (мобильное)', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_tab( 'work_single_result', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'work_single_result', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'work_single_cta_form',
			'Форма захвата',
			array(
				a4_remont_acf_message(
					'work_single_cta_form',
					'guide',
					'Как работает секция',
					'Если ввести shortcode формы, на фронтенде будет выведена реальная форма плагина. Если shortcode оставить пустым, тема покажет встроенный fallback-шаблон.'
				),
				a4_remont_acf_tab( 'work_single_cta_form', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'work_single_cta_form', 'title', 'Заголовок', 'textarea', array( 'rows' => 2 ) ),
				a4_remont_acf_field( 'work_single_cta_form', 'lead', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'work_single_cta_form', 'brand_tab', 'Брендинг' ),
				a4_remont_acf_field( 'work_single_cta_form', 'brand_image', 'Логотип или изображение бренда', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_field( 'work_single_cta_form', 'brand_text', 'Текстовый логотип', 'text' ),
				a4_remont_acf_tab( 'work_single_cta_form', 'form_tab', 'Форма и подписи' ),
				a4_remont_acf_field( 'work_single_cta_form', 'form_shortcode', 'Shortcode формы', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'work_single_cta_form', 'name_placeholder', 'Плейсхолдер поля "Имя"', 'text', array( 'default_value' => 'Ваше имя', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'work_single_cta_form', 'phone_placeholder', 'Плейсхолдер поля "Телефон"', 'text', array( 'default_value' => '+7 000 000 00 00', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'work_single_cta_form', 'email_placeholder', 'Плейсхолдер поля "E-mail"', 'text', array( 'default_value' => 'E-mail', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'work_single_cta_form', 'message_placeholder', 'Плейсхолдер поля "Сообщение"', 'text', array( 'default_value' => 'Сообщение', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field(
					'work_single_cta_form',
					'agreement_text',
					'Текст согласия',
					'wysiwyg',
					array(
						'tabs'         => 'visual',
						'toolbar'      => 'basic',
						'media_upload' => 0,
					)
				),
				a4_remont_acf_field( 'work_single_cta_form', 'submit_label', 'Текст кнопки', 'text', array( 'default_value' => 'Отправить' ) ),
				a4_remont_acf_tab( 'work_single_cta_form', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'work_single_cta_form', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync single work field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_work_single_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_work_single_sections',
		'title'                 => 'Страница работы',
		'fields'                => array(
			a4_remont_acf_message(
				'work_single',
				'guide',
				'Как работать со страницей работы',
				'<strong>Рекомендуемый порядок заполнения:</strong><ol><li>Введите название работы в стандартный заголовок записи.</li><li>Заполните миниатюру записи и краткое описание: они используются в карточках архива и могут работать как fallback для первого экрана.</li><li>Ниже собирайте страницу через Flexible Content и меняйте порядок секций drag-and-drop.</li><li>Если часть полей внутри секций оставить пустой, тема по возможности использует fallback из записи или из статической верстки.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_work_sections',
				'label'        => 'Секции страницы работы',
				'name'         => 'work_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Добавляйте секции, раскрывайте вкладки и настраивайте контент. Порядок секций можно менять перетаскиванием.',
				'layouts'      => a4_remont_get_work_single_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'work',
				),
			),
		),
		'position'              => 'acf_after_title',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => array(
			'the_content',
			'discussion',
			'comments',
			'custom_fields',
		),
		'active'                => true,
	);

	$group_id    = a4_remont_get_acf_field_group_post_id( $field_group['key'] );
	$schema_hash = md5( wp_json_encode( $field_group ) );
	$option_key  = 'a4_remont_work_single_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_work_single_field_group', 45 );

/**
 * Render flexible sections for a single work page.
 *
 * @return bool
 */
function a4_remont_render_work_single_sections() {
	return a4_remont_render_mapped_flexible_sections( 'work_sections', a4_remont_get_work_single_section_map() );
}

/**
 * Render static fallback sections for a single work page.
 *
 * @return bool
 */
function a4_remont_render_default_work_single_sections() {
	return a4_remont_render_mapped_default_sections( a4_remont_get_work_single_section_map() );
}

/**
 * Render an admin-only edit button for the current work.
 *
 * @return void
 */
function a4_remont_render_work_single_edit_link() {
	if ( ! is_singular( 'work' ) || ! is_user_logged_in() || ! current_user_can( 'edit_post', get_the_ID() ) ) {
		return;
	}

	edit_post_link(
		'Редактировать работу',
		'<div class="a4-remont-editor-link _container">',
		'</div>',
		get_the_ID(),
		'btn btn--grey a4-remont-editor-link__button'
	);
}

/**
 * Render a single work page with flexible sections and fallback markup.
 *
 * @return void
 */
function a4_remont_render_work_single_content() {
	a4_remont_render_work_single_edit_link();

	$has_sections = a4_remont_render_work_single_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_work_single_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'work-entry _container' ); ?>>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>

		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div>
	</article>
	<?php
}
