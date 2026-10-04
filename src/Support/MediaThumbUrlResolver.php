<?php

namespace Fomvasss\Lte3\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaThumbUrlResolver
{
    /**
     * URL мініатюри медіа для полів адмінки (lte3.view.media.thumb).
     *
     * @param Media $media
     * @param int|null $size розмір плитки в px (thumb_size поля): imagepreset генерує мініатюру вдвічі більшу,
     *                       щоб на екранах з високою щільністю вона не була розмитою
     * @return string
     */
    public static function resolve(Media $media, ?int $size = null): string
    {
        $config = config('lte3.view.media.thumb', []);
        $driver = $config['driver'] ?? 'conversion';

        if (is_callable($driver)) {
            return $driver($media, $size);
        }

        if ($driver === 'imagepreset') {
            return static::viaImagepreset($media->getUrl(), static::imagepresetParams($size));
        }

        // конверсія ще не згенерована (черга не відпрацювала) — показуємо оригінал, а не 404
        $conversion = $config['conversion_name'] ?? 'thumb';

        return $media->hasGeneratedConversion($conversion) ? $media->getUrl($conversion) : $media->getUrl();
    }

    /**
     * URL мініатюри картинки за її адресою (поле lfmFile/lfmImage, lte3.view.lfm.thumb).
     *
     * @param string $url
     * @param int|null $size
     * @return string
     */
    public static function resolveUrl(string $url, ?int $size = null): string
    {
        $driver = config('lte3.view.lfm.thumb');

        if (is_string($driver) && class_exists($driver)) {
            $driver = app($driver);
        }

        if (is_callable($driver)) {
            return $driver($url, $size);
        }

        return $driver === 'imagepreset' ? static::viaImagepreset($url, static::imagepresetParams($size)) : $url;
    }

    /**
     * Параметри imagepreset: з lte3.view.media.thumb.imagepreset_params, розміри — 2× від розміру плитки
     */
    protected static function imagepresetParams(?int $size): array
    {
        $params = config('lte3.view.media.thumb.imagepreset_params', ['w' => 100, 'h' => 100, 'fit' => 'crop']);

        if ($size) {
            $params['w'] = $params['h'] = $size * 2;
        }

        return $params;
    }

    /**
     * @param string $url
     * @param array $params
     * @return string
     */
    protected static function viaImagepreset(string $url, array $params): string
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
        return imagepreset_url($url, $params, true);
    }
}
