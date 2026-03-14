<?php

namespace Kedeka\Support\Database\Concerns;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasUlid
{
    public static function bootHasUlid()
    {
        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'ulid')) {
                if (! $model->ulid) {
                    $model->ulid = (string) Str::ulid();
                }
            }
        });
    }
}
