# Forms

## Open and close

```blade
{!! Lte3::formOpen(['action' => route('admin.posts.update', $post), 'method' => 'PUT', 'model' => $post]) !!}
    {!! Lte3::text('title') !!}
    {!! Lte3::btnSubmit('Save') !!}
{!! Lte3::formClose() !!}
```

`formOpen()` renders `<form>` with `accept-charset="UTF-8"`. For any method other than `GET` it uses `method="POST"` and adds the hidden `_method` (method spoofing for `PUT`, `PATCH`, `DELETE`) and `_token`. All attributes: [form](../fields/form.md).

> [!NOTE]
> `files` is `true` in the default config, so every form gets `enctype="multipart/form-data"`. Pass `'files' => false` for a form that should be sent urlencoded.

`formClose()` prints `</form>` and unbinds the model.

## Model binding

The `model` of `formOpen()` is the source of field values until `formClose()`. A field takes the value of the attribute with its name:

```blade
{!! Lte3::formOpen(['action' => route('admin.users.update', $user), 'method' => 'PUT', 'model' => $user]) !!}
    {!! Lte3::text('name') !!} {{-- $user->name --}}
    {!! Lte3::text('profile[phone]') !!} {{-- data_get($user, 'profile.phone') --}}
    {!! Lte3::select2('status', null, $statuses) !!} {{-- $user->status --}}
{!! Lte3::formClose() !!}
```

The field name is turned into a `data_get()` key: `profile[phone]` → `profile.phone`, `roles[]` → `roles`. A dot in the name is replaced with `_`, so use brackets for nested values: `meta[title]`, not `meta.title`.

A model with a `getFormValue($key)` method gets the key passed to it instead of `data_get()` — the place to format dates, decode JSON or read a translation:

```php
public function getFormValue(string $key): mixed
{
    return match ($key) {
        'published_at' => $this->published_at?->format('Y-m-d H:i'),
        default => data_get($this, $key),
    };
}
```

Any object or array works as `model`, not only Eloquent. [mediaFile](../fields/mediaFile.md) takes a model of its own as an argument and falls back to the form model.

## Where the value comes from

For fields that take a value (`name` + `value` in their `vars`), the first one that is not `null` wins:

1. `old('name')` — input flashed after a failed validation
2. `request('name')` — the current query or body, e.g. filters of a `GET` form
3. the `value` argument of the call
4. the bound model (see above)
5. the `default` attribute — `Lte3::text('slug', null, ['default' => 'new-post'])`

Steps 4 and 5 exclude each other: when a form has a `model`, the model value is used even if it is `null`, and `default` is ignored — also for a new, unsaved model on a create page. To show a default there, pass it as `value` or set it on the model (`new Post(['status' => 'draft'])`).

`_method` is never taken from old input or the request.

Choice fields — [select2](../fields/select2.md), [checkboxes](../fields/checkboxes.md), [radiogroup](../fields/radiogroup.md) — resolve the selection themselves, in a different order: the `selected` argument, then the plain model attribute `$model->{$name}` (no nested names, no `getFormValue()`), then `old()`, then `default`. For a nested or computed selection pass it explicitly: `Lte3::select2('roles[]', $user->roles->pluck('id')->all(), $roles, ['multiple' => true])`.

If `ConvertEmptyStringsToNull` is active and the page is rendered with validation errors, a field whose old input is empty stays empty instead of falling back to the model — a field the user cleared is not refilled with the saved value.

Because of step 2, a field named like a query parameter of the page (`q`, `page`, `status` on a filtered index) takes its value from the URL. Give form fields and filter parameters different names when both are on one page.

## Formatting the value

`formatter` changes the resolved value before rendering:

```blade
{!! Lte3::text('code', null, ['formatter' => fn ($value) => Str::upper($value)]) !!}
{!! Lte3::text('price', null, ['formatter' => \App\Admin\Formatters\Money::class]) !!}
```

`formatter` is read from the call attributes only, not from the config `default` of the component. A callable receives `($value, $data)`, where `$data` holds all variables passed to the view. A class name is instantiated and its `handle($value, $data)` is called.

## Validation errors

Fields show the error of their own name under the input (`@error`), and the layout shows all errors as alerts (`view.alerts`, see [Layout](layout.md#alerts)). Use the usual Laravel validation:

```php
public function update(Request $request, Post $post)
{
    $data = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'status' => ['required', 'in:draft,published'],
        'published_at' => ['nullable', 'date'],
    ]);

    $post->update($data);

    return redirect()->to(Lte3::backUrl('admin.posts.index') ?: route('admin.posts.index'))
        ->with('success', 'Saved');
}
```

## Fields from an array

`Lte3::field()` renders a component described by an array, with `type` as the component name. Useful when fields come from configuration or the database:

```blade
@foreach($settingsFields as $field)
    {!! Lte3::field($field) !!}
@endforeach
```

```php
$settingsFields = [
    ['type' => 'text', 'name' => 'site_name', 'label' => 'Site name'],
    ['type' => 'select2', 'name' => 'locale', 'options' => ['en' => 'English', 'uk' => 'Ukrainian']],
    ['type' => 'checkbox', 'name' => 'maintenance', 'label' => 'Maintenance mode'],
];
```

Positional arguments of the component (`name`, `value`, `options`, ...) are taken from the array by their names; the whole array is also passed as `$attrs`. `type` defaults to `text`. A second array is merged over the first.

> [!WARNING]
> A positional argument missing from the array is passed as an empty string, not `null`. So `value` (or `selected`) is never missing: the field does not fall back to the bound model or `default`, only `old()` and the request still win. Pass the value explicitly: `Lte3::field($field, ['value' => $settings[$field['name']] ?? null])`.

## Extra hidden fields in every form

`Lte3::formHiddenUsing()` adds hidden inputs to every non-`GET` form opened by `formOpen()`. Register it once, e.g. in `AppServiceProvider::boot()`:

```php
use Fomvasss\Lte3\Facades\Lte as Lte3;

Lte3::formHiddenUsing(fn ($model, array $attrs) => [
    'sLocale' => session('sLocale'),
    '_edit_fingerprint' => $model?->editFingerprint(),
]);
```

The resolver is called on each form render with the form model (`null` without one) and the `formOpen()` attributes, and returns `[name => value]`. Fields with a `null` or `''` value are skipped.

Typical cases:

- the content locale of the panel lives in the session and is shared by all browser tabs. A form opened in one locale and saved after the locale was switched in another tab would write the text into the wrong translation. Send the locale the form was opened with and apply it from the request in your locale middleware
- a fingerprint of the record (e.g. a hash of `updated_at`) lets the controller reject a save over changes made by someone else after the form was opened

If you have published or copied `components/form.blade.php`, render the `$hidden` variable next to `_token` in your copy.

## Filter forms

A `GET` form gets no `_method`, `_token` or hidden fields, and its fields are refilled from the query string (step 2 above). The package has two ready filter wrappers, see [Layout](layout.md#filters).

## Submitting without a form

Links and buttons can send `POST`/`DELETE` requests through a hidden form of the layout, with a confirmation — e.g. a "Delete" link in a table row. See [Actions and AJAX](actions.md).
