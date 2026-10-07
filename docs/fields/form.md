# form

Opens a `<form>` with CSRF token, method spoofing and model binding. How fields get their values from the model: [Forms](../usage/forms.md).

```php
Lte3::formOpen(array $attrs = []): string
Lte3::formClose(): string
```

```blade
{!! Lte3::formOpen(['action' => route('admin.posts.update', $post), 'method' => 'PUT', 'model' => $post]) !!}
    ...
{!! Lte3::formClose() !!}
```

## Attributes

| Attr | Type | Default | Description |
|---|---|---|---|
| `action` | string | `'#'` | Form URL |
| `method` | string | `'POST'` | `GET` renders `method="GET"`; any other method renders `method="POST"` plus a hidden `_method` with the given value |
| `model` | mixed | `null` | Source of field values until `formClose()` |
| `files` | bool | `true` (config default) | Adds `enctype="multipart/form-data"` |
| `class` | string | | CSS class of the form |
| `style` | string | | Inline style |
| `disabled` | bool | | Adds `disabled` to the `<form>` tag. It does not disable the fields |
| `data` | array | | `['confirm' => 'Sure?']` → `data-confirm="Sure?"` |
| `label` | string | | A `<label>` (HTML) in a form group right after the opening tag |
| `close` | bool | | Prints `</form>` right away — for a form without fields, e.g. a target of `form="..."` buttons |
| any of `view.field_attrs` | | | Printed as HTML attributes, e.g. `id`, `title`, `autocomplete` |

For non-`GET` forms the hidden inputs `_method`, `_token` and the fields of [`Lte3::formHiddenUsing()`](../usage/forms.md#extra-hidden-fields-in-every-form) are added. A `GET` form gets none of them.

`formClose()` prints `</form>` and unbinds the model, so fields after it no longer take values from it.

## Examples

Create and update with one view:

```blade
{!! Lte3::formOpen([
    'action' => $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store'),
    'method' => $post->exists ? 'PUT' : 'POST',
    'model' => $post,
]) !!}
```

Upload form that submits itself when a file is picked (see [Actions and AJAX](../usage/actions.md)):

```blade
{!! Lte3::formOpen(['action' => route('admin.import'), 'class' => 'js-form-submit-file-changed']) !!}
    {!! Lte3::file('import') !!}
{!! Lte3::formClose() !!}
```

A form with an id, for buttons outside of it:

```blade
{!! Lte3::formOpen(['action' => route('admin.posts.bulk'), 'id' => 'bulk-form', 'close' => true]) !!}

<button type="submit" form="bulk-form" name="action" value="publish" class="btn btn-default">Publish</button>
```

> [!NOTE]
> `style` is also in `view.field_attrs`, so it is printed twice. Browsers use the first one; both have the same value.
