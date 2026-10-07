# Actions & AJAX

`public/main.js` attaches behaviour to plain HTML through CSS classes and `data-*` attributes. Nothing has to be initialised by hand: almost every handler below is delegated from `document`, so it also works for markup loaded later (modals, AJAX responses). The few handlers that are bound only once on page load are marked as such.

Field-specific behaviours (select2 `url_save`, ajax checkbox, colorpicker, treeview, x-editable and so on) are described on the field pages, see [Fields overview](../fields/overview.md).

## Common conventions

- **CSRF.** `options.blade.php` calls `$.ajaxSetup()` with the `X-CSRF-TOKEN` header taken from `<meta name="csrf-token">` (rendered by the package layout). Every jQuery AJAX request below carries it.
- **Progress bar.** `$(document).ajaxStart()` restarts PACE, so every jQuery AJAX request shows the top progress bar.
- **Confirmation.** Where `data-confirm` is supported, the text is shown with the native `confirm()`; cancelling aborts the action.
- **Messages.** Most handlers show the result with toastr (`toastr.success()` / `toastr.error()`). The internal helper `lteAlert(status, msg)` is local to `main.js` and is not available to project scripts; call `toastr[status](msg)` directly.

## AJAX button: `.js-ajax-send`

A universal button or link that sends an AJAX request and applies the JSON response to the page.

```blade
<a href="{{ route('admin.posts.destroy', $post) }}"
   class="btn btn-danger btn-sm js-ajax-send"
   data-method="DELETE"
   data-confirm="Delete the post?">
    <i class="fas fa-trash"></i>
</a>
```

| Attribute | Default | Meaning |
| --- | --- | --- |
| `data-url` | `href` | Request URL |
| `data-method` | `POST` | HTTP method, upper-cased before sending |
| `data-confirm` | - | Confirmation text |
| `data-data` | - | Request payload. A JSON object string (`data-data='{"id": 5}'`) or a query string |
| `data-submit` | - | `true` switches to a regular (non-AJAX) form submit, see below |
| `data-csrf` | meta token | Token for `data-submit` mode |
| `data-fn-inits` | - | Comma-separated global function names, called after the request completes (success or error) |

**Request.** `$.ajax({url, method, data})` with the parsed `data-data`. While the request runs the button gets `disabled` and the `loading` class; a Bootstrap tooltip on the button (`data-toggle="tooltip"`) is hidden first.

**Response.** A JSON object; every key is optional:

```php
return response()->json([
    'status' => 'success', // toastr type: success, info, warning, error
    'message' => 'Post deleted',
    'action' => 'remove', // reload | redirect | remove
    'selector' => '#post-15', // for remove
]);
```

| Key | Effect |
| --- | --- |
| `message` | Shown with `toastr[status \|\| type \|\| 'success']`; an unknown type falls back to `toastr.info` |
| `html` (object) | `{selector: html}`. A key starting with `#` or `.` is used as is, any other key gets a `.` prefix (`"cart-total"` means `.cart-total`). Each matched element's content is replaced |
| `html` (string) | Replaces the content of the closest `.js-html-container` around the button |
| `htmlAppends` | For object `html`: array of selectors whose content is appended instead of replaced. Use the full selector (`.cart-total`), also when the key in `html` has no prefix |
| `action: "reload"` | `location.reload()` after 800 ms |
| `action: "redirect"` | Navigates to `redirect_url` |
| `action: "remove"` | Removes `selector`, or, without it, the closest `.item`, `tr` or `.card` around the button |

Elements updated from `html` are passed to `Lte3.init()`, so fields inside them are initialised, see [Dynamic content](dynamic-content.md).

After that two optional global hooks are called if they are defined:

```js
window.onAjaxSuccess = function (response, $btn) {
    // runs after every successful .js-ajax-send
};
window.onHtmlUpdated = function (html) {
    // receives response.html (also when it is absent)
};
```

**Errors.** On a non-2xx response `toastr.error()` shows `responseJSON.message` or `Request error`. A Laravel validation error (422) therefore shows only the general message, not the per-field errors.

### Regular submit mode: `data-submit="true"`

```blade
<a href="{{ route('admin.orders.ship', $order) }}"
   class="btn btn-primary js-ajax-send"
   data-submit="true"
   data-method="PATCH"
   data-data='{"notify": 1}'>
    Ship
</a>
```

Instead of AJAX, a `<form>` is built and submitted, so the server can answer with a normal redirect and flash messages:

