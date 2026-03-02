# A4 Remont

Эта ветка, `start-wp-development`, является текущей базовой веткой для интеграции проекта в WordPress.

Репозиторий использует `repo-first` подход для WordPress-разработки:

- `static/` хранит исходную статическую сборку и референсную верстку
- `wp-content/` хранит deployable-слой WordPress: тему и проектные плагины
- WordPress Studio используется как локальный runtime
- GitHub Actions используется для CI-проверок и контролируемого деплоя

## 🎯 Текущая цель ветки

Задача этой ветки: превратить существующую статическую сборку в WordPress-ready архитектуру с:

- чистой базой темы на основе `_s`
- поддержкой ACF Flexible Content
- локальной разработкой через WordPress Studio
- безопасным live-sync потоком для разработки темы
- GitHub-based CI/CD для доставки темы

## 🗂️ Структура репозитория

```text
.
|-- static/                         # исходная статическая сборка
|-- wp-content/
|   |-- themes/
|   |   `-- a4-remont/             # активная WordPress-тема
|   `-- plugins/                   # только проектные плагины
|-- scripts/
|   |-- sync-studio-site.ps1       # ручной sync-хелпер для Studio
|   `-- studio-live.js             # live sync + BrowserSync workflow
|-- .github/workflows/
|   |-- theme-ci.yml               # валидация и упаковка
|   `-- deploy-wp-content.yml      # ручной production deployment
`-- .studio.local.example.json     # шаблон локального machine-config
```

## 🧱 Архитектура темы

Активная тема находится в `wp-content/themes/a4-remont`.

Текущий baseline темы включает:

- модульный bootstrap через `functions.php` и `inc/*`
- `theme.json`
- поддержку ACF local JSON через `acf-json/`
- рендер flexible-секций через `template-parts/section`
- assets, разделенные на `assets/css`, `assets/js`, `assets/img`

## 💻 Локальная разработка

### ✅ Требования

- WordPress Studio
- Node.js 18+
- npm

### ⚙️ Первичная настройка

1. Создай локальный сайт в WordPress Studio.
2. Держи этот репозиторий вне папки сайта Studio.
3. Создай локальный конфиг для своей машины:

```powershell
Copy-Item .\.studio.local.example.json .\.studio.local.json
```

4. Отредактируй `.studio.local.json` и укажи свои локальные значения:

```json
{
  "studioSitePath": "C:\\Users\\<user>\\Studio\\<your-site-folder>",
  "studioUrl": "http://localhost:8881",
  "themeSlug": "a4-remont",
  "browserPort": 3000,
  "syncPlugins": false
}
```

5. Установи локальные зависимости:

```powershell
npm install
```

### 🛠️ Локальные команды

Разовый sync темы в сайт Studio:

```powershell
npm run studio:sync:local
```

Live-разработка темы с auto-sync и BrowserSync:

```powershell
npm run studio:live:local
```

Live-разработка темы и проектных плагинов:

```powershell
npm run studio:live:local:plugins
```

Общий/manual режим без локального конфига:

```powershell
npm run studio:live -- --studioSitePath "C:\Users\<user>\Studio\<site>" --studioUrl "http://localhost:8881"
```

### 🔁 Как работает live-режим

Когда запущен `studio:live:local`:

- файлы темы отслеживаются прямо в этом репозитории
- измененные файлы копируются в сайт Studio
- BrowserSync проксирует сайт Studio на отдельный локальный порт
- браузер автоматически перезагружает страницу после изменений

Важно:

- используй URL BrowserSync, который выводится в терминале, обычно это `http://localhost:3000`
- raw URL Studio, обычно `http://localhost:8881`, остается тем же сайтом, но без автообновления страницы

## 🚀 Модель CI/CD

В проекте уже есть рабочий baseline для CI/CD.

### 🧪 CI

Workflow: `/.github/workflows/theme-ci.yml`

Что он делает:

- запускается на push в `main` и `start-wp-development`
- валидирует обязательные файлы темы
- запускает `php -l` для PHP-файлов темы и project-managed plugins
- собирает zip-артефакты темы и управляемых плагинов

### 📦 CD

Workflow: `/.github/workflows/deploy-wp-content.yml`

Что он делает:

- запускается вручную через GitHub Actions
- деплоит только `wp-content/themes/a4-remont`
- опционально деплоит только проектные плагины из `wp-content/plugins`
- не трогает uploads
- не трогает сторонние плагины
- не трогает базу данных

## 📌 Что CI/CD деплоит, а что нет

### ✅ Деплоится через git и CI/CD

- PHP, CSS, JS и изображения темы
- `theme.json`
- `acf-json`
- кастомные template parts
- проектные плагины, которые лежат в этом репозитории
- код регистрации CPT, если он находится в теме или проектных плагинах

### ❌ Не деплоится через текущий CI/CD

- страницы, записи и CPT entries
- значения ACF-полей, сохраненные в контенте
- загруженные медиафайлы в `wp-content/uploads`
- меню, виджеты, options, users
- live-контент базы данных

Это сделано специально. Текущий CI/CD pipeline деплоит код, а не контент.

## 🗃️ Модель контента и базы данных

Для этого проекта WordPress нужно воспринимать как две отдельные сущности.

### 🧩 Слой кода

Хранится в git:

- тема
- проектные плагины
- ACF JSON
- PHP-шаблоны и логика
- код регистрации CPT

### 📝 Слой контента

Хранится в WordPress database и uploads:

- содержимое страниц
- значения Flexible Content
- записи CPT
- файлы медиабиблиотеки
- editor-managed настройки

## 🛫 Рекомендуемый workflow релиза

### Во время разработки

- интегрируй статическую верстку локально в тему WordPress
- собирай ACF Flexible Content layouts
- создавай CPT и поддерживающую их логику
- используй Studio для разработки и тестирования

### Первый релиз

Если локальный WordPress-сайт уже содержит реальный стартовый контент, медиа и значения ACF:

1. задеплой код темы на сервер
2. один раз мигрируй initial database и uploads
3. активируй нужные плагины на live-сайте

### После первого релиза

Дальше используй простое правило:

- код деплоится через CI/CD
- контент ведется на staging/live WordPress, а не из локального Studio

## 🔐 Настройка GitHub deployment

Создай GitHub environments:

- `staging`
- `production`

Обязательные environment secrets:

- `DEPLOY_HOST`
- `DEPLOY_PORT`
- `DEPLOY_USER`
- `DEPLOY_PATH`
- `DEPLOY_SSH_KEY`

Что они значат:

- `DEPLOY_HOST`: хост или IP сервера
- `DEPLOY_PORT`: SSH-порт, обычно `22`
- `DEPLOY_USER`: SSH-пользователь для деплоя
- `DEPLOY_PATH`: абсолютный путь до WordPress root на сервере
- `DEPLOY_SSH_KEY`: приватный SSH-ключ, который будет использовать GitHub Actions

## 📎 Заметки

- `.studio.local.json` привязан к конкретной машине и игнорируется git
- `.studio.local.example.json` является общим шаблоном для команды
- не используй symlink/junction для темы внутри WordPress Studio, direct sync надежнее
- держи внутри `wp-content/plugins` только проектные плагины, которыми ты реально управляешь из этого репозитория
