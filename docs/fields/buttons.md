# Buttons and link

## btnSubmit

```php
Lte3::btnSubmit(string $title, ?string $name = null, ?string $value = null, array $attrs = [])
```

A `<button type="submit" class="btn btn-primary">`. `name` and `value` are sent with the form, so one form can have several actions:

```blade
{!! Lte3::btnSubmit('Save', 'action', 'save') !!}
{!! Lte3::btnSubmit('Save and close', 'action', 'save-close', ['class' => 'btn-success']) !!}
```

```php
return $request->input('action') === 'save-close'
    ? redirect()->route('admin.posts.index')
    : back();
```

| Attr | Type | Description |
|---|---|---|
| `class` | string | Added after `btn btn-primary`, e.g. `btn-success` (both classes stay, the later one in the CSS wins) |
| `before_title`, `after_title` | string | HTML around the title, e.g. an icon |
| `confirm` | string | Native `confirm()` with this text before submit |
| `disabled` | bool | Disabled button |
| `add` | `'fixed'` | Also renders a round floating save button fixed in the corner of the screen, for long forms |
| any of `view.field_attrs` | | `id`, `title`, `style`, ... |

The title is HTML. The button has `data-toggle="tooltip"`, so a `title` attribute shows a tooltip.

```blade
{!! Lte3::btnSubmit('Submit', null, null, ['before_title' => '<i class="fa fa-check"></i>', 'class' => 'btn-success']) !!}
{!! Lte3::btnSubmit('Save', 'action', 'save', ['add' => 'fixed']) !!}
```

> [!NOTE]
> The floating button of `'add' => 'fixed'` has no `name` and `value`: a submit through it does not send `action=save`.

> [!WARNING]
> `confirm` is inserted into an inline `onclick` JavaScript string. Do not put user input or a single quote in it.

## btnReset

```php
Lte3::btnReset(string $title, array $attrs = [])
```

A link (`<a class="btn btn-default">`) to the current URL without the query string — resets a filter form.

| Attr | Type | Description |
|---|---|---|
| `url` | string | Target URL instead of the current one |
| `ignores` | array | Query parameters to keep: `['ignores' => ['per_page', 'sort']]` |
| `class` | string | Extra classes |
| `confirm` | string | Native `confirm()` before following the link |
| `disabled` | bool | Renders the link without `href` |
| any of `view.field_attrs` | | |

```blade
{!! Lte3::btnReset('Reset', ['ignores' => ['per_page']]) !!}
```

## btnModalClose

```php
Lte3::btnModalClose(string $title, array $attrs = [])
```

A `<button class="btn btn-default" data-dismiss="modal">` for modal footers. Attrs: `class` and `view.field_attrs`.

```blade
<div class="modal-footer">
    {!! Lte3::btnModalClose('Cancel') !!}
    {!! Lte3::btnSubmit('Save') !!}
</div>
```

## link

```php
Lte3::link(string $url, array $attrs = [])
```

A button-styled link in a form group, aligned with the fields of a form row: the `label` is both the link text and a reason to add an empty label line above it.

| Attr | Type | Default | Description |
|---|---|---|---|
| `label` | string | | Link text |
| `btn_class` | string | `primary` | `btn-<btn_class>` |
| `class` | string | | Extra classes |
| `target` | string | | `target` attribute |

```blade
<div class="row">
    <div class="col-md-4">{!! Lte3::text('sku') !!}</div>
    <div class="col-md-2">{!! Lte3::link(route('admin.products.generate-sku'), ['label' => 'Generate', 'btn_class' => 'default']) !!}</div>
</div>
```
