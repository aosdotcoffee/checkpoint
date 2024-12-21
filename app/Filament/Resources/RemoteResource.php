<?php

namespace App\Filament\Resources;

use App\Enums\RemoteStatus;
use App\Filament\Resources\RemoteResource\Pages;
use App\Filament\Resources\RemoteResource\RelationManagers;
use App\Models\Remote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RemoteResource extends Resource
{
    protected static ?string $model = Remote::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(8)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Build and Shoot')
                    ->required()
                    ->columnSpan(6),

                Forms\Components\TextInput::make('short_name')
                    ->label('Short name / identifier')
                    ->placeholder('bns')
                    ->required()
                    ->columnSpan(2),

                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->placeholder('https://services.buildandshoot.com/serverlist.json')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->selectable(false)

            ->reorderable('order')
            ->defaultSort('order', direction: 'asc')

            ->columns([
                Tables\Columns\ToggleColumn::make('enabled')
                    ->extraHeaderAttributes(['style' => 'width: 0']),

                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->url(fn ($state) => $state)
                    ->color('primary')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconPosition(IconPosition::After),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListRemotes::route('/'),
            'create' => Pages\CreateRemote::route('/create'),
            'edit' => Pages\EditRemote::route('/{record}/edit'),
        ];
    }
}
