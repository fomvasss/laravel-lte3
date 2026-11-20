# fields

A whole form part rendered from a description: an array of field definitions and an array of values. For settings that are defined by data, not by a template: integration settings, custom fields, block options. Repeating groups and dependent selects are supported.

## Signature

```php
Lte3::fields(array $fields, array $values = [], array $attrs = [])
```

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `prefix` | string | `''` | Name prefix of all fields: with `settings` the field `title` is `settings[title]` |

## Example

```blade
{!! Lte3::formOpen(['action' => route('admin.connections.update', $connection), 'method' => 'PUT']) !!}
    {!! Lte3::fields($fields, $connection->settings ?? [], ['prefix' => 'settings']) !!}
    {!! Lte3::btnSubmit('Save') !!}
{!! Lte3::formClose() !!}
```

```php
$fields = [
    ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'required' => true],
    ['name' => 'pipelines', 'type' => 'list', 'label' => 'Pipelines', 'min' => 1, 'max' => 20, 'add_label' => 'Add pipeline', 'fields' => [
        ['name' => 'id', 'type' => 'select', 'label' => 'Pipeline', 'url' => route('admin.pipelines.suggest'), 'options' => $pipelineNames, 'required' => true],
        ['name' => 'status_id', 'type' => 'select', 'label' => 'Status', 'url' => route('admin.statuses.suggest'), 'depends_on' => 'id', 'options' => $statusNames],
        ['name' => 'hint', 'type' => 'text', 'label' => 'When to choose'],
    ]],
    ['name' => 'default_pipeline_id', 'type' => 'select', 'label' => 'Default pipeline', 'options' => $pipelineNames, 'required' => true],
    ['name' => 'tags', 'type' => 'multiselect', 'label' => 'Tags', 'options' => ['new' => 'New', 'vip' => 'VIP']],
    ['name' => 'notify', 'type' => 'checkbox', 'label' => 'Notify'],
];
```

A working page is `lte3::examples.fields` (route `lte3.fields`, `/lte3/fields` when the example routes are enabled): it validates the submitted data and shows it.

## Field definition

| Key | Description |
| --- | --- |
| `name` | Required. Key of the value in `$values` and the last segment of the field name |
| `type` | Default `text`. See [Types](#types) |
| `label`, `help`, `placeholder`, `required` | As in the field components |
| `default` | Value when `$values` has no key `name` |
| `options` | Options of `select` / `multiselect`, in any format of [select2](select2.md#options) |
| `url` | `select` / `multiselect`: options are loaded from this URL, see [AJAX search](select2.md#ajax-search) |
| `depends_on` | With `url`: the options depend on this sibling field, see [Dependent select](select2.md#dependent-select) |
| `fields`, `min`, `max`, `add_label`, `empty_label` | `list` only, see [Lists](#lists) |

Other keys are passed to the component as attrs (`class_wrap`, `rows`, `prepend`, ...).

## Types

| `type` | Component |
| --- | --- |
| `select` | [select2](select2.md), single; not required — with an empty option |
| `multiselect` | [select2](select2.md) with `multiple`, the name gets `[]` |
| `list` | A repeating group, see [Lists](#lists) |
| any component with the arguments `name, value, attrs` | That component: `text`, `number`, `email`, `url`, `password`, `secret`, `textarea`, `checkbox`, `hidden`, `colorpicker`, `range`, `datepicker`, ... |

Another type (`select2Tree`, `file`, `mediaFile`, ...) throws `InvalidArgumentException`.

## Values and old input

Values are taken from `$values` by `name`; nested lists from nested arrays (`$values['pipelines'][0]['status_id']`). After a failed validation the old input wins, as in the field components, including the rows of lists. Validation errors are shown under the fields (`settings.pipelines.0.id` under `settings[pipelines][0][id]`).

With `url` the options come by AJAX, so pass the labels of the current values in `options`, otherwise the field shows the raw value (an id) until the user opens it.

## Lists

`type: list` renders a [mb-blocks](../usage/mb-blocks.md) repeater: a card with the label and an «Add» button, one item per row of the value, each item with the `fields` of the list, clone and delete buttons.

- The value is a list of rows: `[['id' => 'retail', 'hint' => '...'], ...]`; the request is `settings[pipelines][0][id]=...`, reindexed in the display order.
- `min` and `max` limit the number of items (`data-mb-min`, `data-mb-max`). They are a hint for the user: validate the count on the server.
- No `weight` input is added (`data-mb-weight="false"`): the order of the rows is the order of the array.
- A list inside a list is supported; a third level throws `InvalidArgumentException`, as mb-blocks supports two levels.
- `add_label`, `empty_label` — the button text and the placeholder of an empty list.

## Server side

Validate with the same keys:

```php
$data = $request->validate([
    'settings.title' => ['required', 'string'],
    'settings.pipelines' => ['required', 'array', 'min:1', 'max:20'],
    'settings.pipelines.*.id' => ['required', 'string'],
    'settings.pipelines.*.status_id' => ['nullable', 'string'],
])['settings'];
```

A `select` that is not chosen is submitted as an empty string (or `null` after `ConvertEmptyStringsToNull`); a `multiselect` with nothing chosen is not submitted.
