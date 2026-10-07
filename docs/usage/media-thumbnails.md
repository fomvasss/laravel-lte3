# Media thumbnails

The image tiles of [mediaImage](../fields/mediaFile.md) and [lfmImage](../fields/lfmFile.md) show a thumbnail instead of the full image. How its URL is built is configured separately for the two fields, both in `config/lte3.php`. The minimal tile width is `view.media.thumb_size` (110 px), or the `thumb_size` attribute of a field.

## mediaFile / mediaImage — `view.media.thumb`

```php
'media' => [
    'thumb' => [
        'driver' => 'conversion', // 'conversion' | 'imagepreset' | callable
        'conversion_name' => 'thumb',
        'imagepreset_params' => ['w' => 100, 'h' => 100, 'fit' => 'crop'],
    ],
],
```

### `conversion` (default)

The Media Library conversion `conversion_name` of the media: `$media->getUrl('thumb')`. Register it on the model:

```php
public function registerMediaConversions(?Media $media = null): void
{
    $this->addMediaConversion('thumb')->fit(Fit::Crop, 220, 220)->nonQueued();
}
```

While the conversion is not generated yet (queued conversions), the original file is shown instead of a broken image.

### `imagepreset`

The thumbnail is generated on the fly by [`fomvasss/laravel-imagepresets`](https://github.com/fomvasss/laravel-imagepresets) (^1.16), no conversion needed:

```bash
composer require fomvasss/laravel-imagepresets
```

```php
'driver' => 'imagepreset',
'imagepreset_params' => ['w' => 100, 'h' => 100, 'fit' => 'crop'],
```

The URL is `imagepreset_url($media->getUrl(), $params, true)`. Width and height are replaced with 2× the tile size (`thumb_size`), so the thumbnail stays sharp on high-density screens; the other parameters (`fit`, format, quality, ...) are taken from `imagepreset_params`.

The third argument (`bypass`) signs the URL with a token, so the sizes do not have to be in `allowed_widths` / `allowed_heights` / `allowed_sizes` of the project's `imagepresets` config: they come from trusted config here, not from user input. With `imagepresets.trusted_bypass = false` the token is ignored and the normal allowlist checks apply.

Without the package installed the field throws a `RuntimeException` that names the missing package.

### Callable

Full control: a callable that receives the media and the tile size and returns the URL.

```php
'driver' => fn (\Spatie\MediaLibrary\MediaCollections\Models\Media $media, ?int $size) => $media->getUrl('preview'),
```

> [!WARNING]
> A closure in the config breaks `php artisan config:cache`. Use an invokable class name resolved through a static method, or set the driver at runtime: `config(['lte3.view.media.thumb.driver' => [ThumbUrl::class, 'forMedia']])`.

## lfmFile / lfmImage — `view.lfm.thumb`

The value of an lfm field is a URL from Laravel File Manager, so there is no conversion:

| Value | Thumbnail |
|---|---|
| `null` (default) | the image itself |
| `'imagepreset'` | `imagepreset_url()` with `view.media.thumb.imagepreset_params`, 2× `thumb_size`, as above |
| a class name | `app($class)($url, $size)` — a class with `__invoke(string $url, ?int $size): string` |

```php
namespace App\Admin;

class LfmThumb
{
    public function __invoke(string $url, ?int $size): string
    {
        return $url . '?w=' . ($size * 2);
    }
}
```

```php
'lfm' => [
    'thumb' => \App\Admin\LfmThumb::class,
],
```

A class name, unlike a closure, survives `config:cache`.

## In your own views

`Fomvasss\Lte3\Support\MediaThumbUrlResolver` is the same resolver the fields use:

```php
use Fomvasss\Lte3\Support\MediaThumbUrlResolver;

MediaThumbUrlResolver::resolve($media, 110); // Media → thumbnail URL by view.media.thumb
MediaThumbUrlResolver::resolveUrl($url, 110); // image URL → thumbnail URL by view.lfm.thumb
```