- `method` is `GET` for `GET`, `POST` for everything else;
- each key of `data-data` becomes a hidden input;
- `PUT`, `PATCH` and `DELETE` add `_method`;
- any non-GET method adds `_token` (from `data-csrf` or the meta tag).

## Action form: `#js-action-form`

`lte3::layouts.inc.options` renders one hidden form on every page:

```html
<form action="" class="hidden" method="POST" id="js-action-form" style="display: none">
    <input type="hidden" name="_token" value="...">
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="_destination" value="{current full URL}" class="f-dest">
</form>
```

The name of the destination input is `config('lte3.view.next_destination_key')` (`_destination` by default). The [LteRequestOptions](request-options.md) middleware stores a non-empty value of this key in the session, so the controller can redirect back to it after the action.

### `.js-click-submit`

> [!NOTE]
> Marked as deprecated in `main.js`. For new code use `.js-ajax-send` with `data-submit="true"`.

```blade
<a href="{{ route('admin.posts.destroy', $post) }}"
   class="js-click-submit"
   data-method="DELETE"
   data-confirm="Delete?"
   data-destination="{{ route('admin.posts.index') }}">Delete</a>
```

On click: sets `_method` to `data-method` (default `POST`), sets the `.f-dest` input to `data-destination` if given, sets the form `action` to `data-url` or `href` and submits `#js-action-form`. The request is a regular POST with `_token`, `_method` and the destination key.

### `.js-radio-submit`

Rendered by the [radiogroup](../fields/radiogroup.md) field for options that have a `url`. On `change` (after `data-confirm`, if set) the action form is submitted to `data-url` or, without it, to the radio `value`. The form is sent with its current `_method` value.

## Navigation helpers

### `.js-click-url`

```blade
<button type="button" class="btn btn-default js-click-url" data-url="{{ route('admin.export') }}" data-confirm="Export all?">Export</button>
```

Navigates to `data-url` (or `href`) after the optional confirmation.

### `.js-change-url-submit`

```blade
<select class="form-control js-change-url-submit">
    <option value="{{ route('admin.posts.index') }}">All</option>
    <option value="{{ route('admin.posts.index', ['status' => 'draft']) }}">Drafts</option>
</select>
```

On `change` the browser navigates to the selected value.

## Modals with AJAX content: `.js-modal-fill-html`

The layout renders three empty modals:

| Id | Size | Closes on backdrop click / Esc |
| --- | --- | --- |
| `#modal-sm` | `modal-sm` | yes |
| `#modal-lg` | `modal-lg` | no (`data-backdrop="static" data-keyboard="false"`) |
| `#modal-xl` | `modal-xl` | no |

```blade
<button type="button" class="btn btn-default js-modal-fill-html"
        data-target="#modal-lg"
        data-url="{{ route('admin.posts.edit-modal', $post) }}">
    Edit
</button>
```

| Attribute | Meaning |
| --- | --- |
| `data-target` | Modal selector, required |
| `data-url` | URL of the content, falls back to `href` |
| `data-fn-inits` | Extra global init functions, called after the content is inserted |

**Request.** `GET data-url` (jQuery `$.get`, with `X-Requested-With: XMLHttpRequest`).

**Response.** JSON with an `html` key holding the whole `.modal-content` markup (header, body, footer):

```php
public function editModal(Post $post)
{
    return response()->json([
        'html' => view('admin.posts.modal', compact('post'))->render(),
    ]);
}
```

The markup is put into `{target} .modal-content`, the modal is shown, then `Lte3.init()` runs on the content and the functions from `data-fn-inits` are called. A plain HTML response does not work: the handler reads `data.html`.

Any modal, including your own ones pushed to the `modals` stack (`@push('modals')`), can also be opened with the standard Bootstrap `data-toggle="modal" data-target="#id"`.

### Reopening a modal after a redirect: `_modal`

`options.blade.php` opens a modal on page load when the modal key (`config('lte3.view.modal_key')`, default `_modal`) has a value in `old()`, in the request or in the session. The value is a selector:

```blade
{!! Lte3::formOpen(['action' => route('admin.settings.update'), 'method' => 'PUT']) !!}
    {!! Lte3::hidden('_modal', '#settings-modal') !!}
    ...
{!! Lte3::formClose() !!}
```

