# slug

Text input for a URL slug with a checkbox that unlocks editing. The package only renders the field and the switch; generating the slug is up to your code.

## Signature

```php
Lte3::slug(string $name, mixed $value = null, array $attrs = [])
```

Blade: `lte3::components.slug`. The value is resolved as described in [Value resolution](overview.md#value-resolution).

## Basic example

```blade
{!! Lte3::formOpen(['action' => route('admin.pages.update', $page), 'method' => 'PUT', 'model' => $page]) !!}
    {!! Lte3::text('title') !!}
    {!! Lte3::slug('slug', null, ['label' => 'Slug']) !!}
{!! Lte3::formClose() !!}
```

## Modes

The field has two modes:

- **Locked** — when the form has a model (`formOpen(['model' => ...])`) or the field has a value. The input is `readonly`; the checkbox next to it unlocks it.
- **Open** — no model and no value (a create form without a model). The input is editable from the start.

The checkbox is named `{name}_change` (e.g. `slug_change`) and is re-checked after a failed validation (`old('slug_change')`).

JS (`initJsVerificationSlugField`, delegated `change` handler on `.js-verification-slug-field`):

- Checking the box removes `readonly` and `disabled` from the input.
- Unchecking it sets both `readonly` and `disabled`, so the slug is then not submitted at all.
- On page load, fields with a checked box are unlocked.

`initJsVerificationSlugField` is registered in `Lte3.init()`, so slug fields in AJAX modals and repeater blocks work without extra calls, see [JavaScript API](../reference/javascript.md).

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Str::studly($name)` | Label HTML. `''` hides it. |
| `help` | string | - | Help HTML under the field. |
| `class` | string | - | Extra classes of the input (locked mode only). |
| `class_wrap` | string | - | Extra classes of the wrapper. |
| `hidden_wrap` | bool | `false` | Hides the whole field. |
| `disabled` | bool | `false` | Adds `disabled` (locked mode only). |
| `default` | mixed | - | Fallback value. |
| `formatter` | callable or class | - | Transforms the value before rendering. |

Keys from `lte3.view.field_attrs` (`placeholder`, `maxlength`, `pattern`, `id`, ...) are printed on the input in both modes.

## Submitted data

| Field | When |
| --- | --- |
| `slug` | The input is not disabled: always in locked mode (readonly input), in open mode unless the box was checked and unchecked again. |
| `slug_change=1` | The box is checked. Absent otherwise. |

A typical handler regenerates the slug unless the user unlocked the field:

```php
$data = $request->validated();

if (! $request->boolean('slug_change') || blank($data['slug'] ?? null)) {
    $data['slug'] = Str::slug($data['title']);
}
```

## Related

- [Fields overview](overview.md).
- Config: `lte3.view.components.slug`.
