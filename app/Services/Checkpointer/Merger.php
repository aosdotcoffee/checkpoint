<?php

declare(strict_types=1);

namespace App\Services\Checkpointer;

use App\Models\Virtual\Server;
use Illuminate\Database\Eloquent\Collection;

final class Merger
{
    /**
     * @param Collection<Server> $collection
     * @return Collection<Server>
     */
    public static function merge(Collection $collection): Collection
    {
        $merged = new Collection();

        foreach ($collection->pluck('identifier')->unique() as $identifier) {
            $server = $collection
                ->where('identifier', $identifier)
                ->sortByDesc(['last_updated', 'remote.priority'])
                ->firstOrFail();

            $merged->add($server);
        }

        return $merged;
    }
}
