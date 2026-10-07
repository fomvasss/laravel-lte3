# Layout and pages

## A page

```blade
@extends('lte3::layouts.app')

@section('content')
    @include('lte3::parts.content-header', [
        'page_title' => 'Posts',
        'url_create' => route('admin.posts.create'),
        'breadcrumbs' => [
            ['title' => 'Home', 'url' => url('admin')],
            ['title' => 'Posts'],
        ],
    ])

    <section class="content">
        <div class="card">
            <div class="card-body">
                ...
            </div>
        </div>
    </section>
@endsection
```

After `lte3:install` the layouts are copied into the project, use `@extends('<slug>.layouts.app')` there.

`lte3::layouts.app` is built from parts in `layouts/inc/`:

| View | Content |
|---|---|
| `begin` | `<head>`: meta, CSRF token, AdminLTE and plugin styles, `main.css`, `@stack('styles')`; applies the color theme before the page is painted |
| `navbar` | top bar: sidebar toggle, server time tooltip, theme switcher, demo dropdowns, user menu or logout |
| `sidebar` | brand (`logo`, link to `dashboard_slug`), user panel (`view.sidebar.auth`), search (`view.sidebar.search`), menu `layouts/inc/sidebar-menu/example` |
| `footer` | footer and the control sidebar |
| `preloader` | shown when `view.preloader` is `true` |
| `end` | jQuery, Bootstrap, plugins, AdminLTE, `main.js`, `mb-blocks.js`, `@stack('scripts')`, then `options` |
| `options` | hidden action form, modals `#modal-sm`, `#modal-lg`, `#modal-xl`, `@stack('modals')`, editor and picker initialization |

The navbar and the sidebar menu are demo content. Publish the layouts and replace them with your own:

```bash
php artisan vendor:publish --tag=lte3-view-layouts
```

Published views in `resources/views/vendor/lte3/layouts` override the package ones, so `lte3::layouts.app` keeps working. See [Views and publishing](../reference/views.md).

Stacks for page-specific code:

```blade
@push('styles')
    <link rel="stylesheet" href="/css/admin-posts.css">
@endpush

@push('scripts')
    <script>
        $(function () { /* ... */ });
    </script>
@endpush

@push('modals')
    <div class="modal fade" id="modal-import">...</div>
@endpush
```

`lte3::layouts.app-print` is a bare page for printing: it has `@yield('content')`, `@stack('styles')`, `@stack('scripts')`, opens the print dialog on load and closes the window after printing.

## Sidebar menu

The active menu item is set by JS: the `js-activeable` class on the menu `<ul>` makes `main.js` mark the link that matches the current URL as `active` and open its parent tree. Keep the class in your menu:

```blade
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column js-activeable" data-widget="treeview" role="menu">
        <li class="nav-item">
            <a href="{{ route('admin.posts.index') }}" class="nav-link">
                <i class="nav-icon fas fa-newspaper"></i>
                <p>Posts</p>
            </a>
        </li>
    </ul>
</nav>
```

Details of the matching: [JavaScript API](../reference/javascript.md).

## Theme

`view.theme` sets the initial theme: `light`, `dark` or `system`. The switcher in the navbar (`lte3ToggleTheme()`) cycles through the three and keeps the choice in `localStorage` under `lte3-theme`; a stored choice overrides the config. The dark theme is AdminLTE's `dark-mode` class on `<body>`.

`view.compact` (default `true`) makes the navbar, the brand and the sidebar menu smaller.

## Content header

`lte3::parts.content-header` renders the page title, buttons and breadcrumbs, then includes callouts and alerts. All variables are optional:

