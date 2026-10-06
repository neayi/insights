<?php

namespace App\Src\Utils\Image;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Laravel\Facades\Image;

/**
 * Replacement for the former intervention/imagecache: scales the smallest side of the
 * picture down to $dim (never upsizes) and caches the encoded result.
 */
class ScaledImageResponse
{
    public static function make(string $path, int $dim, int $ttlMinutes): Response
    {
        $cacheKey = 'scaled-image:' . md5($path . '|' . @filemtime($path) . '|' . $dim);

        [$content, $mediaType] = Cache::remember($cacheKey, $ttlMinutes * 60, function () use ($path, $dim) {
            $img = Image::read($path);

            if ($img->width() <= $img->height())
                $img->scaleDown(width: $dim);
            else
                $img->scaleDown(height: $dim);

            $encoded = $img->encode();

            return [$encoded->toString(), $encoded->mediaType()];
        });

        return response($content, 200, ['Content-Type' => $mediaType]);
    }
}
