<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPRange extends Model
{
    use Traits\IPRange;

    protected $table = 'ip_ranges';

    protected $casts = [
        'enabled' => 'bool',
    ];
}
