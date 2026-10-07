# JavaScript API

The admin panel JavaScript consists of three parts, all included by the package layout:

- `public/main.js` (served as `/vendor/lte3/main.js`): field init functions, the `Lte3` init registry, data-attribute driven [actions](../usage/actions.md), `mediaFile` and `lfmFile` field logic;
- `public/mb-blocks.js`: the [mb-blocks](../usage/mb-blocks.md) repeater;
- `lte3::layouts.inc.options` (Blade view): config passed from PHP, the action form and modals, date pickers, editors, pattern validation, theme switcher.

Everything is built on jQuery 3, Bootstrap 4 and jQuery UI from AdminLTE 3. There is no build step and no module system: the API is a set of global variables.

## Load order

`lte3::layouts.inc.end` renders, in this order:

| # | Script |
| --- | --- |
| 1 | `vendor/adminlte/plugins/jquery/jquery.min.js` |
| 2 | `vendor/adminlte/plugins/jquery-ui/jquery-ui.min.js` (+ `$.widget.bridge('uibutton', $.ui.button)`) |
| 3 | `vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js` |
| 4 | `vendor/adminlte/plugins/toastr/toastr.min.js` |
| 5 | `vendor/adminlte/plugins/sweetalert2/sweetalert2.all.min.js` |
| 6 | `vendor/adminlte/plugins/moment/moment.min.js` |
| 7 | `vendor/adminlte/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js` |
| 8 | `vendor/adminlte/plugins/select2/js/select2.full.min.js` |
| 9 | `vendor/lte3/plugins/pace/pace.min.js` |
| 10 | `vendor/lte3/plugins/jquery-sortable/jquery-sortable.js` |
| 11 | `vendor/lte3/plugins/datepicker/datetimepicker.full.js` |
| 12 | `vendor/lte3/plugins/multidatespicker/jquery-ui.multidatespicker.min.js` |
| 13 | `vendor/lte3/plugins/magnific-popup/dist/jquery.magnific-popup.min.js` |
| 14 | `vendor/lte3/plugins/x-editable/dist/bootstrap-editable.js` |
| 15 | `vendor/lte3/plugins/select2-to-tree/src/select2totree.js` |
| 16 | `vendor/lte3/plugins/bootstrap-treeview/bootstrap-treeview.min.js` |
| 17 | `vendor/adminlte/dist/js/adminlte.js` |
| 18 | `vendor/adminlte/dist/js/demo.js` |
| 19 | `lte3_asset('main.js')` |
| 20 | `lte3_asset('mb-blocks.js')` |
| 21 | `@stack('scripts')` |
| 22 | `@include('lte3::layouts.inc.options')` |

`lte3_asset()` appends `?v=<file modification time>`, so browsers fetch the new files right after a package update, see [Helpers](helpers.md).

The matching stylesheets are in `lte3::layouts.inc.begin` (Font Awesome, flag-icon-css, jQuery UI, toastr, sweetalert2, colorpicker, select2, PACE, datetimepicker, multidatespicker, Magnific Popup, x-editable, select2-to-tree, bootstrap-treeview, `adminlte.min.css`, `main.css`, `mb-block.css`), followed by `@stack('styles')`.

### Bundled plugins

