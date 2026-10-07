# file

Plain file input with a table of already saved files: download, delete and (for several files) sort by dragging. The field does not store anything itself: the controller saves uploads and paths.

For Spatie MediaLibrary use [mediaFile](mediaFile.md), for Laravel File Manager use [lfmFile](lfmFile.md).

## Signature

```php
Lte3::file(string $name, string|array|null $path = null, array $attrs = [])
```

`$path` is the URL (or path) of the saved file, or an array of them. It is not taken from `old()` or the form model: pass it explicitly.

## Basic example

```blade
{!! Lte3::formOpen(['action' => route('admin.articles.update', $article), 'method' => 'PUT', 'files' => true]) !!}

{!! Lte3::file('document', $article->document, [
    'label' => 'Document',
    'accept' => '.pdf',
]) !!}

{!! Lte3::file('attachments', $article->attachments ?? [], [
    'label' => 'Attachments',
    'multiple' => true,
]) !!}

{!! Lte3::btnSubmit('Save') !!}
{!! Lte3::formClose() !!}
```

The form needs `enctype="multipart/form-data"`: `'files' => true` in `formOpen()` (it is the config default of the `form` component).

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `multiple` | bool | `false` | Several files: `multiple` on the input, `name[]`, sortable table. Turned on automatically when `$path` is an array. |
| `name_deleted` | string | `{name}_deleted` | Name of the hidden "deleted" inputs. |
| `name_weight` | string | `{name}_weight` | Name of the hidden order inputs. |
| `label` | string | `Str::studly($name)` | Label HTML. `''` hides it. |
| `help` | string | - | Help HTML under the field. |
| `class` | string | - | Extra classes of the file input. |
| `class_wrap` | string | - | Extra classes of the wrapper `.form-group`. |
| `hidden_wrap` | bool | `false` | Hides the whole field. |
| `disabled` | bool | `false` | Adds `disabled` to the file input. |

Keys from [`field_attrs`](overview.md#field_attrs) (`accept`, `required`, `id`, `data-name`, ...) are printed on the file input.

## Saved files

Every item of `$path` is a table row with the last 50 characters of the path, a download link and a delete button. With no paths the field shows "Files not loaded.".

- **Delete** asks `Confirm?`, hides the row and writes the path into the row's `{name}_deleted` input. Nothing is deleted until the form is saved.
- **Sort** (multiple only): rows are dragged vertically ([`initSortableY`](../reference/javascript.md)); after a drop every `{name}_weight[<path>]` gets its new position.

After a file is chosen, the line under the input shows `Selected: name.pdf (1.2 MiB), ...`.

## What is submitted

Single field, `Lte3::file('document', '/storage/docs/a.pdf')`:

| Field | Value |
| --- | --- |
| `document` | `UploadedFile` when a file was chosen |
| `document_deleted` | `''`, or `/storage/docs/a.pdf` after delete |
| `document_weight[/storage/docs/a.pdf]` | `0` |

Multiple field, `Lte3::file('attachments', [$a, $b])`:

| Field | Value |
| --- | --- |
| `attachments[]` | chosen files |
| `attachments_deleted[]` | one item per saved file: `''` (or `null`) or the path of a deleted file |
| `attachments_weight[<path>]` | position of every saved file, from `0` |

Deleted rows are hidden but still send their weight.

## Controller example

```php
public function update(Request $request, Article $article)
{
    $request->validate([
        'document' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        'attachments' => ['nullable', 'array'],
        'attachments.*' => ['file', 'max:10240'],
    ]);

    // single
    if ($request->hasFile('document')) {
        $article->document = Storage::url($request->file('document')->store('documents', 'public'));
    } elseif ($request->input('document_deleted')) {
        $article->document = null;
    }

    // multiple: drop deleted, apply order, append new
    $deleted = array_filter($request->input('attachments_deleted', []));
    $weights = $request->input('attachments_weight', []);

    $paths = collect($article->attachments ?? [])
        ->reject(fn ($path) => in_array($path, $deleted, true))
        ->sortBy(fn ($path) => $weights[$path] ?? PHP_INT_MAX)
        ->values();

    foreach ($request->file('attachments', []) as $file) {
        $paths->push(Storage::url($file->store('attachments', 'public')));
    }

    $article->attachments = $paths->all();
    $article->save();

    return back();
}
```

Deleting the physical file is up to the controller.

## JS behaviour

Handlers are delegated (`main.js`), so the field also works in content loaded later:

- `.f-file .f-file-item .js-btn-delete` — delete with confirmation;
- `.js-files-input` change — the "Selected: ..." line;
- `.sortable-y` — sorting through `initSortableY()`, initialized automatically in [dynamic content](../usage/dynamic-content.md).
