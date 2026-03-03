<?php
/**
 * Custom content types.
 *
 * @package a4-remont
 */

/**
 * Register project content types.
 *
 * @return void
 */
function a4_remont_register_content_types() {
	register_taxonomy(
		'service_category',
		array( 'service' ),
		array(
			'labels'            => array(
				'name'                       => 'Категории услуг',
				'singular_name'              => 'Категория услуги',
				'search_items'               => 'Найти категорию услуги',
				'all_items'                  => 'Все категории услуг',
				'parent_item'                => 'Родительская категория',
				'parent_item_colon'          => 'Родительская категория:',
				'edit_item'                  => 'Редактировать категорию',
				'update_item'                => 'Обновить категорию',
				'add_new_item'               => 'Добавить категорию',
				'new_item_name'              => 'Название категории',
				'menu_name'                  => 'Категории услуг',
				'not_found'                  => 'Категории не найдены',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'service-category',
				'with_front' => false,
			),
		)
	);

	register_post_type(
		'service',
		array(
			'labels'             => array(
				'name'                  => 'Услуги',
				'singular_name'         => 'Услуга',
				'add_new'               => 'Добавить услугу',
				'add_new_item'          => 'Добавить услугу',
				'edit_item'             => 'Редактировать услугу',
				'new_item'              => 'Новая услуга',
				'view_item'             => 'Открыть услугу',
				'view_items'            => 'Открыть услуги',
				'search_items'          => 'Найти услугу',
				'not_found'             => 'Услуги не найдены',
				'not_found_in_trash'    => 'В корзине услуг нет',
				'all_items'             => 'Все услуги',
				'archives'              => 'Архив услуг',
				'attributes'            => 'Атрибуты услуги',
				'insert_into_item'      => 'Вставить в услугу',
				'uploaded_to_this_item' => 'Загружено для услуги',
				'menu_name'             => 'Услуги',
				'filter_items_list'     => 'Фильтр списка услуг',
				'items_list'            => 'Список услуг',
				'item_published'        => 'Услуга опубликована',
				'item_updated'          => 'Услуга обновлена',
			),
			'public'             => true,
			'menu_icon'          => 'dashicons-hammer',
			'show_in_rest'       => true,
			'has_archive'        => true,
			'rewrite'            => array(
				'slug'       => 'services',
				'with_front' => false,
			),
			'supports'           => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions',
			),
			'taxonomies'         => array( 'service_category' ),
			'show_in_nav_menus'  => true,
			'show_in_admin_bar'  => true,
			'publicly_queryable' => true,
		)
	);

	register_taxonomy(
		'news_category',
		array( 'news' ),
		array(
			'labels'            => array(
				'name'                       => 'Категории новостей',
				'singular_name'              => 'Категория новости',
				'search_items'               => 'Найти категорию новости',
				'all_items'                  => 'Все категории новостей',
				'parent_item'                => 'Родительская категория',
				'parent_item_colon'          => 'Родительская категория:',
				'edit_item'                  => 'Редактировать категорию',
				'update_item'                => 'Обновить категорию',
				'add_new_item'               => 'Добавить категорию',
				'new_item_name'              => 'Название категории',
				'menu_name'                  => 'Категории новостей',
				'not_found'                  => 'Категории не найдены',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'news-category',
				'with_front' => false,
			),
		)
	);

	register_post_type(
		'news',
		array(
			'labels'             => array(
				'name'                  => 'Новости',
				'singular_name'         => 'Новость',
				'add_new'               => 'Добавить новость',
				'add_new_item'          => 'Добавить новость',
				'edit_item'             => 'Редактировать новость',
				'new_item'              => 'Новая новость',
				'view_item'             => 'Открыть новость',
				'view_items'            => 'Открыть новости',
				'search_items'          => 'Найти новость',
				'not_found'             => 'Новости не найдены',
				'not_found_in_trash'    => 'В корзине новостей нет',
				'all_items'             => 'Все новости',
				'archives'              => 'Архив новостей',
				'attributes'            => 'Атрибуты новости',
				'insert_into_item'      => 'Вставить в новость',
				'uploaded_to_this_item' => 'Загружено для новости',
				'menu_name'             => 'Новости',
				'filter_items_list'     => 'Фильтр списка новостей',
				'items_list'            => 'Список новостей',
				'item_published'        => 'Новость опубликована',
				'item_updated'          => 'Новость обновлена',
			),
			'public'             => true,
			'menu_icon'          => 'dashicons-megaphone',
			'show_in_rest'       => true,
			'has_archive'        => true,
			'rewrite'            => array(
				'slug'       => 'news',
				'with_front' => false,
			),
			'supports'           => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions',
			),
			'taxonomies'         => array( 'news_category' ),
			'show_in_nav_menus'  => true,
			'show_in_admin_bar'  => true,
			'publicly_queryable' => true,
		)
	);

	register_taxonomy(
		'work_category',
		array( 'work' ),
		array(
			'labels'            => array(
				'name'                       => 'Категории работ',
				'singular_name'              => 'Категория работы',
				'search_items'               => 'Найти категорию работы',
				'all_items'                  => 'Все категории работ',
				'parent_item'                => 'Родительская категория',
				'parent_item_colon'          => 'Родительская категория:',
				'edit_item'                  => 'Редактировать категорию',
				'update_item'                => 'Обновить категорию',
				'add_new_item'               => 'Добавить категорию',
				'new_item_name'              => 'Название категории',
				'menu_name'                  => 'Категории работ',
				'not_found'                  => 'Категории не найдены',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'work-category',
				'with_front' => false,
			),
		)
	);

	register_post_type(
		'work',
		array(
			'labels'             => array(
				'name'                  => 'Работы',
				'singular_name'         => 'Работа',
				'add_new'               => 'Добавить работу',
				'add_new_item'          => 'Добавить работу',
				'edit_item'             => 'Редактировать работу',
				'new_item'              => 'Новая работа',
				'view_item'             => 'Открыть работу',
				'view_items'            => 'Открыть работы',
				'search_items'          => 'Найти работу',
				'not_found'             => 'Работы не найдены',
				'not_found_in_trash'    => 'В корзине работ нет',
				'all_items'             => 'Все работы',
				'archives'              => 'Архив работ',
				'attributes'            => 'Атрибуты работы',
				'insert_into_item'      => 'Вставить в работу',
				'uploaded_to_this_item' => 'Загружено для работы',
				'menu_name'             => 'Работы',
				'filter_items_list'     => 'Фильтр списка работ',
				'items_list'            => 'Список работ',
				'item_published'        => 'Работа опубликована',
				'item_updated'          => 'Работа обновлена',
			),
			'public'             => true,
			'menu_icon'          => 'dashicons-portfolio',
			'show_in_rest'       => true,
			'has_archive'        => 'our-works',
			'rewrite'            => array(
				'slug'       => 'our-works',
				'with_front' => false,
			),
			'supports'           => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions',
			),
			'taxonomies'         => array( 'work_category' ),
			'show_in_nav_menus'  => true,
			'show_in_admin_bar'  => true,
			'publicly_queryable' => true,
		)
	);

	register_post_type(
		'feedback',
		array(
			'labels'             => array(
				'name'                  => 'Отзывы',
				'singular_name'         => 'Отзыв',
				'add_new'               => 'Добавить отзыв',
				'add_new_item'          => 'Добавить отзыв',
				'edit_item'             => 'Редактировать отзыв',
				'new_item'              => 'Новый отзыв',
				'view_item'             => 'Открыть отзыв',
				'view_items'            => 'Открыть отзывы',
				'search_items'          => 'Найти отзыв',
				'not_found'             => 'Отзывы не найдены',
				'not_found_in_trash'    => 'В корзине отзывов нет',
				'all_items'             => 'Все отзывы',
				'archives'              => 'Архив отзывов',
				'attributes'            => 'Атрибуты отзыва',
				'insert_into_item'      => 'Вставить в отзыв',
				'uploaded_to_this_item' => 'Загружено для отзыва',
				'menu_name'             => 'Отзывы',
				'filter_items_list'     => 'Фильтр списка отзывов',
				'items_list'            => 'Список отзывов',
				'item_published'        => 'Отзыв опубликован',
				'item_updated'          => 'Отзыв обновлен',
			),
			'public'             => true,
			'menu_icon'          => 'dashicons-format-quote',
			'show_in_rest'       => true,
			'has_archive'        => 'reviews',
			'rewrite'            => array(
				'slug'       => 'reviews',
				'with_front' => false,
			),
			'supports'           => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions',
			),
			'show_in_nav_menus'  => true,
			'show_in_admin_bar'  => true,
			'publicly_queryable' => true,
		)
	);
}
add_action( 'init', 'a4_remont_register_content_types', 5 );

