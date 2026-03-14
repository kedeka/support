<?php

namespace Kedeka\Support\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Kedeka\Support\Database\Concerns\HasUlid;

class UlidModel extends Model
{
    use HasUlid;

    protected $guarded = [];
}
