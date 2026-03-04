<?php
/**
 * Admin documentation hub for project editors and maintainers.
 *
 * @package a4-remont
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register documentation page in the admin menu.
 *
 * @return void
 */
function a4_remont_register_admin_docs_page() {
	add_menu_page(
		'Инструкции по сайту',
		'Инструкции',
		'edit_pages',
		'a4-remont-docs',
		'a4_remont_render_admin_docs_page',
		'dashicons-welcome-learn-more',
		62
	);
}
add_action( 'admin_menu', 'a4_remont_register_admin_docs_page' );

/**
 * Enqueue assets for the documentation screen.
 *
 * @param string $hook_suffix Current admin page hook.
 * @return void
 */
function a4_remont_enqueue_admin_docs_assets( $hook_suffix ) {
	if ( 'toplevel_page_a4-remont-docs' !== $hook_suffix ) {
		return;
	}

	wp_enqueue_style(
		'a4-remont-admin-docs',
		get_template_directory_uri() . '/assets/css/admin-docs.css',
		array(),
		A4_REMONT_THEME_VERSION
	);

	wp_enqueue_script(
		'a4-remont-admin-docs',
		get_template_directory_uri() . '/assets/js/admin-docs.js',
		array(),
		A4_REMONT_THEME_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'a4_remont_enqueue_admin_docs_assets' );

/**
 * Return a page ID by slug when it exists.
 *
 * @param string $slug Page slug.
 * @return int
 */
function a4_remont_admin_docs_get_page_id_by_slug( $slug ) {
	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post ) {
		return (int) $page->ID;
	}

	return 0;
}

/**
 * Return a safe page edit URL.
 *
 * @param string $slug Page slug.
 * @return string
 */
function a4_remont_admin_docs_get_page_edit_url( $slug ) {
	$page_id = a4_remont_admin_docs_get_page_id_by_slug( $slug );

	if ( $page_id > 0 ) {
		return (string) get_edit_post_link( $page_id, '' );
	}

	return admin_url( 'edit.php?post_type=page' );
}

/**
 * Return a public page URL with a predictable fallback.
 *
 * @param string $slug Page slug.
 * @return string
 */
function a4_remont_admin_docs_get_page_public_url( $slug ) {
	$page_id = a4_remont_admin_docs_get_page_id_by_slug( $slug );

	if ( $page_id > 0 ) {
		return (string) get_permalink( $page_id );
	}

	return home_url( '/' . trim( (string) $slug, '/' ) . '/' );
}

/**
 * Return edit URL for the static front page if assigned.
 *
 * @return string
 */
function a4_remont_admin_docs_get_front_page_edit_url() {
	$page_id = (int) get_option( 'page_on_front' );

	if ( $page_id > 0 ) {
		return (string) get_edit_post_link( $page_id, '' );
	}

	return admin_url( 'options-reading.php' );
}

/**
 * Build a styled docs link.
 *
 * @param string $url      Link URL.
 * @param string $label    Link label.
 * @param bool   $external Whether to open in new tab.
 * @return string
 */
function a4_remont_admin_docs_link( $url, $label, $external = false ) {
	$attrs = $external ? ' target="_blank" rel="noopener noreferrer"' : '';

	return sprintf(
		'<a class="a4-docs-link" href="%1$s"%3$s>%2$s</a>',
		esc_url( $url ),
		esc_html( $label ),
		$attrs
	);
}

/**
 * Return frequently used admin and frontend links for the docs page.
 *
 * @return array<string,string>
 */
function a4_remont_admin_docs_get_links() {
	return array(
		'pages_list'           => admin_url( 'edit.php?post_type=page' ),
		'page_new'             => admin_url( 'post-new.php?post_type=page' ),
		'front_page_edit'      => a4_remont_admin_docs_get_front_page_edit_url(),
		'about_edit'           => a4_remont_admin_docs_get_page_edit_url( 'about-us' ),
		'contacts_edit'        => a4_remont_admin_docs_get_page_edit_url( 'contacts' ),
		'faq_edit'             => a4_remont_admin_docs_get_page_edit_url( 'faq' ),
		'payment_edit'         => a4_remont_admin_docs_get_page_edit_url( 'payment-delivery' ),
		'privacy_edit'         => a4_remont_admin_docs_get_page_edit_url( 'privacy-policy' ),
		'services_list'        => admin_url( 'edit.php?post_type=service' ),
		'service_new'          => admin_url( 'post-new.php?post_type=service' ),
		'service_categories'   => admin_url( 'edit-tags.php?taxonomy=service_category&post_type=service' ),
		'service_archive'      => admin_url( 'admin.php?page=' . a4_remont_get_service_archive_options_slug() ),
		'news_list'            => admin_url( 'edit.php?post_type=news' ),
		'news_new'             => admin_url( 'post-new.php?post_type=news' ),
		'news_categories'      => admin_url( 'edit-tags.php?taxonomy=news_category&post_type=news' ),
		'news_archive'         => admin_url( 'admin.php?page=' . a4_remont_get_news_archive_options_slug() ),
		'works_list'           => admin_url( 'edit.php?post_type=work' ),
		'work_new'             => admin_url( 'post-new.php?post_type=work' ),
		'work_categories'      => admin_url( 'edit-tags.php?taxonomy=work_category&post_type=work' ),
		'works_archive'        => admin_url( 'admin.php?page=' . a4_remont_get_work_archive_options_slug() ),
		'feedback_list'        => admin_url( 'edit.php?post_type=feedback' ),
		'feedback_new'         => admin_url( 'post-new.php?post_type=feedback' ),
		'feedback_archive'     => admin_url( 'admin.php?page=' . a4_remont_get_feedback_archive_options_slug() ),
		'site_shell'           => admin_url( 'admin.php?page=' . a4_remont_get_site_shell_options_slug() ),
		'popups'               => admin_url( 'admin.php?page=' . a4_remont_get_popups_options_slug() ),
		'seo'                  => admin_url( 'admin.php?page=a4-remont-seo' ),
		'plugins'              => admin_url( 'plugins.php' ),
		'reading'              => admin_url( 'options-reading.php' ),
		'media'                => admin_url( 'upload.php' ),
		'front_home'           => home_url( '/' ),
		'front_about'          => a4_remont_admin_docs_get_page_public_url( 'about-us' ),
		'front_contacts'       => a4_remont_admin_docs_get_page_public_url( 'contacts' ),
		'front_faq'            => a4_remont_admin_docs_get_page_public_url( 'faq' ),
		'front_payment'        => a4_remont_admin_docs_get_page_public_url( 'payment-delivery' ),
		'front_privacy'        => a4_remont_admin_docs_get_page_public_url( 'privacy-policy' ),
		'front_services'       => home_url( '/services/' ),
		'front_news'           => home_url( '/news/' ),
		'front_works'          => home_url( '/our-works/' ),
		'front_reviews'        => home_url( '/reviews/' ),
		'front_search_example' => home_url( '/?s=ремонт' ),
	);
}

