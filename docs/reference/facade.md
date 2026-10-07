# Lte3 facade

`Fomvasss\Lte3\Facades\Lte`, registered under the alias `Lte3`, so Blade views use `Lte3::...` without an import. In PHP:

```php
use Fomvasss\Lte3\Facades\Lte as Lte3;
```

The facade resolves `Fomvasss\Lte3\Lte`, bound as a scoped singleton (one instance per request, reset between Octane requests and queue jobs).

## Components

```php
Lte3::<component>(...$vars): string
```

Any key of `view.components` in the config is a method: `Lte3::text()`, `Lte3::select2()`, `Lte3::mediaImage()`, your own components. The arguments are the `vars` of the component in order; the last one is `$attrs`. An unknown name throws `Exception("Lte3 method or component '<name>' not found!")`. See [Fields overview](../fields/overview.md).

## Methods

| Method | Returns | Description |
|---|---|---|
| `field(array $params, array $params2 = [])` | `string` | Renders the component `$params['type']` (default `text`) from an array. [Forms](../usage/forms.md#fields-from-an-array) |
| `formOpen(array $attrs = [])` | `string` | Opens a form and binds `$attrs['model']`. [form](../fields/form.md) |
| `formClose()` | `string` | `</form>`, unbinds the model |
| `formHiddenUsing(?callable $resolver)` | `void` | Static. Extra hidden fields for every non-`GET` form; `null` removes the resolver. [Forms](../usage/forms.md#extra-hidden-fields-in-every-form) |
| `formHiddenFields($model = null, array $attrs = [])` | `array` | The fields the resolver returns for a form, without `null` and `''` values |
| `pagination($paginator)` | `string` | Pagination links with the current query except `page`. [Layout](../usage/layout.md#pagination) |
| `user(?string $column = null)` | `mixed` | The authenticated user, one of its attributes, or `null` |
| `backUrl(?string $key = null)` | `string` | `_back` from the request or the session, else the session value under `$key`, else `''`. [Back URL](../usage/request-options.md) |
| `getValueAttribute($name, $value = null, $default = null)` | `mixed` | The value a field with this name would show: `old()`, request, `$value`, bound model, `$default`. [Forms](../usage/forms.md#where-the-value-comes-from) |

`getValueAttribute()` is useful in your own component views:

```blade
@php($value = Lte3::getValueAttribute($name, $value ?? null))
```

## In custom component views

A view registered in `view.components` receives:

| Variable | Description |
|---|---|
| each name from `vars` | the positional arguments of the call (`$name`, `$value`, `$options`, ...) |
| `$attrs` | the attributes: config `default` merged with the call attributes, `false` / `null` values of `field_attrs` removed |
| `$model` | the `model` argument if the component has it in `vars` (like `mediaFile`), else the model of the open form |
| `$field_attrs` | the `view.field_attrs` list — print `Arr::only($attrs, $field_attrs)` as HTML attributes |

If the component has both `name` and `value` in `vars`, `$value` is already resolved by `getValueAttribute()` and passed through `formatter`.
