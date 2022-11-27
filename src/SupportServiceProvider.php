<?php

namespace Kedeka\Support;

use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\UrlWindow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\HtmlString;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Kedeka\Support\SupportServiceProvider.
 */
class SupportServiceProvider extends PackageServiceProvider
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

    public function packageRegistered()
    {
        $this->registerLengthAwarePaginator();
    }

    protected function bootMacro()
    {
        RedirectResponse::macro('banner', function($message) {
            /** @var RedirectResponse $this */
            $this->with('flash', [
                'bannerStyle' => 'success',
                'timestamp' => now()->timestamp,
                'banner' => $message,
            ]);
        });

        RedirectResponse::macro('dangerBanner', function($message) {
            /** @var RedirectResponse $this */
            $this->with('flash', [
                'bannerStyle' => 'danger',
                'timestamp' => now()->timestamp,
                'banner' => $message,
            ]);
        });
    }

    protected function bootBlueprint()
    {
        Blueprint::macro('ulidAlias', function ($name = 'ulid') {
            /** @var Blueprint $this */
            $this->string($name, 26)->unique();
        });

        Blueprint::macro('ulidPrimary', function ($name = 'id') {
            /** @var Blueprint $this */
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

    protected function registerLengthAwarePaginator()
    {
        $this->app->bind(LengthAwarePaginator::class, function ($app, $values) {
            return new class(...array_values($values)) extends LengthAwarePaginator
            {
                public function only(...$attributes)
                {
                    return $this->transform(function ($item) use ($attributes) {
                        return $item->only($attributes);
                    });
                }

                public function transform($callback)
                {
                    $this->items->transform($callback);

                    return $this;
                }

                public function merge($attributes = [])
                {
                    $this->merge = $attributes;

                    return $this;
                }

                public function toArray()
                {
                    return [
                        'data' => $this->items->toArray(),
                        'links' => $this->links(),
                        'from' => (($this->currentPage() - 1) * $this->perPage()) + 1,
                        'prev' => [
                            'url' => $this->previousPageUrl(),
                            'label' => 'Previous',
                            'active' => false,
                        ],
                        'next' => [
                            'url' => $this->nextPageUrl(),
                            'label' => 'Next',
                            'active' => false,
                        ],
                        ...$this->merge ?? [],
                    ];
                }

                public function links($view = null, $data = [])
                {
                    $this->appends(Request::all());

                    $window = UrlWindow::make($this);

                    $elements = array_filter([
                        $window['first'],
                        is_array($window['slider']) ? '...' : null,
                        $window['slider'],
                        is_array($window['last']) ? '...' : null,
                        $window['last'],
                    ]);

                    return Collection::make($elements)->flatMap(function ($item) {
                        if (is_array($item)) {
                            return Collection::make($item)->map(function ($url, $page) {
                                return [
                                    'url' => $url,
                                    'label' => $page,
                                    'active' => $this->currentPage() === $page,
                                ];
                            });
                        } else {
                            return [
                                [
                                    'url' => null,
                                    'label' => '...',
                                    'active' => false,
                                ],
                            ];
                        }
                    });
                }
            };
        });
    }
}
