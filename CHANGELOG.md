# Changelog

## 1.123.0 - 2026-10-04

### Added
- Fields in HTML inserted after page load are initialized automatically: the content of `.js-modal-fill-html` modals, new items of `.f-multyblocks` and `.mb-wrap` (added and cloned), html from a `.js-ajax-send` response. `data-fn-inits` isn't needed for `initSelect2`, `initColorpicker`, `initSortableY`, `initSelect2Tree`, `initTreeview`, `initTooltip`, `initJsVerificationSlugField`, `initMediaFile` anymore; existing `data-fn-inits` keep working and run after the auto init.
- `Lte3.register(name, fn)` — adds a project init function to the auto init. The function takes `root` (the inserted content) and must be safe to call repeatedly.
- `Lte3.init(root)` — runs all registered init functions inside `root`, then triggers the `lte3:init` event on it, e.g. `$(document).on('lte3:init', function (e) { ... e.target ... })`.

## 1.122.2 - 2026-10-04

### Fixed
- JS init functions (`initSelect2`, `initColorpicker`, `initCheckbox`, `initJsVerificationSlugField`, `initInputCalc`, `initSortableY`, `initSelect2Tree`, `initTreeview`, `initTooltip`) can be called again safely, e.g. from `data-fn-inits` of a modal: fields that are already initialized are skipped. Before, every call added one more handler, so `url_save` of select2, colorpicker and ajax checkbox sent the request N times, and select2-tree / treeview were rebuilt with a new AJAX request.
- `data-fn-inits` with an empty value or a function name that doesn't exist no longer throws in `.js-modal-fill-html` and dynamic blocks (`.f-multyblocks`): a warning is logged and the other functions still run.

### Changed
- The init functions above take an optional `root` (element or jQuery) and look for fields only inside it; without it — in the whole document, as before.
- `initCheckbox` and `initInputCalc` are no-ops now: the ajax checkbox and the calculator input work through delegated handlers, also in content loaded later. The functions stay for existing `data-fn-inits`.
- `initSelect2` doesn't re-create a select2 that is already initialized. To apply new options to such a field, call `.select2('destroy')` on it first.
- `.js-ajax-send` calls functions from `data-fn-inits` without arguments (the button was passed before).

## 1.122.1 - 2026-10-04

### Fixed
- `lfmFile`: attributes from `field_attrs` (`data-name`, `id`, `required`, ...) are rendered on the value input again, not only with `editable`. Block repeaters that rename fields by `data-name` lost the picked file in new items.

## 1.122.0 - 2026-10-04

### Added
- New UI of the `lfmFile` / `lfmImage` field (Laravel File Manager): an empty field is a drop zone, a click opens File Manager in a modal instead of a popup window (LFM `callback` param), a file dragged from the computer is uploaded to File Manager (`{lfm_prefix}/upload`) and its URL becomes the value. A picked file is a card: an image tile with a thumbnail or a document row with a type icon, with Replace / Open / Clear buttons. The value is still a URL string under the same name; `url_save`, `trim_host`, `lfm_category`, `label`, `help` work as before.
- `lfmFile` options: `editable` (a text input for a manual URL), `thumb_size`, `lfm_prefix` (default `/filemanager`), `lfm_folder` (`working_dir` for dropped files).
- `lfmFile` takes the value from `old()` / the form model when `path` is `null`, like `text` and other fields — before that a `null` path always showed an empty field.
- `lte3.view.lfm.thumb` — thumbnail of the `lfmImage` tile: `null` (the image itself), `'imagepreset'` or a class with `__invoke(string $url, ?int $size): string`.
- `thumb_size` attribute and `lte3.view.media.thumb_size` config (110) — minimal width of an image tile in px for `mediaImage` and `lfmImage`; the `imagepreset` thumbnail is generated 2× of it. `MediaThumbUrlResolver::resolve()` takes the size, the new `resolveUrl()` works with a URL (lfm), a callable driver receives `($media, $size)`.
- `mediaFile`: files that don't match `accept` are no longer dropped silently — the drop zone shows "Not allowed: file.exe." (key `Not allowed: :files.`).

### Changed
- `lfmFile`: the `multiple` mode is removed (it was used only on example pages): with an array `path` the first item is taken. `hide_input`, `readonly`, `name_deleted`, `name_weight` are no longer used. The old `js-lfm-btn-add/delete/clear` handlers are removed from `main.js`; `stand-alone-button.js` and `initLfmBtn()` stay for layout copies in projects and don't affect the new field. If you have published or copied `components/lfmFile.blade.php`, the copy keeps the old UI.
- `mediaFile`, single field: a file that will be replaced by a newly picked one is hidden instead of being shown faded next to it; × on the new file brings it back.
- Thumbnails with the `conversion` driver: if the conversion is not generated yet (queue), the original is shown instead of a broken image.

### Fixed
- `mediaFile`: drag-and-drop handlers are scoped to `.f-media` — a drop on another element with the `f-media-drop` class threw an error and stopped other drop handlers.

## 1.121.0 - 2026-10-04

