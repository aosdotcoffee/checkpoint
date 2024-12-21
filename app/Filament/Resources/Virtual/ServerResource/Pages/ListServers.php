<?php

namespace App\Filament\Resources\Virtual\ServerResource\Pages;

use App\Filament\Resources\Virtual\ServerResource;
use Filament\Resources\Pages\ListRecords;

class ListServers extends ListRecords
{
    protected static string $resource = ServerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
