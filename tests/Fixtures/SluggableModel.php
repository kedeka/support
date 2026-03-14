<?php

namespace Kedeka\Support\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Kedeka\Support\Database\Concerns\HasSlug;

class SluggableModel extends Model
{
    use HasSlug;

    protected $guarded = [];
}
