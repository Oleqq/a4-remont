# A4 Ремонт — стартовый бандл (DS-ART)

Стек: Pug + SCSS + vanilla JS. Сборка: **Prepros** (как в регламенте).

## Структура (по регламенту)
- `css/` — скомпилированный CSS
- `scss/global/` — глобальные стили (reset/vars/mixins/base/header/footer)
- `scss/section/` — стили секций/страниц
- `js/` — скрипты (ES6+, модули)
- `fonts/` — локальные шрифты (woff2 + woff)
- `images/` — изображения (по секциям)
- `img/` — SVG-иконки и спрайт (`icons.svg`)
- `template-parts/` — шаблоны (тут лежат исходники Pug)

## Быстрый старт в Prepros
1) Открой Prepros → **Add Project** → выбери папку проекта.
2) Проверь пути компиляции:
   - `scss/style.scss` → output: `css/style.css` (compressed можно включить на прод)
   - `template-parts/pug/pages/*.pug` → output: `*.html` в корень проекта
   - `js/script.js` → output: `js/script.min.js` (по желанию; можно без минификации на старте)
3) Включи **Auto Refresh / BrowserSync** в Prepros (по вкусу).
4) Открывай `index.html`.

## Важно (коротко)
- БЭМ обязателен.
- На странице 1 `h1` (обычно `visually-hidden`).
- Hover только `@media (min-width: 1200px)`.
- Иконки — SVG-спрайт `img/icons.svg` + `use`.
- Фото — WebP (в `images/`), у `<img>` всегда `alt/title/width/height`.
