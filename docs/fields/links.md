# links

An editable, sortable list of key/value pairs (two inputs per row) with add and delete buttons. Submitted as an array of `[key, value]` rows.

## Signature

```php
Lte3::links(string $name, array $items = [], array $attrs = [])
```

## Basic example

```blade
{!! Lte3::links('pay_methods', [
    ['key' => 'liqpay', 'value' => 'LiqPay', 'safe' => 1],
    ['key' => 'paypal', 'value' => 'PayPal'],
], [
    'label' => 'Payment methods:',
    'placeholder_key' => 'Code',
    'placeholder_value' => 'Title',
]) !!}
```

## Items

`$items` is the list of rows to render. Each row is an array with the key field, the value field and an optional `safe` flag:

```php
[
    ['key' => 'liqpay', 'value' => 'LiqPay', 'safe' => 1],
    ['key' => 'paypal', 'value' => 'PayPal'],
]
```

- The key and value field names are `key` and `value` by default, see `key_key` and `key_value`.
- `safe` — a protected row: its key input is read-only and its delete button is disabled. Use it for entries the code relies on.
- An empty `$items` renders one empty row.

The component does not read the form model, `old()` or `default`: pass the items explicitly, e.g. `old('pay_methods', $settings->pay_methods ?? [])`.

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Str::studly($name)` | Label HTML. `''` hides it. |
| `help` | string | - | Help HTML under the list. |
| `key_key` | string | `key` | Name of the first field in each row (item key and input name). |
| `key_value` | string | `value` | Name of the second field. |
| `placeholder_key` | string | `Key` | Placeholder of the first input. |
| `placeholder_value` | string | `Value` | Placeholder of the second input. |
| `input_type_key` | string | `text` | `type` of the first input in rendered rows. |
| `input_type_value` | string | `text` | `type` of the second input in rendered rows. |
| `class_wrap` | string | - | Extra classes of the wrapper `.form-group.f-links`. |
| `hidden_wrap` | bool | `false` | Hides the whole field. |

`field_attrs` keys and `attrs` are not printed.

## What is submitted

```text
pay_methods[0][key]=liqpay
pay_methods[0][value]=LiqPay
pay_methods[0][safe]=1
pay_methods[1][key]=paypal
pay_methods[1][value]=PayPal
```

- `safe=1` is sent only for safe rows. The initial empty row (when `$items` is empty) sends `safe=0`.
- Rows can be reordered by dragging; indexes keep their original numbers, but PHP builds the array in the order the rows appear in the form. Use `array_values()` to get a list in the new order.
- Empty rows are submitted too; filter them on the server.

```php
$methods = collect($request->input('pay_methods', []))
    ->filter(fn ($row) => filled($row['key'] ?? null))
    ->values()
    ->all();
```

Validation errors are shown for the field name itself (`pay_methods`), not for nested keys like `pay_methods.0.key`.

## JS behaviour

- `+` inserts an empty row after the current one, `-` removes the current row; the last remaining row cannot be removed. Both are delegated click handlers, so they work in content inserted later.
- Rows added by `+` always use `type="text"` inputs.
- Sorting: rows are in a `tbody.sortable-y`, made sortable by `initSortableY(root)` (jQuery UI), see [JavaScript API](../reference/javascript.md).

## Related

- [lists](lists.md) — the same with one value per row.
- [mb-blocks](../usage/mb-blocks.md) — repeaters with any fields per row.
