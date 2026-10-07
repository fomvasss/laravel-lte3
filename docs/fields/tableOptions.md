# tableOptions

A "Select columns" button with a side modal where the user shows, hides and reorders the columns of an index table. The choice is submitted to your endpoint, stored per user and applied to the table in the browser.

## Signature

```php
Lte3::tableOptions(array $columns, array $options = [], array $attrs = [])
```

- `$columns` — the column definitions.
- `$options` — the saved choice of the current user for this table.

## How it fits together

1. Describe the columns once and pass them, with the saved options, both to `Lte3::tableOptions()` and to the `<table>` as `data-columns` / `data-options`.
2. Mark every `<th>` and `<td>` of a column with the class `js-table-options-{key}`.
3. On page load the package JS hides and reorders the cells according to the options.
4. The modal form submits the new choice to `action`; your endpoint stores it and redirects back.

## Basic example

```blade
@php
    $columns = [
        ['key' => 'id', 'name' => 'ID'],
        ['key' => 'name', 'name' => 'Name'],
        ['key' => 'email', 'name' => 'Email'],
        ['key' => 'created_at', 'name' => 'Created', 'default' => false],
        ['key' => 'actions', 'name' => 'Actions', 'hidden' => true],
    ];
    $tableOptions = auth()->user()->table_options['users'] ?? [];
@endphp

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover" data-columns='@json($columns)' data-options='@json($tableOptions)'>
            <thead>
            <tr>
                <th class="js-table-options-id">ID</th>
                <th class="js-table-options-name">Name</th>
                <th class="js-table-options-email">Email</th>
                <th class="js-table-options-created_at">Created</th>
                <th class="js-table-options-actions"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="js-table-options-id">{{ $user->id }}</td>
                    <td class="js-table-options-name">{{ $user->name }}</td>
                    <td class="js-table-options-email">{{ $user->email }}</td>
                    <td class="js-table-options-created_at">{{ $user->created_at }}</td>
                    <td class="js-table-options-actions">...</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {!! Lte3::tableOptions($columns, $tableOptions, [
        'action' => route('admin.table-options', 'users'),
        'table' => 'users',
        'name' => 'options',
        'btn_modal_title' => 'Columns',
        'btn_save_title' => 'Save',
        'btn_reset_title' => 'Reset',
    ]) !!}
</div>
```

## Columns

| Key | Type | Default | Description |
| --- | --- | --- | --- |
| `key` | string | - | Column key, used in the cell class `js-table-options-{key}` and in the submitted names. |
| `name` | string | - | Title in the modal. |
| `default` | bool | `true` | Visibility when the user has no saved options for this column. |
| `hidden` | bool | `false` | The column is always hidden and not listed in the modal. |

## Options

The saved choice, keyed by column key:

```php
[
    'name' => ['active' => '1', 'weight' => '0'],
    'id' => ['active' => '1', 'weight' => '1'],
    'email' => ['active' => '0', 'weight' => '2'],
]
```

- `active` — `"1"` shown, `"0"` hidden. The browser compares with the string `"0"`, so keep the values as strings as they come from the request (an `array`/`json` cast does that); an integer `0` does not hide the column.
- `weight` — position; columns are sorted by it in the modal and in the table.
- Columns missing from the options are shown or hidden by their `default`; options for unknown or `hidden` columns are ignored.

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `action` | string | `#` | URL the modal form is submitted to. |
| `method` | string | `POST` | `GET` (uppercase) submits by GET, any other value by POST. |
| `table` | string | `''` | Table key, the second level of the submitted names. Set it: an empty key breaks the names. |
| `name` | string | `options` | Root name of the submitted array. |
| `btn_modal_title` | string | `Select columns` | Text of the open button and the modal title (HTML). |
| `btn_save_title` | string | `Submit` | Text of the save button. |
| `btn_reset_title` | string | `Reset` | Text of the reset button. |
| `preloader` | bool | `true` | Renders an `.overlay` with a spinner that hides the table until the columns are applied. |
| `preloader_id` | string | `table-preloader` | `id` of the overlay. The package JS fades out only `#table-preloader`. |

## What is submitted

Save button:

```text
options[users][name][active]=1
options[users][name][weight]=0
options[users][id][active]=1
options[users][id][weight]=1
options[users][email][active]=0
options[users][email][weight]=2
```

- `active` comes from a switch ([checkbox](checkbox.md) with a hidden `0`), so it is always `0` or `1`.
- `weight` is updated when the user drags the rows in the modal.
- Columns with `hidden` are not submitted.

Reset button: submits `options=[]`, i.e. the string `"[]"` instead of the array. Treat a non-array value as "forget the saved options".

```php
public function tableOptions(Request $request, string $table)
{
    $user = $request->user();
    $all = $user->table_options ?? [];
    $options = $request->input('options');

    if (is_array($options)) {
        $all[$table] = $options[$table] ?? [];
    } else {
        unset($all[$table]);
    }

    $user->update(['table_options' => $all]);

    return back();
}
```

## Placement

- The component renders its own `<form>` (through `Lte3::formOpen()`), so put it outside other forms; `formClose()` also ends the model binding of an outer `formOpen()`.
- With `preloader`, put it inside the same `.card` as the table: the AdminLTE `.overlay` covers its closest positioned parent.
- The modal id is fixed (`#table__options-modal`) and the JS works with the first `.table` element on the page, so use one configured table per page and make it the first `.table`.

## JS behaviour

- On page load the package JS reads `data-columns` and `data-options` of the first `.table`, moves the `js-table-options-{key}` cells of every row into the saved order, adds `d-none` to inactive and `hidden` columns, then fades out `#table-preloader`.
- In the modal, rows are sortable (`.sortable-y` with `data-input-weight-class="js-input-weight"`): after a drag the `weight` inputs are renumbered, see `initSortableY` in [JavaScript API](../reference/javascript.md).

## Related

- [checkbox](checkbox.md) — the switch used for each column.
- [Request options](../usage/request-options.md) — other per-user list settings (`per_page`).
