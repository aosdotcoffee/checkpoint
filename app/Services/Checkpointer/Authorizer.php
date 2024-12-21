<?php

declare(strict_types=1);

namespace App\Services\Checkpointer;

use App\Models\Authority;
use App\Models\Virtual\Server;
use Illuminate\Database\Eloquent\Collection;

final class Authorizer
{
    /**
     * @param Collection<Server> $collection
     * @return Collection<Server>
     */
    public static function verify(Collection $collection): Collection
    {
        $authorities = Authority::all();

        /** @var Server $server */
        foreach ($collection as $server) {
            /* check if the server has a name reserved by an authority */
            $authority = $authorities->firstWhere(
                fn (Authority $authority) => preg_match($authority->regex, $server->name),
            );

            $server->authority_id = $authority?->id;

            /* none found - proceed */
            if (! $authority) {
                continue;
            }

            /* found an authority, check if the server is in an authorized range */
            $authorizedRange = $authority
                ->ranges()
                ->ofAddress($server->ip_address)
                ->where('enabled', '=', true)
                ->first();

            if (! $authorizedRange) {
                /* range not allowed - remove from list */
                $collection = $collection->reject($server);
            }
        }

        $collection->loadMissing('authority');
        return $collection;
    }
}
