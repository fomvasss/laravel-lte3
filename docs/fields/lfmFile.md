# lfmFile, lfmImage

A file picked in [Laravel File Manager](https://unisharp.github.io/laravel-filemanager/) (LFM). The value is a URL string. An empty field is a drop zone: a click opens File Manager in a modal, a file dragged from the computer is uploaded to File Manager and its URL becomes the value. A picked file is shown as a card with Replace, Open and Clear buttons.

`lfmImage` is the same component with `is_image` on: an image tile with a thumbnail, only images can be dropped.

## Signature

```php
Lte3::lfmFile(string $name, string|array|null $path = null, array $attrs = [])
Lte3::lfmImage(string $name, string|array|null $path = null, array $attrs = [])
```

| Component | Config `default` |
| --- | --- |
| `lfmFile` | - |
| `lfmImage` | `['lfm_category' => 'image', 'is_image' => 1]` |

When `$path` is `null`, the value is taken from `old()`, the request or the form model, like `value` of other fields (see [Value resolution](overview.md#value-resolution)); there is no `default`. An explicit `$path` is used as is, also after a validation error. One file per field: if `$path` is an array, its first item is used.

## Basic example

```blade
{!! Lte3::formOpen(['action' => route('admin.articles.update', $article), 'method' => 'PUT', 'model' => $article]) !!}

{!! Lte3::lfmImage('poster', null, [
    'label' => 'Poster',
    'thumb_size' => 150,
]) !!}

{!! Lte3::lfmFile('instruction', null, [
    'label' => 'Instruction',
    'lfm_category' => 'file',
    'trim_host' => true,
    'help' => 'PDF, DOC',
]) !!}

{!! Lte3::btnSubmit('Save') !!}
{!! Lte3::formClose() !!}
```

## Requirements

The field works with UniSharp LFM 2.x (it uses the `callback` parameter of the File Manager page and the JSON response of its upload route). Install LFM per [its docs](https://unisharp.github.io/laravel-filemanager/installation):

```bash
composer require unisharp/laravel-filemanager
php artisan vendor:publish --tag=lfm_config
php artisan vendor:publish --tag=lfm_public
php artisan storage:link
```

- LFM routes must be reachable under `lfm_prefix` (default `/filemanager`, LFM `url_prefix`) for the admin user: the package routes are protected by the LFM `middlewares` config (`web`, `auth` by default).
- `lfm_category` must be a key of `folder_categories` in `config/lfm.php` (`file`, `image` by default).
- The layout includes `stand-alone-button.js` and `initLfmBtn()` when `public/vendor/laravel-filemanager` exists. The field does not use them; they remain for project layouts with old LFM buttons.

The same File Manager is used by the editors, see [Editors](../usage/editors.md).

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `is_image` | bool | `false` (`1` for `lfmImage`) | Image tile with a thumbnail instead of a document row; non-image files can't be dropped. |
| `lfm_category` | string | `image` with `is_image`, otherwise `file` | LFM category, sent as `type` to File Manager and to the upload. |
| `lfm_prefix` | string | `/filemanager` | URL prefix of the LFM routes. |
| `lfm_folder` | string | `''` | `working_dir` for dropped files; empty — the default folder of LFM. |
| `thumb_size` | int | `lte3.view.media.thumb_size` (110) | Width of the image tile in px. |
| `trim_host` | bool | `false` | Removes the current origin (`https://example.com`) from a picked URL, so `/storage/...` is saved. |
| `url_save` | string | - | Saves the value by AJAX right after pick or clear, see [AJAX save](#ajax-save). |
| `editable` | bool | `false` | Shows a text input for a manual URL under the card. |
| `label` | string | `Str::studly($name)` | Label HTML. `''` hides it. |
| `help` | string | - | Help HTML under the field. |
| `class` | string | - | Extra classes of the value input. |
| `class_wrap` | string | - | Extra classes of the wrapper `.form-group`. |
| `hidden_wrap` | bool | `false` | Hides the whole field. |
| `disabled` | bool | `false` | Disables the value input (not submitted) and the picker. |

Keys from [`field_attrs`](overview.md#field_attrs) (`id`, `data-name`, `required`, `placeholder`, ...) are printed on the value input, also when it is hidden, so block repeaters can rename it by `data-name`. `placeholder` is useful with `editable`.

## Picking a file

- **Click** on the drop zone or on Replace opens a modal with an iframe `{lfm_prefix}?type={lfm_category}&callback=lteLfmPicked`. LFM calls `parent.lteLfmPicked(items)`, the field takes `items[0].url` and closes the modal.
- **Drag and drop** a file on the drop zone uploads it to `{lfm_prefix}/upload` (`upload`, `type`, `working_dir`, CSRF token from `<meta name="csrf-token">`). The `url` of the LFM response becomes the value; an error (`error.message` or the response `message`) is shown under the zone. LFM validates the file by its own config (size, mime types).
- **Clear** empties the value; **Open** opens the URL in a new tab.
- With `editable`, typing a URL updates the card after a 500 ms pause.

## Thumbnail

For an image tile the server-side thumbnail URL comes from `lte3.view.lfm.thumb`:

| Value | Thumbnail |
| --- | --- |
| `null` (default) | The image itself by its URL. |
| `'imagepreset'` | `imagepreset_url()` of [fomvasss/laravel-imagepresets](https://github.com/fomvasss/laravel-imagepresets) with `lte3.view.media.thumb.imagepreset_params`, width and height `2 × thumb_size`. |
| class name | A class with `__invoke(string $url, ?int $size): string`, resolved from the container. |

```php
// config/lte3.php
'view' => [
    'lfm' => [
        'thumb' => \App\Support\LfmThumb::class,
    ],
],
```

Use a class, not a closure: closures in config break `config:cache`. Right after a pick in File Manager the tile shows the LFM `thumb_url` (or the image itself); the resolver is applied on the next page render. Details on drivers: [Media thumbnails](../usage/media-thumbnails.md).

## What is submitted

One string field, `name` without `[]`:

| Field | Value |
| --- | --- |
| `poster` | `https://example.com/storage/photos/1/poster.jpg`, or `/storage/photos/1/poster.jpg` with `trim_host`, or `''` after Clear |

```php
$request->validate(['poster' => ['nullable', 'string', 'max:2048']]);

$article->update($request->only('poster', 'instruction'));
```

## AJAX save

With `url_save`, every change of the value (pick, drop, clear, manual URL) sends `POST url_save` with `name` and `value` (the CSRF header comes from `$.ajaxSetup`). A JSON `message` in the response is shown as a success toast; an error response shows "Error Ajax!". The field is still submitted with the form.

```blade
{!! Lte3::lfmImage('poster', $article->poster, [
    'url_save' => route('admin.articles.field', $article),
]) !!}
```

```php
public function field(Request $request, Article $article)
{
    $request->validate([
        'name' => ['required', 'in:poster'],
        'value' => ['nullable', 'string', 'max:2048'],
    ]);

    $article->update([$request->input('name') => $request->input('value')]);

    return response()->json(['message' => 'Saved']);
}
```

## JS behaviour

All handlers in `main.js` are delegated, so the field works in modals and repeater items without initialization; `initLfmFile()` exists only for `data-fn-inits` and does nothing. Styles are in `main.css` (shared with [mediaFile](mediaFile.md)).

If lte3 assets are published as a copy instead of a symlink, publish them again after an update. A published or copied `components/lfmFile.blade.php` keeps the old UI (before 1.122): remove it to use the new field.

## Translations

Texts go through `__()`. Add the keys to `lang/<locale>.json`:

```json
{
    "Choose image": "Оберіть зображення",
    "Choose file": "Оберіть файл",
    "or drag it here": "або перетягніть сюди",
    "Replace": "Замінити",
    "Open": "Відкрити",
    "Clear": "Очистити",
    "File manager": "Файловий менеджер",
    "Uploading…": "Завантаження…",
    "Upload failed": "Не вдалося завантажити",
    "Images only": "Лише зображення"
}
```