/**
 * Ensure required default terms exist for the homepage previews.
 *
 * @return void
 */
function a4_remont_ensure_default_content_terms() {
	if ( taxonomy_exists( 'service_category' ) ) {
		$service_terms = array(
			'repair' => 'Ремонт',
			'design' => 'Дизайн',
		);

		foreach ( $service_terms as $slug => $name ) {
			if ( ! term_exists( $slug, 'service_category' ) ) {
				wp_insert_term(
					$name,
					'service_category',
					array(
						'slug' => $slug,
					)
				);
			}
		}
	}

	if ( taxonomy_exists( 'news_category' ) && ! term_exists( 'company-news', 'news_category' ) ) {
		wp_insert_term(
			'Новости компании',
			'news_category',
			array(
				'slug' => 'company-news',
			)
		);
	}
}
add_action( 'init', 'a4_remont_ensure_default_content_terms', 20 );

/**
 * Flush rewrite rules after theme activation.
 *
 * @return void
 */
function a4_remont_flush_rewrite_rules_on_theme_switch() {
	a4_remont_register_content_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'a4_remont_flush_rewrite_rules_on_theme_switch' );

/**
 * Flush rewrite rules once after content type changes.
 *
 * @return void
 */
function a4_remont_maybe_flush_content_type_rewrite_rules() {
	$schema_version = '2026-03-03-work-archive';
	$option_key     = 'a4_remont_content_types_schema_version';

	if ( get_option( $option_key ) === $schema_version ) {
		return;
	}

	a4_remont_register_content_types();
	flush_rewrite_rules( false );
	update_option( $option_key, $schema_version, false );
}
add_action( 'init', 'a4_remont_maybe_flush_content_type_rewrite_rules', 99 );
