# Changelog

## Unreleased

### Fixed
- `Lte::pagination()` тепер приймає й `Illuminate\Contracts\Pagination\CursorPaginator` (`cursorPaginate()`), не лише `LengthAwarePaginator`/`Paginator` — рендериться як Назад/Вперед (`simple_view`), той самий шлях, що й для `simplePaginate()`. Раніше падало в `Log::error` і повертало порожній рядок.

## 1.110.0 - 2026-07-29

### Added
- `view.compact` config option (default `true`) - compact size for main-header and sidebar (`text-sm` + `nav-compact`)

## 1.0.0 - 2023-04-09

- Release

## 0.0.0 - 2023-02-26

- Started