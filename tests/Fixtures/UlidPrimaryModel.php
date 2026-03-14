<?php

namespace Kedeka\Support\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Kedeka\Support\Database\Concerns\UlidAsPrimary;

class UlidPrimaryModel extends Model
{
    use UlidAsPrimary;

    protected $guarded = [];
}
