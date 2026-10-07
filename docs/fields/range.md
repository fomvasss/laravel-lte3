# range

Slider (`<input type="range">`) with the current value shown next to it.

## Signature

```php
Lte3::range(string $name, mixed $value = null, array $attrs = [])
```

Blade: `lte3::components.range`. The value is resolved as described in [Value resolution](overview.md#value-resolution).

## Basic example

```blade
{!! Lte3::range('age', 18, ['min' => 12, 'max' => 100, 'step' => 1]) !!}
```

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `min` | number | `0` (browser) | Minimal value. |
| `max` | number | `100` (browser) | Maximal value. |
| `step` | number | `1` (browser) | Step. |
| `label` | string | `Str::studly($name)` | Label HTML. `''` hides it. |
| `help` | string | - | Help HTML under the field. |
| `class` | string | - | Extra classes of the input (it always has `custom-range`). |
| `class_wrap` | string | - | Extra classes of the wrapper `.form-group`. |
| `hidden_wrap` | bool | `false` | Hides the whole field. |
| `disabled` | bool | `false` | Adds `disabled`; the field is not submitted. |
| `default` | mixed | - | Fallback value. |
| `formatter` | callable or class | - | Transforms the value before rendering. |

Other keys from `lte3.view.field_attrs` (`id`, `title`, `required`, ...) are printed on the input.

The current value is printed into an `<output>` element to the right of the slider and updated on every `input` event by an inline handler; no init function is needed, so the field works in content loaded by AJAX too.

> [!TIP]
> Without a value the browser puts the slider in the middle of the range and the `<output>` stays empty until the first move. Pass a value or a `default`.

## Submitted data

`name` — the selected number as a string (nothing when `disabled`).

## Related

- [number](text.md#number) — numeric input with `min` / `max` / `step`.
- [Fields overview](overview.md).
- Config: `lte3.view.components.range`.
