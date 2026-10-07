# radiogroup

A group of radio buttons for choosing one value, with optional toggling of page blocks and submit-on-change.

## Signature

```php
Lte3::radiogroup(string $name, mixed $selected = null, array $options = [], array $attrs = [])
```

## Basic example

```blade
{!! Lte3::radiogroup('size', 'm', ['s' => 'Small', 'm' => 'Medium', 'l' => 'Large'], ['label' => 'Size:']) !!}

{!! Lte3::radiogroup('payment', null, ['paypal' => 'PayPal', 'fondy' => 'Fondy', 'liqpay' => 'LiqPay'], [
    'default' => 'liqpay',
    'class_wrap' => 'row',
]) !!}
```

## Options

`$options` is a plain PHP array:

| Element | Submitted value | Label |
| --- | --- | --- |
| `key => 'Label'` | the array key | the string |
| `key => [...]` | first non-empty of `id`, `slug`, `key`, `value`, otherwise the array key | first of `label`, `name`, `title` |

Array elements can also have:

- `disabled` — `true` disables this radio.
- `url` — URL for [Submit on change](#submit-on-change).

Labels are escaped (plain text), unlike the group `label`.

## Selected value

Resolved in the component view, not by the common [Value resolution](overview.md#value-resolution):

1. `$selected`, if not `null`.
2. Otherwise the form model attribute `$model->{$name}`, if `Lte3::formOpen(['model' => ...])` is open (even when it is `null`).
3. Otherwise `old($name)`.
4. Otherwise the `default` attr.
5. Otherwise the first key of `$options`.

A radio is checked when `$selected == $value` (loose comparison, so `1` matches `'1'`). `request()` and `getFormValue()` are not used.

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | - | Group label HTML. Shown only when set. |
| `help` | string | - | Help HTML under the group. |
| `default` | mixed | - | Selected value when there is no `$selected`, model and old input. |
| `disabled` | bool | `false` | Disables all radios. |
| `class_wrap` | string | - | Extra classes of the wrapper `.form-group.f-radiogroup`, e.g. `row`. |
| `hidden_wrap` | bool | `false` | Hides the whole group. |
| `field_id_prefix` | string | `''` | Prefix of the generated ids `_{prefix}_{name}_{index}`. Set it when the same name appears twice on a page. |
| `map` | array | - | Shows and hides page blocks by the checked value, see [Toggle blocks](#toggle-blocks). |
| `data` | array | - | `data-*` attributes on every radio. |
| `submit_method` | string | - | Enables [Submit on change](#submit-on-change) for options with `url`. |
| `attrs` | array | `[]` | Extra HTML attributes on every radio. |

`field_attrs` keys (`required`, `x-model`, ...) are printed on every radio:

```blade
{!! Lte3::radiogroup('payment', null, $methods, ['x-model' => 'payment', 'required' => true]) !!}
```

## What is submitted

`name=value` of the checked radio. If no radio is checked, the key is missing. This happens when the selected value matches no option, e.g. when a form model is open and its attribute is `null` (the `default` attr is not applied then).

## Toggle blocks

`map` links option values to CSS selectors. The blocks of the checked value are shown, the blocks of all other values are hidden, on page load and on every change:

```blade
{!! Lte3::radiogroup('channel', 'sms', ['push' => 'Push', 'email' => 'Email', 'sms' => 'SMS'], [
    'label' => 'Channel:',
    'map' => [
        'push' => ['.js-block-push'],
        'email' => ['.js-block-email'],
        'sms' => ['.js-block-sms'],
    ],
]) !!}

<div class="js-block-push">Push settings</div>
<div class="js-block-email">Email settings</div>
<div class="js-block-sms">SMS settings</div>
```

Hidden blocks stay in the form and their fields are still submitted. The same `map` works for [select2](select2.md#toggle-blocks).

## Submit on change

With `submit_method` set, options that have a `url` get the `js-radio-submit` class. Checking such a radio submits the hidden `#js-action-form` of the layout to that URL:

```blade
{!! Lte3::radiogroup('brand', $current, [
    'apple' => ['label' => 'Apple', 'url' => route('admin.brand.switch', 'apple')],
    'samsung' => ['label' => 'Samsung', 'url' => route('admin.brand.switch', 'samsung')],
], [
    'label' => 'Brand:',
    'submit_method' => 'POST',
    'attrs' => ['data-confirm' => 'Switch brand?'],
]) !!}
```

- The request is a regular (non-AJAX) `POST` with `_token`, `_method=POST` and `_destination` (the current URL, key from `lte3.view.next_destination_key`). The radio value itself is not sent, so put it into the URL.
- `data-confirm` (via `attrs`) asks for confirmation first.
- `#js-action-form` is rendered by the package layout; see [Actions](../usage/actions.md).

## JS behaviour

- `map`: the delegated `change` handler on `.js-map-blocks` toggles the blocks; the initial state is applied by `initSelect2(root)`, which also runs for content loaded later (see [Dynamic content](../usage/dynamic-content.md)).
- Submit on change: delegated `change` handler on `.js-radio-submit`.

## Related

- [select2](select2.md) — the same choice as a dropdown.
- [checkboxes](checkboxes.md) — several values.
