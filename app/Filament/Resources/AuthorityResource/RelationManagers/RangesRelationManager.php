<?php

namespace App\Filament\Resources\AuthorityResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RangesRelationManager extends RelationManager
{
    protected static string $relationship = 'ranges';

    protected static ?string $label = 'range';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\TextInput::make('cidr')
                    ->required()
                    ->maxLength(255)
                    ->label('Range (CIDR)')
                    ->placeholder('192.168.2.0/24')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->paginated(false)
            ->selectable(false)
            ->columns([
                Tables\Columns\ToggleColumn::make('enabled')
                    ->label(false)
                    ->extraHeaderAttributes(['style' => 'width: 0']),

                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('cidr')
                    ->label('Range'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
