<?php

declare(strict_types=1);

namespace App\Services\Checkpointer;

use App\Models\Remote;
use App\Models\Virtual\ServerDto;
use Illuminate\Database\Eloquent;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

final class Fetcher
{
    /**
     * @param Eloquent\Collection<int, Remote> $remotes
     */
    public function __construct(
        private Eloquent\Collection $remotes,
    ) { }

    /**
     * Add the requests to send out to the HTTP pool
     */
    private function createRequests(Pool $pool)
    {
        foreach ($this->remotes as $remote) {
            $pool
                ->as((string) $remote->id)
                ->get($remote->url);
        }
    }

    /**
     * Send the serverlist requests to the remotes in parallel and wait for an answer
     */
    private function fetchServerlists()
    {
        return Http::createPendingRequest()
            ->withHeader('User-Agent', 'Checkpoint')
            ->withHeader('Accept', 'application/json')
            ->pool($this->createRequests(...));
    }

    /**
     * Fetch the JSON from the remotes and match them to the remote models
     */
    private function getResponses()
    {
        foreach ($this->fetchServerlists() as $key => $value) {
            $remote = $this->remotes
                ->where('id', $key)
                ->firstOrFail();

            yield $remote => $value->json();
        }
    }

    /**
     * Map JSON received by a server class to a {@see App\Models\Virtual\Server} model
     */
    private function mapJsonToServer(Remote $remote, array $json)
    {
        $url = parse_url($json['identifier']);
        $byteString = pack('N', (int) $url['host']);
        $bytes = array_map(array: str_split($byteString), callback: ord(...));
        $address = "{$bytes[3]}.{$bytes[2]}.{$bytes[1]}.{$bytes[0]}";
        $port = (int) $url['port'];

        return new ServerDto([
            'name' => $json['name'],
            'ip_address' => $address,
            'port' => $port,
            'map' => $json['map'],
            'gamemode' => $json['game_mode'],
            'country' => $json['country'],
            'latency' => $json['latency'],
            'players_current' => $json['players_current'],
            'players_max' => $json['players_max'],
            'last_updated' => $json['last_updated'],
            'game_version' => $json['game_version'],
            'remote_id' => $remote->id,
        ]);
    }

    /**
     * Fetch the servers, returning a collection of {@see App\Models\Virtual\ServerDto} objects
     */
    public function fetchServers()
    {
        /** @var Eloquent\Collection<ServerDto> */
        $collection = new Eloquent\Collection;

        /** @var Remote $remote */
        foreach ($this->getResponses() as $remote => $servers) {
            $objects = array_map(
                array: $servers,
                callback: fn (array $json) =>
                    $this->mapJsonToServer($remote, $json),
            );

            $collection->push(...$objects);
        }

        $collection->loadMissing('remote');

        return $collection;
    }
}
