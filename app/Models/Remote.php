<?php

namespace App\Models;

use App\Services\Remote\Status;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;

class Remote extends Model
{
    protected $casts = [
        'enabled' => 'boolean',
        'status' => Status::class,
    ];

    #[Scope]
    protected function enabled(Builder $builder)
    {
        $builder->where('enabled', '=', true);
    }

    #[Scope]
    protected function status(Builder $builder, Status $status)
    {
        $builder->where('status', '=', $status);
    }
}
