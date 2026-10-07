# Dynamic content

Most fields need JavaScript: select2, colorpicker, sortable lists, treeview and so on. This page explains when that JavaScript runs, how fields in HTML inserted after page load (AJAX modals, repeaters, AJAX responses) get initialised, and how to plug your own widgets into the same mechanism.

## Initialisation on page load

The package layout loads the scripts in this order (see [JavaScript API](../reference/javascript.md) for the full list):

1. jQuery, jQuery UI, Bootstrap and the bundled plugins;
2. `main.js` and `mb-blocks.js`;
3. the `scripts` stack (`@push('scripts')`);
4. `lte3::layouts.inc.options`.

`main.js` initialises its fields in a jQuery ready handler: `initTooltip`, `initSortableY`, `initJsVerificationSlugField`, `initColorpicker`, `initSelect2`, `initSelect2Tree`, `initTreeview`, `initMediaFile`. `options.blade.php` runs its init functions (`initSummernote`, `initCodeMirror`, `initDatetimepicker`, `initPopupImage`, `initEasyMdEditor`, `initLivePatternValidation`, `initSummaryTable`, x-editable, the editors) directly when the script is parsed, at the end of `<body>`.

Behaviours bound with delegated handlers (`$(document).on(...)`) need no initialisation at all and work for any markup, whenever it was added: ajax checkbox, select2 `url_save`, calculator input, links/lists add and remove buttons, `lfmFile`, `mediaFile` buttons, live pattern validation, all [actions](actions.md).

> [!NOTE]
> The init functions of `main.js` are assigned inside the ready handler. Before that they are empty stubs. In a script pushed to the `scripts` stack, call them inside `$(function () { ... })`: ready handlers run in registration order, so yours runs after `main.js` has set them.

## Automatic initialisation of inserted HTML

The package itself calls `Lte3.init(root)` on HTML it inserts:

| Source | `root` |
| --- | --- |
| `.js-modal-fill-html` | `{data-target} .modal-content` |
| `.mb-wrap` add and clone buttons ([mb-blocks](mb-blocks.md)) | The new `.mb-item` |
| `.f-multyblocks` add button (deprecated repeater) | The new `.f-item` |
| `.js-ajax-send` response with `html` | Every updated container |

`Lte3.init(root)` runs every registered init function with `root`, then triggers the `lte3:init` event on it. Registered by the package:

| Name | Initialises |
| --- | --- |
| `initTooltip` | `[data-toggle="tooltip"]` |
| `initSortableY` | `.sortable-y` |
| `initJsVerificationSlugField` | `.js-verification-slug-field` ([slug](../fields/slug.md)) |
| `initColorpicker` | `.f-colorpicker` |
| `initSelect2` | `.f-select2`, and `.js-map-blocks` visibility |
| `initSelect2Tree` | `.f-select2-tree-wrap` |
| `initTreeview` | `.f-treeview-wrap` |
| `initMediaFile` | `.f-media` (sorting, state) |

So a select2 or colorpicker in an AJAX modal works without any extra attribute:

```blade
<button type="button" class="btn btn-default js-modal-fill-html"
        data-target="#modal-lg"
        data-url="{{ route('admin.orders.status-modal', $order) }}">
    Change status
</button>
```

`Lte3.init()` is not called on page load and the `lte3:init` event is not triggered then.

> [!NOTE]
> There is no `MutationObserver`. HTML that your own code inserts (`$.get(...).then(html => $box.html(html))`) is not initialised automatically; call `Lte3.init($box)` after inserting it.

## Safe repeated calls

Every registered function:

- takes an optional `root` (element or jQuery). It looks for fields inside `root` and in `root` itself; without `root`, in the whole document;
- skips fields that are already initialised, so calling it again does not create a second widget, does not bind a second `url_save` handler and does not repeat the AJAX request of select2-tree or treeview.

The "already initialised" mark is stored in jQuery data, not in an attribute. Markup cloned from an initialised field (for example a repeater template copied with `innerHTML`) has no jQuery data and is initialised again.

`initSelect2` does not apply new options to an existing select2. To change options, destroy it first:

```js
$('#status').select2('destroy');
initSelect2($('#status').parent());
```

`initCheckbox`, `initInputCalc` and `initLfmFile` are no-ops: those fields use delegated handlers. They exist only so that old `data-fn-inits` values keep working.

## `data-fn-inits`

Use `data-fn-inits` for functions that are not registered with `Lte3`, for example the init functions of `options.blade.php`:

```blade
<button type="button" class="btn btn-default js-modal-fill-html"
        data-target="#modal-xl"
        data-url="{{ route('admin.posts.edit-modal', $post) }}"
        data-fn-inits="initDatetimepicker,initPopupImage">
    Edit
</button>
```

The value is a comma-separated list of names of global functions (`window[name]`). They are called without arguments, after `Lte3.init()`:

| Where | When |
| --- | --- |
| `.js-modal-fill-html` | After the content is inserted and the modal is shown |
| `.mb-wrap` | After an item is added or cloned |
| `.f-multyblocks` wrapper (`.f-wrap`) | After an item is added |
| `.js-ajax-send` | When the request completes, on success and on error |

A name that is not a function is skipped; `main.js` logs `No such function: name` (mb-blocks skips silently).

> [!WARNING]
> The functions defined in `options.blade.php` (`initSummernote`, `initCodeMirror`, `initDatetimepicker`, `initPopupImage`, `initEasyMdEditor`, `initSummaryTable`, `initTinyMce`, `initLfmBtn`) take no `root`, always process the whole document and are not guarded against repeated calls. Calling `initCodeMirror` or `initEasyMdEditor` again creates a second editor over every field already initialised. Prefer a scoped function registered with `Lte3.register()`.

## Registering your own init function

```js
Lte3.register('initPhoneMask', function (root) {
    $(root || document).find('.js-phone').each(function () {
        if ($(this).data('phoneMask')) {
            return;
        }
        $(this).data('phoneMask', true).inputmask('+38 (999) 999-99-99');
    });
});
```

- `root` can be an element or a jQuery object; it is `undefined` only when you call the function yourself without arguments.
- The function must be safe to call repeatedly on the same content.
- An exception in one function is caught and logged as `Lte3 init <name>:`; the other functions still run.
- Registering under an existing name replaces that function.
- Registration does not run the function on page load. Call it once yourself if it must also cover the initial page.

The same pattern for a widget from `options.blade.php`, scoped to the inserted content:

```js
Lte3.register('initDatepickerScoped', function (root) {
    $(root).find('.f-datepicker').datetimepicker({timepicker: false, format: 'Y-m-d', scrollInput: false});
});
```

## The `lte3:init` event

```js
$(document).on('lte3:init', function (e) {
    $(e.target).find('[data-autofocus]').first().trigger('focus');
});
```

The event is triggered with jQuery on the `root` passed to `Lte3.init()` and bubbles to `document`. It fires after all registered functions have run.

## Calling init manually after your own AJAX

```js
$.get('/admin/orders/' + id + '/items', function (html) {
    var $box = $('#order-items').html(html);
    Lte3.init($box);
});
```

To re-run a single function on a fragment:

```js
initSelect2($('#filters'));
```

## Live pattern validation

Pattern validation is delegated, so it covers inserted fields without any init. Only the optional check on load (`validate_on_load`) is limited to the initial page. See [Pattern validation](pattern-validation.md).
