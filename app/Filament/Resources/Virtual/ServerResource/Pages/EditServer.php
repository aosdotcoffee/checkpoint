<?php

namespace App\Filament\Resources\Virtual\ServerResource\Pages;

use App\Filament\Resources\Virtual\ServerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServer extends EditRecord
{
    protected static string $resource = ServerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
