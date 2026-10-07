# Installation

## Requirements

- PHP ^8.0
- Laravel 9 – 13
- [`almasaeed2010/adminlte`](https://packagist.org/packages/almasaeed2010/adminlte) ^3.2 — the AdminLTE 3 CSS, JS and plugins the layout loads from `public/vendor/adminlte`

Optional, for specific fields:

- [`fomvasss/laravel-medialibrary-extension`](https://github.com/fomvasss/laravel-medialibrary-extension) — server side of the [mediaFile](fields/mediaFile.md) field
- [`unisharp/laravel-filemanager`](https://github.com/UniSharp/laravel-filemanager) — the [lfmFile](fields/lfmFile.md) field and the file picker of the editors
- [`fomvasss/laravel-imagepresets`](https://github.com/fomvasss/laravel-imagepresets) ^1.16 — the `imagepreset` driver of [media thumbnails](usage/media-thumbnails.md)

## Install

```bash
composer require fomvasss/laravel-lte3
composer require almasaeed2010/adminlte
```

The service provider and the `Lte3` alias are registered by package discovery.

The layout expects the assets at fixed public paths:

| Path | Content |
|---|---|
| `public/vendor/adminlte/dist` | AdminLTE CSS, JS, images (from `vendor/almasaeed2010/adminlte/dist`) |
| `public/vendor/adminlte/plugins` | jQuery, Bootstrap, select2, toastr, moment and other plugins (from `vendor/almasaeed2010/adminlte/plugins`) |
| `public/vendor/lte3` | the package's `main.js`, `main.css`, `mb-blocks.js`, extra plugins and images (from `vendor/fomvasss/laravel-lte3/public`) |

Choose one of the ways below to put them there.

### `lte3:install` — a starter panel in the project

```bash
php artisan vendor:publish --tag=lte3-config # optional
php artisan lte3:install
```

The command asks for the dashboard slug (default `admin`) and then:

- copies (or symlinks, on request) the AdminLTE `dist` and `plugins` into `public/vendor/adminlte`
- copies the views `auth`, `examples`, `layouts` and `parts` into `resources/views/<slug>` and rewrites `'lte3::` references in them to `'<slug>.`, so the copies are yours to edit
- publishes the package assets (`lte3-assets`) into `public/vendor/lte3`
- creates `routes/<slug>.php` with `Route::view('<slug>', '<slug>.examples.home')` and appends `require __DIR__ . '/<slug>.php';` to `routes/web.php`
- runs `storage:link` if `public/storage` does not exist

The field components are not copied: `Lte3::text()` and the others keep rendering `lte3::components.*` from the package. Details: [Artisan commands](reference/commands.md).

> [!WARNING]
> Copied assets are not updated by `composer update`. After every package update run `php artisan vendor:publish --tag=lte3-assets --force`, or use symlinks instead (below). The same applies to the copied views: changes of the package layouts are not applied to your copies, see [Upgrading](upgrading.md).

### Symlinks — assets follow `composer update`

To use the package layouts as they are (`@extends('lte3::layouts.app')`) and get asset updates with Composer, link the three directories:

```bash
php artisan lte3:link
```

`lte3:link` creates absolute symlinks, which break when the project directory is moved or deployed to another path. Relative links survive both:

```bash
mkdir -p public/vendor/adminlte
ln -s ../../../vendor/almasaeed2010/adminlte/dist public/vendor/adminlte/dist
ln -s ../../../vendor/almasaeed2010/adminlte/plugins public/vendor/adminlte/plugins
ln -s ../../vendor/fomvasss/laravel-lte3/public public/vendor/lte3
```

With symlinks `almasaeed2010/adminlte` must be a regular (not `--dev`) dependency, otherwise `composer install --no-dev` on production leaves the links dangling.

> [!TIP]
> A dangling link is not visible on the dashboard status: the HTML renders with 200, only without styles. Check a specific asset: `curl -o /dev/null -w '%{http_code}' https://site.test/vendor/adminlte/dist/css/adminlte.min.css`.

## Middleware

`LteRequestOptions` remembers the last URL of index pages for the "back" button, opens a modal after a redirect and stores a few UI options in the session — see [Back URL, modals and options](usage/request-options.md). Add it to the routes of the panel.

Laravel 11 and later, `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Fomvasss\Lte3\Http\Middleware\LteRequestOptions::class,
    ]);
})
```

Laravel 9 and 10, `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \Fomvasss\Lte3\Http\Middleware\LteRequestOptions::class,
    ],
];
```

Or only on the admin group:

```php
Route::middleware(['web', 'auth', \Fomvasss\Lte3\Http\Middleware\LteRequestOptions::class])
    ->prefix('admin')
    ->group(base_path('routes/admin.php'));
```

The `middleware` key of the config applies only to the demo routes of the package.

## Demo pages

Outside of the `production` environment and while `routes` in the config is `true`, the package registers demo routes under `/lte3`:

| URL | Page |
|---|---|
| `/lte3`, `/lte3/2`, `/lte3/3` | dashboards |
| `/lte3/components` | all fields and components |
| `/lte3/mb-blocks` | block repeaters |
| `/lte3/blank` | an empty page |
| `/lte3/login`, `/lte3/register`, `/lte3/forgot-password`, `/lte3/reset-password` | auth pages |

The demo routes have no authentication, and the demo endpoint `/lte3/data/save` writes request values into the session under a key taken from the request.

> [!WARNING]
> The demo is disabled only by `APP_ENV=production`. On a publicly reachable staging or test server set `'routes' => false` in a published config.

## Translations

Texts of the newer field UIs (media and lfm fields) go through `__()` with English keys. To translate them, add the keys to `lang/<locale>.json` of the project; the lists are on the [mediaFile](fields/mediaFile.md) and [lfmFile](fields/lfmFile.md) pages.

## Next steps

- [Configuration](configuration.md)
- [Layout and pages](usage/layout.md) — build the first page
- [Forms](usage/forms.md)
