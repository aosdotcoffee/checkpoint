<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Remote;
use App\Services\Checkpointer\Authorizer;
use App\Services\Checkpointer\Fetcher;
use App\Services\Checkpointer\Merger;
use App\Services\Checkpointer\Moderator;
use Illuminate\Support\Facades\Cache;

final class CheckpointerService
{
    /**
     * @param array<int, Remote> $remotes
     */
    private array $remotes;

    public function __construct(array $remotes)
    {
        $this->remotes = $remotes;
    }

    public function getList(bool $cacheRemotes = false)
    {
        $fetcher = new Fetcher($this->remotes);

        if ($cacheRemotes) {
            $servers = Cache::remember(
                key: 'fetcher.servers',
                ttl: 5,
                callback: fn () => $fetcher->fetchServers(),
            );
        } else {
            $servers = $fetcher->fetchServers();
        }

        $servers = Merger::merge($servers);
        $servers = Moderator::moderate($servers);
        $servers = Authorizer::verify($servers);

        return $servers;
    }
}
