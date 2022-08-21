<?php

namespace Kedeka\Support;

use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Http\RedirectResponse;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Kedeka\Support\ServiceProvider.
 */
class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('support');
    }

    public function packageBooted()
    {
        $this->bootBlueprint();
        $this->bootBlade();
    }

    protected function bootMacro()
    {
        RedirectResponse::macro('banner', fn ($message) => $this->with('flash', [
            'bannerStyle' => 'success',
            'timestamp' => now()->timestamp,
            'banner' => $message,
        ]));

        RedirectResponse::macro('dangerBanner', fn ($message) => $this->with('flash', [
            'bannerStyle' => 'danger',
            'timestamp' => now()->timestamp,
            'banner' => $message,
        ]));
    }

    protected function bootBlueprint()
    {
        Blueprint::macro('ulid', function ($name = 'ulid') {
            $this->string($name, 26)->unique();
        });

        Blueprint::macro('ulidPrimary', function ($name = 'id') {
            $this->string($name, 26)->primary();
        });
    }

    protected function bootBlade()
    {
        Blade::directive('viteCssOnly', function ($expression) {
            if (is_file(public_path('/hot'))) {
                return app(\Illuminate\Foundation\Vite::class)('resources/css/app.css');
            }

            $manifestPath = public_path('build/manifest.json');
            $manifests = json_decode(file_get_contents($manifestPath), true);

            $css = $manifests['resources/js/app.js']['css'][0] ?? null;

            if ($css) {
                if (file_exists(public_path('build/'.$css))) {
                    $cssUrl = asset("build/{$css}");

                    return new HtmlString("<link rel=\"stylesheet\" href=\"{$cssUrl}\">");
                }
            }

            throw new Exception('CSS File Not found');
        });
    }
}
