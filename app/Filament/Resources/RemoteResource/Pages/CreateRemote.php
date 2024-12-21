<?php

namespace App\Filament\Resources\RemoteResource\Pages;

use App\Filament\Resources\RemoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRemote extends CreateRecord
{
    protected static string $resource = RemoteResource::class;
}
