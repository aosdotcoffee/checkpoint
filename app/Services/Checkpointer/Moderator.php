<?php

declare(strict_types=1);

namespace App\Services\Checkpointer;

use App\Models\Ban;
use App\Models\Virtual\ServerDto;
use Illuminate\Database\Eloquent\Collection;

final class Moderator
{
    /**
     * @param Collection<ServerDto> $collection
     * @return Collection<ServerDto>
     */
    public static function moderate(Collection $collection): Collection
    {
        $bans = Ban::query()
            ->where(function ($where) use ($collection) {
                /** @var ServerDto $server */
                foreach ($collection as $server) {
                    $where->orWhere(
                        fn ($orWhere) => $orWhere->ofAddress($server->ip_address),
                    );
                }
            })
            ->get();

        /** @var ServerDto $server */
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
