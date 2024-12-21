<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BanResource\Pages;
use App\Filament\Resources\BanResource\RelationManagers;
use App\Models\Ban;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BanResource extends Resource
{
    protected static ?string $model = Ban::class;

    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->columnSpanFull()
                    ->autofocus()
                    ->required(),

                Forms\Components\TextInput::make('cidr')
                    ->required()
                    ->maxLength(255)
                    ->label('Range (CIDR)')
                    ->placeholder('192.168.2.0/24')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('reason')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('cidr')
                    ->label('Range'),
                Tables\Columns\TextColumn::make('reason'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
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
            'index' => Pages\ListBans::route('/'),
            'create' => Pages\CreateBan::route('/create'),
            'edit' => Pages\EditBan::route('/{record}/edit'),
        ];
    }
}
