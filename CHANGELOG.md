# Changelog

## Unreleased

### Added
- `view.media.thumb` config — обирає, як `mediaFile`-компонента генерує thumb-прев'ю у списку файлів: `driver => 'conversion'` (за замовчуванням, як і раніше — `$media->getUrl('thumb')`, Spatie MediaLibrary конверсія), `'imagepreset'` (через опційний пакет `fomvasss/laravel-imagepresets`, `imagepreset_params` — параметри) або `callable` (`fn (Media $media): string` — повний контроль).

### Fixed
- `Lte::pagination()` тепер приймає й `Illuminate\Contracts\Pagination\CursorPaginator` (`cursorPaginate()`), не лише `LengthAwarePaginator`/`Paginator` — рендериться як Назад/Вперед (`simple_view`), той самий шлях, що й для `simplePaginate()`. Раніше падало в `Log::error` і повертало порожній рядок.

## 1.110.0 - 2026-07-29

### Added
- `view.compact` config option (default `true`) - compact size for main-header and sidebar (`text-sm` + `nav-compact`)

## 1.0.0 - 2023-04-09

- Release

## 0.0.0 - 2023-02-26

- Started