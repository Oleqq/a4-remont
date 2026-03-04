<?php
/**
 * Admin UI and meta fields.
 *
 * @package A4_Remont_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class A4_Remont_SEO_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post_meta' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'init', array( $this, 'register_term_hooks' ) );
		add_action( 'init', array( $this, 'register_list_table_hooks' ) );
	}

	/**
	 * Register plugin menu.
	 *
	 * @return void
	 */
	public function register_menu() {
		add_menu_page(
			'SEO оптимизация',
			'SEO оптимизация',
			'manage_options',
			'a4-remont-seo',
			array( $this, 'render_settings_page' ),
			'dashicons-chart-area',
			59
		);
	}

	/**
	 * Register plugin settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'a4_remont_seo_settings_group',
			A4_Remont_SEO::OPTION_KEY,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'A4_Remont_SEO', 'sanitize_settings' ),
				'default'           => A4_Remont_SEO::get_defaults(),
			)
		);
	}

	/**
	 * Register term field hooks.
	 *
	 * @return void
	 */
	public function register_term_hooks() {
		foreach ( A4_Remont_SEO::get_supported_taxonomies() as $taxonomy => $object ) {
			add_action( $taxonomy . '_add_form_fields', array( $this, 'render_term_add_fields' ) );
			add_action( $taxonomy . '_edit_form_fields', array( $this, 'render_term_edit_fields' ), 10, 2 );
			add_action( 'created_' . $taxonomy, array( $this, 'save_term_meta' ) );
			add_action( 'edited_' . $taxonomy, array( $this, 'save_term_meta' ) );
		}
	}

	/**
	 * Register list table columns.
	 *
	 * @return void
	 */
	public function register_list_table_hooks() {
		foreach ( A4_Remont_SEO::get_supported_post_types() as $post_type => $object ) {
			add_filter( 'manage_' . $post_type . '_posts_columns', array( $this, 'register_post_list_columns' ) );
			add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'render_post_list_column' ), 10, 2 );
		}
	}

	/**
	 * Register post meta boxes.
	 *
	 * @return void
	 */
	public function register_meta_boxes() {
		foreach ( A4_Remont_SEO::get_supported_post_types() as $post_type => $object ) {
			add_meta_box(
				'a4-remont-seo-meta',
				'SEO оптимизация страницы',
				array( $this, 'render_post_meta_box' ),
				$post_type,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @param string $hook_suffix Current admin hook.
	 * @return void
	 */
	public function enqueue_assets( $hook_suffix ) {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		$supported_post_types = A4_Remont_SEO::get_supported_post_types();
		$supported_taxonomies = A4_Remont_SEO::get_supported_taxonomies();
		$is_plugin_page       = 'toplevel_page_a4-remont-seo' === $hook_suffix;
		$is_post_screen       = in_array( $screen->base, array( 'post', 'post-new' ), true ) && ! empty( $supported_post_types[ $screen->post_type ] );
		$is_term_screen       = in_array( $screen->base, array( 'edit-tags', 'term' ), true ) && ! empty( $supported_taxonomies[ $screen->taxonomy ] );
		$is_list_screen       = 'edit' === $screen->base && ! empty( $supported_post_types[ $screen->post_type ] );

		if ( ! $is_plugin_page && ! $is_post_screen && ! $is_term_screen && ! $is_list_screen ) {
			return;
		}

		$settings = A4_Remont_SEO::get_settings();

		wp_enqueue_media();
		wp_enqueue_style(
			'a4-remont-seo-admin',
			A4_REMONT_SEO_URL . 'assets/admin.css',
			array(),
			A4_REMONT_SEO_VERSION
		);
		wp_enqueue_script(
			'a4-remont-seo-admin',
			A4_REMONT_SEO_URL . 'assets/admin.js',
			array( 'jquery' ),
			A4_REMONT_SEO_VERSION,
			true
		);
		wp_localize_script(
			'a4-remont-seo-admin',
			'a4SeoAdmin',
			array(
				'mediaTitle'       => 'Выберите изображение',
				'mediaButton'      => 'Использовать изображение',
				'emptyImageLabel'  => 'Изображение не выбрано',
				'siteName'         => A4_Remont_SEO::get_site_name(),
				'titleSeparator'   => $settings['general']['title_separator'],
				'paginationSuffix' => 'Страница',
				'addRedirectRule'  => 'Добавить правило',
				'removeRule'       => 'Удалить правило',
			)
		);
	}

	/**
	 * Render plugin settings page.
	 *
	 * @return void
	 */
	public function render_settings_page() {
		$settings         = A4_Remont_SEO::get_settings();
		$archive_contexts = A4_Remont_SEO::get_archive_contexts();
		$page_contexts    = A4_Remont_SEO::get_page_contexts();
		$redirect_rules   = $settings['redirects']['rules'] ?? array();
		$sitemap_url      = A4_Remont_SEO::get_sitemap_url();
		$robots_url       = home_url( '/robots.txt' );
		?>
		<div class="wrap a4seo-admin">
			<div class="a4seo-shell">
				<header class="a4seo-hero">
					<div class="a4seo-hero__content">
						<span class="a4seo-hero__eyebrow">A4 Remont SEO</span>
						<h1 class="a4seo-hero__title">SEO-центр проекта</h1>
						<p class="a4seo-hero__lead">Единый экран для управления title, description, keywords, canonical, robots, Open Graph, schema.org, sitemap, счетчиками и техническими SEO-настройками сайта.</p>
					</div>
					<div class="a4seo-hero__stats">
						<div class="a4seo-stat">
							<strong><?php echo esc_html( count( A4_Remont_SEO::get_supported_post_types() ) ); ?></strong>
							<span>типов записей под SEO</span>
						</div>
						<div class="a4seo-stat">
							<strong><?php echo esc_html( count( A4_Remont_SEO::get_supported_taxonomies() ) ); ?></strong>
							<span>таксономий с отдельными полями</span>
						</div>
						<div class="a4seo-stat">
							<strong>Sitemap + OG</strong>
							<span>технический и social SEO в одном плагине</span>
						</div>
					</div>
				</header>

				<form method="post" action="options.php" class="a4seo-settings-form" data-a4seo-tabs>
					<?php settings_fields( 'a4_remont_seo_settings_group' ); ?>

					<div class="a4seo-tabs">
						<button type="button" class="a4seo-tabs__button is-active" data-a4seo-target="general">Общие настройки</button>
						<button type="button" class="a4seo-tabs__button" data-a4seo-target="social">Open Graph</button>
						<button type="button" class="a4seo-tabs__button" data-a4seo-target="technical">Техническое SEO</button>
						<button type="button" class="a4seo-tabs__button" data-a4seo-target="tracking">Счетчики и коды</button>
						<button type="button" class="a4seo-tabs__button" data-a4seo-target="redirects">Редиректы</button>
						<button type="button" class="a4seo-tabs__button" data-a4seo-target="archives">Архивы и системные страницы</button>
						<button type="button" class="a4seo-tabs__button" data-a4seo-target="schema">Schema.org</button>
						<button type="button" class="a4seo-tabs__button" data-a4seo-target="verification">Верификация</button>
					</div>

					<section class="a4seo-panel is-active" data-a4seo-panel="general">
						<div class="a4seo-grid">
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Базовые параметры сайта</h2>
								<p class="a4seo-card__hint">Эти значения работают как глобальный fallback, если у конкретной страницы, архива или записи поля SEO не заполнены.</p>
								<?php
								$this->render_text_input( 'a4_remont_seo_settings[general][site_name]', 'Имя сайта для SEO', $settings['general']['site_name'], 'Если оставить пустым, будет использовано стандартное название сайта WordPress.' );
								$this->render_select_input(
									'a4_remont_seo_settings[general][title_separator]',
									'Разделитель в title',
									$settings['general']['title_separator'],
									array(
										'|' => '|',
										'-' => '-',
										'•' => '•',
										'/' => '/',
									)
								);
								$this->render_textarea_input( 'a4_remont_seo_settings[general][default_description]', 'Описание по умолчанию', $settings['general']['default_description'], 'Используется, если у объекта нет собственного description.', 3 );
								$this->render_text_input( 'a4_remont_seo_settings[general][default_keywords]', 'Ключевые слова по умолчанию', $settings['general']['default_keywords'], 'Через запятую. Для Google это не ключевой сигнал, но тег можно поддерживать для совместимости.' );
								?>
							</div>
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Что уже покрывает плагин</h2>
								<ul class="a4seo-checklist">
									<li>Обычные страницы, записи и все публичные CPT</li>
									<li>Архивы post type, категории и кастомные таксономии</li>
									<li>Canonical и robots</li>
									<li>Open Graph для предпросмотров в Telegram, VK и мессенджерах</li>
									<li>XML sitemap и robots.txt</li>
									<li>JSON-LD для сайта, страниц и хлебных крошек</li>
									<li>Верификация Google, Яндекса, Bing и Pinterest</li>
								</ul>
								<div class="a4seo-note">
									<strong>Логика работы:</strong> сначала плагин ищет SEO-поля у конкретного объекта, затем у архивных настроек, и только потом берет глобальные fallback-значения.
								</div>
							</div>
						</div>
					</section>

					<section class="a4seo-panel" data-a4seo-panel="social">
						<div class="a4seo-grid">
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Open Graph по умолчанию</h2>
								<?php
								$this->render_image_picker( 'a4_remont_seo_settings[social][default_og_image_id]', 'Изображение Open Graph по умолчанию', (int) $settings['social']['default_og_image_id'], 'Используется, если у страницы, записи или архива не задано собственное OG-изображение.' );
								?>
							</div>
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Поведение предпросмотров</h2>
								<p class="a4seo-card__hint">Если поля Open Graph у конкретного объекта не заполнены, плагин автоматически использует SEO title/description и основное изображение объекта.</p>
								<div class="a4seo-preview-card">
									<div class="a4seo-preview-card__image"></div>
									<div class="a4seo-preview-card__body">
										<strong>Превью соцсетей</strong>
										<span>Telegram, VK, мессенджеры, локальные каталоги</span>
									</div>
								</div>
							</div>
						</div>
					</section>

					<section class="a4seo-panel" data-a4seo-panel="technical">
						<div class="a4seo-grid">
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Sitemap и robots.txt</h2>
								<?php
								$this->render_toggle_input( 'a4_remont_seo_settings[tools][enable_sitemap]', 'Включить XML sitemap', ! empty( $settings['tools']['enable_sitemap'] ) );
								$this->render_number_input( 'a4_remont_seo_settings[tools][sitemap_items_per_file]', 'URL в одном sitemap-файле', (int) $settings['tools']['sitemap_items_per_file'], 50, 2000, 50, 'Оптимальный диапазон: 200-1000 адресов в одном файле.' );
								$this->render_toggle_input( 'a4_remont_seo_settings[tools][enable_robots_txt]', 'Управлять virtual robots.txt из плагина', ! empty( $settings['tools']['enable_robots_txt'] ) );
								$this->render_textarea_input( 'a4_remont_seo_settings[tools][robots_txt_extra]', 'Дополнительные правила robots.txt', $settings['tools']['robots_txt_extra'], 'Каждое правило с новой строки. Например: Disallow: /private/', 5 );
								?>
							</div>
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Индексация и вложения</h2>
								<?php
								$this->render_toggle_input( 'a4_remont_seo_settings[tools][noindex_paged_archives]', 'Закрывать пагинацию архивов от индексации', ! empty( $settings['tools']['noindex_paged_archives'] ) );
								$this->render_select_input( 'a4_remont_seo_settings[tools][attachment_handling]', 'Как вести себя со страницами вложений', $settings['tools']['attachment_handling'], A4_Remont_SEO::get_attachment_handling_options() );
								?>
								<div class="a4seo-note">
									<strong>Sitemap:</strong> <a href="<?php echo esc_url( $sitemap_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $sitemap_url ); ?></a><br>
									<strong>Robots:</strong> <a href="<?php echo esc_url( $robots_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $robots_url ); ?></a>
								</div>
							</div>
						</div>
					</section>

					<section class="a4seo-panel" data-a4seo-panel="tracking">
						<div class="a4seo-grid">
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Счетчики аналитики</h2>
								<?php
								$this->render_text_input( 'a4_remont_seo_settings[analytics][yandex_metrika_id]', 'ID Яндекс.Метрики', $settings['analytics']['yandex_metrika_id'], 'Только числовой идентификатор счетчика, без полного кода вставки.' );
								$this->render_toggle_input( 'a4_remont_seo_settings[analytics][yandex_metrika_webvisor]', 'Включить Webvisor для Яндекс.Метрики', ! empty( $settings['analytics']['yandex_metrika_webvisor'] ) );
								$this->render_text_input( 'a4_remont_seo_settings[analytics][google_tag_manager_id]', 'Google Tag Manager ID', $settings['analytics']['google_tag_manager_id'], 'Например: GTM-XXXXXXX. Поле необязательное.' );
								?>
							</div>
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Дополнительные коды</h2>
								<?php
								$this->render_textarea_input( 'a4_remont_seo_settings[analytics][head_code]', 'Код в &lt;head&gt;', $settings['analytics']['head_code'], 'Для нестандартных верификаций, пикселей и служебных интеграций.', 6 );
								$this->render_textarea_input( 'a4_remont_seo_settings[analytics][body_code]', 'Код сразу после &lt;body&gt;', $settings['analytics']['body_code'], 'Сюда можно вставить noscript-блоки и внешние контейнеры.', 6 );
								$this->render_textarea_input( 'a4_remont_seo_settings[analytics][footer_code]', 'Код перед &lt;/body&gt;', $settings['analytics']['footer_code'], 'Подходит для сторонних счетчиков и кастомных виджетов.', 6 );
								?>
							</div>
						</div>
					</section>

					<section class="a4seo-panel" data-a4seo-panel="redirects">
						<div class="a4seo-grid">
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Управление редиректами</h2>
								<p class="a4seo-card__hint">Используйте правила для 301, 302 и 307 перенаправлений после смены URL, миграции разделов и очистки дублей. Редиректы работают на уровне WordPress и не вмешиваются в админку, REST API и sitemap.</p>
								<?php $this->render_toggle_input( 'a4_remont_seo_settings[redirects][enabled]', 'Включить менеджер редиректов', ! empty( $settings['redirects']['enabled'] ) ); ?>
								<div class="a4seo-note">
									<strong>Точное совпадение:</strong> перенаправляет только один путь.<br>
									<strong>По префиксу:</strong> подходит для миграции раздела целиком и сохраняет хвост URL.
								</div>
							</div>
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Список правил</h2>
								<p class="a4seo-card__hint">Источник задавайте как путь от корня сайта: например, <code>/old-page</code>. Цель можно указывать как относительный путь или полную ссылку.</p>
								<?php $this->render_redirect_rules( $redirect_rules ); ?>
							</div>
						</div>
					</section>

					<section class="a4seo-panel" data-a4seo-panel="archives">
						<?php
						$special_context_keys = array( 'front_page', 'posts_page', 'author', 'date', 'attachment', 'search', '404' );
						$first_context_key    = '';

						if ( ! empty( $archive_contexts ) ) {
							$first_context_key = (string) key( $archive_contexts );
						}

						if ( '' === $first_context_key && ! empty( $page_contexts ) ) {
							$page_ids          = array_keys( $page_contexts );
							$first_context_key = 'page-' . (int) reset( $page_ids );
						}
						?>
						<div class="a4seo-archive-settings" data-a4seo-context-switcher>
							<div class="a4seo-card a4seo-card--switcher">
								<h2 class="a4seo-card__title">Выбор раздела для настройки</h2>
								<p class="a4seo-card__hint">Сначала выберите нужный архив или системную страницу, затем настройте только его SEO-параметры. Так экран остается компактным и не превращается в длинный список.</p>
								<label class="a4seo-field">
									<span class="a4seo-field__label">Какой раздел настраиваем</span>
									<select class="a4seo-field__select" data-a4seo-context-select>
										<optgroup label="Системные страницы">
											<?php foreach ( $archive_contexts as $context_key => $context_data ) : ?>
												<?php if ( ! in_array( $context_key, $special_context_keys, true ) ) : ?>
													<?php continue; ?>
												<?php endif; ?>
												<option value="<?php echo esc_attr( $context_key ); ?>"<?php selected( $context_key, $first_context_key ); ?>><?php echo esc_html( $context_data['label'] ); ?></option>
											<?php endforeach; ?>
										</optgroup>
										<optgroup label="Архивы контента">
											<?php foreach ( $archive_contexts as $context_key => $context_data ) : ?>
												<?php if ( in_array( $context_key, $special_context_keys, true ) ) : ?>
													<?php continue; ?>
												<?php endif; ?>
												<option value="<?php echo esc_attr( $context_key ); ?>"<?php selected( $context_key, $first_context_key ); ?>><?php echo esc_html( $context_data['label'] ); ?></option>
											<?php endforeach; ?>
										</optgroup>
										<?php if ( ! empty( $page_contexts ) ) : ?>
											<optgroup label="Страницы сайта">
												<?php foreach ( $page_contexts as $page_id => $page_context ) : ?>
													<?php $page_context_key = 'page-' . (int) $page_id; ?>
													<option value="<?php echo esc_attr( $page_context_key ); ?>"<?php selected( $page_context_key, $first_context_key ); ?>><?php echo esc_html( $page_context['label'] ); ?></option>
												<?php endforeach; ?>
											</optgroup>
										<?php endif; ?>
									</select>
								</label>
							</div>

							<div class="a4seo-stack">
								<?php foreach ( $archive_contexts as $context_key => $context_data ) : ?>
									<?php
									$context_group = in_array( $context_key, $special_context_keys, true ) ? 'special' : 'archives';
									$values        = $settings[ $context_group ][ $context_key ] ?? A4_Remont_SEO::get_context_defaults();
									$prefix        = 'a4_remont_seo_settings[' . $context_group . '][' . $context_key . ']';
									$is_active     = $context_key === $first_context_key;
									?>
									<div class="a4seo-card a4seo-card--archive<?php echo $is_active ? ' is-active' : ''; ?>" data-a4seo-context-card="<?php echo esc_attr( $context_key ); ?>">
										<h2 class="a4seo-card__title"><?php echo esc_html( $context_data['label'] ); ?></h2>
										<p class="a4seo-card__hint"><?php echo esc_html( $context_data['description'] ); ?></p>
										<div class="a4seo-grid a4seo-grid--compact">
											<?php
											$this->render_text_input( $prefix . '[title]', 'SEO title', $values['title'] );
											$this->render_text_input( $prefix . '[keywords]', 'Ключевые слова', $values['keywords'] );
											$this->render_textarea_input( $prefix . '[description]', 'Description', $values['description'], 'Если оставить пустым, архив попробует использовать свое описание.', 3 );
											$this->render_text_input( $prefix . '[canonical]', 'Canonical URL', $values['canonical'] );
											$this->render_text_input( $prefix . '[og_title]', 'Open Graph title', $values['og_title'] );
											$this->render_textarea_input( $prefix . '[og_description]', 'Open Graph description', $values['og_description'], '', 3 );
											$this->render_image_picker( $prefix . '[og_image_id]', 'Open Graph изображение', (int) $values['og_image_id'] );
											$this->render_select_input( $prefix . '[schema_type]', 'Тип schema', $values['schema_type'], A4_Remont_SEO::get_schema_type_options() );
											$this->render_toggle_input( $prefix . '[robots_index]', 'Разрешить индексацию', ! empty( $values['robots_index'] ) );
											$this->render_toggle_input( $prefix . '[robots_follow]', 'Разрешить переход по ссылкам', ! empty( $values['robots_follow'] ) );
											?>
										</div>
									</div>
								<?php endforeach; ?>

								<?php foreach ( $page_contexts as $page_id => $page_context ) : ?>
									<?php
									$page_context_key = 'page-' . (int) $page_id;
									$values           = A4_Remont_SEO::get_post_meta_bundle( $page_id );
									$prefix           = 'a4_remont_seo_settings[pages][' . (int) $page_id . ']';
									$is_active        = $page_context_key === $first_context_key;
									?>
									<div class="a4seo-card a4seo-card--archive<?php echo $is_active ? ' is-active' : ''; ?>" data-a4seo-context-card="<?php echo esc_attr( $page_context_key ); ?>">
										<h2 class="a4seo-card__title"><?php echo esc_html( $page_context['label'] ); ?></h2>
										<p class="a4seo-card__hint"><?php echo esc_html( $page_context['description'] ); ?></p>
										<div class="a4seo-note">
											<?php if ( ! empty( $page_context['permalink'] ) ) : ?>
												<strong>URL:</strong>
												<a href="<?php echo esc_url( $page_context['permalink'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $page_context['permalink'] ); ?></a><br>
											<?php endif; ?>
											<?php if ( ! empty( $page_context['edit_link'] ) ) : ?>
												<a href="<?php echo esc_url( $page_context['edit_link'] ); ?>">Открыть страницу в редакторе</a>
											<?php endif; ?>
										</div>
										<div class="a4seo-grid a4seo-grid--compact">
											<?php
											$this->render_text_input( $prefix . '[title]', 'SEO title', $values['title'] );
											$this->render_text_input( $prefix . '[keywords]', 'Ключевые слова', $values['keywords'] );
											$this->render_textarea_input( $prefix . '[description]', 'Description', $values['description'], 'Эти поля сохраняются как SEO-мета именно этой страницы.', 3 );
											$this->render_text_input( $prefix . '[canonical]', 'Canonical URL', $values['canonical'] );
											$this->render_text_input( $prefix . '[og_title]', 'Open Graph title', $values['og_title'] );
											$this->render_textarea_input( $prefix . '[og_description]', 'Open Graph description', $values['og_description'], '', 3 );
											$this->render_image_picker( $prefix . '[og_image_id]', 'Open Graph изображение', (int) $values['og_image_id'] );
											$this->render_select_input( $prefix . '[schema_type]', 'Тип schema', $values['schema_type'], A4_Remont_SEO::get_schema_type_options() );
											$this->render_toggle_input( $prefix . '[robots_index]', 'Разрешить индексацию', ! empty( $values['robots_index'] ) );
											$this->render_toggle_input( $prefix . '[robots_follow]', 'Разрешить переход по ссылкам', ! empty( $values['robots_follow'] ) );
											?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</section>

					<section class="a4seo-panel" data-a4seo-panel="schema">
						<div class="a4seo-grid">
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Организация</h2>
								<?php
								$this->render_text_input( 'a4_remont_seo_settings[schema][organization_name]', 'Название компании', $settings['schema']['organization_name'] );
								$this->render_text_input( 'a4_remont_seo_settings[schema][legal_name]', 'Юридическое название', $settings['schema']['legal_name'] );
								$this->render_image_picker( 'a4_remont_seo_settings[schema][logo_id]', 'Логотип для schema.org', (int) $settings['schema']['logo_id'] );
								$this->render_text_input( 'a4_remont_seo_settings[schema][phone]', 'Телефон', $settings['schema']['phone'] );
								$this->render_text_input( 'a4_remont_seo_settings[schema][email]', 'Email', $settings['schema']['email'] );
								$this->render_textarea_input( 'a4_remont_seo_settings[schema][address]', 'Адрес', $settings['schema']['address'], '', 3 );
								$this->render_textarea_input( 'a4_remont_seo_settings[schema][same_as]', 'Профили SameAs', $settings['schema']['same_as'], 'Одна ссылка на строку: соцсети, каталоги, карты, маркетплейсы.', 4 );
								?>
							</div>
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Как schema будет работать</h2>
								<ul class="a4seo-checklist">
									<li>На всем сайте выводится WebSite</li>
									<li>При заполнении блока организации выводится Organization</li>
									<li>Для страниц и записей выводится отдельный JSON-LD по типу объекта</li>
									<li>Для внутренних страниц строится BreadcrumbList</li>
									<li>Для новостей автоматически используется NewsArticle</li>
								</ul>
								<div class="a4seo-note">
									Если на конкретном объекте не выбрать тип schema вручную, плагин подберет его автоматически по типу контента.
								</div>
							</div>
						</div>
					</section>

					<section class="a4seo-panel" data-a4seo-panel="verification">
						<div class="a4seo-grid">
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Коды верификации поисковых систем</h2>
								<?php
								$this->render_text_input( 'a4_remont_seo_settings[verification][google]', 'Google Search Console', $settings['verification']['google'] );
								$this->render_text_input( 'a4_remont_seo_settings[verification][yandex]', 'Яндекс Вебмастер', $settings['verification']['yandex'] );
								$this->render_text_input( 'a4_remont_seo_settings[verification][bing]', 'Bing Webmaster Tools', $settings['verification']['bing'] );
								$this->render_text_input( 'a4_remont_seo_settings[verification][pinterest]', 'Pinterest', $settings['verification']['pinterest'] );
								?>
							</div>
							<div class="a4seo-card">
								<h2 class="a4seo-card__title">Как использовать</h2>
								<p class="a4seo-card__hint">Вставляйте только значение кода без полного HTML-тега. Плагин сам выведет корректные meta-теги в <code>&lt;head&gt;</code>.</p>
								<div class="a4seo-note">
									После сохранения страницы открой исходный код сайта и проверь наличие тегов верификации в блоке <code>&lt;head&gt;</code>.
								</div>
							</div>
						</div>
					</section>

					<div class="a4seo-savebar">
						<button type="submit" class="button button-primary button-hero">Сохранить SEO-настройки</button>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render post SEO meta box.
	 *
	 * @param WP_Post $post Current post object.
	 * @return void
	 */
	public function render_post_meta_box( $post ) {
		$meta                = A4_Remont_SEO::get_post_meta_bundle( $post->ID );
		$permalink           = get_permalink( $post );
		$default_title       = A4_Remont_SEO::build_title( get_the_title( $post ) );
		$default_description = A4_Remont_SEO::trim_description( $post->post_excerpt ?: $post->post_content );
		$settings            = A4_Remont_SEO::get_settings();

		if ( ! $permalink ) {
			$permalink = home_url( '/future-url/' );
		}

		wp_nonce_field( 'a4_remont_seo_post_meta', 'a4_remont_seo_post_meta_nonce' );
		?>
		<div class="a4seo-metabox" data-a4seo-tabs data-a4seo-site-name="<?php echo esc_attr( A4_Remont_SEO::get_site_name() ); ?>" data-a4seo-separator="<?php echo esc_attr( $settings['general']['title_separator'] ); ?>" data-a4seo-default-title="<?php echo esc_attr( $default_title ); ?>" data-a4seo-default-description="<?php echo esc_attr( $default_description ); ?>">
			<div class="a4seo-snippet">
				<span class="a4seo-snippet__label">Предпросмотр сниппета</span>
				<strong class="a4seo-snippet__title" data-a4seo-preview="title"><?php echo esc_html( $meta['title'] ? A4_Remont_SEO::build_title( $meta['title'] ) : $default_title ); ?></strong>
				<span class="a4seo-snippet__url"><?php echo esc_html( $permalink ); ?></span>
				<p class="a4seo-snippet__description" data-a4seo-preview="description"><?php echo esc_html( $meta['description'] ? $meta['description'] : $default_description ); ?></p>
			</div>

			<div class="a4seo-tabs a4seo-tabs--compact">
				<button type="button" class="a4seo-tabs__button is-active" data-a4seo-target="search">Поиск</button>
				<button type="button" class="a4seo-tabs__button" data-a4seo-target="social">Open Graph</button>
				<button type="button" class="a4seo-tabs__button" data-a4seo-target="indexing">Индексация</button>
			</div>

			<div class="a4seo-panel is-active" data-a4seo-panel="search">
				<?php
				$this->render_text_input( 'a4_remont_seo_meta[title]', 'SEO title', $meta['title'], 'Если оставить пустым, title будет собран автоматически из заголовка записи.' );
				$this->render_textarea_input( 'a4_remont_seo_meta[description]', 'Description', $meta['description'], 'Если оставить пустым, плагин возьмет excerpt или начало контента.', 3, 'description' );
				$this->render_text_input( 'a4_remont_seo_meta[keywords]', 'Ключевые слова', $meta['keywords'] );
				$this->render_text_input( 'a4_remont_seo_meta[canonical]', 'Canonical URL', $meta['canonical'], 'Используйте только если нужен нестандартный canonical.' );
				?>
			</div>

			<div class="a4seo-panel" data-a4seo-panel="social">
				<?php
				$this->render_text_input( 'a4_remont_seo_meta[og_title]', 'Open Graph title', $meta['og_title'] );
				$this->render_textarea_input( 'a4_remont_seo_meta[og_description]', 'Open Graph description', $meta['og_description'], '', 3 );
				$this->render_image_picker( 'a4_remont_seo_meta[og_image_id]', 'Open Graph изображение', (int) $meta['og_image_id'], 'Если оставить пустым, плагин попробует использовать миниатюру записи.' );
				?>
			</div>

			<div class="a4seo-panel" data-a4seo-panel="indexing">
				<?php
				$this->render_toggle_input( 'a4_remont_seo_meta[robots_index]', 'Разрешить индексацию', ! empty( $meta['robots_index'] ) );
				$this->render_toggle_input( 'a4_remont_seo_meta[robots_follow]', 'Разрешить переход по ссылкам', ! empty( $meta['robots_follow'] ) );
				$this->render_select_input( 'a4_remont_seo_meta[schema_type]', 'Тип schema', $meta['schema_type'], A4_Remont_SEO::get_schema_type_options() );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Save post SEO meta.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function save_post_meta( $post_id ) {
		if ( ! isset( $_POST['a4_remont_seo_post_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['a4_remont_seo_post_meta_nonce'] ) ), 'a4_remont_seo_post_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$input = isset( $_POST['a4_remont_seo_meta'] ) && is_array( $_POST['a4_remont_seo_meta'] ) ? wp_unslash( $_POST['a4_remont_seo_meta'] ) : array();

		A4_Remont_SEO::save_post_meta_bundle( $post_id, $input );
	}

	/**
	 * Render term add fields.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @return void
	 */
	public function render_term_add_fields( $taxonomy ) {
		wp_nonce_field( 'a4_remont_seo_term_meta', 'a4_remont_seo_term_meta_nonce' );
		$this->render_term_fields_markup( A4_Remont_SEO::get_context_defaults(), false );
	}

	/**
	 * Render term edit fields.
	 *
	 * @param WP_Term $term     Current term.
	 * @param string  $taxonomy Taxonomy.
	 * @return void
	 */
	public function render_term_edit_fields( $term, $taxonomy ) {
		wp_nonce_field( 'a4_remont_seo_term_meta', 'a4_remont_seo_term_meta_nonce' );
		$this->render_term_fields_markup( A4_Remont_SEO::get_term_meta_bundle( $term->term_id ), true );
	}

	/**
	 * Save term SEO meta.
	 *
	 * @param int $term_id Term ID.
	 * @return void
	 */
	public function save_term_meta( $term_id ) {
		if ( ! isset( $_POST['a4_remont_seo_term_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['a4_remont_seo_term_meta_nonce'] ) ), 'a4_remont_seo_term_meta' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_categories' ) ) {
			return;
		}

		$input = isset( $_POST['a4_remont_seo_term_meta'] ) && is_array( $_POST['a4_remont_seo_term_meta'] ) ? wp_unslash( $_POST['a4_remont_seo_term_meta'] ) : array();

		A4_Remont_SEO::save_term_meta_bundle( $term_id, $input );
	}

	/**
	 * Add SEO column into post lists.
	 *
	 * @param array<string,string> $columns Existing columns.
	 * @return array<string,string>
	 */
	public function register_post_list_columns( $columns ) {
		$result = array();

		foreach ( $columns as $key => $label ) {
			$result[ $key ] = $label;

			if ( 'title' === $key ) {
				$result['a4seo_status'] = 'SEO';
			}
		}

		if ( ! isset( $result['a4seo_status'] ) ) {
			$result['a4seo_status'] = 'SEO';
		}

		return $result;
	}

	/**
	 * Render SEO list column.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public function render_post_list_column( $column, $post_id ) {
		if ( 'a4seo_status' !== $column ) {
			return;
		}

		$status = $this->get_post_seo_status( $post_id );
		?>
		<div class="a4seo-list-status">
			<span class="a4seo-score <?php echo esc_attr( $status['score_class'] ); ?>"><?php echo esc_html( $status['score'] ); ?>%</span>
			<div class="a4seo-list-status__chips">
				<?php foreach ( $status['chips'] as $chip ) : ?>
					<span class="a4seo-chip <?php echo esc_attr( $chip['class'] ); ?>"><?php echo esc_html( $chip['label'] ); ?></span>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Build SEO status payload for list row.
	 *
	 * @param int $post_id Post ID.
	 * @return array<string,mixed>
	 */
	protected function get_post_seo_status( $post_id ) {
		$post      = get_post( $post_id );
		$meta      = A4_Remont_SEO::get_post_meta_bundle( $post_id );
		$title     = $meta['title'] ? $meta['title'] : get_the_title( $post_id );
		$desc      = $meta['description'] ? $meta['description'] : A4_Remont_SEO::trim_description( $post ? ( $post->post_excerpt ?: $post->post_content ) : '' );
		$og_image  = ! empty( $meta['og_image_id'] ) || has_post_thumbnail( $post_id );
		$indexable = ! empty( $meta['robots_index'] );
		$score     = 0;
		$total     = 4;
		$chips     = array();

		$chips[] = array(
			'label' => $title ? 'Title' : 'Нет title',
			'class' => $title ? 'is-good' : 'is-bad',
		);
		$score  += $title ? 1 : 0;

		$chips[] = array(
			'label' => $desc ? 'Description' : 'Нет description',
			'class' => $desc ? 'is-good' : 'is-bad',
		);
		$score  += $desc ? 1 : 0;

		$chips[] = array(
			'label' => $og_image ? 'OG image' : 'Нет OG image',
			'class' => $og_image ? 'is-good' : 'is-warn',
		);
		$score  += $og_image ? 1 : 0;

		$chips[] = array(
			'label' => $indexable ? 'Index' : 'Noindex',
			'class' => $indexable ? 'is-good' : 'is-muted',
		);
		$score  += $indexable ? 1 : 0;

		$score_percent = (int) round( ( $score / $total ) * 100 );

		return array(
			'score'       => $score_percent,
			'score_class' => $score_percent >= 75 ? 'is-good' : ( $score_percent >= 50 ? 'is-warn' : 'is-bad' ),
			'chips'       => $chips,
		);
	}

	/**
	 * Render term fields markup.
	 *
	 * @param array<string,mixed> $meta    Meta values.
	 * @param bool                $is_edit Edit mode flag.
	 * @return void
	 */
	protected function render_term_fields_markup( $meta, $is_edit ) {
		$wrapper_open  = $is_edit ? '<tr class="form-field"><th scope="row">SEO оптимизация</th><td>' : '<div class="form-field a4seo-term-form">';
		$wrapper_close = $is_edit ? '</td></tr>' : '</div>';

		echo $wrapper_open; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<div class="a4seo-term-panel">
			<p class="description">Эти поля управляют SEO для архива термина: категории, тега или кастомной таксономии.</p>
			<?php
			$this->render_text_input( 'a4_remont_seo_term_meta[title]', 'SEO title', $meta['title'] );
			$this->render_textarea_input( 'a4_remont_seo_term_meta[description]', 'Description', $meta['description'], '', 3 );
			$this->render_text_input( 'a4_remont_seo_term_meta[keywords]', 'Ключевые слова', $meta['keywords'] );
			$this->render_text_input( 'a4_remont_seo_term_meta[canonical]', 'Canonical URL', $meta['canonical'] );
			$this->render_text_input( 'a4_remont_seo_term_meta[og_title]', 'Open Graph title', $meta['og_title'] );
			$this->render_textarea_input( 'a4_remont_seo_term_meta[og_description]', 'Open Graph description', $meta['og_description'], '', 3 );
			$this->render_image_picker( 'a4_remont_seo_term_meta[og_image_id]', 'Open Graph изображение', (int) $meta['og_image_id'] );
			$this->render_select_input( 'a4_remont_seo_term_meta[schema_type]', 'Тип schema', $meta['schema_type'], A4_Remont_SEO::get_schema_type_options() );
			$this->render_toggle_input( 'a4_remont_seo_term_meta[robots_index]', 'Разрешить индексацию', ! empty( $meta['robots_index'] ) );
			$this->render_toggle_input( 'a4_remont_seo_term_meta[robots_follow]', 'Разрешить переход по ссылкам', ! empty( $meta['robots_follow'] ) );
			?>
		</div>
		<?php
		echo $wrapper_close; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render redirect rules UI.
	 *
	 * @param array<int,array<string,mixed>> $rules Redirect rules.
	 * @return void
	 */
	protected function render_redirect_rules( $rules ) {
		$rules      = array_values( array_filter( $rules, 'is_array' ) );
		$next_index = count( $rules );
		?>
		<div class="a4seo-redirects" data-a4seo-repeater data-a4seo-next-index="<?php echo esc_attr( $next_index ); ?>">
			<div class="a4seo-redirects__list" data-a4seo-repeater-list>
				<?php if ( ! empty( $rules ) ) : ?>
					<?php foreach ( $rules as $index => $rule ) : ?>
						<?php $this->render_redirect_rule_row( (string) $index, $rule ); ?>
					<?php endforeach; ?>
				<?php else : ?>
					<p class="a4seo-redirects__empty" data-a4seo-repeater-empty>Пока нет ни одного правила. Добавьте первое правило редиректа.</p>
				<?php endif; ?>
			</div>

			<template data-a4seo-repeater-template>
				<?php $this->render_redirect_rule_row( '__index__', A4_Remont_SEO::get_redirect_rule_defaults(), true ); ?>
			</template>

			<div class="a4seo-redirects__footer">
				<button type="button" class="button button-secondary" data-a4seo-repeater-add>Добавить правило</button>
			</div>
		</div>
		<?php
	}

	/**
	 * Render a single redirect rule row.
	 *
	 * @param string              $index       Row index.
	 * @param array<string,mixed> $rule        Rule payload.
	 * @param bool                $is_template Whether row is a template.
	 * @return void
	 */
	protected function render_redirect_rule_row( $index, $rule, $is_template = false ) {
		$rule       = wp_parse_args( $rule, A4_Remont_SEO::get_redirect_rule_defaults() );
		$field_base = 'a4_remont_seo_settings[redirects][rules][' . $index . ']';
		$row_class  = 'a4seo-redirect-row' . ( $is_template ? ' is-template' : '' );
		?>
		<div class="<?php echo esc_attr( $row_class ); ?>" data-a4seo-repeater-item>
			<div class="a4seo-redirect-row__header">
				<strong>Правило редиректа</strong>
				<button type="button" class="button-link-delete" data-a4seo-repeater-remove>Удалить правило</button>
			</div>
			<div class="a4seo-grid a4seo-grid--redirects">
				<?php
				$this->render_text_input( $field_base . '[source]', 'Источник', (string) $rule['source'], 'Путь от корня сайта, например: /old-page' );
				$this->render_select_input( $field_base . '[match_type]', 'Тип совпадения', (string) $rule['match_type'], A4_Remont_SEO::get_redirect_match_options() );
				$this->render_text_input( $field_base . '[target]', 'Цель', (string) $rule['target'], 'Относительный путь или полный URL' );
				$this->render_select_input( $field_base . '[code]', 'HTTP-код', (string) $rule['code'], array_map( 'strval', A4_Remont_SEO::get_redirect_code_options() ) );
				$this->render_text_input( $field_base . '[note]', 'Комментарий', (string) $rule['note'], 'Необязательная пометка для команды' );
				$this->render_toggle_input( $field_base . '[enabled]', 'Правило активно', ! empty( $rule['enabled'] ) );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render text input.
	 *
	 * @param string $name        Field name.
	 * @param string $label       Field label.
	 * @param string $value       Current value.
	 * @param string $description Optional help text.
	 * @return void
	 */
	protected function render_text_input( $name, $label, $value, $description = '' ) {
		?>
		<label class="a4seo-field">
			<span class="a4seo-field__label"><?php echo esc_html( $label ); ?></span>
			<input class="regular-text a4seo-field__input" type="text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>">
			<?php if ( $description ) : ?>
				<span class="a4seo-field__hint"><?php echo esc_html( $description ); ?></span>
			<?php endif; ?>
		</label>
		<?php
	}

	/**
	 * Render number input.
	 *
	 * @param string $name        Field name.
	 * @param string $label       Field label.
	 * @param int    $value       Current value.
	 * @param int    $min         Minimum value.
	 * @param int    $max         Maximum value.
	 * @param int    $step        Step.
	 * @param string $description Optional help text.
	 * @return void
	 */
	protected function render_number_input( $name, $label, $value, $min = 0, $max = 9999, $step = 1, $description = '' ) {
		?>
		<label class="a4seo-field">
			<span class="a4seo-field__label"><?php echo esc_html( $label ); ?></span>
			<input class="regular-text a4seo-field__input" type="number" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" step="<?php echo esc_attr( $step ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>">
			<?php if ( $description ) : ?>
				<span class="a4seo-field__hint"><?php echo esc_html( $description ); ?></span>
			<?php endif; ?>
		</label>
		<?php
	}

	/**
	 * Render textarea input.
	 *
	 * @param string $name        Field name.
	 * @param string $label       Field label.
	 * @param string $value       Current value.
	 * @param string $description Optional help text.
	 * @param int    $rows        Row count.
	 * @param string $counter     Optional counter type.
	 * @return void
	 */
	protected function render_textarea_input( $name, $label, $value, $description = '', $rows = 4, $counter = '' ) {
		?>
		<label class="a4seo-field">
			<span class="a4seo-field__label"><?php echo esc_html( $label ); ?></span>
			<textarea class="large-text a4seo-field__textarea" rows="<?php echo esc_attr( $rows ); ?>" name="<?php echo esc_attr( $name ); ?>"<?php echo $counter ? ' data-a4seo-counter="' . esc_attr( $counter ) . '"' : ''; ?>><?php echo esc_textarea( $value ); ?></textarea>
			<?php if ( $description ) : ?>
				<span class="a4seo-field__hint"><?php echo esc_html( $description ); ?></span>
			<?php endif; ?>
			<?php if ( $counter ) : ?>
				<span class="a4seo-field__counter" data-a4seo-counter-value="<?php echo esc_attr( $counter ); ?>">0</span>
			<?php endif; ?>
		</label>
		<?php
	}

	/**
	 * Render select input.
	 *
	 * @param string               $name    Field name.
	 * @param string               $label   Field label.
	 * @param string               $value   Current value.
	 * @param array<string,string> $options Options.
	 * @return void
	 */
	protected function render_select_input( $name, $label, $value, $options ) {
		?>
		<label class="a4seo-field">
			<span class="a4seo-field__label"><?php echo esc_html( $label ); ?></span>
			<select class="a4seo-field__select" name="<?php echo esc_attr( $name ); ?>">
				<?php foreach ( $options as $option_value => $option_label ) : ?>
					<option value="<?php echo esc_attr( $option_value ); ?>"<?php selected( (string) $value, (string) $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<?php
	}

	/**
	 * Render toggle checkbox.
	 *
	 * @param string $name    Field name.
	 * @param string $label   Field label.
	 * @param bool   $checked Checked state.
	 * @return void
	 */
	protected function render_toggle_input( $name, $label, $checked ) {
		?>
		<label class="a4seo-toggle">
			<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="0">
			<input class="a4seo-toggle__input" type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="1"<?php checked( $checked ); ?>>
			<span class="a4seo-toggle__control"></span>
			<span class="a4seo-toggle__label"><?php echo esc_html( $label ); ?></span>
		</label>
		<?php
	}

	/**
	 * Render media picker.
	 *
	 * @param string $name          Field name.
	 * @param string $label         Field label.
	 * @param int    $attachment_id Attachment ID.
	 * @param string $description   Optional help text.
	 * @return void
	 */
	protected function render_image_picker( $name, $label, $attachment_id, $description = '' ) {
		$image_url = A4_Remont_SEO::get_image_url( $attachment_id, 'medium' );
		?>
		<div class="a4seo-field a4seo-field--image">
			<span class="a4seo-field__label"><?php echo esc_html( $label ); ?></span>
			<input type="hidden" class="a4seo-image-field__value" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $attachment_id ); ?>">
			<div class="a4seo-image-field">
				<div class="a4seo-image-field__preview">
					<?php if ( $image_url ) : ?>
						<img src="<?php echo esc_url( $image_url ); ?>" alt="">
					<?php else : ?>
						<span>Изображение не выбрано</span>
					<?php endif; ?>
				</div>
				<div class="a4seo-image-field__actions">
					<button type="button" class="button a4seo-image-field__open">Выбрать изображение</button>
					<button type="button" class="button-link-delete a4seo-image-field__clear">Очистить</button>
				</div>
			</div>
			<?php if ( $description ) : ?>
				<span class="a4seo-field__hint"><?php echo esc_html( $description ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}
}
