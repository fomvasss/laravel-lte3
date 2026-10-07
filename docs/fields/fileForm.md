# fileForm

A self-contained upload form: a label with a hidden file input that submits its own form as soon as a file is chosen. Typical use is an avatar or a quick import button next to other content.

## Signature

```php
Lte3::fileForm(string $name, array $attrs = [])
```

## Basic example

```blade
{!! Lte3::fileForm('avatar', [
    'url_save' => route('admin.users.avatar', $user),
    'label' => 'Upload avatar',
    'accept' => 'image/*',
    'html' => '<img src="'.e($user->avatar_url).'" style="width: 100px;">',
]) !!}
```

Clicking the label (the `html` and the bold label text) opens the file dialog; after a file is chosen the form is submitted with a full page request.

## Attrs

| Attr | Type | Default | Description |
| --- | --- | --- | --- |
| `url_save` | string | - | Form `action`. |
| `action` | string | `''` | Form `action` when `url_save` is not set. |
| `method` | string | `POST` | Form method. Anything other than `GET` is sent as `POST` with `_method` (method spoofing). |
| `multiple` | bool | `false` | `multiple` on the input, the name gets `[]`. |
| `html` | string | - | HTML printed inside the label before the text, e.g. the current image. |
| `label` | string | `Str::studly($name)` | Bold label text. `''` hides it. |
| `class` | string | - | Classes of the `<form>` and of the file input. |
| `disabled` | bool | `false` | Adds `disabled` to the file input. |

Keys from [`field_attrs`](overview.md#field_attrs) (`accept`, `id`, `title`, ...) are printed on the file input.

The form is opened with `Lte3::formOpen()`, so it gets `enctype="multipart/form-data"`, `_token`, `_method` and the hidden fields of `Lte3::formHiddenUsing()` (see [Forms](../usage/forms.md)). It is `display: inline-block`.

> [!WARNING]
> `fileForm` renders its own `<form>`. Place it outside other forms: nested forms are invalid HTML, and its `formClose()` resets the form model of an outer `Lte3::formOpen(['model' => ...])`, so fields after it lose model values.

## What is submitted

| Field | Value |
| --- | --- |
| `avatar` | `UploadedFile` (or `avatar[]` with `multiple`) |
| `_token`, `_method` | form service fields |

```php
public function avatar(Request $request, User $user)
{
    $request->validate(['avatar' => ['required', 'image', 'max:5120']]);

    $user->update(['avatar' => $request->file('avatar')->store('avatars', 'public')]);

    return back()->with('success', 'Avatar updated');
}
```

## JS behaviour

`main.js` submits the closest form on `change` of any `.js-form-submit-file-changed input[type="file"]` (a delegated handler). The same class works on any form of your own:

```blade
{!! Lte3::formOpen(['action' => route('admin.import'), 'files' => true, 'class' => 'js-form-submit-file-changed']) !!}
<label class="btn btn-primary"><input type="file" name="file" hidden> Import</label>
{!! Lte3::formClose() !!}
```

See also [file](file.md) for a file input inside a regular form.
