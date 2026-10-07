# hidden

Hidden input, optionally with a visible label.

## Signature

```php
Lte3::hidden(string $name, mixed $value = null, array $attrs = [])
```

Blade: `lte3::components.hidden`. The value is resolved as described in [Value resolution](overview.md#value-resolution), so inside a form with a model `Lte3::hidden('id')` takes `$model->id`.

## Basic example

```blade
{!! Lte3::hidden('parent_id', $parent->id) !!}

{!! Lte3::hidden('__tmp', '666', ['label' => 'Hidden field']) !!}
```

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | - | When set, a `.form-group` with this label is printed before the input. No label by default. |
| `help` | string | - | Help HTML after the input. |
| `class` | string | - | Classes of the input. |
| `class_wrap` | string | - | Wraps the field in a `<div>` with these classes. No wrapper by default. |
| `disabled` | bool | `false` | Adds `disabled`; the field is not submitted. |
| `attrs` | array | - | Arbitrary HTML attributes of the input. |
| `default` | mixed | - | Fallback value. |
| `formatter` | callable or class | - | Transforms the value before rendering. |

Keys from `lte3.view.field_attrs` (`id`, `data-name`, `x-model`, ...) are printed on the input.

## Service fields

The package reads several hidden fields on submit, for example:

```blade
{{-- open this modal after the form is submitted and the page reloads --}}
{!! Lte3::hidden('_modal', '#my-modal-lg') !!}
```

The key names come from the config: `lte3.view.modal_key` (`_modal`) and `lte3.view.next_destination_key` (`_destination`), see [Request options](../usage/request-options.md).

For a field named `_method`, `old()` and request values are ignored and the passed value (or the model value) is always used.

## Editor storage

A hidden input with an `id` often holds data of a JS editor, e.g. Editor.js JSON:

```blade
{!! Lte3::hidden('editorjs_data', $json, ['id' => 'editorjs_data']) !!}
```

See [Editors](../usage/editors.md).

## Submitted data

`name` — the value (nothing when `disabled`).

## Related

- [Fields overview](overview.md).
- Config: `lte3.view.components.hidden`.
