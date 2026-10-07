# Pattern validation

Fields with the HTML `pattern` attribute are validated live, while the user types. The check uses the same rules as the browser's native `pattern` check, works for `textarea` too (which has no native `pattern`), and blocks the form submit while a value is invalid.

## Usage

`pattern` is one of the `field_attrs` passed through to the field, so it works on [text](../fields/text.md), [textarea](../fields/textarea.md) and every field built on them (`email`, `url`, `number`, ...):

```blade
{!! Lte3::text('options[smtp_host]', null, [
    'label' => 'SMTP host',
    'pattern' => '[A-Za-z0-9.\-]+',
    'data' => ['pattern-message' => 'Host only, without https:// and port'],
]) !!}
```

Plain HTML works the same way:

```html
<input type="text" name="zip" class="form-control" pattern="\d{5}" data-pattern-message="5 digits">
```

## Rules

- **Whole value.** The pattern must match the entire value, like the native check: it is compiled as `^(?:pattern)$`. `\d{5}` does not accept `abc12345xyz`. Leading `^` and trailing `$` in your pattern are harmless.
- **Flags.** The pattern is compiled with the `v` flag, as browsers do. If it is invalid in `v` mode (for example an unescaped `-` or `/` inside a character class), the `u` flag is tried, then no flags. A pattern invalid in all modes is logged to the console (`Invalid pattern: ...`) and the field is not checked.
- **Case-sensitive.** There is no way to pass the `i` flag; use `[A-Za-z]`.
- **Empty value is valid.** Add `required` if the field is mandatory.
- **When.** On every `input` and `change` event of `input[pattern]` and `textarea[pattern]`. The handler is delegated from `document`, so fields added later (AJAX modals, [mb-blocks](mb-blocks.md)) are covered without any init.

## What happens on an invalid value

- The field gets the Bootstrap `is-invalid` class (red border).
- `setCustomValidity(message)` marks it invalid for the browser's constraint validation. A submit is blocked by the browser, which shows the message in its validation bubble at the field.
- The message is `data-pattern-message` of the field or, without it, `view.pattern_validation.message` (default `Format is not valid.`). It is not rendered under the field.

When the value becomes valid (or empty), the custom validity and `is-invalid` are removed. A valid field does not get `is-valid`.

> [!NOTE]
> Blocking relies on the browser's constraint validation. It does not apply to a form with the `novalidate` attribute or to a form submitted from script with `form.submit()`, which skips validation (this includes the final submit of [`.js-form-submit-prevalidate`](actions.md#js-form-submit-prevalidate)). Always validate on the server too.

> [!WARNING]
> If an invalid field is hidden (an inactive tab, a collapsed card), the browser cannot show its bubble: the submit is blocked with only a console message `An invalid form control ... is not focusable`. Keep `validate_on_load` off for such forms or make sure invalid fields are visible.

## Configuration

```php
// config/lte3.php
'view' => [
    'pattern_validation' => [
        'validate_on_load' => false,
        'message' => 'Format is not valid.',
    ],
],
```

| Key | Default | Meaning |
| --- | --- | --- |
| `validate_on_load` | `false` | Also check prefilled values once, on page load |
| `message` | `Format is not valid.` | Default error text |

With `validate_on_load`, a saved value that does not match is highlighted when the page opens and blocks the submit until it is fixed. The load check only marks invalid fields: it never removes an `is-invalid` set by the server for a validation error. Fields inserted later are not checked on insertion; they are validated as soon as the user edits them.

The load check is `initLivePatternValidation()`. It does nothing when `validate_on_load` is `false`; when it is `true`, calling it again re-checks every field of the document, for example after inserting HTML with prefilled values.
