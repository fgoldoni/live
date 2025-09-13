<?php

namespace Core\Traits;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

trait WithSEOInitializable
{
    public function initializeSEO(string $title, string $description, ?string $image = null, ?string $url = null, array $tags = []): void
    {
        static::initialize($title, $description, $image, $url, $tags);
    }

    public static function initialize(string $title, string $description, ?string $image = null, ?string $url = null, array $tags = []): void
    {
        $url ??= url()->current();
        $image ??= asset('images/share.png');

        if (app()->environment('production') && ! Str::startsWith($image, ['http://', 'https://'])) {
            $path = public_path(parse_url($image, PHP_URL_PATH));

            if (is_file($path)) {
                $resized = self::resizeSeoImage($path, [
                    'maxWidth'  => 1200,
                    'maxHeight' => 630,
                    'format'    => pathinfo($path, PATHINFO_EXTENSION) ?: 'jpg',
                ]);

                $image = asset(Str::after($resized, public_path()));
            }
        }

        SEOTools::setTitle($title, false);
        SEOTools::setDescription($description);
        SEOTools::setCanonical($url);

        $og = SEOTools::opengraph();
        $og->setUrl($url);
        $og->addProperty('type', 'website');
        $og->addProperty('locale', app()->getLocale());
        $og->addImage($image, ['width' => 1200, 'height' => 630]);

        $tw = SEOTools::twitter();
        $tw->setType('summary_large_image');
        $tw->setImage($image);
        $tw->setTitle($title);
        $tw->setDescription($description);

        $jsonLd = SEOTools::jsonLd();
        $jsonLd->setType('WebPage')->setTitle($title)->setDescription($description)->addImage($image);

        SEOMeta::addKeyword($tags);
    }

    protected static function resizeSeoImage(string $path, array $opts): string
    {
        $diskName    = config('filesystems.default', 'public');
        $disk        = Storage::disk($diskName);
        $ext         = strtolower((string) $opts['format']);
        $name        = pathinfo($path, PATHINFO_FILENAME);
        $relativeDir = 'seo-images';

        $disk->makeDirectory($relativeDir);

        // Conserve l’extension d’origine, sinon jpg
        $ext = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? $ext : 'jpg';

        $relativePath = "{$relativeDir}/{$name}-seo.{$ext}";
        $absolutePath = public_path($relativePath);

        $img = Image::make($path)->resize(
            $opts['maxWidth'],
            $opts['maxHeight'],
            function ($c) { $c->aspectRatio()->upsize(); }
        );

        $encoded = ($ext === 'webp')
            ? $img->encode('webp', 80)
            : $img->encode($ext, 90);

        // Écrit le fichier optimisé dans /public/seo-images
        file_put_contents($absolutePath, (string) $encoded);

        // Passe la moulinette Spatie (jpegoptim/optipng/cwebp etc. si installés)
        try {
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($absolutePath);
        } catch (\Throwable) {
            // silencieux: si binaire manquant, on garde l’image déjà compressée
        }

        return $absolutePath;
    }
}
