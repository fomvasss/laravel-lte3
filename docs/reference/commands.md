# Artisan commands

| Command | Description |
|---|---|
| `lte3:install` | Copy the starter views into the project, publish assets, add a route |
| `lte3:link` | Symlink AdminLTE and lte3 assets into `public/vendor` |

## `lte3:install`

Interactive. Asks:

1. **Dashboard slug** (default `admin`) — converted to snake case; the name of the views directory and the route file
2. **Copy files or make symbolic links?** — for the AdminLTE `dist` and `plugins`

Steps:

1. Stops with a warning if `vendor/almasaeed2010` does not exist (`composer require almasaeed2010/adminlte`)
2. Creates `public/vendor/adminlte` (warns if it exists and continues) and copies or symlinks `dist` and `plugins` into it. Existing links are kept
3. Stops with a warning if `resources/views/<slug>` already exists
4. Copies the package views `auth`, `examples`, `layouts`, `parts` into `resources/views/<slug>` and replaces `'lte3::` with `'<slug>.` in them. Only quoted view names are rewritten
5. Publishes `lte3-assets` — a copy of the package `public` into `public/vendor/lte3`
6. Writes `routes/<slug>.php` with `Route::view('<slug>', '<slug>.examples.home');` and appends `require __DIR__ . '/<slug>.php';` to `routes/web.php`
7. Runs `storage:link` if `public/storage` does not exist

The route has no middleware of its own: it gets the middleware of `routes/web.php` (`web`). Add authentication before deploying, e.g. wrap the route file in `Route::middleware('auth')->group(...)`.

Running the command again with another slug creates a second copy and appends one more `require` to `routes/web.php`.

## `lte3:link`

Not interactive. For each target that does not exist yet:

- `public/vendor/adminlte/dist` and `public/vendor/adminlte/plugins` → `vendor/almasaeed2010/adminlte/...` (warns if the package is not installed)
- `public/vendor/lte3` → the package `public` directory
- runs `storage:link` if `public/storage` does not exist

The links are absolute. They break when the project is moved or deployed into another path; relative links are shown in [Installation](../installation.md#symlinks--assets-follow-composer-update).

## Publishing

| Tag | What |
|---|---|
| `lte3-config` | `config/lte3.php` |
| `lte3-assets` | `public/vendor/lte3` (copy of the package `public`) |
| `lte3-views` | all views → `resources/views/vendor/lte3` |
| `lte3-view-components`, `lte3-view-layouts`, `lte3-view-parts`, `lte3-view-auth`, `lte3-view-examples` | one directory of views |

```bash
php artisan vendor:publish --tag=lte3-view-components
```

See [Views and publishing](views.md).
