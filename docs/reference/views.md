# Views and publishing

Views are registered under the `lte3::` namespace.

| Directory | Views |
|---|---|
| `layouts` | `app`, `app-print`, `inc/begin`, `inc/end`, `inc/navbar`, `inc/sidebar`, `inc/sidebar-menu/example`, `inc/footer`, `inc/preloader`, `inc/options` |
| `parts` | `content-header`, `alerts`, `alerts/toastr`, `alerts/sweetalert`, `alerts/bootstrap`, `callouts`, `filter-wrap`, `filter-wrap2` |
| `components` | one view per field, see [Fields overview](../fields/overview.md) |
| `auth` | `app`, `login`, `register`, `forgot-password`, `reset-password` |
| `examples` | demo pages of `/lte3` |

What the layouts and parts do: [Layout and pages](../usage/layout.md).

## Overriding

Laravel looks for `lte3::<view>` in `resources/views/vendor/lte3/<view>.blade.php` first. Publish the whole set or one directory and edit the copy:

```bash
php artisan vendor:publish --tag=lte3-view-layouts
php artisan vendor:publish --tag=lte3-view-components
php artisan vendor:publish --tag=lte3-views # everything
```

A single view can be overridden without publishing the rest: create the file at the same path, e.g. `resources/views/vendor/lte3/layouts/inc/sidebar-menu/example.blade.php` for your own menu.

To change one field for the whole project, pointing its config entry to your own view is often simpler than overriding the package view — see [Configuration](../configuration.md#viewcomponents).

> [!WARNING]
> A published or copied view stops receiving package updates: new options, fixes and the scripts a newer `main.js` relies on. Publish only what you change, and check [Upgrading](../upgrading.md) for notes about copies after each update. The views most often affected are `layouts/inc/begin`, `layouts/inc/end`, `layouts/inc/options` and `components/*`.

## Assets

`public/vendor/lte3` must contain the package `public` directory: `main.js`, `main.css`, `mb-blocks.js`, `mb-block.css`, `img/` and `plugins/`. Plugins shipped with the package (not with AdminLTE): datetimepicker, multidatespicker, magnific-popup, x-editable, select2-to-tree, bootstrap-treeview, jquery-sortable, pace and others in `public/plugins`.

A copy made with `--tag=lte3-assets` must be republished after updates:

```bash
php artisan vendor:publish --tag=lte3-assets --force
```

A symlink ([Installation](../installation.md#symlinks--assets-follow-composer-update)) needs nothing.
