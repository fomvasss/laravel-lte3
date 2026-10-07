# Helpers

Global functions loaded by Composer autoload. Each is defined only if a function with the same name does not exist yet.

## Assets

### `lte3_asset(string $path): string`

URL of a file in `public/vendor/lte3` with `?v=<modification time>`, so browsers load the new file right after a package update or asset republish:

```blade
<link rel="stylesheet" href="{{ lte3_asset('main.css') }}">
<script src="{{ lte3_asset('main.js') }}"></script>
```

Without the file on disk the URL has no version. The package layout uses it for `main.js`, `main.css`, `mb-blocks.js` and `mb-block.css`; use it in published or copied layouts too.

## Trees

### `treeview(array $tree, array $selected = []): array`

Converts a nested tree (`[['id' => 1, 'name' => 'Sport', 'children' => [...]], ...]`) into the data format of the [treeview](../fields/treeview.md) plugin: `id`, `text`, `state.expanded`, `state.checked` for ids in `$selected`, `nodes` for children. Every item must have a `children` array.

### `build_linear_array_sort(array $tree, $parent_id = null, bool $use_parent = true): array`

Turns the nested order sent by [nestedset](../fields/nestedset.md) sorting back into flat rows to save: `[['id' => 5, 'weight' => 0, 'parent_id' => null], ...]`. `weight` is the position among siblings; with `$use_parent = false` the `parent_id` key is omitted.

## Formatting

| Function | Result |
|---|---|
| `human_filesize(int $bytes, int $precision = 2)` | `1536` → `1.5 KB` (`B`, `KB`, `MB`, `GB`, `TB`, base 1024) |
| `human_duration(int $seconds)` | `3725` → `1:02:05` (`H:MM:SS`; the `$format` parameter is ignored) |
| `pagination_row_number(LengthAwarePaginator $items, int $loopIndex)` | Row number counting down from the total across pages: `{{ pagination_row_number($posts, $loop->index) }}` |
| `string_to_color_code(string $str)` | A stable 6-digit hex color (without `#`) from a string, e.g. for avatars or tags |
| `rand_color()` | A random `#rrggbb` |

## Arrays

| Function | Result |
|---|---|
| `explode_assoc(string $string)` | `'1:106;2:110'` → `[1 => '106', 2 => '110']` |
| `array_values_recursive(array $array)` | All scalar values of a nested array as a flat list |
| `is_array_assoc(array $array)` | `true` unless the keys are `0..n-1`; `false` for `[]` |
| `array_search_assoc(array $needle, array $haystack, $returnItem = false)` | Index of the first item of `$haystack` whose keys from `$needle` are equal (`==`), or the item itself with `$returnItem`; `false` if none |

## Request and old input

| Function | Result |
|---|---|
| `old_contain($key, $needle)` | Whether the old input `$key` equals `$needle`, or contains it if it is an array — for checkboxes and multiple selects |
| `old_request($key, $default = null)` | `request($key)`, else `old($key)`, else `$default` |
| `comparison_bool($value)` | `true` for `true`, `1`, `"1"`, `"true"`; `false` otherwise |

## URLs

### `url_add_params(string $url, array $params = []): string`

Adds or replaces query parameters:

```php
url_add_params('https://site.test/posts?page=2', ['status' => 'draft']);
// https://site.test/posts?page=2&status=draft
```

The URL must be absolute: the host is required, a missing scheme becomes `https://`, a port, credentials and `#fragment` are dropped.
