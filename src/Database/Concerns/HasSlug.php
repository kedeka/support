<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'slug')) {
                if (!$model->slug) {
                    $slugable = $model->{$model->getSlugableColumn()} ?: $model->name;
                    $model->slug = (string) Str::slug($slugable);
                }
            }
        });
    }

    public function getSlugableColumn()
    {
        return 'title';
    }
}
