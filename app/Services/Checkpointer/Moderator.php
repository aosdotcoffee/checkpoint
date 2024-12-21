<?php

declare(strict_types=1);

namespace App\Services\Checkpointer;

use App\Models\Ban;
use App\Models\Virtual\Server;
use Illuminate\Database\Eloquent\Collection;

final class Moderator
{
    /**
     * @param Collection<Server> $collection
     * @return Collection<Server>
     */
    public static function moderate(Collection $collection): Collection
    {
        $bans = Ban::query()
            ->where(function ($where) use ($collection) {
                /** @var Server $server */
                foreach ($collection as $server) {
                    $where->orWhere(
                        fn ($orWhere) => $orWhere->ofAddress($server->ip_address),
                    );
                }
            })
            ->get();

        /** @var Server $server */
        foreach ($collection as $server) {
            $ban = $bans->firstWhere(
                fn (Ban $ban) => $ban->matchesAddress($server->ip_address),
            );

            if ($ban) {
                $collection = $collection->reject($server);
            }
        }

        return $collection;
    }
}
