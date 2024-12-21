<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Remote;
use App\Services\Checkpointer\Authorizer;
use App\Services\Checkpointer\Fetcher;
use App\Services\Checkpointer\Merger;
use App\Services\Checkpointer\Moderator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class CheckpointerService
{
    /**
     * @param Collection<Remote> $remotes
     */
    public function __construct(private Collection $remotes) {}

    public function getList(bool $cacheRemotes = false)
    {
        if ($cacheRemotes) {
            $servers = Cache::remember(
                key: 'fetcher.servers',
                ttl: 15,
                callback: fn () => Fetcher::fetchServers(remotes: $this->remotes),
            );
        } else {
            $servers = Fetcher::fetchServers(remotes: $this->remotes);
        }

        $servers = Merger::merge($servers);
        $servers = Moderator::moderate($servers);
        $servers = Authorizer::verify($servers);

        return $servers;
    }
}