/**
 * Return quick links shown in the hero area.
 *
 * @return array<int,array<string,string>>
 */
function a4_remont_admin_docs_get_quick_links() {
	$links = a4_remont_admin_docs_get_links();

	return array(
		array(
			'label' => 'Страницы',
			'url'   => $links['pages_list'],
			'type'  => 'admin',
		),
		array(
			'label' => 'Услуги',
			'url'   => $links['services_list'],
			'type'  => 'admin',
		),
		array(
			'label' => 'Новости',
			'url'   => $links['news_list'],
			'type'  => 'admin',
		),
		array(
			'label' => 'Работы',
			'url'   => $links['works_list'],
			'type'  => 'admin',
		),
		array(
			'label' => 'Отзывы',
			'url'   => $links['feedback_list'],
			'type'  => 'admin',
		),
		array(
			'label' => 'Шапка и подвал',
			'url'   => $links['site_shell'],
			'type'  => 'admin',
		),
		array(
			'label' => 'Модальные окна',
			'url'   => $links['popups'],
			'type'  => 'admin',
		),
		array(
			'label' => 'SEO',
			'url'   => $links['seo'],
			'type'  => 'admin',
		),
		array(
			'label' => 'Плагины',
			'url'   => $links['plugins'],
			'type'  => 'admin',
		),
		array(
			'label' => 'Главная сайта',
			'url'   => $links['front_home'],
			'type'  => 'front',
		),
		array(
			'label' => 'Архив услуг',
			'url'   => $links['front_services'],
			'type'  => 'front',
		),
		array(
			'label' => 'Архив отзывов',
			'url'   => $links['front_reviews'],
			'type'  => 'front',
		),
	);
}

/**
 * Return sections for the user guide.
 *
 * @return array<int,array<string,mixed>>
 */
