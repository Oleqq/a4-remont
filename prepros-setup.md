# Prepros настройка (шпаргалка)

## 1) SCSS
- Input: `scss/style.scss`
- Output: `css/style.css`
- Autoprefixer: ON
- Source maps: по желанию (на dev — ON)

## 2) Pug
- Input: `template-parts/pug/pages/*.pug`
- Output: в корень проекта, например `index.html`
- Pretty HTML: ON (на dev), OFF (на прод)

## 3) JS
- Input: `js/script.js`
- Минификация: по желанию (на старте можно OFF)

## 4) Live reload
- Включи BrowserSync / Live Preview внутри Prepros.
