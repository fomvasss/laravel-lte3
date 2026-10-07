# Laravel LTE3

[![License](https://img.shields.io/packagist/l/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-lte3)
[![Latest Stable Version](https://img.shields.io/packagist/v/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-lte3)
[![Total Downloads](https://img.shields.io/packagist/dt/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-lte3)

An admin panel for Laravel built on [AdminLTE 3](https://adminlte.io/themes/v3/): a ready layout with navbar, sidebar, alerts and auth pages, and a Blade form builder with almost forty components — select2 with AJAX search, date pickers, file managers, Spatie Media Library uploads, trees, inline editing.

[Українською](README.uk.md) · Documentation: **https://fomvasss.github.io/laravel-lte3/**

![Components](public/img/screen.gif)

- **Fields in one line** — `{!! Lte3::text('title') !!}` renders a form group with label, value, validation error and help
- **Value from everywhere** — old input, the request, an explicit value, the bound form model or a default
- **AJAX out of the box** — autosave, AJAX search and tags, tree selects, modals filled from the server
- **Files** — plain uploads, Laravel File Manager picker with drag and drop, Media Library field with previews, sorting and properties
- **Dynamic content** — fields in modals, repeaters and AJAX responses are initialized automatically
- **Layout** — light / dark / system theme, filters, content header with breadcrumbs, toastr / sweetalert / Bootstrap alerts

## Requirements

- PHP ^8.0
- Laravel 9 – 13
- `almasaeed2010/adminlte` ^3.2

## Installation

```bash
composer require fomvasss/laravel-lte3
composer require almasaeed2010/adminlte
php artisan lte3:install
```

Add `\Fomvasss\Lte3\Http\Middleware\LteRequestOptions` to the routes of the panel. Details, symlinked assets and the demo pages at `/lte3`: [Installation](https://fomvasss.github.io/laravel-lte3/installation/).

## Quick start

```blade
@extends('lte3::layouts.app')

@section('content')
    @include('lte3::parts.content-header', ['page_title' => 'Edit post'])

    <section class="content">
        <div class="card"><div class="card-body">
            {!! Lte3::formOpen(['action' => route('admin.posts.update', $post), 'method' => 'PUT', 'model' => $post]) !!}
                {!! Lte3::text('title', null, ['required' => true]) !!}
                {!! Lte3::select2('status', null, ['draft' => 'Draft', 'published' => 'Published']) !!}
                {!! Lte3::datetimepicker('published_at') !!}
                {!! Lte3::btnSubmit('Save') !!}
            {!! Lte3::formClose() !!}
        </div></div>
    </section>
@endsection
```

## Documentation

- [Installation](docs/installation.md) · [Configuration](docs/configuration.md)
- [Layout and pages](docs/usage/layout.md) · [Forms](docs/usage/forms.md) · [Actions and AJAX](docs/usage/actions.md)
- [Fields](docs/fields/overview.md) · [JavaScript API](docs/reference/javascript.md)
- [Upgrading](docs/upgrading.md) · [Changelog](CHANGELOG.md)

## Recommended

- [fomvasss/laravel-medialibrary-extension](https://github.com/fomvasss/laravel-medialibrary-extension) — server side of the media fields
- [fomvasss/laravel-simple-taxonomy](https://github.com/fomvasss/laravel-simple-taxonomy) — taxonomy for tree fields
- [fomvasss/laravel-variables](https://github.com/fomvasss/laravel-variables) — settings storage
- [UniSharp/laravel-filemanager](https://github.com/UniSharp/laravel-filemanager) — file manager

## Credits

- [AdminLTE 3](https://adminlte.io/themes/v3/)
- [fomvasss/laravel-its-lte](https://github.com/fomvasss/laravel-its-lte)
- [laravelcollective/html](https://laravelcollective.com/docs/6.x/html)

## License

MIT. See [LICENSE.md](LICENSE.md).