function a4_remont_get_user_guide_sections() {
	$links = a4_remont_admin_docs_get_links();

	return array(
		array(
			'id'    => 'start',
			'title' => 'С чего начать работу в админке',
			'lead'  => 'Этот раздел рассчитан на администратора или редактора, который впервые открыл админку именно этого сайта. Здесь описана не абстрактная WordPress-логика, а реальный порядок работы с текущим проектом.',
			'groups' => array(
				array(
					'title'   => 'Базовый маршрут по проекту',
					'ordered' => true,
					'items'   => array(
						'Для редактирования обычных страниц начните с ' . a4_remont_admin_docs_link( $links['pages_list'], 'раздела «Страницы»' ) . '. Примеры: ' . a4_remont_admin_docs_link( $links['front_page_edit'], 'главная' ) . ', ' . a4_remont_admin_docs_link( $links['about_edit'], 'страница «О нас»' ) . ', ' . a4_remont_admin_docs_link( $links['contacts_edit'], 'страница «Контакты»' ) . '.',
						'Для услуг используйте ' . a4_remont_admin_docs_link( $links['services_list'], 'раздел «Услуги»' ) . '. Для новостей, работ и отзывов есть отдельные разделы: ' . a4_remont_admin_docs_link( $links['news_list'], '«Новости»' ) . ', ' . a4_remont_admin_docs_link( $links['works_list'], '«Работы»' ) . ', ' . a4_remont_admin_docs_link( $links['feedback_list'], '«Отзывы»' ) . '.',
						'Общие элементы сайта не редактируются внутри страниц. Для этого существуют отдельные экраны: ' . a4_remont_admin_docs_link( $links['site_shell'], '«Шапка и подвал»' ) . ', ' . a4_remont_admin_docs_link( $links['popups'], '«Модальные окна»' ) . ' и ' . a4_remont_admin_docs_link( $links['seo'], 'раздел SEO' ) . '.',
						'После каждой правки нажимайте «Обновить» или «Опубликовать» и обязательно проверяйте результат на фронтенде. Для примера можно сразу открыть ' . a4_remont_admin_docs_link( $links['front_home'], 'главную страницу сайта', true ) . '.',
					),
				),
				array(
					'title' => 'Что важно понимать сразу',
					'items' => array(
						'Большая часть страниц и архивов в проекте собирается через ACF Flexible Content. Секции можно раскрывать, сворачивать, дублировать и менять местами drag-and-drop.',
						'Во многих шаблонах есть резервный вывод из статической верстки. Это помогает не развалить страницу, если часть полей пока пустая, но в боевой работе все ключевые поля все равно нужно заполнять.',
						'На сайте принята латиница в slug-ах. Если создаете новую страницу или запись, следите, чтобы URL был понятным и без кириллицы.',
					),
				),
			),
		),
		array(
			'id'    => 'pages',
			'title' => 'Обычные страницы и их шаблоны',
			'lead'  => 'Обычные страницы живут в разделе «Страницы», но почти каждая важная страница проекта использует собственный шаблон и свои ACF-секции.',
			'groups' => array(
				array(
					'title' => 'Основные страницы проекта',
					'items' => array(
						'Главная страница редактируется через ' . a4_remont_admin_docs_link( $links['front_page_edit'], 'назначенную статическую главную' ) . '. Если главная не назначена, сначала проверьте ' . a4_remont_admin_docs_link( $links['reading'], 'Настройки → Чтение' ) . '.',
						'Страница «О нас»: ' . a4_remont_admin_docs_link( $links['about_edit'], 'открыть в редакторе' ) . ' · ' . a4_remont_admin_docs_link( $links['front_about'], 'посмотреть на сайте', true ) . '.',
						'Страница «Контакты»: ' . a4_remont_admin_docs_link( $links['contacts_edit'], 'открыть в редакторе' ) . ' · ' . a4_remont_admin_docs_link( $links['front_contacts'], 'посмотреть на сайте', true ) . '.',
						'Страница FAQ: ' . a4_remont_admin_docs_link( $links['faq_edit'], 'открыть в редакторе' ) . ' · ' . a4_remont_admin_docs_link( $links['front_faq'], 'посмотреть на сайте', true ) . '.',
						'Страница «Оплата и гарантии»: ' . a4_remont_admin_docs_link( $links['payment_edit'], 'открыть в редакторе' ) . ' · ' . a4_remont_admin_docs_link( $links['front_payment'], 'посмотреть на сайте', true ) . '.',
						'Страница «Политика конфиденциальности»: ' . a4_remont_admin_docs_link( $links['privacy_edit'], 'открыть в редакторе' ) . ' · ' . a4_remont_admin_docs_link( $links['front_privacy'], 'посмотреть на сайте', true ) . '.',
					),
				),
				array(
					'title'   => 'Как редактировать страницу правильно',
					'ordered' => true,
					'items'   => array(
						'Откройте страницу через ' . a4_remont_admin_docs_link( $links['pages_list'], 'список страниц' ) . '.',
						'Проверьте справа шаблон страницы. Для ключевых страниц он должен соответствовать назначению страницы.',
						'Прокрутите экран ниже основного редактора до блока ACF с секциями. Именно здесь настраивается реальная структура экрана.',
						'Работайте секциями: раскрывайте нужный блок, заполняйте поля по русским подсказкам, при необходимости меняйте порядок секций мышкой.',
						'После сохранения откройте страницу на сайте и проверьте результат. Если нужно быстро убедиться, что шаблон применился правильно, используйте кнопку «Редактировать страницу» на фронтенде под админской панелью.',
					),
				),
				array(
					'title' => 'Служебные и особые страницы',
					'items' => array(
						'Страница 404 не создается как обычная страница в WordPress. Она обслуживается шаблоном темы автоматически.',
						'Страница результатов поиска тоже не редактируется как обычная страница. Проверить ее можно по примеру ' . a4_remont_admin_docs_link( $links['front_search_example'], 'поискового запроса', true ) . '.',
						'Для SEO-служебных экранов вроде 404 и поиска используйте общий раздел ' . a4_remont_admin_docs_link( $links['seo'], 'SEO' ) . ', а не список страниц.',
					),
				),
			),
		),
		array(
			'id'    => 'services',
			'title' => 'Услуги и их архив',
			'lead'  => 'Раздел услуг построен на отдельном типе записей. У каждой услуги есть своя карточка, внутренняя страница и отдельный архив /services/.',
			'groups' => array(
				array(
					'title' => 'Где редактировать услуги',
					'items' => array(
						'Сами услуги: ' . a4_remont_admin_docs_link( $links['services_list'], 'раздел «Услуги»' ) . '.',
						'Категории услуг: ' . a4_remont_admin_docs_link( $links['service_categories'], '«Категории услуг»' ) . '.',
						'Архив /services/: ' . a4_remont_admin_docs_link( $links['service_archive'], '«Услуги → Настройки архива»' ) . ' · ' . a4_remont_admin_docs_link( $links['front_services'], 'посмотреть архив на сайте', true ) . '.',
					),
				),
				array(
					'title'   => 'Как создать новую услугу',
					'ordered' => true,
					'items'   => array(
						'Если нужно, сначала создайте категорию в ' . a4_remont_admin_docs_link( $links['service_categories'], 'категориях услуг' ) . '.',
						'Добавьте новую запись через ' . a4_remont_admin_docs_link( $links['service_new'], '«Добавить услугу»' ) . '.',
						'Заполните название, краткое описание, основное содержимое, изображение записи и категории.',
						'Ниже заполните ACF-блок «Страница услуги». Это и есть конструктор внутренней страницы услуги.',
						'Проверьте, что карточка корректно смотрится в архиве услуг и при необходимости заполните SEO-поля записи.',
					),
				),
				array(
					'title' => 'Как работает архив услуг',
					'items' => array(
						'Архив услуг не является обычной страницей WordPress. Его не нужно искать среди страниц.',
						'Секции архива настраиваются в отдельном option-экране. Там задаются первый экран, поток услуг по категориям, отзывы, портфолио, контакты и другие блоки.',
						'Секция service-stream умеет работать и автоматически от категорий/записей, и в ручном режиме.',
					),
				),
			),
		),
		array(
			'id'    => 'news-works',
			'title' => 'Новости и работы',
			'lead'  => 'Новости и работы устроены похожим образом: у них есть отдельные архивы, внутренние записи и ACF-конструкторы для single-страниц.',
			'groups' => array(
				array(
					'title' => 'Новости',
					'items' => array(
						'Список новостей: ' . a4_remont_admin_docs_link( $links['news_list'], 'раздел «Новости»' ) . '.',
						'Категории новостей: ' . a4_remont_admin_docs_link( $links['news_categories'], 'категории новостей' ) . '.',
						'Архив /news/: ' . a4_remont_admin_docs_link( $links['news_archive'], 'настройки архива новостей' ) . ' · ' . a4_remont_admin_docs_link( $links['front_news'], 'посмотреть архив', true ) . '.',
						'У каждой новости есть свой экран ACF «Страница новости», который управляет внутренней страницей записи.',
					),
				),
				array(
					'title' => 'Работы',
					'items' => array(
						'Список работ: ' . a4_remont_admin_docs_link( $links['works_list'], 'раздел «Работы»' ) . '.',
						'Категории работ: ' . a4_remont_admin_docs_link( $links['work_categories'], 'категории работ' ) . '.',
						'Архив /our-works/: ' . a4_remont_admin_docs_link( $links['works_archive'], 'настройки архива работ' ) . ' · ' . a4_remont_admin_docs_link( $links['front_works'], 'посмотреть архив', true ) . '.',
						'У каждой работы есть свой экран ACF «Страница работы». Через него собирается кейс целиком.',
					),
				),
				array(
					'title' => 'Практический совет',
					'items' => array(
						'Для новостей и работ всегда заполняйте миниатюру записи. Она участвует сразу в карточках, архивах, предпросмотрах и метаданных.',
						'Если карточка записи выводится на главной или в превью-секции, особенно важно аккуратно заполнить заголовок, краткое описание и изображение.',
					),
				),
			),
		),
		array(
			'id'    => 'reviews',
			'title' => 'Отзывы: текст, фото и видео',
			'lead'  => 'Отзывы реализованы как один тип записей с тремя форматами: текстовый, фото-отзыв и видео-отзыв. В админке уже настроены подсказки, цветовые маркеры и фильтрация по формату.',
			'groups' => array(
				array(
					'title'   => 'Как создать отзыв',
					'ordered' => true,
					'items'   => array(
						'Откройте ' . a4_remont_admin_docs_link( $links['feedback_list'], 'раздел «Отзывы»' ) . ' и создайте новую запись через ' . a4_remont_admin_docs_link( $links['feedback_new'], '«Добавить отзыв»' ) . '.',
						'Выберите формат отзыва: текстовый, фото или видео.',
						'После выбора формата переходите во вкладку соответствующего типа. Интерфейс уже настроен так, чтобы кнопка формата переключала на нужную вкладку.',
						'Заполните только поля своего формата. Например, для видео-отзыва не нужно заполнять текстовые карточки и наоборот.',
						'Сохраните запись и проверьте, что она отображается в нужной секции или в архиве отзывов.',
					),
				),
				array(
					'title' => 'Где используется каждый формат',
					'items' => array(
						'Текстовые отзывы используются в общих секциях отзывов на разных страницах сайта.',
						'Фото- и видео-отзывы используются в архиве /reviews/: ' . a4_remont_admin_docs_link( $links['feedback_archive'], 'настройки архива отзывов' ) . ' · ' . a4_remont_admin_docs_link( $links['front_reviews'], 'посмотреть архив', true ) . '.',
						'Если в ручном выборе секции вы видите relationship-поле, список там уже фильтруется по формату. Фото-блоки показывают только фото-отзывы, видео-блоки — только видео.',
					),
				),
				array(
					'title' => 'Как не запутаться в отзывах',
					'items' => array(
						'В списке записей используйте колонку «Формат» и фильтр сверху таблицы, чтобы быстро находить нужный тип отзыва.',
						'Заголовок записи лучше делать понятным: имя клиента, объект или краткое описание, чтобы потом легче было выбирать отзыв в секциях.',
					),
				),
			),
		),
		array(
			'id'    => 'shell',
			'title' => 'Шапка, подвал и модальные окна',
			'lead'  => 'Глобальные элементы сайта редактируются не через страницы, а через отдельные экраны. Это защищает структуру сайта от дублирования и помогает держать единый источник данных.',
			'groups' => array(
				array(
					'title' => 'Шапка и подвал',
					'items' => array(
						'Экран ' . a4_remont_admin_docs_link( $links['site_shell'], '«Шапка и подвал»' ) . ' хранит навигацию, логотип, телефон, основную кнопку, соцссылки, юридические ссылки и реквизиты.',
						'Внутри экрана есть два уровня вкладок: отдельно для шапки и отдельно для подвала. Сначала выберите верхний раздел, затем настройте соответствующие внутренние поля.',
						'Меню в шапке не дублируется между desktop и mobile. Оно задается один раз и рендерится в обе версии автоматически.',
						'Для пунктов шапки можно создавать выпадающие меню с колонками и ссылками.',
					),
				),
				array(
					'title' => 'Модальные окна',
					'items' => array(
						'Глобальные попапы редактируются в ' . a4_remont_admin_docs_link( $links['popups'], 'разделе «Модальные окна»' ) . '.',
						'Сначала создайте само модальное окно: внутренний ключ, заголовок, описание, форму или shortcode.',
						'После этого в ACF-полях кнопок на страницах можно выбрать действие «Открыть модальное окно» и указать конкретный popup.',
						'Если кнопка должна вести на страницу, а не открывать окно, выбирайте обычный режим ссылки.',
					),
				),
			),
		),
		array(
			'id'    => 'forms-seo',
			'title' => 'Формы, SEO и служебные настройки',
			'lead'  => 'В проекте уже предусмотрены отдельные экраны и плагины для форм, отправки писем, метрик и базовой SEO-оптимизации. Ниже — безопасный порядок работы для администратора.',
			'groups' => array(
				array(
					'title' => 'Формы и заявки',
					'items' => array(
						'Формы на сайте обычно подключаются через shortcode Contact Form 7. Этот shortcode может быть указан в секции страницы, в архиве или в модальном окне.',
						'Если нужно заменить форму, сначала создайте или отредактируйте ее в плагине Contact Form 7, затем вставьте новый shortcode в соответствующее поле ACF.',
						'Для стабильной отправки почты на проекте должен быть настроен SMTP-плагин. Если письма не приходят, сначала проверяйте почтовую доставку, а не шаблоны страниц.',
						'Для боевых форм желательно держать антиспам и reCAPTCHA включенными, если это требуется на проекте.',
					),
				),
				array(
					'title' => 'SEO и метаданные',
					'items' => array(
						'Все базовые SEO-настройки собраны в отдельном экране ' . a4_remont_admin_docs_link( $links['seo'], '«SEO»' ) . '.',
						'Там настраиваются общие метаданные, системные экраны, архивы, конкретные страницы, robots, sitemap, редиректы и счетчики вроде Яндекс.Метрики.',
						'У страниц и записей также есть собственный SEO-блок внизу экрана редактирования. Для важных разделов лучше заполнять метаданные вручную.',
						'Не подключайте второй SEO-инструмент без согласования: один проект должен иметь один основной источник SEO-настроек.',
					),
				),
				array(
					'title' => 'Базовый набор плагинов проекта',
					'items' => array(
						'Обязательный минимум по регламенту: ACF Pro, Contact Form 7, SMTP-плагин, один SEO-инструмент, один cache-плагин и Classic Editor.',
						'Рекомендуемые рабочие плагины: Cyr-To-Lat, Post Duplicator, Better Search, а для временной диагностики — Query Monitor.',
						'Не отключайте плагины «на пробу» на живом сайте. Даже один выключенный плагин может убрать поля, формы, письма или часть технической логики.',
						'Проверить текущий состав можно через ' . a4_remont_admin_docs_link( $links['plugins'], 'экран «Плагины»' ) . '.',
					),
				),
			),
		),
		array(
			'id'    => 'media',
			'title' => 'Медиа, URL и чеклист публикации',
			'lead'  => 'Визуальные блоки на сайте очень зависят от качества изображений, правильных URL и аккуратной публикации. Этот раздел нужен как короткий редакторский чеклист.',
			'groups' => array(
				array(
					'title' => 'Медиафайлы',
					'items' => array(
						'Загружайте изображения через ' . a4_remont_admin_docs_link( $links['media'], 'медиабиблиотеку' ) . ' или прямо из полей ACF.',
						'Для фотографий используйте оптимизированные изображения, а для иконок и логотипов — SVG там, где это уместно.',
						'У проекта есть поддержка SVG для доверенных ролей, но даже SVG лучше загружать осознанно и только из проверенного источника.',
						'Миниатюра записи — это не формальность. Она влияет на карточки, архивы, предпросмотры и метаданные.',
					),
				),
				array(
					'title' => 'URL и slug-и',
					'items' => array(
						'Перед публикацией проверяйте slug: он должен быть коротким, понятным и в латинице.',
						'Если вы переименовали страницу или запись, проверьте итоговый URL и ссылки на нее в меню, секциях и кнопках.',
						'Не вставляйте в поля сайта локальные адреса вроде localhost или BrowserSync-ссылки.',
					),
				),
				array(
					'title'   => 'Чеклист перед публикацией',
					'ordered' => true,
					'items'   => array(
						'Проверен шаблон страницы или тип записи.',
						'Заполнены ключевые ACF-поля и порядок секций.',
						'Заданы заголовок, изображение, краткое описание и нужные кнопки.',
						'Проверен slug и итоговый URL.',
						'Заполнены SEO-поля для важного экрана.',
						'Страница просмотрена на фронтенде хотя бы в одной десктопной и одной мобильной ширине.',
					),
				),
			),
		),
	);
}

