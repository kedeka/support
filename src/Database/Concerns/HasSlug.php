<?php

namespace Kedeka\Support\Database\Concerns;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug()
    {
        $callback = function($model){
            if (Schema::hasColumn($model->getTable(), 'slug')) {
                if (!$model->slug) {
                    $slugable = $model->{$model->getSlugableColumn()} ?: $model->name;
                    $model->slug = (string) Str::slug($slugable);
                }
            }
        };

        static::saving($callback);
    }

    public function getSlugableColumn()
    {
        return $this->slugColumn ?? 'title';
    }
}
