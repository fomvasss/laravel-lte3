# colorpicker

Text input with a color picker (bootstrap-colorpicker from AdminLTE plugins) and a swatch of the current color; can save the color by AJAX on change.

## Signature

```php
Lte3::colorpicker(string $name, mixed $value = null, array $attrs = [])
```

Blade: `lte3::components.colorpicker`. The value is resolved as described in [Value resolution](overview.md#value-resolution).

## Basic example

```blade
{!! Lte3::colorpicker('color', null, ['label' => 'Color', 'default' => '#FFFFFF']) !!}
```

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Str::studly($name)` | Label HTML. `''` hides it. |
| `help` | string | - | Help HTML under the field. |
| `class` | string | - | Extra classes of the input. |
| `class_wrap` | string | - | Extra classes of the wrapper `.form-group`. |
| `hidden_wrap` | bool | `false` | Hides the input group (the label stays visible). |
| `disabled` | bool | `false` | Adds `disabled` to the input. |
| `readonly` | bool | `false` | Adds `readonly` to the input. |
| `transparent` | bool | `false` | Adds `data-color="transparent"` to the input. |
| `url_save` | string | - | URL for autosave, see [Autosave](#autosave). |
| `default` | mixed | - | Fallback value, e.g. `#FFFFFF`. |
| `formatter` | callable or class | - | Transforms the value before rendering. |

Keys from `lte3.view.field_attrs` (`placeholder`, `required`, `pattern`, `id`, ...) are printed on the input. The input always has `autocomplete="off"`.

```blade
{!! Lte3::colorpicker('background', null, ['label' => 'Background', 'transparent' => true]) !!}
```

## JS

`initColorpicker(root)` calls `.colorpicker()` on every `.f-colorpicker` wrapper. On each `colorpickerChange` event the swatch in the add-on is repainted with the new color. The function is registered in `Lte3.init()`, so pickers in AJAX modals and repeater blocks are initialized automatically, and calling it again skips already initialized pickers, see [JavaScript API](../reference/javascript.md).

## Autosave

With `url_save` the color is sent to the server 500 ms after the user stops changing it (one request per pause):

```blade
{!! Lte3::colorpicker('color', $category->color, [
    'label' => 'Color',
    'url_save' => route('admin.categories.color', $category),
]) !!}
```

Request: `POST` to `url_save`, form-encoded, expects a JSON response.

| Parameter | Value |
| --- | --- |
| `name` | The field name. |
| `value` | The color as a string from the plugin. |

The CSRF token is sent in the `X-CSRF-TOKEN` header set by the package layout (`$.ajaxSetup`). If the JSON response has `message`, it is shown as a success toastr; on an HTTP error the toastr shows "Error Ajax!".

```php
public function color(Request $request, Category $category)
{
    $category->update(['color' => $request->input('value')]);

    return response()->json(['message' => 'Saved']);
}
```

## Submitted data

`name` — the color string (when the input is inside a submitted form and not disabled).

## Related

- [Fields overview](overview.md).
- [Actions](../usage/actions.md) — other AJAX helpers.
- Config: `lte3.view.components.colorpicker`.
