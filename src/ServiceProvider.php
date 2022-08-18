<?php

namespace Kedeka\Support;

use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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

    public function bootBlueprint()
    {
        Blueprint::macro('ulid', function ($name = 'ulid') {
            $this->string($name, 26)->unique();
        });

        Blueprint::macro('ulidPrimary', function ($name = 'id') {
            $this->string($name, 26)->primary();
        });
    }

    public function bootBlade()
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
