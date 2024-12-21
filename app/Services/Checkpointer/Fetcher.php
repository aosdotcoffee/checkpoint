<?php

declare(strict_types=1);

namespace App\Services\Checkpointer;

use App\Models\Remote;
use App\Models\Virtual\Server;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use WeakMap;

final class Fetcher
{
    /**
     * @param EloquentCollection<Remote> $remotes
     * @return WeakMap<Remote, array>
     */
    private static function fetchRemoteJSONs(EloquentCollection $remotes)
    {
        $map = new WeakMap();

        $responses = Http::pool(function (Pool $pool) use ($remotes) {
            $requests = [];

            /** @var Remote $remote */
            foreach ($remotes as $remote) {
                $requests[] = $pool->as("{$remote->id}")->get($remote->url);
            }

            return $requests;
        });

        foreach ($responses as $key => $value) {
            $remote = $remotes->where('id', $key)->firstOrFail();
            $map[$remote] = $value->json();
        }

        return $map;
    }

    /**
     * @param EloquentCollection<Remote> $remotes
     */
    public static function fetchServers(EloquentCollection $remotes)
    {
        /** @var EloquentCollection<Server> */
        $collection = new EloquentCollection();
        $listMap = static::fetchRemoteJSONs(remotes: $remotes);

        /** @var Remote $remote */
        foreach ($remotes as $remote) {
            foreach ($listMap[$remote] as $serverJSON) {
                $url = parse_url($serverJSON['identifier']);
                $byteString = pack('N', (int) $url['host']);
                $bytes = array_map(array: str_split($byteString), callback: ord(...));
                $address = "{$bytes[3]}.{$bytes[2]}.{$bytes[1]}.{$bytes[0]}";
                $port = (int) $url['port'];

                $server = new Server([
                    'name' => $serverJSON['name'],
                    'ip_address' => $address,
                    'port' => $port,
                    'map' => $serverJSON['map'],
                    'gamemode' => $serverJSON['game_mode'],
                    'country' => $serverJSON['country'],
                    'latency' => $serverJSON['latency'],
                    'players_current' => $serverJSON['players_current'],
                    'players_max' => $serverJSON['players_max'],
                    'last_updated' => $serverJSON['last_updated'],
                    'game_version' => $serverJSON['game_version'],
                    'remote_id' => $remote->id,
                ]);

                $collection->add($server);
            }
        }

        $collection->loadMissing('remote');
        return $collection;
    }
}