| Plugin | Used for |
| --- | --- |
| [Select2](https://select2.org/) 4 | [select2](../fields/select2.md) |
| [select2-to-tree](https://github.com/clivezhg/select2-to-tree) | [select2Tree](../fields/select2Tree.md) |
| [bootstrap-treeview](https://github.com/jonmiles/bootstrap-treeview) | [treeview](../fields/treeview.md) |
| [jquery-sortable](https://johnny.github.io/jquery-sortable/) (as `$.fn.sortableNested`) | [nestedset](../fields/nestedset.md) |
| jQuery UI sortable | `.sortable-y`, [mb-blocks](../usage/mb-blocks.md), [mediaFile](../fields/mediaFile.md) |
| [Bootstrap Colorpicker](https://itsjavi.com/bootstrap-colorpicker/) | [colorpicker](../fields/colorpicker.md) |
| [xdsoft datetimepicker](https://xdsoft.net/jqplugins/datetimepicker/) | [datetime](../fields/datetime.md) |
| [Multiple Dates Picker for jQuery UI](https://dubrox.com/Multiple-Dates-Picker-for-jQuery-UI/) | [datetime](../fields/datetime.md) (`multidatespicker`) |
| [X-editable](https://vitalets.github.io/x-editable/) | [xEditable](../fields/xEditable.md) |
| [Magnific Popup](https://dimsemenov.com/plugins/magnific-popup/) | `.js-popup-image`, `.js-popup-images` |
| [toastr](https://github.com/CodeSeven/toastr) | Messages of all AJAX handlers, flash alerts |
| [SweetAlert2](https://sweetalert2.github.io/) | Flash alerts with `view.alerts` = `sweetalert` |
| [PACE](https://codebyzach.github.io/pace/) | Progress bar, restarted on every jQuery AJAX request |
| moment.js | Available for project code |

Plugins shipped in `vendor/lte3/plugins` but not loaded by the layout: EasyMDE, highlight.js, Chart.js 4, the LFM stand-alone button. See [Editors](../usage/editors.md).

## Config passed from Blade

| Value | Source | Used by |
| --- | --- | --- |
| `<html lang>` | `app()->getLocale()` (`_` replaced by `-`) | `LANGUAGE` for select2, CKEditor, TinyMCE |
| `<meta name="csrf-token">` | `csrf_token()` | `$.ajaxSetup` header `X-CSRF-TOKEN`; `.js-ajax-send` submit mode; LFM drag and drop upload |
| `#js-action-form` destination input name | `view.next_destination_key` | [Actions](../usage/actions.md#action-form-js-action-form) |
| Modal opened on load | `view.modal_key` in `old()` / request / session | [Actions](../usage/actions.md#reopening-a-modal-after-a-redirect-_modal) |
| `patternValidateOnLoad`, `patternDefaultMessage` | `view.pattern_validation.*` | [Pattern validation](../usage/pattern-validation.md) |
| Theme | `view.theme` | Theme switcher, TinyMCE skin |
| TinyMCE `document_base_url` | `url('/')` | TinyMCE |

`options.blade.php` sets the CSRF header for all jQuery requests:

```js
$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
```

## `Lte3`

Global registry of init functions for HTML inserted after page load. See [Dynamic content](../usage/dynamic-content.md).

### `Lte3.register(name, fn)`

Adds `fn(root)` to the registry under `name` (an existing name is replaced). The function must look for fields only inside `root` and be safe to call repeatedly.

### `Lte3.init(root)`

Calls every registered function with `root` (element or jQuery), each in its own `try/catch` (errors are logged as `Lte3 init <name>:`), then triggers `lte3:init` on `root`. Called by the package for `.js-modal-fill-html` content, new and cloned `.mb-wrap` and `.f-multyblocks` items, and HTML from `.js-ajax-send` responses. Not called on page load.

### `Lte3.inits`

The registry object, `{name: fn}`.

## Field init functions (`main.js`)

Global variables. Before the jQuery ready handler of `main.js` has run they are empty functions.

All take an optional `root` (element or jQuery): fields are looked up inside it and in `root` itself, without it in the whole document. Already initialised fields are skipped.

| Function | Initialises | Registered with `Lte3` | Called on load |
| --- | --- | --- | --- |
| `initTooltip(root)` | Bootstrap tooltip on `[data-toggle="tooltip"]` | yes | yes |
| `initSortableY(root)` | jQuery UI sortable on `.sortable-y`, see [Actions](../usage/actions.md#sortable-y-jquery-ui) | yes | yes |
| `initJsVerificationSlugField(root)` | Unlocks `.js-slug-field-input` when the checkbox of `.js-verification-slug-field` is checked ([slug](../fields/slug.md)) | yes | yes |
| `initColorpicker(root)` | `.f-colorpicker` ([colorpicker](../fields/colorpicker.md)) | yes | yes |
| `initSelect2(root)` | `.f-select2`, then the visibility of `.js-map-blocks` blocks ([select2](../fields/select2.md)) | yes | yes |
| `initSelect2Tree(root)` | `.f-select2-tree-wrap`, loads the tree by AJAX ([select2Tree](../fields/select2Tree.md)) | yes | yes |
| `initTreeview(root)` | `.f-treeview-wrap` from `data-url` or `data-data` ([treeview](../fields/treeview.md)) | yes | yes |
| `initMediaFile(root)` | `.f-media`: order, state, sorting ([mediaFile](../fields/mediaFile.md)) | yes | yes |
| `initCheckbox()` | No-op, the ajax checkbox is delegated | no | - |
| `initInputCalc()` | No-op, `.js-input-calc` is delegated | no | - |
| `initLfmFile()` | No-op, the `lfmFile` field is delegated | no | - |

`$.fn.select2` is wrapped by `main.js`: before a select is initialised it gets a unique `data-select2-id` (`lte3-N`) if it has none or shares it with another element. This keeps two selects with the same `id` (a field on the page and the same field in a modal) from destroying each other. It applies to every `.select2()` call, including project code.

## Global functions of `options.blade.php`

Defined at the end of the layout. They take no arguments, process the whole document and are not guarded against repeated calls.

| Function | What it does |
| --- | --- |
| `initSummernote()` | Summernote on `.f-summernote` |
| `initCodeMirror()` | CodeMirror on `.f-codeMirror` |
| `initDatetimepicker()` | xdsoft datetimepicker on `.f-datetimepicker` (`Y-m-d H:i:s`), `.f-datepicker` (`Y-m-d`), `.f-timepicker` (`H:i:s`), locale `uk`; then `initMultiDatesPicker()` |
| `initDatetimepickerOptions()` | Deprecated alias of `initDatetimepicker()` |
| `initMultiDatesPicker()` | Multiple dates picker on `.f-multiDatesPicker` |
| `initPopupImage()` | Magnific Popup on `.js-popup-image` and `.js-popup-images` |
| `initEasyMdEditor()` | EasyMDE on `.f-md-editor` |
| `initLivePatternValidation()` | Checks prefilled `pattern` fields when `validate_on_load` is on, see [Pattern validation](../usage/pattern-validation.md) |
| `initSummaryTable()` | Copies `thead tr.t-summary` of every table to `tfoot` |
| `initTinyMce()` | TinyMCE on `.f-tinymce`; defined only when `public/vendor/tinymce` exists |
| `initLfmBtn()` | LFM stand-alone button on `.f-lfm-btn`; defined only when `public/vendor/laravel-filemanager` exists |
| `initEditorJS()` | Editor.js on `#editorjs`; defined only when `public/vendor/editorjs` exists |

Also on load, without a named function: x-editable on `.f-x-editable` ([xEditable](../fields/xEditable.md)), fixed cell widths while dragging `table tbody.sortable-y` rows, highlight.js on `.f-highlight`, CKEditor on `textarea.f-cke-mini`, `.f-cke-small`, `.f-cke-full`.

Global config objects: `ckMini`, `ckSmall`, `ckFull` (CKEditor), `tinymceOptions` and `tinymceSelector` (TinyMCE). They are created before the editors are initialised on load, so changing them affects only later calls (e.g. `tinymce.init(tinymceOptions)` for inserted content).

See [Editors](../usage/editors.md) for assets and markup.

## Other globals

| Name | Description |
| --- | --- |
| `window.lte3ToggleTheme()` | Cycles the theme `light` → `dark` → `system`, stores it in `localStorage` (`lte3-theme`) and applies it |
| `window.lteLfmPicked(items)` | Callback of Laravel File Manager for the `lfmFile` field (`?callback=lteLfmPicked`): sets the first item's URL and closes the modal |
| `window.onAjaxSuccess(response, $btn)` | Optional hook you define; called after every successful `.js-ajax-send` |
| `window.onHtmlUpdated(html)` | Optional hook you define; called after every successful `.js-ajax-send` with `response.html` |
| `window.SetUrl` | Set by the LFM stand-alone button and the Editor.js LFM tool before opening the file manager popup |

`LANGUAGE` is a `const` of the options script (not a `window` property); `lteAlert()`, `numberWithSpaces()`, `humanFileSize()` and `callFnInits()` are private to `main.js`.

## Events

| Event | Target | When |
| --- | --- | --- |
| `lte3:init` | `root` of `Lte3.init()`, bubbles | After the registered init functions ran on inserted HTML |
| `sortupdate` | `.mb-items` | jQuery UI event after a drop; mb-blocks renumbers items |
| `change` | `.js-lfm-input` | After a file is picked, dropped or cleared in `lfmFile`; triggers `url_save` |
| `input`, `change` | Field | Triggered after `.js-token-insert` inserts a token |

## Data attributes read from markup

A summary; the details are on the linked pages.

| Attribute | Element | Page |
| --- | --- | --- |
| `data-fn-inits` | `.js-modal-fill-html`, `.js-ajax-send`, `.mb-wrap`, `.f-multyblocks .f-wrap` | [Dynamic content](../usage/dynamic-content.md#data-fn-inits) |
| `data-url`, `data-method`, `data-confirm`, `data-data`, `data-submit`, `data-csrf` | `.js-ajax-send` | [Actions](../usage/actions.md#ajax-button-js-ajax-send) |
| `data-url`, `data-method`, `data-confirm`, `data-destination` | `.js-click-submit` | [Actions](../usage/actions.md#js-click-submit) |
| `data-target`, `data-url` | `.js-modal-fill-html` | [Actions](../usage/actions.md#modals-with-ajax-content-js-modal-fill-html) |
| `data-url`, `data-method`, `data-input-weight-class` | `.sortable-y` | [Actions](../usage/actions.md#sortable-y-jquery-ui) |
| `data-pat` | Links in `.js-activeable`, `.js-activeable-url` | [Actions](../usage/actions.md#active-menu-items) |
| `data-pattern-message` | `input[pattern]`, `textarea[pattern]` | [Pattern validation](../usage/pattern-validation.md) |
| `data-mb-min`, `data-mb-max`, `data-mb-placeholder` | `.mb-wrap`, `template.mb-template` | [mb-blocks](../usage/mb-blocks.md) |
| `data-url-save`, `data-method-save`, `data-url-suggest`, `data-url-tags`, `data-map` | `.f-select2` | [select2](../fields/select2.md) |

## Server responses expected by the handlers

| Handler | Request | Response |
| --- | --- | --- |
| `.js-ajax-send` | `data-method` to `data-url`, payload `data-data` | JSON: `message`, `status`/`type`, `html`, `htmlAppends`, `action`, `redirect_url`, `selector` |
| `.js-modal-fill-html` | `GET data-url` | JSON `{"html": "<modal-content markup>"}` |
| `.js-form-submit-prevalidate` | Form method and action, form data + `prevalidate=1` | 2xx to continue; 422 with `errors` to show them |
| `.sortable-y` | `data-method` (POST) to `data-url`, `data[]` = item ids in order | JSON, content not used |
| `.js-sortable-nested` | `data-method` (POST) to `data-url` of `.f-sortable-nested-wrap`, `data` = serialized tree | JSON `{"message": "..."}` |
