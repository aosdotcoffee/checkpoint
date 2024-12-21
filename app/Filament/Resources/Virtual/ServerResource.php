<?php

namespace App\Filament\Resources\Virtual;

use App\Filament\Resources\Virtual\ServerResource\Pages;
use App\Filament\Resources\Virtual\ServerResource\RelationManagers;
use App\Models\Ban;
use App\Models\Virtual\Server;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->selectable(false)
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->icon(fn (Server $server) => $server->authority ? 'heroicon-m-check-badge': null)
                    ->iconPosition(IconPosition::After)
                    ->iconColor('success')
                    ->tooltip(fn (Server $server) =>
                        $server->authority ?
                            "Verified authority: {$server->authority->name}":
                            null
                    ),

                Tables\Columns\TextColumn::make('players_current')
                    ->sortable()
                    ->label('Players')
                    ->formatStateUsing(fn ($state, Server $server) => "{$server->players_current}/{$server->players_max}"),

                Tables\Columns\TextColumn::make('map')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gamemode')
                    ->sortable()
                    ->label('Gamemode'),

                Tables\Columns\TextColumn::make('identifier')
                    ->url(fn ($state) => $state, shouldOpenInNewTab: true)
                    ->color('primary')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconPosition(IconPosition::After),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('ban')
                    ->label('Ban')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->fillForm(fn (Server $server) => [
                        'name' => $server->name,
                        'ip_address' => $server->ip_address,
                    ])
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->required(),

                        Forms\Components\TextInput::make('ip_address')
                            ->label('Range (CIDR)')
                            ->required(),

                        Forms\Components\Textarea::make('reason')
                            ->required()
                            ->autofocus(),
                    ])
                    ->modalIcon('heroicon-o-no-symbol')
                    ->modalHeading(fn (Server $server) => "Ban \"{$server->name}\"?")
                    ->modalDescription('This server will no longer appear in the global list.')
                    ->modalSubmitActionLabel('Ban')
                    ->action(function (array $data) {
                        Ban::create([
                            'name' => $data['name'],
                            'cidr' => $data['ip_address'],
                            'reason' => $data['reason'],
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('players_current', direction: 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServers::route('/'),
        ];
    }
}