/**
 * Return sections for the technical guide.
 *
 * @return array<int,array<string,mixed>>
 */
function a4_remont_get_technical_guide_sections() {
	$links = a4_remont_admin_docs_get_links();

	return array(
		array(
			'id'    => 'architecture',
			'title' => 'Общая архитектура проекта',
			'lead'  => 'Проект построен как кастомная WordPress-тема с ACF-driven архитектурой и статическим fallback-слоем. В работе важно понимать разделение на тему, контентные сущности, глобальные опции и технические модули.',
			'groups' => array(
				array(
					'title' => 'Ключевые принципы',
					'items' => array(
						'<strong>Fallback-first</strong> — если часть полей секции не заполнена, шаблон может подхватить резервную статическую разметку и не развалить экран.',
						'<strong>ACF как редакторский слой</strong> — страницы, архивы и глобальные зоны редактируются через Flexible Content и option pages.',
						'<strong>Повторное использование секций</strong> — FAQ, CTA, портфолио, отзывы и контакты переиспользуются между страницами вместо дублирования шаблонов.',
						'<strong>SEO вынесено отдельно от темы</strong> — тема не должна дублировать SEO-логику, редиректы и системные метаданные.',
					),
				),
				array(
					'title' => 'Два рабочих контура проекта',
					'items' => array(
						'<code>static/</code> хранит исходную статическую сборку и служит исходным визуальным слоем для интеграции.',
						'<code>wp-content/themes/a4-remont/</code> — рабочая тема WordPress, куда переносится функциональная интеграция.',
						'<code>wp-content/plugins/</code> — проектные плагины и отдельные модули, которые не должны жить внутри темы.',
					),
				),
			),
		),
		array(
			'id'    => 'theme-map',
			'title' => 'Карта темы и шаблонов',
			'lead'  => 'Точка входа темы — functions.php, который подключает модули из inc. Важно поддерживать это разделение и не превращать functions.php в свалку логики.',
			'groups' => array(
				array(
					'title' => 'Основные модули темы',
					'items' => array(
						'<code>inc/setup.php</code> — базовая настройка темы и theme supports.',
						'<code>inc/slugs.php</code> — нормализация slug-ов и канонических путей шаблонных страниц.',
						'<code>inc/media.php</code> — медиа-логика и поддержка SVG.',
						'<code>inc/enqueue.php</code> — подключение стилей и скриптов темы.',
						'<code>inc/content-types.php</code> — регистрация CPT и таксономий.',
						'<code>inc/static-content.php</code> — работа с HTML-fallback из статики.',
						'<code>inc/section-helpers.php</code> — общие helper-функции секций.',
						'<code>inc/header-builder.php</code>, <code>inc/footer-builder.php</code>, <code>inc/popups.php</code> — глобальный shell сайта.',
					),
				),
				array(
					'title' => 'Где лежат шаблоны',
					'items' => array(
						'<code>page-templates/</code> — шаблоны обычных страниц.',
						'<code>archive-*.php</code> — архивы CPT.',
						'<code>single-*.php</code> — внутренние страницы CPT.',
						'<code>template-parts/section/</code> — PHP-шаблоны секций.',
						'<code>template-parts/static/section/</code> — резервные HTML partials для секций.',
						'<code>404.php</code> и <code>search.php</code> — отдельные системные экраны темы.',
					),
				),
			),
		),
		array(
			'id'    => 'content-model',
			'title' => 'Модель контента',
			'lead'  => 'Основные бизнес-сущности проекта вынесены в отдельные типы записей. Обычные страницы используются только там, где это действительно статичные или шаблонные экраны.',
			'groups' => array(
				array(
					'title' => 'CPT и маршруты',
					'items' => array(
						'<strong>Услуги</strong> — <code>service</code>, архив <code>/services/</code>, таксономия <code>service_category</code>.',
						'<strong>Новости</strong> — <code>news</code>, архив <code>/news/</code>, таксономия <code>news_category</code>.',
						'<strong>Работы</strong> — <code>work</code>, архив <code>/our-works/</code>, таксономия <code>work_category</code>.',
						'<strong>Отзывы</strong> — <code>feedback</code>, архив <code>/reviews/</code>, формат управляется через ACF-поле внутри записи.',
					),
				),
				array(
					'title' => 'Какие экраны не являются обычными страницами',
					'items' => array(
						'Архивы услуг, новостей, работ и отзывов редактируются через option pages внутри соответствующих CPT-разделов: ' . a4_remont_admin_docs_link( $links['service_archive'], 'услуги' ) . ', ' . a4_remont_admin_docs_link( $links['news_archive'], 'новости' ) . ', ' . a4_remont_admin_docs_link( $links['works_archive'], 'работы' ) . ', ' . a4_remont_admin_docs_link( $links['feedback_archive'], 'отзывы' ) . '.',
						'Страница 404 и результаты поиска собираются шаблонами темы и не имеют собственной записи в разделе «Страницы».',
					),
				),
			),
		),
		array(
			'id'    => 'acf-layer',
			'title' => 'ACF-слой и редакторская логика',
			'lead'  => 'Поля проекта описаны кодом и затем отображаются через интерфейс ACF. Это позволяет держать структуру контролируемой, но при этом удобной для редактора.',
			'groups' => array(
				array(
					'title' => 'Как устроен ACF в проекте',
					'items' => array(
						'Структура полей задается в PHP-модулях <code>inc/acf-*.php</code>, а не создается вручную как разрозненный конструктор внутри админки.',
						'Flexible Content используется для страниц, архивов и single-экранов там, где нужен секционный монтаж.',
						'Option pages используются для глобальных зон: шапки, подвала, модальных окон и архивных экранов CPT.',
						'Для части редакторских сценариев интерфейс дополнительно улучшен кастомным admin CSS/JS, если стандартных средств ACF недостаточно.',
					),
				),
				array(
					'title' => 'Что важно при доработках',
					'items' => array(
						'Если поле уже существует в коде, не нужно дублировать его вручную в интерфейсе ACF.',
						'Если добавляется новая секция, сначала проектируется ее PHP-структура, затем шаблон секции, затем fallback и только потом UX-подсказки для редактора.',
						'При доработке существующей секции важно не ломать ее fallback-ветку, потому что она защищает сайт от пустых экранов на этапе наполнения.',
					),
				),
			),
		),
		array(
			'id'    => 'shell',
			'title' => 'Глобальный shell сайта',
			'lead'  => 'Шапка, подвал, попапы и часть CTA-логики живут отдельно от конкретных страниц. Это принципиально для предсказуемого UX и отсутствия дублей.',
			'groups' => array(
				array(
					'title' => 'Шапка и подвал',
					'items' => array(
						'Шапка и подвал настраиваются через один общий экран ' . a4_remont_admin_docs_link( $links['site_shell'], '«Шапка и подвал»' ) . '.',
						'Навигация задается как единый источник правды: desktop и mobile не должны расходиться по ссылкам и структуре.',
						'В шапке поддерживаются обычные ссылки и выпадающие меню с колонками.',
						'Подвал умеет переиспользовать навигацию из шапки либо иметь собственные колонки ссылок.',
					),
				),
				array(
					'title' => 'Модальные окна и CTA-логика',
					'items' => array(
						'Модальные окна живут в ' . a4_remont_admin_docs_link( $links['popups'], 'отдельном экране' ) . ' и могут вызываться из кнопок на секциях, в шапке и в других глобальных элементах.',
						'Для CTA-полей в проекте используется режим выбора действия: переход по ссылке или открытие модального окна. Это нужно сохранять и в будущих доработках.',
					),
				),
			),
		),
		array(
			'id'    => 'plugins',
			'title' => 'Плагины и эксплуатационный стек',
			'lead'  => 'В корневом регламенте проекта зафиксирован ожидаемый набор плагинов и технических зависимостей. Эти пункты важно соблюдать при переносе на staging и production.',
			'groups' => array(
				array(
					'title' => 'Обязательный стек по регламенту',
					'items' => array(
						'ACF Pro — обязательный редакторский слой проекта.',
						'Contact Form 7 — базовый источник shortcode-форм.',
						'SMTP-плагин — обязательный транспорт писем с сайта.',
						'Один SEO-инструмент — единый источник метаданных, редиректов и технических правил.',
						'Один cache-плагин — обязательный производственный слой на боевой среде.',
						'Classic Editor — полезен для предсказуемого редакторского UX на классических проектах.',
					),
				),
				array(
					'title' => 'Рекомендуемые инструменты',
					'items' => array(
						'Cyr-To-Lat — если нужен строгий контроль транслитерации URL.',
						'Post Duplicator — для быстрого клонирования записей и страниц.',
						'Better Search — когда контентных сущностей становится много и нужен удобный внутренний поиск.',
						'Query Monitor и Password Protected — только для локалки, staging и диагностики, но не как постоянный production-инструмент без необходимости.',
					),
				),
				array(
					'title' => 'Формы, письма и безопасность',
					'items' => array(
						'Если форма отправляется, но письма не доходят, первым делом проверяется SMTP и настройки формы, а не секция страницы.',
						'Для публичных форм желательно использовать антиспам и CAPTCHA там, где это требуется проектом.',
						'На сдаче и тестировании сайт должен быть закрыт от индексации и внешнего доступа, если это оговорено процессом.',
					),
				),
			),
		),
		array(
			'id'    => 'dev-support',
			'title' => 'Локальная разработка и сопровождение',
			'lead'  => 'Тема разрабатывается в репозитории и синхронизируется в WordPress Studio. Это отдельный поток от продовой среды и от работы редактора в админке.',
			'groups' => array(
				array(
					'title' => 'Локальный цикл',
					'items' => array(
						'Исходный код темы хранится в репозитории, а локальный WordPress Studio выступает как runtime-среда.',
						'Для локальной разработки используется sync/live-слой из репозитория, а не ручные правки прямо в папке сайта Studio.',
						'Статическая сборка хранится отдельно, чтобы интеграция в тему оставалась контролируемой.',
					),
				),
				array(
					'title' => 'Что важно для поддержки',
					'items' => array(
						'Новые секции и поля добавляются в коде, а не хаотично на боевом сайте.',
						'Тему, проектные плагины и документацию нужно обновлять синхронно, чтобы админка не расходилась с реальным функционалом.',
						'После крупных изменений всегда проверяйте минимум главную, один архив, один single-экран и одну форму.',
					),
				),
			),
		),
	);
}

