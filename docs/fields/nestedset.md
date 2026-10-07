# nestedset

A drag-and-drop tree of records (categories, menu items) with edit/create/show/delete links and AJAX saving of the new order. Made for models using [kalnoy/nestedset](https://github.com/lazychaser/laravel-nestedset).

## Signature

```php
Lte3::nestedset(\Illuminate\Support\Collection $terms, array $attrs = [])
```

`$terms` is a flat collection of records that has a `toTree()` method, i.e. a `Kalnoy\Nestedset\Collection` (`Category::defaultOrder()->get()`). Each record needs `id` and `name`; children are taken from the `children` relation built by `toTree()`.

## Basic example

```blade
{!! Lte3::nestedset(Category::defaultOrder()->get(), [
    'label' => 'Categories',
    'root_btn_create' => 'Create',
    'routes' => [
        'edit' => 'admin.categories.edit',
        'create' => 'admin.categories.create',
        'delete' => 'admin.categories.destroy',
        'show' => 'admin.categories.show',
        'order' => 'admin.categories.order',
        'params' => [],
    ],
]) !!}
```

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | - | Card title HTML; the number of records is appended (`Categories: 12`). Without it there is no card header (and no root create button). |
| `routes` | array | `[]` | Route names for item links and ordering, see [Routes](#routes). |
| `root_btn_create` | string | - | Text of the "create" button in the card header (needs `label` and `routes.create`). |
| `has_nested` | bool | `true` | Allows nesting: renders an empty child list under every item (a drop target) and the "create child" link. `false` gives a flat sortable list. |
| `is_nested` | bool | - | Alias of `has_nested`, used when `has_nested` is not set. |
| `handle_icon` | string | `<i class="fa fa-arrows-alt"></i>` | HTML of the drag handle. |
| `item` | string | `lte3::components.nestedset.item` | View of one level of items (config default). Override it to change the item markup. |

## Routes

Each key is a route name; links are rendered only for the keys you set.

| Key | Rendered as | Route parameters |
| --- | --- | --- |
| `edit` | item title link and edit icon | `[$item, ...params]` |
| `show` | eye icon, opens in a new tab | `[$item, ...params]` |
| `create` | header button (root) and plus icon per item (child, only with `has_nested`) | root: `params`; child: `params + ['parent_id' => $item->id]` |
| `delete` | trash icon, asks `Delete?` and submits a `DELETE` request | `[$item, ...params]` |
| `order` | enables drag-and-drop and receives the new order, see [Saving the order](#saving-the-order) | `params` |
| `params` | extra route parameters for all of the above | - |

`delete` uses the hidden `#js-action-form` of the layout (`js-click-submit`, see [Actions](../usage/actions.md)): a regular form submit with `_method=DELETE` and `_destination` set to the current URL.

## Saving the order

With `routes.order`, items can be dragged by the handle (after holding it for 500 ms) and dropped at another position or level. After every drop the field sends:

- Method: `POST`, URL: `route(routes.order, params)`, AJAX with the CSRF header.
- Body: `data` — the whole tree as nested arrays: `data[0]` is the list of root items, each item is `{id, children}` where `children[0]` is the list of its child items (`children` is missing for items without children).

```text
data[0][0][id]=1
data[0][0][children][0][0][id]=2
data[0][0][children][0][1][id]=3
data[0][1][id]=4
```

The [`build_linear_array_sort()`](../reference/helpers.md) helper turns this into a flat list with `id`, `weight` (position among siblings) and `parent_id`. For a model that stores the tree in `parent_id` and `weight` columns:

```php
public function order(Request $request)
{
    foreach (build_linear_array_sort($request->input('data', [])) as $row) {
        Category::whereKey($row['id'])->update([
            'parent_id' => $row['parent_id'],
            'weight' => $row['weight'],
        ]);
    }

    return response()->json(['message' => 'Order saved']);
}
```

For a kalnoy/nestedset model, apply the rows through the model API (`appendToNode()`, `insertAfterNode()`, ...) instead of a mass `update()`, which does not maintain `_lft`/`_rgt`.

Response: JSON with `message`, shown as a success toast. On an HTTP error the toast `Error SortableNested Ajax!` is shown.

## JS behaviour

- Drag-and-drop uses the bundled `jquery-sortable` plugin (registered as `$.fn.sortableNested`) on `ul.js-sortable-nested`. It is initialized once on page load, not by `Lte3.init()`, so a tree inserted later is not sortable.
- The wrapper `.f-sortable-nested-wrap` carries `data-url` (the `order` route).

See [JavaScript API](../reference/javascript.md).

## Related

- [treeview](treeview.md) — choose nodes of a tree with checkboxes.
- [select2Tree](select2Tree.md) — choose nodes of a tree in a dropdown.