### Added
- New UI of the `mediaFile` / `mediaImage` field: drop zone (click or drag and drop), previews of picked files before saving, thumbnail grid for images and a file list with type icons for documents, delete with restore instead of `confirm()`, drag-and-drop sorting (grid in both directions), a single field hides the drop zone while it has a file and replaces it with a "Replace" button. The `accept` attribute is shown under the drop zone in plain words (`image/*,.pdf` → "Allowed: Images, PDF") and files of other types are not added.
- File properties are edited in a modal (pencil on a file) instead of inputs under every file. `custom_properties` accepts the `Lte3::field` format: `['alt', 'title']`, `['alt' => 'Alt text']` or `[['name' => 'caption', 'type' => 'textarea', ...]]`.
- `format` attribute and `lte3.view.media.format` config: `legacy` (default) keeps the old form fields (`name[]`, `name_deleted`, `name_weight[id]`, `name_custom[id][prop]`); `expand` sends a row per file (`name[N][id|file|weight|delete|is_main|<property>]`) — properties and order of new files, main file (`main` attribute). `expand` needs fomvasss/laravel-medialibrary-extension 6.4.1+ and validation rules that accept an array in `name.*` (the file is in `name.*.file`).
- `initMediaFile()` in `main.js`; for the field in an AJAX modal use `data-fn-inits="initMediaFile"`.
- `lte3_asset('main.js')` helper — URL of an lte3 asset with `?v=<file modification time>`, so browsers load new `main.js` / `main.css` right after a package update. The package layout uses it for `main.js`, `main.css`, `mb-blocks.js`, `mb-block.css`. If you have published or copied `layouts/inc/begin.blade.php` / `end.blade.php`, replace `/vendor/lte3/main.js` (and a manual `?v=...`) with `{{ lte3_asset('main.js') }}` in your copy — otherwise browsers may keep the old cached files and the new media field works without its scripts and styles.

### Changed
- Styles and behaviour of the field are in `main.css` / `main.js`. If lte3 assets are published (copied) instead of symlinked, publish them again, otherwise the new view works without them. If you have published or copied `components/mediaFile.blade.php`, the copy keeps the old UI.
- Field texts are English through `__()` — add translations to `lang/<locale>.json` of the project (the list of keys is in the docs, field `mediaFile`).
- Examples: the media block shows the legacy grid with properties, a single image with alt and an `expand` field with main file and `accept`; the documents example used the `documents` collection that the example model doesn't have — now `files`.

## 1.120.0 - 2026-10-02

### Added
- The `Lte3::formHiddenUsing()` resolver receives the form model (`model` of `formOpen()`, or `null`) and the `formOpen()` attributes, so hidden fields can depend on the edited record — e.g. a fingerprint to reject a save over changes made after the form was opened. Resolvers without parameters keep working.

## 1.119.0 - 2026-10-02

### Added
- `Lte3::formHiddenUsing(callable $resolver)` — extra hidden fields for every non-GET form opened by `Lte3::formOpen()`. The resolver returns `[name => value]` and is called on each form render; `null` and `''` values are skipped. Main use: send the content locale the form was opened with, so a save after the session locale was switched in another tab does not write the text into the wrong translation. If you have published or copied `components/form.blade.php`, render the `$hidden` variable next to `_token`.

## 1.118.0 - 2026-10-01

### Added
- `types` and `class` parameters for `lte3::parts.alerts.bootstrap`, so the block can be included on its own in a specific place of the page — e.g. only errors and warnings above a form: `@include('lte3::parts.alerts.bootstrap', ['types' => ['warning', 'error'], 'class' => ''])`. Without parameters it shows all types in `container-fluid p-2`, as before.

### Fixed
- The `error`/`danger` alert of `lte3::parts.alerts.bootstrap` showed `1` instead of the message text.
- Typos in the sweetalert titles: `Warting!` → `Warning!`, `Excelent!` → `Excellent!`.

## 1.117.0 - 2026-09-30

### Added
- `tokens_action` option for the `text` and `textarea` components — what a click on a token does: `copy` (default, copies to the clipboard as before), `insert` (inserts the token into the field at the cursor; for a textarea with TinyMCE — into the editor), `none` (the list is only shown).

### Changed
- `#modal-lg` and `#modal-xl` in `lte3::layouts.inc.options` no longer close on a click outside the modal or on Esc (`data-backdrop="static" data-keyboard="false"`), so form data in AJAX modals is not lost by accident. They close with the close buttons. `#modal-sm` is unchanged. If you have published or copied this view, apply the change to your copy.

### Fixed
- The tokens button of `text` and `textarea` moved below the field when the field had `help` text. It now stays inside the field; the validation error text of these fields remains visible.

## 1.116.2 - 2026-09-30

### Fixed
- File lists of the `mediaFile`, `file` and `lfmFile` components are wrapped in `.table-responsive`: on narrow screens a long file name or path scrolls inside the field instead of overflowing the card.

## 1.116.1 - 2026-09-30

### Fixed
- A table inside `.table-responsive` lost horizontal scrolling until page reload after its row actions dropdown was opened (most visible on touch devices). Scrolling is now restored when the dropdown closes. Also covers `.dropleft` menus and rows added after page load. If your project has its own copy of the `$('.dropdown').hover(...)` snippet that sets `overflow-x: clip`, remove it.

## 1.116.0 - 2026-09-30

### Changed
- `lte3::parts.content-header` on small screens: the search field and the buttons (filter, `btn-content-header` section) stay in one row while they fit, otherwise the buttons move to the next row as a whole. The title and the tools now stack below 768px instead of 576px.
- The search field of `lte3::parts.content-header` is now shown on screens narrower than 768px (it used to be hidden there).
- If you have published or copied this view, the changes do not apply until you update your copy.

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