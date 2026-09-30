# Changelog

## 1.115.0 - 2026-09-30

### Changed
- On screens narrower than 768px, tables inside `.table-responsive` no longer wrap header text and keep a minimal column width, so a wide table scrolls horizontally instead of squeezing columns. Applies to a `.table` that is a direct child of `.table-responsive`.

## 1.114.0 - 2026-09-16

### Added
- `view.pattern_validation.validate_on_load` config (default `false`) — check prefilled values of fields with `pattern` on page load.
- `view.pattern_validation.message` config — default error text for fields with `pattern`; per field — `data-pattern-message` attribute.

### Changed
- A valid field with `pattern` no longer gets the `is-valid` class — only an invalid one is highlighted (`is-invalid`).

### Fixed
- Live `pattern` validation now requires the whole value to match, like the native browser check: previously a partial match (`\d{5}` on `abc12345xyz`) was shown as valid while the browser blocked submit. Affects unanchored patterns, and for `textarea` (no native `pattern`) makes the check stricter.
- Live `pattern` validation now works for fields added after page load (mb-blocks, AJAX modals); calling `initLivePatternValidation()` again no longer binds the handler twice.

## 1.113.0 - 2026-09-04

### Added
- `view.media.thumb` config — обирає, як `mediaFile`-компонента генерує thumb-прев'ю у списку файлів: `driver => 'conversion'` (за замовчуванням, як і раніше — `$media->getUrl('thumb')`, Spatie MediaLibrary конверсія), `'imagepreset'` (через опційний пакет `fomvasss/laravel-imagepresets`, `imagepreset_params` — параметри) або `callable` (`fn (Media $media): string` — повний контроль). Драйвер `imagepreset` викликає `imagepreset_url()` з `$bypass = true` (потрібен `fomvasss/laravel-imagepresets` від `^1.16`): URL підписується `_t`-токеном і не проходить перевірку `allowed_widths`/`allowed_heights`/`allowed_sizes` проєкту — розміри тут задаються з довіреного конфігу `imagepreset_params`, а не з призначеного для користувача вводу. Якщо в проєкті `imagepresets.trusted_bypass = false`, токен ігнорується і діють звичайні allowlist-перевірки.

### Fixed
- `Lte::pagination()` тепер приймає й `Illuminate\Contracts\Pagination\CursorPaginator` (`cursorPaginate()`), не лише `LengthAwarePaginator`/`Paginator` — рендериться як Назад/Вперед (`simple_view`), той самий шлях, що й для `simplePaginate()`. Раніше падало в `Log::error` і повертало порожній рядок.

## 1.110.0 - 2026-07-29

### Added
- `view.compact` config option (default `true`) - compact size for main-header and sidebar (`text-sm` + `nav-compact`)

## 1.0.0 - 2023-04-09

- Release

## 0.0.0 - 2023-02-26

- Started