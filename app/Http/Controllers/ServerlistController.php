<?php

namespace App\Http\Controllers;

use App\Models\Virtual\Server;
use App\Services\CheckpointerService;
use Illuminate\Http\Request;

class ServerlistController extends Controller
{
    public function index(CheckpointerService $checkpointer, Request $request)
    {
        $list = $checkpointer->getList(cacheRemotes: true)
            ->map(
                $request->input('debug') === '1' ?
                    $this->mapServerDebug(...):
                    $this->mapServer(...)
            )
            ->values()
            ->toArray();

        $body = json_encode($list, flags: JSON_UNESCAPED_SLASHES);

        return response($body)
            ->header('content-type', 'application/json')
            ->header('access-control-allow-origin', '*');
    }

    private function mapServer(Server $server)
    {
        return [
            'name' => $server->name,
            'identifier' => $server->identifier,
            'map' => $server->map,
            'game_mode' => $server->gamemode,
            'country' => $server->country,
            'latency' => $server->latency,
            'players_current' => $server->players_current,
            'players_max' => $server->players_max,
            'last_updated' => $server->last_updated,
            'game_version' => $server->game_version,
        ];
    }

    private function mapServerDebug(Server $server)
    {
        $nameSuffix = '';
        if ($server->authority) {
            $nameSuffix .= " [{$server->authority->name}]";
        } else {
            $nameSuffix .= ' [/]';
        }

        $nameSuffix .= " [{$server->remote->short_name}]";

        return [
            'name' => $server->name . $nameSuffix,
            'identifier' => $server->identifier,
            'map' => $server->map,
            'game_mode' => $server->gamemode,
            'country' => $server->country,
            'latency' => $server->latency,
            'players_current' => $server->players_current,
            'players_max' => $server->players_max,
            'last_updated' => $server->last_updated,
            'game_version' => $server->game_version,
        ];
    }
}
