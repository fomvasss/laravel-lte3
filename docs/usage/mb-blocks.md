# Repeating blocks (mb-blocks)

mb-blocks is a markup-driven repeater: a group of fields that the user can add, clone, delete and reorder, submitted as an indexed array (`content[items][0][title]`, `content[items][1][title]`, ...). It is implemented in `public/mb-blocks.js` and `public/mb-block.css`, both loaded by the package layout. A full working page is `lte3::examples.mb-blocks` (route `lte3.mb-blocks`, `/lte3/mb-blocks` when the example routes are enabled).

It replaces the deprecated `.f-multyblocks` repeater of `main.js`.

## Markup

```blade
<div class="card mb-wrap">
    <div class="card-header">
        <h3 class="card-title">Items</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-sm btn-outline-primary mb-btn-add"><i class="fa fa-plus"></i> Add</button>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-items sortable-y">

            <template class="mb-template">
                <div class="mb-item" data-mb-idx="$i">
                    <div class="mb-item-controls">
                        <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                        <span class="mb-item-num"></span>
                        <div class="mb-item-actions">
                            <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                            <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                    {!! Lte3::text('content[items][$i][title]', null, ['label' => 'Title']) !!}
                    {!! Lte3::url('content[items][$i][url]', null, ['label' => 'URL']) !!}
                </div>
            </template>

            @forelse($items as $item)
                <div class="mb-item" data-mb-idx="{{ $loop->index }}">
                    <div class="mb-item-controls">
                        <span class="mb-handle"><i class="fa fa-arrows-alt-v"></i></span>
                        <span class="mb-item-num"></span>
                        <div class="mb-item-actions">
                            <button type="button" class="btn btn-xs btn-outline-secondary mb-btn-clone" title="Clone"><i class="fa fa-clone"></i></button>
                            <button type="button" class="btn btn-xs btn-outline-danger mb-btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                    {!! Lte3::text("content[items][{$loop->index}][title]", $item['title'] ?? '', ['label' => 'Title']) !!}
                    {!! Lte3::url("content[items][{$loop->index}][url]", $item['url'] ?? '', ['label' => 'URL']) !!}
                </div>
            @empty
                <p class="mb-empty">No items added</p>
            @endforelse

        </div>
    </div>
</div>
```

The repeater must be inside a `<form>`: the indexes and weights are fixed in a `submit` handler of `form:has(.mb-wrap)`.

| Element | Role |
| --- | --- |
| `.mb-wrap` | Repeater root; holds the options below |
| `.mb-btn-add` | Adds an item from the template. Must be inside `.mb-wrap`, anywhere |
| `.mb-items` | Container of items. Add `sortable-y` to enable drag and drop |
| `template.mb-template` | Direct child of `.mb-items`; markup of a new item |
| `.mb-item` | One item, a direct child of `.mb-items`, with its index in `data-mb-idx` |
| `.mb-item-controls` | Direct child of `.mb-item`; contains the number, handle and buttons |
| `.mb-item-num` | Filled with the visible number `#1`, `#2`, ... |
| `.mb-handle` | Drag icon (decorative: the whole item is draggable) |
| `.mb-item-actions` | Container of `.mb-btn-clone` and `.mb-btn-delete` |
| `.mb-empty` | Placeholder shown when there are no items; removed on add |

`data-mb-idx` of existing items must match the index used in their field names. Render existing items with `$loop->index`.

## Options

