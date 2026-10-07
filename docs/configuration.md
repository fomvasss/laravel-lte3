# Configuration

```bash
php artisan vendor:publish --tag=lte3-config
```

The file is `config/lte3.php`. Without publishing, the package defaults apply.

> [!NOTE]
> A published config is not updated by `composer update`. New keys added to the package are merged one level deep only (`mergeConfigFrom`), so a published `view` array hides every new key inside `view`. After an update compare your copy with [`config/lte3.php`](https://github.com/fomvasss/laravel-lte3/blob/master/config/lte3.php) of the package.

## Top level

| Key | Default | Description |
|---|---|---|
| `logo` | `'<b>Admin</b>LTE'` | HTML of the brand in the sidebar and on the auth pages |
| `dashboard_slug` | `'lte3'` | Path of the dashboard home: the brand link goes to `url(dashboard_slug)` |
| `routes` | `true` | Register the [demo routes](installation.md#demo-pages) under `/lte3`. They are never registered in `production` |
| `middleware` | `['web', LteRequestOptions::class]` | Middleware of the demo routes only |

## `view`

| Key | Default | Description |
|---|---|---|
| `view.theme` | `'system'` | Initial color theme: `light`, `dark` or `system` (follows the OS). The theme switcher in the navbar keeps the user's choice in `localStorage` (`lte3-theme`), which overrides this value |
| `view.preloader` | `false` | Show the AdminLTE preloader while the page loads |
| `view.compact` | `true` | Compact navbar and sidebar: `text-sm` on the header and brand, `nav-compact` on the sidebar menu |
| `view.pattern_validation.validate_on_load` | `false` | Also check prefilled values of fields with `pattern` on page load. See [Pattern validation](usage/pattern-validation.md) |
| `view.pattern_validation.message` | `'Format is not valid.'` | Default error text of a field with `pattern` |
| `view.alerts` | `['toastr']` | How flash messages and validation errors are shown: any of `toastr`, `sweetalert`, `bootstrap`. See [Layout](usage/layout.md#alerts) |
| `view.sidebar.search` | `true` | Search input above the sidebar menu |
| `view.sidebar.auth` | `false` | User panel at the top of the sidebar (only for a logged in user) |
| `view.components` | see below | Field components: Blade view, positional arguments, default attributes |
| `view.field_attrs` | see below | Attribute names printed as HTML attributes of the field |
| `view.next_destination_key` | `'_destination'` | Name of the "redirect after the action" parameter. See [Back URL, modals and options](usage/request-options.md) |
| `view.modal_key` | `'_modal'` | Name of the "open this modal after the redirect" parameter |
| `view.pagination.view` | `'pagination::bootstrap-5'` | View of `Lte3::pagination()` for `paginate()` |
| `view.pagination.simple_view` | `'pagination::simple-bootstrap-5'` | View for `simplePaginate()` and `cursorPaginate()` |
| `view.media.format` | `'legacy'` | Request format of [mediaFile](fields/mediaFile.md): `legacy` or `expand`. The field attribute `format` overrides it |
| `view.media.thumb_size` | `110` | Minimal width in px of an image tile in `mediaImage` and `lfmImage`. The field attribute `thumb_size` overrides it |
| `view.media.thumb.driver` | `'conversion'` | How `mediaFile` gets thumbnail URLs: `conversion`, `imagepreset` or a callable. See [Media thumbnails](usage/media-thumbnails.md) |
| `view.media.thumb.conversion_name` | `'thumb'` | Media Library conversion for the `conversion` driver |
| `view.media.thumb.imagepreset_params` | `['w' => 100, 'h' => 100, 'fit' => 'crop']` | Parameters for the `imagepreset` driver |
| `view.lfm.thumb` | `null` | Thumbnail of `lfmImage`: `null` (the image itself), `'imagepreset'` or an invokable class name |

## `view.components`

Every `Lte3::<name>(...)` call is looked up here:

```php
'select2' => [
    'blade' => 'lte3::components.select2',
    'vars' => ['name', 'selected', 'options', 'attrs'],
],
'number' => [
    'blade' => 'lte3::components.text',
    'vars' => ['name', 'value', 'attrs'],
    'default' => ['type' => 'number', 'default' => 0],
],
```

- `blade` — the view to render
- `vars` — names of the positional arguments, in order. `Lte3::select2('status', 'new', $options, ['label' => 'Status'])` passes `$name`, `$selected`, `$options` and `$attrs` to the view
- `default` — attributes merged under the ones passed in the call

Several entries share one view with different defaults: `text`, `number`, `email`, `url`, `search`, `secret`, `password` all render `components.text`; `lfmImage` is `lfmFile` with `is_image`; `mediaImage` is `mediaFile` with `is_image` and `accept => 'image/*'`.

Change the defaults of a field for the whole project:

```php
'textarea' => ['blade' => 'lte3::components.textarea', 'vars' => ['name', 'value', 'attrs'], 'default' => ['rows' => 5]],
```

Point a field to your own view, or add a new component — it becomes available as `Lte3::rating(...)` at once:

```php
'rating' => ['blade' => 'admin.fields.rating', 'vars' => ['name', 'value', 'attrs'], 'default' => ['max' => 5]],
```

Details on how the view receives the value and attributes: [Fields overview](fields/overview.md).

`timepicker` and `datetimepicker` take the default `timezone` from `env('APP_TIMEZONE_CLIENT', 'Europe/Kyiv')`. With `config:cache` the value is read when the cache is built.

> [!WARNING]
> Default values like `now()->startOfDay()` of the date components are evaluated when the config is loaded. With `config:cache` they are frozen at the moment the cache was built.

## `view.field_attrs`

Attribute names a component prints as HTML attributes of the input when they are passed in `$attrs`:

```php
'field_attrs' => [
    'autocomplete', 'autofocus', 'accept', 'placeholder', 'required', 'maxlength', 'minlength',
    'pattern', 'max', 'min', 'step', 'rows', 'title', 'alt', 'style', 'id', 'data-name', 'x-model',
],
```

Add a name to pass another attribute through, e.g. `'inputmode'` or an Alpine directive. An attribute set to `false` or `null` is not rendered at all (`'required' => false` does not make the field required); `0` and `''` are rendered.
