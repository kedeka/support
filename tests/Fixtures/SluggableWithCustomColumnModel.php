<?php

namespace Kedeka\Support\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Kedeka\Support\Database\Concerns\HasSlug;

class SluggableWithCustomColumnModel extends Model
{
    use HasSlug;

    protected $table = 'sluggable_models';

    protected $guarded = [];

    protected string $slugColumn = 'name';
}