| Variable | Description |
|---|---|
| `page_title` | Title (HTML allowed) |
| `small_page_title` | Smaller text after the title |
| `url_back` | Shows a "back" button before the title |
| `url_create` | Shows a "Create" button after the title |
| `btn_search` | `true` — a search input that submits `q` to the current URL with `GET` |
| `btn_filter` | `true` — a button that toggles `#collapseFilter` ([filters](#filters)). An array `[['url' => ..., 'title' => ...], ...]` adds a dropdown of saved filters and a "Clear" link |
| `breadcrumbs` | `[['title' => ..., 'url' => ...], ...]`; the last item and items without `url` are plain text, empty items are skipped |

Extra buttons on the right — the `btn-content-header` section:

```blade
@section('btn-content-header')
    <a href="{{ route('admin.posts.export') }}" class="btn btn-flat btn-default mb-1"><i class="fas fa-download"></i></a>
@endsection
```

`url_back` pairs well with `Lte3::backUrl()` — the last visited URL of the index page with its filters and page number, see [Back URL, modals and options](request-options.md):

```blade
@include('lte3::parts.content-header', [
    'page_title' => 'Edit post',
    'url_back' => Lte3::backUrl('admin.posts.index') ?: route('admin.posts.index'),
])
```

## Filters

Two wrappers turn `@section('body')` into a `GET` form to the current URL with "Reset" and "Submit" buttons. A hidden `_f=1` marks that the filter was applied; the block is expanded while `_f` is in the query (pass `collapsed` to override).

- `lte3::parts.filter-wrap` — an AdminLTE card with a collapse button
- `lte3::parts.filter-wrap2` — a Bootstrap collapse `#collapseFilter`, toggled by `btn_filter` of the content header. When the page has a `q` parameter, matches of it in `.content` are highlighted

```blade
@include('lte3::parts.content-header', ['page_title' => 'Orders', 'btn_filter' => true, 'btn_search' => true])

@section('body')
    <div class="row">
        <div class="col-md-3">{!! Lte3::select2('status', null, $statuses, ['empty_value' => '']) !!}</div>
        <div class="col-md-3">{!! Lte3::datepicker('date_from', null, ['default' => '']) !!}</div>
    </div>
@endsection
@include('lte3::parts.filter-wrap2')
```

Fields of a `GET` form take their values from the query string, so the filter keeps its state after submit. "Reset" leads to the URL without the query, see [btnReset](../fields/buttons.md#btnreset).

## Alerts

Flash messages with the keys `success`, `info`, `warning`, `error` and validation errors are shown by `lte3::parts.alerts`, which the content header includes. `view.alerts` selects how:

| Value | Output |
|---|---|
| `toastr` | toast notifications (default) |
| `sweetalert` | SweetAlert2 dialogs |
| `bootstrap` | dismissible Bootstrap alerts at the top of the content |

```php
return back()->with('success', 'Saved');
```

`lte3::parts.alerts.bootstrap` can also be included on its own, e.g. only errors and warnings above a form:

```blade
@include('lte3::parts.alerts.bootstrap', ['types' => ['warning', 'error'], 'class' => ''])
```

`types` — which messages to show (default all four; `error` also covers the `danger` key and validation errors), `class` — the wrapper class (default `container-fluid p-2`).

Callouts — larger AdminLTE blocks — are shown for the flash keys `callout.success`, `callout.info`, `callout.warning`, `callout.error`:

```php
session()->flash('callout.warning', 'The import is still running.');
```

> [!NOTE]
> `callout.error` (and `callout.danger`) currently prints `1` instead of the message text. Use `callout.warning` or the `error` alert until it is fixed.

From JavaScript: `lteAlert(status, message)`, see [JavaScript API](../reference/javascript.md).

## Pagination

```blade
{!! Lte3::pagination($posts) !!}
```

Works with `paginate()`, `simplePaginate()` and `cursorPaginate()` results; keeps the current query string except `page`. Views: `view.pagination.view` and `view.pagination.simple_view` (Bootstrap 5 views of Laravel by default). Anything else logs an error and renders an empty string.

Row numbers counting down from the total: `pagination_row_number($posts, $loop->index)`, see [Helpers](../reference/helpers.md).

## Auth pages

`lte3::auth.login`, `register`, `forgot-password` and `reset-password` extend `lte3::auth.app` and post to `/login`, `url('register')`, `route('password.email')` and `route('password.update')` — the routes of Laravel Breeze / Fortify. Use them as views of your auth routes, or publish (`lte3-view-auth`) and adapt.

## Current user

`Lte3::user()` returns the authenticated user or `null`, `Lte3::user('name')` — one attribute. The navbar uses it for the user menu.
