# Back URL, modals and options

The `LteRequestOptions` middleware ([installation](../installation.md#middleware)) keeps a little navigation state in the session.

## Back to the filtered list

On every `GET` request of a route named `*.index` (that is not an AJAX/JSON request) the middleware stores the full URL — with filters, sorting and page — in the session under the route name. `Lte3::backUrl()` reads it back:

```blade
<a href="{{ Lte3::backUrl('admin.posts.index') ?: route('admin.posts.index') }}">Back to posts</a>
```

```php
return redirect()->to(Lte3::backUrl('admin.posts.index') ?: route('admin.posts.index'))->with('success', 'Saved');
```

`backUrl($key)` returns the first non-empty of:

1. the `_back` parameter of the current request
2. `_back` stored in the session — any `GET` request with `?_back=<url>` stores it
3. the session value under `$key` (the remembered index URL)
4. `''`

So a link can override where "back" leads: `route('admin.posts.edit', [$post, '_back' => url()->full()])`.

> [!NOTE]
> A `_back` stored in the session stays there until another `_back` replaces it, and it takes priority over the remembered index URL on all later pages. Pass `_back` only on links where you really need it.

To remember other routes, extend the middleware and change `$indexPageRouteNamesForBackAction` (patterns for `Str::is()`):

```php
class AdminRequestOptions extends \Fomvasss\Lte3\Http\Middleware\LteRequestOptions
{
    protected $indexPageRouteNamesForBackAction = ['*.index', 'admin.reports.*'];
}
```

## Destination after an action

If a request has the `_destination` parameter (name: `view.next_destination_key`), the middleware stores its value in the session under the same key. The hidden action form of the layout sends the current URL as `_destination`, so links and buttons that submit through it ([Actions and AJAX](actions.md)) tell the controller where the user was:

```php
public function destroy(Post $post)
{
    $post->delete();

    return redirect()->to(session('_destination') ?: route('admin.posts.index'));
}
```

The package does not redirect by itself — reading the value is up to the controller.

## Open a modal after the redirect

If a request has the `_modal` parameter (name: `view.modal_key`), the middleware flashes it to the session, and the layout opens that modal on the next page: `$('<value>').modal()`. The value is a jQuery selector of a modal present on the page, e.g. one pushed to the `modals` stack.

```blade
{!! Lte3::formOpen(['action' => route('admin.orders.comment', $order)]) !!}
    {!! Lte3::hidden('_modal', '#modal-comments') !!}
    ...
{!! Lte3::formClose() !!}
```

After the redirect back (also after a failed validation — `old('_modal')` is checked too) the page opens `#modal-comments` again. A link can do the same: `route('admin.orders.show', [$order, '_modal' => '#modal-comments'])`.

> [!WARNING]
> The middleware flashes the value under the literal key `_modal`, while the layout reads the session key from `view.modal_key`. With a custom `modal_key` the modal is reopened only from `old()` and the request, not after a plain redirect.

## Items per page

The middleware stores `per_page` from the query in the session and sets it to `16` when the session has none. Read it in index actions:

```php
$posts = Post::latest()->paginate(session('per_page'));
```

A link or a select with `?per_page=50` changes it for all following pages.

## Session toggles

A `GET` request with one of the keys below switches its value in the session between two states and redirects to the same URL without the key:

| Key | States (first is the default) |
|---|---|
| `lte_sidebar_collapse` | `0`, `1` |
| `product_group_collapse` | `in`, `on` |
| `show_products_type_list` | `0`, `1` |

The package views do not read them; they are a mechanism for project views, e.g. `session('lte_sidebar_collapse') ? 'sidebar-collapse' : ''` on `<body>` of a published layout and a link `?lte_sidebar_collapse=1`. To use your own keys or defaults, extend the middleware and override `$toggleKeysValues` and `$enabledOptionKeys`; `beforeHandle()` and `afterHandle()` are empty hooks for the same purpose.