| Attribute | On | Meaning |
| --- | --- | --- |
| `data-mb-min` | `.mb-wrap` | Delete buttons are disabled while the number of items is at the minimum |
| `data-mb-max` | `.mb-wrap` | The add button is disabled while the number of items is at the maximum. Clone is not limited |
| `data-fn-inits` | `.mb-wrap` | Global functions called after an item is added or cloned, see [Dynamic content](dynamic-content.md#data-fn-inits) |
| `data-mb-placeholder` | `template.mb-template` | Index placeholder of this template, default `$i` |
| `data-confirm` | `.mb-btn-delete` | Delete immediately after a `confirm()` instead of the undo flow |

## Adding

The add button takes the template of its own `.mb-items` (not of a nested repeater), replaces every occurrence of the placeholder with the next index (highest `data-mb-idx` + 1) and appends the item. Then `Lte3.init()` runs on the new item, so select2, colorpicker, tooltips and nested `.sortable-y` lists work right away, and the `data-fn-inits` functions are called.

## Deleting

Without `data-confirm` the item is marked as removed (faded, buttons disabled) and an undo button appears; after 900 ms the item is removed from the DOM. Clicking the undo button in that time keeps the item. Items still marked as removed when the form is submitted are removed before sending.

With `data-confirm="Delete this item?"` on the delete button the item is removed right after the confirmation.

When the last item is removed, a `.mb-empty` placeholder is added.

## Cloning

`.mb-btn-clone` inserts a copy of the item right after it, with the next free index:

- input and textarea values are copied; select values are restored explicitly;
- the item index in `name`, `id`, `label[for]` and `data-field-name` is replaced; for nested repeaters the indexes inside the inner templates are updated too;
- the select2 containers of the copy are removed and the copy is initialised with `Lte3.init()`, then `data-fn-inits` is called.

## Sorting

Add `sortable-y` to `.mb-items`. It is a jQuery UI sortable (`initSortableY`): items are dragged vertically by any non-input part, after 5 px of movement. Without `data-url` no request is sent; the item numbers are refreshed after a drop.

## Submitted data

Items may have gaps in their indexes after deletes, and their DOM order may differ from the index order after sorting. On form `submit` every `.mb-items` list is reindexed in DOM order:

- the item index in each field name becomes its position, `0`, `1`, `2`, ...;
- a hidden `{item path}[weight]` input with the same position is added to each item (or updated if the item already has one as a direct child).

For the markup above the request is:

```
content[items][0][title]=First
content[items][0][url]=https://example.com
content[items][0][weight]=0
content[items][1][title]=Second
content[items][1][url]=
content[items][1][weight]=1
```

The array keys are already in display order, so `weight` is only needed if the data is sorted again later.

## Nested repeaters

A `.mb-wrap` can be placed inside an item. Give the inner template its own placeholder, so the outer replacement does not touch it:

```blade
<template class="mb-template">
    <div class="mb-item" data-mb-idx="$i">
        <div class="mb-item-controls">...</div>
        {!! Lte3::text('content[sections][$i][title]', null, ['label' => 'Section title']) !!}

        <div class="mb-wrap">
            <button type="button" class="btn btn-xs btn-outline-primary mb-btn-add">Add point</button>
            <div class="mb-items sortable-y">
                <template class="mb-template" data-mb-placeholder="$j">
                    <div class="mb-item" data-mb-idx="$j">
                        <div class="mb-item-controls">...</div>
                        {!! Lte3::text('content[sections][$i][points][$j][text]', null, ['label' => 'Point']) !!}
                    </div>
                </template>
                <p class="mb-empty">No points added</p>
            </div>
        </div>
    </div>
</template>
```

When an outer item is added, `$i` is replaced in the inner template too; `$j` is replaced when a point is added. For existing sections render the inner template with the real outer index (`'content[sections][' . $outerIndex . '][points][$j][text]'`). Every button and option acts on its own repeater level.

> [!NOTE]
> Reindexing and cloning are designed for two levels of nesting. With three or more levels an inner index can be replaced at the wrong level of the name; avoid deeper nesting.

## Fields inside items

- Fields with their own JavaScript are initialised by `Lte3.init()` on add and clone, see [Dynamic content](dynamic-content.md).
- [lfmFile](../fields/lfmFile.md) and [mediaFile](../fields/mediaFile.md) buttons use delegated handlers and need nothing.
- Editors from `options.blade.php` (Summernote, TinyMCE, CKEditor, CodeMirror, EasyMDE) and datetimepickers are not registered with `Lte3`; register a scoped init function for them, see [Dynamic content](dynamic-content.md#registering-your-own-init-function).
- Live [pattern validation](pattern-validation.md) works in new items without any init.

## Server side

```php
$request->validate([
    'content.items' => ['array', 'max:5'],
    'content.items.*.title' => ['required', 'string'],
    'content.items.*.url' => ['nullable', 'url'],
]);

$items = $request->input('content.items', []);
```

After a failed validation, render the items from `old('content.items', $items)` so the user's input is kept.
