<?php

namespace App\Http\Controllers;

use App\Services\CheckpointerService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function show(CheckpointerService $checkpointer, Request $request)
    {
        $servers = $checkpointer->getList(cacheRemotes: true)
            ->sortByDesc('players_current');

        return view('home', [
            'servers' => $servers,
            'advanced' => $request->boolean('advanced'),
        ]);
    }
}
