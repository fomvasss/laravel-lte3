# Editors & file manager

Rich text, Markdown and code editors are not separate field types. Each one is enabled by a CSS class on a `textarea` (or by a fixed `id` for Editor.js), and initialised by `lte3::layouts.inc.options` at the end of the package layout.

Two groups differ in how their assets get onto the page:

| Editor | Enabled by | Assets |
| --- | --- | --- |
| Summernote | `textarea.f-summernote` | Push them yourself (shipped with AdminLTE) |
| CodeMirror | `textarea.f-codeMirror` | Push them yourself (shipped with AdminLTE) |
| EasyMDE | `textarea.f-md-editor` | Push them yourself (bundled with lte3) |
| highlight.js | `.f-highlight` | Push them yourself (bundled with lte3) |
| Magnific Popup | `.js-popup-image`, `.js-popup-images` | Loaded by the layout |
| CKEditor 4 | `textarea.f-cke-mini`, `.f-cke-small`, `.f-cke-full` | Loaded when `public/vendor/ckeditor` exists |
| TinyMCE | `textarea.f-tinymce` | Loaded when `public/vendor/tinymce` exists |
| Laravel File Manager button | `.f-lfm-btn` | Loaded when `public/vendor/laravel-filemanager` exists |
| Editor.js | `#editorjs` + `#editorjs_data` | Loaded when `public/vendor/editorjs` exists |

Paths below are relative to `public/`. `vendor/adminlte` and `vendor/lte3` are created by the package install, see [Installation](../installation.md).

The class is set with the `class` option of the field:

```blade
{!! Lte3::textarea('body', $post->body, ['label' => 'Body', 'class' => 'f-tinymce']) !!}
```

