<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;

class Remote extends Model
{
    protected $casts = [
        'enabled' => 'boolean',
    ];

    #[Scope]
    protected function enabled(Builder $builder)
    {
        $builder->where('enabled', '=', true);
    }
}
