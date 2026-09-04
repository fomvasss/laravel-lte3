<?php

namespace Fomvasss\Lte3\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaThumbUrlResolver
{
    /**
     * @param Media $media
     * @return string
     */
    public static function resolve(Media $media): string
    {
        $config = config('lte3.view.media.thumb', []);
        $driver = $config['driver'] ?? 'conversion';

        if (is_callable($driver)) {
            return $driver($media);
        }

        return match ($driver) {
            'imagepreset' => static::viaImagepreset($media, $config['imagepreset_params'] ?? []),
            default => $media->getUrl($config['conversion_name'] ?? 'thumb'),
        };
    }

    /**
     * @param Media $media
     * @param array $params
     * @return string
     */
    protected static function viaImagepreset(Media $media, array $params): string
    {
        if (! function_exists('imagepreset_url')) {
            throw new \RuntimeException(
                "lte3.view.media.thumb.driver=imagepreset потребує пакет fomvasss/laravel-imagepresets: composer require fomvasss/laravel-imagepresets"
            );
        }

        // bypass=true — виклик суто бекендовий (Blade-компонента адмінки, не публічний API),
        // саме такий сценарій пакет офіційно рекомендує для _t-токена: інакше w/h/fit мають
        // збігтись з allowed_widths/allowed_heights/allowed_sizes у конфізі проєкту (imagepresets.php),
        // а тут ці розміри задаються окремо через lte3.view.media.thumb.imagepreset_params.
        return imagepreset_url($media->getUrl(), $params, true);
    }
}