- After a failed validation the input is flashed, so `old('_modal')` reopens the modal with the errors.
- After a successful save the [LteRequestOptions](request-options.md) middleware has flashed the value to the session, so the modal is opened on the page the controller redirects to.
- `?_modal=%23settings-modal` in a GET URL opens the modal directly.

Only modals that exist in the page markup on load can be reopened this way; the AJAX modals are empty until filled.

## Form helpers

### `.js-form-submit-prevalidate`

A submit button that validates the form on the server before the real submit.

```blade
<button type="button" class="btn btn-success js-form-submit-prevalidate">Save</button>
```

**Request.** `$.ajax` to the closest form's `action` with the form's `method` and `$form.serialize() + '&prevalidate=1'`. File inputs are not sent.

**Server side.** Run the validation, then stop if the flag is present:

```php
public function update(PostRequest $request, Post $post)
{
    if ($request->prevalidate) {
        return 'ok';
    }
    // save...
}
```

Any 2xx response triggers the real `submit()` of the form. On an error response with an `errors` object (the Laravel 422 format) every message is shown with `toastr.error()`.

### `.js-form-submit-file-changed`

Put the class on a form: choosing a file in any of its `input[type="file"]` submits the form immediately.

```blade
{!! Lte3::formOpen(['action' => route('admin.import'), 'files' => true, 'class' => 'js-form-submit-file-changed']) !!}
    <input type="file" name="file">
{!! Lte3::formClose() !!}
```

### `.js-files-input`

On `change` of a file input with this class, the closest `.f-wrap .js-files-info` gets the text `Selected: name.jpg (1.2 MiB), ...`.

### `.js-compare-value`

Shows or hides blocks depending on whether a field equals a value. Checked on page load and on every `input` / `change`.

```blade
{!! Lte3::text('sum', null, [
    'class' => 'js-compare-value',
    'data' => [
        'compare-value' => 42,
        'compare-equal-target' => '.js-sum-ok',
        'compare-not-equal-target' => '.js-sum-diff',
    ],
]) !!}
<div class="callout callout-success js-sum-ok" style="display: none;">Sum matches</div>
<div class="callout callout-warning js-sum-diff" style="display: none;">Sum differs</div>
```

Both values are compared as trimmed strings. The initial check runs only for fields present on page load.

### `.js-map-blocks`

Select2 and radiogroup fields with the `map` option show only the blocks mapped to the selected value. See [select2](../fields/select2.md) and [radiogroup](../fields/radiogroup.md).

### Input helpers

| Class | Behaviour |
| --- | --- |
| `.js-input-calc` | Evaluates an arithmetic expression on Enter or blur (`3+4*2` becomes `11`). While typing, characters other than digits, `()+-*/.` and spaces are removed; Enter does not submit the form |
| `.js-passgen` | Click generates a password into `data-input-recipient` (selector) or the first `input` of the closest `.form-group`, and switches the input to `type="text"`. `data-length-from` / `data-length-to` (default 8) set the length range, `data-complexity` 1-5 the character set (lowercase, + uppercase, + digits, + symbols) |
| `.js-secret-value-btn` | Toggles the closest `.input-group .js-secret-value` between `password` and its `data-origin-type`. Rendered by the `secret` option of [text](../fields/text.md). Bound on page load only |
| `.js-token-insert` | Inserts `data-text` at the cursor of the field in the closest `.position-relative` (into TinyMCE if the field is a TinyMCE textarea). Rendered by `tokens_action => 'insert'` of [text](../fields/text.md) |

## Sortable lists

### `.sortable-y` (jQuery UI)

```blade
<tbody class="sortable-y" data-url="{{ route('admin.posts.sort') }}">
    @foreach($posts as $post)
        <tr id="{{ $post->id }}">...</tr>
    @endforeach
</tbody>
```

Vertical drag and drop (`axis: 'y'`, starts after 5 px of movement). After a drop:

- with `data-input-weight-class="js-input-weight"` the inputs with that class inside the list get their position (`0, 1, 2...`) as value;
- with `data-url` a request is sent: method `data-method` (default `POST`), payload `data[]` = the `id` attributes of the items in the new order (`sortable('toArray')`), expecting JSON. Toastr shows `Success Ajax!` or `Error Ajax!`; the response content is not used.

```php
public function sort(Request $request)
{
    foreach ($request->input('data', []) as $weight => $id) {
        Post::whereKey($id)->update(['weight' => $weight]);
    }

    return response()->json(['message' => 'Saved']);
}
```

