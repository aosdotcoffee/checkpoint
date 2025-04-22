<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Remote extends Model
{
    protected $casts = [
        'enabled' => 'boolean',
    ];

    #[Scope]
    protected function enabled(Builder|EloquentBuilder $builder)
    {
        $builder->where('enabled', '=', true);
    }
}
