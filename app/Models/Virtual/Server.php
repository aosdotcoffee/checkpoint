<?php

namespace App\Models\Virtual;

use App\Services\CheckpointerService;

class Server extends ServerDto
{
    public function getRows()
    {
        return app(CheckpointerService::class)->getList(cacheRemotes: true)->toArray();
    }
}
