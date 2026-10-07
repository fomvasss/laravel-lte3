# Upgrading

The package follows semantic versioning within `1.x`: minor and patch releases do not require changes in code that uses the package views as they are. What does need attention are **copies**: a published config, published or copied views (`lte3:install` copies the layouts) and copied assets. The full list of changes is in [CHANGELOG.md](https://github.com/fomvasss/laravel-lte3/blob/master/CHANGELOG.md).

## After every update

- Copied assets: `php artisan vendor:publish --tag=lte3-assets --force` (not needed with symlinks)
- Published config: compare with the package [`config/lte3.php`](https://github.com/fomvasss/laravel-lte3/blob/master/config/lte3.php) — new keys inside `view` do not appear in a published copy
- Published or copied views: check the notes below for the versions you skip

## 1.123.4

A copied `layouts/inc/options.blade.php` must guard `initSummernote()` with `if (!$.fn.summernote) return;`. Without it, on pages that do not load Summernote the script stops at the first line and date pickers, live pattern validation, x-editable and the editors below it are not initialized.

## 1.123.2

The default timezone of `timepicker` and `datetimepicker` is `Europe/Kyiv`. PHP builds with current tzdata do not know `Europe/Kiev` and every page with these fields failed with 500. Replace the old name in a published `config/lte3.php` and in `.env` (`APP_TIMEZONE_CLIENT`).

## 1.123.0, 1.122.2

Fields in HTML inserted after page load (AJAX modals, mb-blocks, `.js-ajax-send` responses) are initialized automatically, and init functions are safe to call repeatedly. Existing `data-fn-inits` keep working. Behaviour changes:

- `initSelect2` no longer re-creates an initialized select2. To apply new options, call `.select2('destroy')` on it first
- `.js-ajax-send` calls the functions of `data-fn-inits` without arguments (the button was passed before)
- `initCheckbox` and `initInputCalc` are no-ops: the handlers are delegated

Project init code can join the auto init with `Lte3.register()`. See [Dynamic content](usage/dynamic-content.md).

## 1.122.0 — lfmFile

New UI of `lfmFile` / `lfmImage`. A published or copied `components/lfmFile.blade.php` keeps the old UI, and the old `js-lfm-btn-*` handlers it relies on are removed from `main.js` — delete the copy or rebuild it on the new view. The `multiple` mode, `hide_input`, `readonly`, `name_deleted`, `name_weight` are removed. See [lfmFile](fields/lfmFile.md).

## 1.121.0 — mediaFile, `lte3_asset()`

- New UI of `mediaFile` / `mediaImage`, styles and scripts in `main.css` / `main.js`. A published copy of `components/mediaFile.blade.php` keeps the old UI. Copied assets must be republished
- Copied `layouts/inc/begin.blade.php` and `end.blade.php`: load the package files through `lte3_asset()`, otherwise browsers keep the cached `main.js` and the new field works without its scripts:

```blade
<link rel="stylesheet" href="{{ lte3_asset('main.css') }}">
<link rel="stylesheet" href="{{ lte3_asset('mb-block.css') }}">
<script src="{{ lte3_asset('main.js') }}"></script>
<script src="{{ lte3_asset('mb-blocks.js') }}"></script>
```

- Field texts go through `__()` — add translations to `lang/<locale>.json`, see [mediaFile](fields/mediaFile.md)

## 1.119.0 — hidden fields

A copied `components/form.blade.php` must render the `$hidden` variable next to `_token`, otherwise `Lte3::formHiddenUsing()` has no effect:

```blade
@foreach($hidden ?? [] as $hiddenName => $hiddenValue)
    <input name="{{ $hiddenName }}" value="{{ $hiddenValue }}" type="hidden">
@endforeach
```

## 1.117.0 — static modals

`#modal-lg` and `#modal-xl` no longer close on a click outside or on Esc (`data-backdrop="static" data-keyboard="false"`). Apply the change to a copied `layouts/inc/options.blade.php` if you want the same behaviour.

## 1.116.0 — content header

The search field and buttons of `parts/content-header` wrap differently on small screens and the search is visible below 768px. A copied view keeps the old markup.

## 1.116.1

If the project has its own copy of the `$('.dropdown').hover(...)` snippet that sets `overflow-x: clip` on `.table-responsive`, remove it — the package restores horizontal scrolling itself.

## 1.114.0 — pattern validation

Live `pattern` validation requires the whole value to match, like the browser does, and a valid field no longer gets `is-valid`. Unanchored patterns that relied on a partial match now show an error. See [Pattern validation](usage/pattern-validation.md).
