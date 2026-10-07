# checkbox

A single checkbox (or switch) with a hidden "unchecked" value, custom checked/unchecked values and optional AJAX save on change.

## Signature

```php
Lte3::checkbox(string $name, mixed $value = null, array $attrs = [])
```

The value is resolved as described in [Value resolution](overview.md#value-resolution): `old()`, request, `$value`, form model, `default`. The box is checked when the resolved value is truthy and not identical (`!==`) to `unchecked_value`.

## Basic example

```blade
{!! Lte3::checkbox('publish', null, ['label' => 'Publish']) !!}

{!! Lte3::checkbox('archived', null, ['label' => 'Archived', 'is_simple' => true]) !!}

{!! Lte3::checkbox('accept', 0, [
    'label' => 'Accept <a href="#">Terms</a>',
    'checked_value' => 2,
    'unchecked_value' => 0,
    'class_control' => 'custom-switch',
]) !!}
```

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Str::studly($name)` | Label HTML (unescaped). `''` hides it. |
| `help` | string | - | Help HTML under the field. |
| `checked_value` | mixed | `1` | `value` of the checkbox, submitted when checked. |
| `unchecked_value` | mixed | `0` | Value of a hidden input with the same name, submitted when unchecked. `''` removes the hidden input. |
| `is_simple` | bool | `false` | Plain Bootstrap `form-check` markup instead of AdminLTE `custom-control`. |
| `class_control` | string | - | Extra classes of the control wrapper, e.g. `custom-switch` for a toggle switch. |
| `class` | string | - | Extra classes of the `<input>`. |
| `class_wrap` | string | - | Extra classes of the wrapper `.form-group`. |
| `hidden_wrap` | bool | `false` | Hides the whole field. |
| `title` | string | - | Tooltip on the wrapper (also printed on the input, it is a `field_attrs` key). |
| `disabled` | bool | `false` | Adds `disabled` to the checkbox, see the note below. |
| `readonly` | bool | `false` | Adds `onclick="return false;"`: the state cannot be changed, but the value is still submitted. |
| `id` | string | `_{field_id_prefix}_{name}` | Input `id`, also used by the label `for`. |
| `field_id_prefix` | string | `''` | Prefix of the generated `id`. Use it when the same name appears twice on a page (page and modal). |
| `url_save` | string | - | Saves the state by AJAX on change, see [AJAX save](#ajax-save). |
| `method_save` | string | `POST` | HTTP method of the AJAX request. |
| `format` | string | `name=value` | Payload format of the AJAX request: `name=value` or `name,value`. |
| `raw_name` | string | `$name` without trailing `[]` | Field name sent in the AJAX request. |
| `attrs` | array | `[]` | Extra HTML attributes of the `<input>`. |

`field_attrs` keys (`required`, `id`, `x-model`, ...) are printed on the `<input>`.

> [!NOTE]
> `disabled` disables only the checkbox, not the hidden `unchecked_value` input, so a disabled field still submits `unchecked_value`. Use `readonly` to keep the current state, or `'unchecked_value' => ''` to submit nothing.

## What is submitted

The component renders a hidden input followed by the checkbox, both with the same `name`:

```html
<input type="hidden" name="publish" value="0">
<input type="checkbox" name="publish" value="1">
```

- Checked: `publish=1` (the checkbox comes last and wins).
- Unchecked: `publish=0`.
- With `'unchecked_value' => ''`: the key is missing when unchecked, as with a plain HTML checkbox.

So `$request->boolean('publish')` or a `boolean` validation rule works without extra code.

## AJAX save

With `url_save`, every change sends a request right away, without submitting the form. Used in tables to toggle a flag per row:

```blade
{!! Lte3::checkbox('allowed', $user->allowed, [
    'label' => 'Allowed',
    'url_save' => route('admin.users.update-field', $user),
    'method_save' => 'PATCH',
]) !!}
```

Request (`method_save`, `POST` by default, with the `X-CSRF-TOKEN` header from the layout):

| `format` | Body |
| --- | --- |
| `name=value` (default) | `{raw_name}=1` or `{raw_name}=0` |
| `name,value` | `name={raw_name}&value=1` or `name={raw_name}&value=0` |

The AJAX value is always `1`/`0`, not `checked_value`/`unchecked_value`.

Expected JSON response:

```json
{"status": "ok", "message": "Saved"}
```

- `status: "error"` — the checkbox goes back to its previous state and `message` is shown as an error toast.
- Any other `status` — `message` is shown as a success toast.
- HTTP error — the checkbox goes back to its previous state and a toast is shown.

```php
public function updateField(Request $request, User $user)
{
    $user->update($request->only('allowed'));

    return response()->json(['status' => 'ok', 'message' => 'Saved']);
}
```

## JS behaviour

- AJAX save is a delegated `change` handler on `.f-checkbox-ajax` (the class is added when `url_save` is set). It works for checkboxes inserted later without any init call. `initCheckbox()` is a no-op kept for existing `data-fn-inits`, see [JavaScript API](../reference/javascript.md).
- Data attributes on the input: `data-url-save`, `data-method-save`, `data-format`, `data-raw-name`.

## Related

- [checkboxes](checkboxes.md) — a group of checkboxes submitted as an array.
- [tableOptions](tableOptions.md) uses `checkbox` with `custom-switch` for its column toggles.
