# lists

An editable, sortable list of single values (one input per row) with add and delete buttons. Submitted as an array of strings.

## Signature

```php
Lte3::lists(string $name, array $items = [], array $attrs = [])
```

## Basic example

```blade
{!! Lte3::lists('countries', ['Ukraine', 'Poland', 'France'], [
    'label' => 'Countries:',
    'placeholder_value' => 'Title',
]) !!}
```

## Items

`$items` is a flat array of values, one row each. An empty array renders one empty row.

The component does not read the form model, `old()` or `default`: pass the items explicitly, e.g. `old('countries', $settings->countries ?? [])`.

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Str::studly($name)` | Label HTML. `''` hides it. |
| `help` | string | - | Help HTML under the list. |
| `placeholder_value` | string | `Value` | Placeholder of the inputs; rendered rows get the row number appended (`Title 1`, `Title 2`). |
| `input_type_value` | string | `text` | `type` of the inputs in rendered rows. |
| `class` | string | - | Extra classes of the wrapper `.form-group.f-lists` (this component has no `class_wrap`). |
| `hidden_wrap` | bool | `false` | Hides the whole field. |

`field_attrs` keys and `attrs` are not printed.

## What is submitted

```text
countries[]=Ukraine
countries[]=Poland
countries[]=France
```

- Rows added in the browser get explicit indexes (`countries[3]`), so the array keys may not be sequential. PHP keeps the order the rows appear in the form; use `array_values()` for a clean list.
- Empty rows are submitted too.

```php
$countries = array_values(array_filter($request->input('countries', []), 'filled'));
```

Validation errors are shown for the field name itself (`countries`), not for `countries.0`.

## JS behaviour

- `+` inserts an empty row after the current one, `-` removes the current row; the last remaining row cannot be removed. Both are delegated click handlers, so they work in content inserted later.
- Rows added by `+` always use `type="text"` inputs.
- Sorting: rows are in a `tbody.sortable-y`, made sortable by `initSortableY(root)` (jQuery UI), see [JavaScript API](../reference/javascript.md).

## Related

- [links](links.md) — the same with key/value pairs.
- [mb-blocks](../usage/mb-blocks.md) — repeaters with any fields per row.
