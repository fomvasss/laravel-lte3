# textarea

Multi-line text field, also the base for WYSIWYG and code editors.

## Signature

```php
Lte3::textarea(string $name, mixed $value = null, array $attrs = [])
```

Blade: `lte3::components.textarea`. The value is resolved as described in [Value resolution](overview.md#value-resolution).

## Basic example

```blade
{!! Lte3::textarea('message', 'Hello World!', [
    'label' => 'Message',
    'rows' => 3,
]) !!}
```

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Str::studly($name)` | Label HTML. `''` hides it. |
| `help` | string | - | Help HTML under the field. |
| `class` | string | - | Extra classes of the `<textarea>`, e.g. an editor class. |
| `class_wrap` | string | - | Extra classes of the wrapper `.form-group`. |
| `hidden_wrap` | bool | `false` | Hides the whole field. |
| `disabled` | bool | `false` | Adds `disabled`; the field is not submitted. |
| `readonly` | bool | `false` | Adds `readonly`. |
| `tokens` | array | - | `[token => title]` dropdown inside the field. |
| `tokens_action` | string | `copy` | `copy`, `insert` or `none`. |
| `attrs` | array | - | Arbitrary HTML attributes of the textarea. |
| `data` | array | - | `data-*` attributes of the textarea. |
| `default` | mixed | - | Fallback value. |
| `formatter` | callable or class | - | Transforms the value before rendering, see [Formatter](overview.md#formatter). |

Keys from `lte3.view.field_attrs` are printed on the textarea; the useful ones here are `rows`, `placeholder`, `required`, `maxlength`, `minlength`, `pattern`, `id`, `style`.

> [!WARNING]
> The value is printed into the textarea without escaping (`{!! $value !!}`). A value that contains `</textarea>` breaks the markup, so do not render untrusted content in this field as is.

## Tokens

Works as in [text](text.md#tokens): a dropdown in the bottom right corner of the field with `copy` (clipboard), `insert` (at the cursor) or `none` action.

```blade
{!! Lte3::textarea('body', null, [
    'label' => 'Template',
    'rows' => 6,
    'tokens' => ['[user:name]' => 'Name', '[order:number]' => 'Order number'],
    'tokens_action' => 'insert',
]) !!}
```

With `insert`, if the textarea is attached to a TinyMCE editor, the token is inserted into the editor content.

## Editors

An editor is enabled by a class on the textarea:

```blade
{!! Lte3::textarea('body', null, ['label' => 'Body', 'class' => 'f-tinymce']) !!}

{!! Lte3::textarea('body_md', null, ['class' => 'f-md-editor']) !!}
```

Available classes (`f-tinymce`, `f-summernote`, `f-codeMirror`, `f-md-editor`, CKEditor 4 `f-cke-mini` / `f-cke-small` / `f-cke-full`) and their setup are described in [Editors](../usage/editors.md).

## Pattern

`pattern` is printed on the textarea and checked live by the package script, see [Pattern validation](../usage/pattern-validation.md).

## Submitted data

`name` — the text (nothing when `disabled`).

## Related

- [text](text.md) — single-line input with the same tokens dropdown.
- [Fields overview](overview.md).
- Config: `lte3.view.components.textarea`, `lte3.view.field_attrs`.