/**
 * Render one docs panel.
 *
 * @param string $panel_id    Panel ID.
 * @param string $panel_title Panel title.
 * @param string $panel_lead  Panel lead.
 * @param array  $sections    Panel sections.
 * @param bool   $is_active   Whether the panel is active.
 * @return void
 */
function a4_remont_render_docs_panel( $panel_id, $panel_title, $panel_lead, array $sections, $is_active = false ) {
	?>
	<section
		class="a4-docs__panel<?php echo $is_active ? ' is-active' : ''; ?>"
		data-a4-docs-panel="<?php echo esc_attr( $panel_id ); ?>"
	>
		<div class="a4-docs__panel-intro">
			<span class="a4-docs__panel-kicker"><?php echo 'user' === $panel_id ? 'Для редактора и администратора' : 'Для разработчика и поддерживающего специалиста'; ?></span>
			<h2><?php echo esc_html( $panel_title ); ?></h2>
			<p><?php echo esc_html( $panel_lead ); ?></p>
		</div>

		<div class="a4-docs__panel-grid">
			<aside class="a4-docs__side">
				<div class="a4-docs__side-card">
					<span class="a4-docs__side-label">Разделы</span>

					<ul class="a4-docs__anchors">
						<?php foreach ( $sections as $section ) : ?>
							<li>
								<a href="#<?php echo esc_attr( $panel_id . '-' . $section['id'] ); ?>">
									<?php echo esc_html( $section['title'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</aside>

			<div class="a4-docs__content">
				<?php foreach ( $sections as $section ) : ?>
					<article class="a4-docs-card" id="<?php echo esc_attr( $panel_id . '-' . $section['id'] ); ?>">
						<div class="a4-docs-card__header">
							<span class="a4-docs-card__eyebrow"><?php echo esc_html( strtoupper( $section['id'] ) ); ?></span>
							<h3><?php echo esc_html( $section['title'] ); ?></h3>
							<p><?php echo esc_html( $section['lead'] ); ?></p>
						</div>

						<div class="a4-docs-card__body">
							<?php foreach ( $section['groups'] as $group ) : ?>
								<section class="a4-docs-block">
									<h4><?php echo esc_html( $group['title'] ); ?></h4>

									<?php if ( ! empty( $group['ordered'] ) ) : ?>
										<ol class="a4-docs-list">
											<?php foreach ( $group['items'] as $item ) : ?>
												<li><?php echo wp_kses_post( $item ); ?></li>
											<?php endforeach; ?>
										</ol>
									<?php else : ?>
										<ul class="a4-docs-list">
											<?php foreach ( $group['items'] as $item ) : ?>
												<li><?php echo wp_kses_post( $item ); ?></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</section>
							<?php endforeach; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the project documentation page.
 *
 * @return void
 */
function a4_remont_render_admin_docs_page() {
	$user_sections      = a4_remont_get_user_guide_sections();
	$technical_sections = a4_remont_get_technical_guide_sections();
	$quick_links        = a4_remont_admin_docs_get_quick_links();
	$brand_logo_url     = 'https://ds-art.ru/wp-content/uploads/2026/01/group-17428.webp';
	?>
	<div class="wrap a4-docs" data-a4-docs>
		<section class="a4-docs__hero">
			<div class="a4-docs__hero-main">
				<div class="a4-docs__brand-row">
					<a class="a4-docs__brand" href="https://ds-art.ru/" target="_blank" rel="noopener noreferrer">
						<img src="<?php echo esc_url( $brand_logo_url ); ?>" alt="DS-ART" />
					</a>
					<span class="a4-docs__eyebrow">База знаний A4 Remont</span>
				</div>
				<h1>Инструкции по работе с сайтом</h1>
				<p>
					Единая база знаний по текущему проекту: как редактировать контент, где находятся
					архивы и глобальные настройки, какие плагины обязательны, как устроена тема и
					какие технические правила важно соблюдать при сопровождении.
				</p>

				<div class="a4-docs__quick-links">
					<?php foreach ( $quick_links as $link ) : ?>
						<a
							class="a4-docs__quick-link a4-docs__quick-link--<?php echo esc_attr( $link['type'] ); ?>"
							href="<?php echo esc_url( $link['url'] ); ?>"
							<?php echo 'front' === $link['type'] ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
						>
							<?php echo esc_html( $link['label'] ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="a4-docs__hero-stats">
				<div class="a4-docs__stat">
					<strong>2</strong>
					<span>Главные вкладки: редакторская и техническая.</span>
				</div>
				<div class="a4-docs__stat">
					<strong>4</strong>
					<span>Ключевых контентных раздела: услуги, новости, работы и отзывы.</span>
				</div>
				<div class="a4-docs__stat">
					<strong>3</strong>
					<span>Глобальных зоны: шапка/подвал, модальные окна и SEO.</span>
				</div>
				<div class="a4-docs__stat a4-docs__stat--accent">
					<strong>Правило</strong>
					<span>Сначала ищем правильный экран редактирования, только потом меняем поля.</span>
				</div>
			</div>
		</section>

		<div class="a4-docs__tabs" role="tablist" aria-label="Разделы инструкций">
			<button
				type="button"
				class="a4-docs__tab is-active"
				data-a4-docs-tab="user"
				role="tab"
				aria-selected="true"
			>
				Пользовательская инструкция
			</button>
			<button
				type="button"
				class="a4-docs__tab"
				data-a4-docs-tab="technical"
				role="tab"
				aria-selected="false"
			>
				Техническая документация
			</button>
		</div>

		<?php
		a4_remont_render_docs_panel(
			'user',
			'Пользовательская инструкция',
			'Пошаговое руководство для администратора сайта, контент-менеджера и редактора. Здесь собрана практическая логика работы именно с этим проектом.',
			$user_sections,
			true
		);

		a4_remont_render_docs_panel(
			'technical',
			'Техническая документация',
			'Справка для разработчика, интегратора и специалиста по поддержке: структура темы, контентная модель, ACF-слой, fallback-архитектура и эксплуатационный стек.',
			$technical_sections
		);
		?>
	</div>
	<?php
}
