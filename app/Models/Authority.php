<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Authority extends Model
{
    public function ranges(): HasMany
    {
        return $this->hasMany(IPRange::class, foreignKey: 'authority_id');
    }
}
