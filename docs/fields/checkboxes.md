# checkboxes

A group of checkboxes submitted as an array of the checked values.

## Signature

```php
Lte3::checkboxes(string $name, mixed $selected = null, array $options = [], array $attrs = [])
```

## Basic example

```blade
{!! Lte3::checkboxes('cars', ['audi', 'tesla'], [
    ['id' => 'audi', 'name' => 'Audi'],
    ['id' => 'tesla', 'name' => 'Tesla'],
    ['id' => 'bmw', 'name' => 'BMW'],
    ['id' => 'ford', 'name' => 'Ford', 'disabled' => true],
], [
    'label' => 'Cars',
    'help' => '* Pick any',
]) !!}
```

## Options

`$options` is a plain PHP array (not a Collection). Each element is either an array or a scalar:

| Element | Submitted value | Label |
| --- | --- | --- |
| Array | first non-empty of `id`, `slug`, `key`, `value` | first of `label`, `name`, `title` |
| Scalar (`'evening'`, `'day' => 'Day'`) | the element itself | the element itself |

Array elements can also have:

- `disabled` — `true` disables this checkbox.
- `readonly` — `true` makes it unchangeable (`onclick="return false;"`), but still submitted when checked.
- `attrs` — extra HTML attributes of this checkbox, `['data-price' => 10]`.

> [!WARNING]
> For scalar elements the array key is ignored: `['day' => 'Day']` submits `Day`, not `day`. When the value and the label differ, use array elements: `['id' => 'day', 'name' => 'Day']`. Build them from models with `$models->map(fn ($m) => ['id' => $m->id, 'name' => $m->name])->all()`.

## Selected values

The checked values are resolved in the component view, not by the common [Value resolution](overview.md#value-resolution):

1. `$selected`, if not `null`.
2. Otherwise the form model attribute `$model->{$name}`, if `Lte3::formOpen(['model' => ...])` is open.
3. Otherwise `old($name)`.
4. Otherwise the `default` attr.

The result is wrapped into an array, and the `old($name)` values are always added to it. A value is checked when it is in this array (loose `in_array`).

`request()` and `getFormValue()` are not used. The model lookup reads the attribute by the literal field name, so for relations or nested names pass `$selected` explicitly:

```blade
{!! Lte3::checkboxes('roles', $user->roles->pluck('id')->all(), $roleOptions, ['label' => 'Roles']) !!}
```

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | - | Group label HTML. Shown only when set. |
| `help` | string | - | Help HTML under the group. |
| `default` | mixed | - | Checked values when there is no `$selected`, model and old input. |
| `disableds` | array | `[]` | Option values to disable. |
| `readonlys` | array | `[]` | Option values to make unchangeable. |
| `unchecked_value` | mixed | `0` | Value of a hidden input `name` submitted when nothing is checked. `''` removes it. |
| `is_simple` | bool | `false` | Plain Bootstrap `form-check` markup instead of `custom-control`. |
| `class_control` | string | - | Extra classes of each control wrapper, e.g. `custom-switch`. |
| `class` | string | - | Extra classes of each `<input>`. |
| `class_wrap` | string | - | Extra classes of the wrapper `.form-group`, e.g. `row`. |
| `hidden_wrap` | bool | `false` | Hides the whole group. |
| `title` | string | - | Tooltip on the wrapper. |
| `field_id_prefix` | string | `''` | Prefix of the generated ids `_{prefix}_{name}_{index}`. Set it when the same name appears twice on a page. |

`field_attrs` keys (`required`, `x-model`, ...) and the `attrs` attr are not printed by this component; use the per-option `attrs` instead.

## What is submitted

```html
<input type="hidden" name="cars" value="0">
<input type="checkbox" name="cars[]" value="audi">
<input type="checkbox" name="cars[]" value="tesla">
```

- Some checked: `cars` is an array of the checked values, `['audi', 'tesla']` (the hidden scalar is replaced by the array).
- Nothing checked: `cars` is `"0"` (the `unchecked_value`), or missing with `'unchecked_value' => ''`.

Validate with this in mind, e.g. normalize before validation:

```php
$request->merge(['cars' => array_filter((array) $request->input('cars'))]);
```

## Related

- [checkbox](checkbox.md) — a single checkbox, with AJAX save.
- [radiogroup](radiogroup.md) — one value from a group.
- [select2](select2.md) with `multiple` — the same choice as a dropdown.
