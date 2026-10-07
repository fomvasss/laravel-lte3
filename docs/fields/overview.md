# Fields overview

Every form field is a Blade component rendered through the `Lte3` facade. The list of components, their arguments and default attributes lives in `config/lte3.php` under `view.components`.

## Calling a field

```blade
{!! Lte3::text('title', null, ['label' => 'Title', 'required' => true]) !!}
```

- `Lte3` is an alias of `Fomvasss\Lte3\Facades\Lte`, registered by the package service provider and by Laravel package discovery.
- The call returns rendered HTML, so output it with `{!! !!}`, not `{{ }}`.
- Any method that is not defined on the class is looked up in `lte3.view.components.<name>`. An unknown name throws `Exception: Lte3 method or component '<name>' not found!`.

### Positional arguments

Positional arguments are mapped, in order, to the component `vars` from the config. Missing arguments become `null`.

```php
'text' => ['blade' => 'lte3::components.text', 'vars' => ['name', 'value', 'attrs'], 'default' => ['type' => 'text']],
'select2' => ['blade' => 'lte3::components.select2', 'vars' => ['name', 'selected', 'options', 'attrs']],
```

So `Lte3::text('title', 'Hello', [...])` gives the view `$name = 'title'`, `$value = 'Hello'`, `$attrs = [...]`, and `Lte3::select2('status', 'new', $options, [...])` gives `$name`, `$selected`, `$options`, `$attrs`.

Every component view also receives:

- `$attrs` — the component `default` array from the config merged with the attrs you passed (yours win).
- `$field_attrs` — the `lte3.view.field_attrs` list.
- `$model` — the model of the current `Lte3::formOpen(['model' => ...])`, unless the component has its own `model` var that you passed.

## `Lte3::field()` — array form

`Lte3::field(array $params, array $params2 = [])` renders a component described by one array. Useful when fields are built from data (settings pages, block definitions).

```blade
{!! Lte3::field([
    'type' => 'text',
    'name' => 'nickname',
    'value' => 'Nik',
    'label' => 'Nickname',
    'class' => 'some-class',
    'data' => ['rr' => 'qq'],
]) !!}

{!! Lte3::field([
    'type' => 'checkboxes',
    'name' => 'rd',
    'label' => 'Time of day',
    'selected' => ['morning', 'day'],
    'options' => ['night' => 'Night', 'morning' => 'Morning', 'day' => 'Day'],
]) !!}
```

- `type` is the component name from the config, `text` by default.
- The two arrays are merged (`$params2` wins).
- For each component var except `attrs`, the value is taken from the key with the same name (`name`, `value`, `selected`, `options`, ...). Missing keys become an empty string, not `null`.
- The whole merged array is passed as `attrs`, so `label`, `help`, `class`, etc. go at the top level next to `name`.