All these editors are initialised once, on page load, over the whole document. For editors in AJAX modals or [mb-blocks](mb-blocks.md) items see [Dynamic content](dynamic-content.md#data-fn-inits).

## Summernote

```blade
{!! Lte3::textarea('body', null, ['class' => 'f-summernote']) !!}

@push('styles')
    <link rel="stylesheet" href="/vendor/adminlte/plugins/summernote/summernote-bs4.min.css">
@endpush
@push('scripts')
    <script src="/vendor/adminlte/plugins/summernote/summernote-bs4.min.js"></script>
@endpush
```

Options: `height: 300`, `lang: 'uk-UA'`. The interface is in English unless `vendor/adminlte/plugins/summernote/lang/summernote-uk-UA.min.js` is also loaded. Function: `initSummernote()`.

## CodeMirror

```blade
{!! Lte3::textarea('template', null, ['class' => 'f-codeMirror']) !!}

@push('styles')
    <link rel="stylesheet" href="/vendor/adminlte/plugins/codemirror/codemirror.css">
    <link rel="stylesheet" href="/vendor/adminlte/plugins/codemirror/theme/monokai.css">
@endpush
@push('scripts')
    <script src="/vendor/adminlte/plugins/codemirror/codemirror.js"></script>
    <script src="/vendor/adminlte/plugins/codemirror/mode/css/css.js"></script>
    <script src="/vendor/adminlte/plugins/codemirror/mode/xml/xml.js"></script>
    <script src="/vendor/adminlte/plugins/codemirror/mode/javascript/javascript.js"></script>
    <script src="/vendor/adminlte/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>
@endpush
```

Options: mode `htmlmixed`, theme `monokai`, line numbers. CodeMirror copies its content back to the textarea when the form is submitted. Function: `initCodeMirror()`.

## EasyMDE (Markdown)

```blade
{!! Lte3::textarea('body_md', null, ['class' => 'f-md-editor']) !!}

@push('styles')
    <link rel="stylesheet" href="/vendor/lte3/plugins/easy-markdown-editor/easymde.min.css">
@endpush
@push('scripts')
    <script src="/vendor/lte3/plugins/easy-markdown-editor/easymde.min.js"></script>
@endpush
```

Options: spell checker off, `forceSync: true` (the textarea always holds the current Markdown), side-by-side preview not fullscreen. Function: `initEasyMdEditor()`.

## highlight.js

Read-only syntax highlighting of code blocks:

```blade
<pre><code class="f-highlight language-php">{{ $code }}</code></pre>

@push('styles')
    <link rel="stylesheet" href="/vendor/lte3/plugins/highlightjs/styles/default.min.css">
@endpush
@push('scripts')
    <script src="/vendor/lte3/plugins/highlightjs/highlight.min.js"></script>
    <script src="/vendor/lte3/plugins/highlightjs/languages/php.min.js"></script>
@endpush
```

Every `.f-highlight` element is highlighted with `hljs.highlightBlock()`. The language comes from the `language-*` class, or is detected automatically. The bundled build is highlight.js 11 (beta); other themes are in `styles/`, other languages in `languages/`.

## Magnific Popup (image preview)

Loaded by the layout, no setup needed:

```blade
<a href="{{ $image->url }}" class="js-popup-image"><img src="{{ $image->thumb }}" alt=""></a>

<div class="js-popup-images">
    @foreach($images as $image)
        <a href="{{ $image->url }}"><img src="{{ $image->thumb }}" alt=""></a>
    @endforeach
</div>
```

`.js-popup-image` opens the linked image with a zoom animation; `.js-popup-images` is a gallery of all its `a` children. Function: `initPopupImage()`.

## CKEditor 4

Install: download CKEditor 4 from [ckeditor.com](https://ckeditor.com/ckeditor-4/download/) and unpack it so that `public/vendor/ckeditor/ckeditor.js` and `public/vendor/ckeditor/adapters/jquery.js` exist. The layout then loads both files.

```blade
<textarea name="intro" class="form-control f-cke-mini"></textarea>
<textarea name="excerpt" class="form-control f-cke-small"></textarea>
{!! Lte3::textarea('body', null, ['class' => 'f-cke-full']) !!}
```

| Class | Toolbar |
| --- | --- |
| `f-cke-mini` | Lists, alignment, link, image, anchor, colours |
| `f-cke-small` | Source, basic styles, lists, alignment, links, format, font size, colours; any HTML allowed |
| `f-cke-full` | Default full toolbar, height `25em`; any HTML allowed |

The interface language is the `lang` of the page. `f-cke-small` and `f-cke-full` use Laravel File Manager at `/filemanager` for browsing (`/filemanager?type=Images`, `/filemanager?type=Files`) and uploading (`/filemanager/upload?type=...`). The configs are the global variables `ckMini`, `ckSmall`, `ckFull`; there is no init function.

## TinyMCE

Install: download the self-hosted TinyMCE package from [tiny.cloud](https://www.tiny.cloud/get-tiny/) and unpack it into `public/vendor`, so that `public/vendor/tinymce/js/tinymce/tinymce.min.js` exists. For a non-English admin panel add the language pack to `public/vendor/tinymce/js/tinymce/langs/` (the language is the `lang` of the page, e.g. `uk`).

```blade
{!! Lte3::textarea('body', $post->body, ['label' => 'Body', 'class' => 'f-tinymce']) !!}
```

Configuration (global `tinymceOptions`):

- plugins: `anchor code table lists autolink emoticons image link visualblocks media preview fullscreen wordcount`;
- absolute URLs (`relative_urls: false`, `document_base_url` = app URL);
- skin `oxide-dark` when `view.theme` is `dark`, otherwise `oxide`;
- style formats: `Cite` and coloured blocks (`black-block`, `green-block`, `blue-block`, `yellow-block`);
- file picker: Laravel File Manager at `/filemanager?editor=...&type=Images` (images) or `&type=Files` (other files) in a TinyMCE dialog.

`initTinyMce()` initialises every `.f-tinymce` on the page. It also stops Bootstrap modals from stealing focus from TinyMCE dialogs, so the editor works inside `#modal-lg` and other modals. For `tokens_action => 'insert'` of [textarea](../fields/textarea.md) the token is inserted into the editor.

## Laravel File Manager

The [lfmFile](../fields/lfmFile.md) field works with [unisharp/laravel-filemanager](https://github.com/UniSharp/laravel-filemanager) through its own handlers in `main.js` and needs nothing from this section. The editors above expect LFM at `/filemanager`.

When `public/vendor/laravel-filemanager` exists (created by `php artisan vendor:publish --tag=lfm_public`), the layout also loads `vendor/lte3/plugins/laravel-filemanager/js/stand-alone-button.js` and runs `initLfmBtn()`, which binds `.f-lfm-btn` buttons of the older file field markup:

```html
<div class="f-wrap" data-lfm-category="image" data-is-image="1" data-trim-host="1">
    <div class="f-wrap-item">
        <input type="text" name="image" class="form-control js-lfm-input">
        <button type="button" class="btn btn-default f-lfm-btn">Choose</button>
        <div class="preview-block"></div>
    </div>
</div>
```

A click opens `/filemanager?type={data-lfm-category}` in a popup window; the picked URLs (comma-separated, without the host with `data-trim-host`) are written to `input.js-lfm-input`, which triggers `change`, and thumbnails or extensions are shown in `.preview-block`.

## Editor.js

Install: create `public/vendor/editorjs` and put the UMD build of `@editorjs/editorjs` there as `editorjs.mjs` (for example `dist/editorjs.umd.js` from the npm package). When the directory exists, the layout loads the Editor.js core and the tools Header, List, Checklist, Quote, Embed, Table, SimpleImage, Raw, Marker, Warning and TextVariantTune from jsDelivr (`@latest`), then the local file.

```blade
{!! Lte3::formOpen(['action' => route('admin.pages.update', $page), 'method' => 'PUT']) !!}
    <div id="editorjs" data-lfm-image-folder="images" data-lfm-file-folder="files" class="mb-3"></div>
    {!! Lte3::hidden('body', $page->body, ['id' => 'editorjs_data']) !!}
    {!! Lte3::btnSubmit('Save') !!}
{!! Lte3::formClose() !!}
```

- The holder must have `id="editorjs"`, the hidden input `id="editorjs_data"` and the input must be inside the form. Only one Editor.js per page.
- The initial content is the JSON in the input value.
- On submit the default submit is cancelled, the editor content is saved into the input as JSON (without the `time` and `version` keys) and the form is submitted again with `form.submit()`.
- `readonly="readonly"` on the holder opens the editor in read-only mode.
- The extra `LFM` tool inserts an image or a file link picked in Laravel File Manager (`/filemanager?type={data-lfm-image-folder}` or `{data-lfm-file-folder}`, defaults `images` and `files`). Its block data is `{url, type}`.

Function: `initEditorJS()`.

## Code and other widgets

Datetime pickers ([datetime](../fields/datetime.md)) and x-editable ([xEditable](../fields/xEditable.md)) are also initialised in `options.blade.php`; see their field pages. The full list of global functions is in [JavaScript API](../reference/javascript.md).
