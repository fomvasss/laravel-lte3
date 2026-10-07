# datepicker, timepicker, datetimepicker, multidatespicker

Date and time inputs. `datepicker`, `timepicker` and `datetimepicker` are text inputs with the [xdsoft jQuery DateTimePicker](https://xdsoft.net/jqplugins/datetimepicker/); `multidatespicker` picks several dates with [Multiple Dates Picker for jQuery UI](https://dubrox.com/Multiple-Dates-Picker-for-jQuery-UI/).

## Signature

```php
Lte3::datepicker(string $name, mixed $value = null, array $attrs = [])
Lte3::timepicker(string $name, mixed $value = null, array $attrs = [])
Lte3::datetimepicker(string $name, mixed $value = null, array $attrs = [])
Lte3::multidatespicker(string $name, array|string|null $value = null, array $attrs = [])
```

| Component | Config `default` | JS format |
| --- | --- | --- |
| `datepicker` | `['default' => now()->startOfDay()]` | `Y-m-d` |
| `timepicker` | `['timezone' => env('APP_TIMEZONE_CLIENT', 'Europe/Kyiv'), 'default' => now()->startOfHour()]` | `H:i:s` |
| `datetimepicker` | `['timezone' => env('APP_TIMEZONE_CLIENT', 'Europe/Kyiv'), 'default' => now()->startOfHour()]` | `Y-m-d H:i:s` |
| `multidatespicker` | `[]` | `yy-mm-dd` (jQuery UI) |

The value is resolved as described in [Value resolution](overview.md#value-resolution): `old()`, request, `$value`, form model, then `default`.

## Basic example

```blade
{!! Lte3::datepicker('date', now(), ['label' => 'Date', 'format' => 'Y-m-d']) !!}

{!! Lte3::timepicker('time', now(), ['label' => 'Time', 'format' => 'H:i:s']) !!}

{!! Lte3::datetimepicker('published_at', null, [
    'label' => 'Published at',
    'format' => 'Y-m-d H:i:s',
    'help' => 'Kyiv time',
]) !!}

{!! Lte3::multidatespicker('dates', ['2026-11-07', '2026-12-07'], [
    'label' => 'Dates',
    'min' => 0,
]) !!}
```

## Attrs

`datepicker`, `timepicker`, `datetimepicker`:

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `format` | string | - | PHP date format used to print a `DateTime` value. Should match the JS format of the picker (see the table above). |
| `timezone` | string | `APP_TIMEZONE_CLIENT` or `Europe/Kyiv` for `timepicker` / `datetimepicker`, none for `datepicker` | A `DateTime` value is converted to this timezone before printing. |
| `default` | mixed | see the config table | Value used when nothing else is found and there is no form model. |
| `label` | string | `Str::studly($name)` | Label HTML. `''` hides it. |
| `help` | string | - | Help HTML under the field. |
| `class` | string | - | Extra classes of the `<input>`. |
| `class_wrap` | string | - | Extra classes of the wrapper `.form-group`. |
| `hidden_wrap` | bool | `false` | Hides the whole field. |
| `disabled` | bool | `false` | Adds `disabled`; the field is not submitted. |
| `readonly` | bool | `false` | Adds `readonly`. Only `datepicker` and `timepicker` read it. |
| `formatter` | callable or class | - | See [Formatter](overview.md#formatter). Applied before `format` / `timezone`. |

Keys from [`field_attrs`](overview.md#field_attrs) (`placeholder`, `required`, `id`, `data-name`, ...) are printed on the input.

`multidatespicker`:

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `format` | string | `yy-mm-dd` | jQuery UI date format (not PHP) of the picker and of the submitted dates. |
| `min` | int or string | - | jQuery UI `minDate`: days from today (`0`, `-1`), a period (`'+1m'`) or a date in `format`. |
| `max` | int or string | - | jQuery UI `maxDate`, same values as `min`. |
| `label` | string | `Str::studly($name)` | Label HTML. `''` hides it. |
| `help` | string | - | Help HTML under the field. |
| `class` | string | - | Extra classes of the visible input. |
| `class_wrap` | string | - | Extra classes of the wrapper. |
| `hidden_wrap` | bool | `false` | Hides the whole field. |

## Format and printed value

The component formats the value only when it is a `DateTime` (e.g. a Carbon attribute of the form model, `now()` or the config `default`):

1. if `timezone` is set, the value is converted with `setTimezone()`;
2. if `format` is set, it is printed with `format()`; otherwise as a Carbon string, `Y-m-d H:i:s`.

A string value (from `old()`, the request, or a `date`/`time` column read as string) is printed as is, without timezone conversion.

The JS formats are fixed in `layouts/inc/options.blade.php` (`initDatetimepicker()`): `Y-m-d` for `datepicker`, `H:i:s` for `timepicker`, `Y-m-d H:i:s` for `datetimepicker`. The `format` attr does not change them, so pass the matching `format`: without it a `datepicker` with a Carbon value shows `2026-10-07 00:00:00`, and a `timepicker` shows the full date and time.

```blade
{!! Lte3::datepicker('birthday', null, ['format' => 'Y-m-d']) !!}
{!! Lte3::timepicker('opens_at', null, ['format' => 'H:i:s']) !!}
```

## Default value

The config `default` (`now()->startOfDay()` / `now()->startOfHour()`) is used only when there is no `old()`, request value, explicit `$value` and no form model. With a form model (`Lte3::formOpen(['model' => $model])`) the model attribute is used even when it is `null`, so an edit form shows an empty field for an empty column.

`'default' => null` does not turn the default off (the config default is taken instead). To render an empty field in a form without a model, pass an empty string:

```blade
{!! Lte3::datetimepicker('starts_at', null, ['default' => '']) !!}
```

> [!WARNING]
> The config default is evaluated when the config is loaded. With `php artisan config:cache` the date is frozen at the moment of caching, and in long-running processes (Octane, queue workers rendering views) it is the time the process started. If the current date matters, pass `'default' => now()` explicitly.

## Timezone

`timepicker` and `datetimepicker` print `DateTime` values in the client timezone: the `timezone` attr, by default `env('APP_TIMEZONE_CLIENT', 'Europe/Kyiv')` from `config/lte3.php`. The application timezone (`config('app.timezone')`, usually `UTC`) stays the storage timezone.

```dotenv
APP_TIMEZONE_CLIENT=Europe/Warsaw
```

The conversion is one-way: the submitted string is in the client timezone and the package does not convert it back. Convert it in the controller (or a mutator / form request):

```php
use Illuminate\Support\Carbon;

$tz = config('lte3.view.components.datetimepicker.default.timezone');

$article->published_at = $request->filled('published_at')
    ? Carbon::parse($request->input('published_at'), $tz)->setTimezone(config('app.timezone'))
    : null;
```

To print a value without conversion, pass an empty timezone: `['timezone' => '']`.

`datepicker` has no default timezone. Do not set one for `date` columns: Eloquent reads them as midnight in the application timezone, and a conversion can move the date to the previous or next day.

> [!NOTE]
> The timezone name must be known to PHP. `Europe/Kiev` fails on PHP builds with current tzdata ("Unknown or bad timezone"); use `Europe/Kyiv` in `.env` and in a published `config/lte3.php`.

## multidatespicker

The visible input is readonly and shows the selected dates joined with `, `. Every selected date is a separate hidden input `name[]`; `[]` is appended to the name if it is missing. The value is an array of date strings in `format` (a single string is also accepted).

```blade
{!! Lte3::multidatespicker('holidays', $shop->holidays ?? [], [
    'label' => 'Holidays',
    'min' => 0,
    'max' => '+6m',
]) !!}
```

The calendar opens on the month of the first selected date.

## What is submitted

| Component | Request |
| --- | --- |
| `datepicker` | `date=2026-10-07` |
| `timepicker` | `time=14:00:00` |
| `datetimepicker` | `published_at=2026-10-07 14:00:00` (client timezone) |
| `multidatespicker` | `holidays[]=2026-11-07&holidays[]=2026-12-07` |

An empty picker sends an empty string (`null` with `ConvertEmptyStringsToNull`).

For `multidatespicker`:

- with no dates selected the field is missing from the request, read it with a default: `$request->input('holidays', [])`;
- the visible input is also submitted as `_holidays[]` with the joined string; ignore it.

```php
$request->validate([
    'published_at' => ['nullable', 'date_format:Y-m-d H:i:s'],
    'holidays' => ['array'],
    'holidays.*' => ['date_format:Y-m-d'],
]);

$shop->holidays = $request->input('holidays', []); // e.g. a json column cast to array
```

## JS behaviour

- `initDatetimepicker()` (in `layouts/inc/options.blade.php`) initializes `.f-datepicker`, `.f-timepicker`, `.f-datetimepicker` and calls `initMultiDatesPicker()` for `.f-multiDatesPicker`. It runs on page load for the whole document.
- The locale of the xdsoft picker is set with `$.datetimepicker.setLocale('uk')`. To change it, override `layouts/inc/options.blade.php` (see [Views](../reference/views.md)).
- Date pickers are not in the automatic init of [dynamic content](../usage/dynamic-content.md). For fields in an AJAX modal or a repeater item, add `data-fn-inits="initDatetimepicker"`.
- `initDatetimepickerOptions()` is a deprecated alias of `initDatetimepicker()`.

Assets come with the package layout: `plugins/datepicker/datetimepicker.full.js` and `.min.css`, `plugins/multidatespicker/jquery-ui.multidatespicker.min.js` and `.min.css`, Moment and jQuery UI from AdminLTE.