> [!WARNING]
> Because a missing `value` key becomes `''` instead of `null`, a field rendered with `Lte3::field()` without `value` does not fall back to the form model or the `default` attr (see [Value resolution](#value-resolution)). `'value' => null` does not help either (`null` also becomes `''`): pass the actual value, e.g. `'value' => $model->nickname`.

## Common attrs

Most components read the attrs below. Exact support differs per component, so check the field page; `link`, the buttons, `tableOptions`, `treeview` and `nestedset` read only a few of them.

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | studly name | Label HTML (printed unescaped). `Str::studly($name)` when not set: `first_name` becomes `FirstName`. `''` hides the label. `hidden` shows a label only when it is set. |
| `help` | string | - | Help text under the field (HTML, printed unescaped). |
| `class` | string | - | Extra classes of the input element. |
| `class_wrap` | string | - | Extra classes of the wrapping `.form-group`, e.g. grid classes. |
| `hidden_wrap` | bool | `false` | Renders the wrapper with the `hidden` attribute. |
| `disabled` | bool | `false` | Any truthy value adds `disabled`. |
| `readonly` | bool | `false` | Any truthy value adds `readonly` (in components that support it). |
| `attrs` | array | `[]` | Arbitrary HTML attributes printed on the input as `key="value"` (text, textarea, hidden, checkbox, checkboxes, radiogroup, select2, select2Tree, xEditable). |
| `data` | array | `[]` | `data-*` attributes: `['compare-value' => 42]` gives `data-compare-value="42"` (text, textarea, select2, radiogroup, treeview). |
| `default` | mixed | - | Value used when nothing else provides one, see [Value resolution](#value-resolution). |
| `formatter` | callable or class | - | Transforms the resolved value before rendering, see [Formatter](#formatter). |
| `field_attrs` keys | mixed | - | `placeholder`, `required`, `id`, `pattern`, `min`, `max`, ... printed as HTML attributes, see [`field_attrs`](#field_attrs). |

Validation errors are shown automatically: components check `$errors` for the field name (`@error($name)`) and print the message under the field; most of them also add `is-invalid` to the input.

## Value resolution

For components whose `vars` contain both `name` and `value` (hidden, text and its variants, slug, textarea, checkbox, colorpicker, range, xEditable, date/time pickers), the value is resolved in this order:

1. `old($name)` — the input flashed after a failed validation.
2. `request($name)` — the current request query/body.
3. The `$value` argument, if not `null`.
4. The form model, if `Lte3::formOpen(['model' => $model])` is open: `$model->getFormValue($key)` when the model has such a method, otherwise `data_get($model, $key)`.
5. The `default` attr (from your attrs, or from the component `default.default` in the config).

Notes:

- The name is converted to a dot key for lookups: `meta[title]` becomes `meta.title`, `tags[]` becomes `tags`, and `.` becomes `_` (as PHP does with request keys).
- Steps 1 and 2 are skipped for the field named `_method`.
- If the `ConvertEmptyStringsToNull` middleware is active, there are validation errors and both `old()` and `$value` are `null`, the result is `null` (a field the user cleared stays empty instead of showing the model value again).
- When a form model is set, step 4 returns its value even if it is `null`, so `default` only applies to forms without a model.
- Components with other vars (`selected`, `path`, `items`) resolve their values in their own views; see their pages.

### `getFormValue()`

Define `getFormValue(string $key)` on a model to control what the form shows, e.g. for casts, translations or relations:

```php
public function getFormValue($key)
{
    return match ($key) {
        'tags' => $this->tags->pluck('id')->all(),
        default => data_get($this, $key),
    };
}
```

## Formatter

`formatter` changes the resolved value before it is passed to the view. It is applied only to components with a `value` var and only when given in the call attrs (not in the config `default`).

```blade
{!! Lte3::text('formatter', null, [
    'default' => 'some example formatter',
    'formatter' => fn ($value) => \Illuminate\Support\Str::upper($value),
    // 'formatter' => 'some_upper_helper',
    // 'formatter' => \App\Formatters\UpperFormatter::class,
]) !!}
```

- A callable (closure, function name, `[Class::class, 'method']`) is called as `$formatter($value, $res)`.
- A class name that is not callable is instantiated and called as `(new $formatter())->handle($value, $res)`.
- `$res` holds the component vars as passed (`name`, `value`, `attrs`); config defaults are not merged into it yet.

```php
namespace App\Formatters;

class UpperFormatter
{
    public function handle($value, array $res)
    {
        return mb_strtoupper((string) $value);
    }
}
```

## `field_attrs`

`lte3.view.field_attrs` lists the attr keys that components print as HTML attributes on the input as `key="value"`:

```php
'field_attrs' => [
    'autocomplete', 'autofocus', 'accept', 'placeholder', 'required', 'maxlength', 'minlength',
    'pattern', 'max', 'min', 'step', 'rows', 'title', 'alt', 'style', 'id', 'data-name', 'x-model',
],
```

- Keys from this list set to `false` or `null` are dropped, so `'required' => $isRequired` is safe. `0` and `''` are still printed.
- `true` is printed as `"1"` (`required="1"`), which browsers treat as present.
- Add keys to print more attributes from the attrs array, e.g. `'inputmode'` or `'x-on:change'`.
- `pattern` enables live validation, see [Pattern validation](../usage/pattern-validation.md).
- `data-name` is used by block repeaters to rename fields, see [mb-blocks](../usage/mb-blocks.md).

## Components

| Name | Page | Blade |
| --- | --- | --- |
| `text`, `number`, `email`, `url`, `search`, `password`, `secret` | [text](text.md) | `lte3::components.text` |
| `textarea` | [textarea](textarea.md) | `lte3::components.textarea` |
| `slug` | [slug](slug.md) | `lte3::components.slug` |
| `hidden` | [hidden](hidden.md) | `lte3::components.hidden` |
| `colorpicker` | [colorpicker](colorpicker.md) | `lte3::components.colorpicker` |
| `range` | [range](range.md) | `lte3::components.range` |
| `checkbox` | [checkbox](checkbox.md) | `lte3::components.checkbox` |
| `checkboxes` | [checkboxes](checkboxes.md) | `lte3::components.checkboxes` |
| `radiogroup` | [radiogroup](radiogroup.md) | `lte3::components.radiogroup` |
| `select2` | [select2](select2.md) | `lte3::components.select2` |
| `select2Tree` | [select2Tree](select2Tree.md) | `lte3::components.select2Tree` |
| `treeview` | [treeview](treeview.md) | `lte3::components.treeview` |
| `nestedset` | [nestedset](nestedset.md) | `lte3::components.nestedset.tree` |
| `xEditable` | [xEditable](xEditable.md) | `lte3::components.xEditable` |
| `links` | [links](links.md) | `lte3::components.links` |
| `lists` | [lists](lists.md) | `lte3::components.lists` |
| `tableOptions` | [tableOptions](tableOptions.md) | `lte3::components.tableOptions` |
| `datepicker`, `timepicker`, `datetimepicker`, `multidatespicker` | [datetime](datetime.md) | `lte3::components.datepicker`, `.timepicker`, `.datetimepicker`, `.multidatespicker` |
| `file` | [file](file.md) | `lte3::components.file` |
| `fileForm` | [fileForm](fileForm.md) | `lte3::components.fileForm` |
| `lfmFile`, `lfmImage` | [lfmFile](lfmFile.md) | `lte3::components.lfmFile` |
| `mediaFile`, `mediaImage` | [mediaFile](mediaFile.md) | `lte3::components.mediaFile` |
| `form` (`formOpen()` / `formClose()`) | [form](form.md) | `lte3::components.form` |
| `link`, `btnSubmit`, `btnReset`, `btnModalClose` | [buttons](buttons.md) | `lte3::components.link`, `.btnSubmit`, `.btnReset`, `.btnModalClose` |

## Custom components

Add an entry to `view.components` and call it like any built-in field:

```php
// config/lte3.php
'view' => [
    'components' => [
        // ...
        'money' => [
            'blade' => 'admin.fields.money',
            'vars' => ['name', 'value', 'attrs'],
            'default' => ['currency' => 'UAH', 'default' => 0],
        ],
    ],
],
```

```blade
{{-- resources/views/admin/fields/money.blade.php --}}
<div class="form-group {{ $attrs['class_wrap'] ?? '' }}">
    <label>{!! $attrs['label'] ?? Str::studly($name) !!}</label>
    <div class="input-group">
        <input type="number" step="0.01" name="{{ $name }}" value="{{ $value }}"
               class="form-control @error($name) is-invalid @enderror"
            @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
                {{ $key }}="{{ $val }}"
            @endforeach
        >
        <div class="input-group-append"><span class="input-group-text">{{ $attrs['currency'] }}</span></div>
    </div>
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
```

```blade
{!! Lte3::money('price', null, ['label' => 'Price', 'required' => true]) !!}
```

- `blade` — any view name.
- `vars` — names of the positional arguments, passed to the view as variables. If both `name` and `value` are present, the value goes through [Value resolution](#value-resolution) and the [Formatter](#formatter).
- `default` — attrs merged under the passed attrs; `default.default` is the fallback value.
- The component also works with `Lte3::field(['type' => 'money', ...])`.

> [!NOTE]
> The package config is merged with `mergeConfigFrom()`, which is not recursive: once `config/lte3.php` is published, the whole `view` array comes from your file. Keep all the components you use in it, and add components that new package versions introduce by hand (compare with the package config after updating).

## Overriding a component view

Publish the component views and edit the copies:

```bash
php artisan vendor:publish --tag=lte3-view-components
```

The files land in `resources/views/vendor/lte3/components/` and take precedence over the package views with the same name. Alternatively, point the `blade` key of a component in your config to your own view.

> [!WARNING]
> A published copy does not get fixes and new features of later package versions. Override only what you need and check the [Upgrading](../upgrading.md) notes and `CHANGELOG.md` after updates.