For `table tbody.sortable-y` the dragged row keeps its cell widths. Lists inserted later are initialised by `Lte3.init()` (`initSortableY`).

### `.js-sortable-nested`

Nested drag and drop for trees, rendered by the [nestedset](../fields/nestedset.md) field. Items are dragged by `.handle` after a 500 ms delay. After a drop, if the wrapping `.f-sortable-nested-wrap` has `data-url`, a request is sent with method `data-method` (default `POST`) and payload `data` = the serialized tree (`sortableNested('serialize')`: nested arrays of each `li`'s `data-*`, e.g. `data-id`, with `children`). The response `message` is shown with toastr. Initialised on page load only.

## Clipboard

```blade
<a href="#" class="js-clipboard" data-text="{{ $order->number }}">Copy number</a>
<small class="js-clipboard with-mark">{{ $order->number }}</small>
```

Click copies `data-text`, or the element's text without it, and shows `Copied!`. `with-mark` adds a copy icon on hover.

## Display helpers

| Markup | Behaviour | When |
| --- | --- | --- |
| `.js-num-format` | Text `12400` becomes `12 400` | Page load |
| `.js-blur-text` | Blurred text, click reveals it for 10 seconds, a second click hides it | Page load |
| `.js-popup-image` (link to an image) | Opens the image in Magnific Popup with zoom | Page load (`initPopupImage()`) |
| `.js-popup-images` | Gallery: every `a` inside opens in Magnific Popup | Page load (`initPopupImage()`) |
| `.f-highlight` | Syntax highlighting with highlight.js, see [Editors](editors.md) | Page load |
| `table` with `thead tr.t-summary` | The summary row is copied to `tfoot`, so it is shown above and below the table | Page load (`initSummaryTable()`) |
| `#table-preloader` | Faded out when `main.js` is ready | Page load |
| `.btn-actions.lte-actions a[href]` | Links pointing to the current URL (origin + path) are hidden | Page load |
| `.table-responsive .dropdown`, `.dropleft` | Horizontal overflow is clipped while the dropdown is open, so the menu is not cut off | Delegated |
| `.select2` | Click focuses the search field of the open dropdown | Delegated |

## Active menu items

### Sidebar: `.nav-sidebar.js-activeable`

The package sidebar menu has this class. On page load each `li > a` is checked, in order:

1. `data-pat` is a regular expression matched against the full URL (`location.href`);
2. `href` equals `location.pathname`;
3. `href` equals the full URL without the query string.

The matching link gets `active`, its top-level `.nav-item` gets `menu-open`, and the parent link of the open submenu gets `active` too.

```blade
<li class="nav-item">
    <a href="/admin/posts" class="nav-link" data-pat="/admin/posts(/|\?|$)">Posts</a>
</li>
```

### Any list: `.js-activeable-url`

The same matching for any container. `data-tag` selects the elements to check (default `a`), `data-class` the class to set (default `active`).

```blade
<ul class="nav nav-tabs js-activeable-url" data-tag="a" data-class="active">
    <li class="nav-item"><a class="nav-link" href="/admin/settings" data-pat="settings$">General</a></li>
    <li class="nav-item"><a class="nav-link" href="/admin/settings/seo" data-pat="seo">SEO</a></li>
</ul>
```

## Flash messages

Session flashes are shown by `lte3::parts.alerts`, which is included by `lte3::parts.content-header`. The `view.alerts` config chooses the renderers: `toastr` (default), `sweetalert`, `bootstrap`. The keys are `success`, `info`, `warning`, `error` (`danger` is also accepted by the bootstrap renderer), and validation errors from `$errors` are shown as errors.

```php
return redirect()->route('admin.posts.index')->with('success', 'Post saved');
```

See [Layout](layout.md) for the alerts block and [Configuration](../configuration.md) for `view.alerts`.

## Theme switcher

`window.lte3ToggleTheme()` cycles the colour theme `light` → `dark` → `system`, stores it in `localStorage` (`lte3-theme`) and applies it immediately. The navbar button calls it; the initial value comes from `view.theme`.

## Print

`main.js` has no print handler. For printable pages extend `lte3::layouts.app-print`: a bare page without AdminLTE assets that calls `window.print()` on load and closes the window after printing. Open it in a new tab (`target="_blank"`). Its `scripts` stack is rendered inside a `<script>` tag, so push plain JavaScript there, not `<script>` elements. See [Layout](layout.md).
