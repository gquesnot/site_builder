<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DbModelResource\Pages;
use App\Filament\Resources\DbModelResource\RelationManagers;
use App\Models\DbModel;
use App\Models\DbProperty;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class DbModelResource extends Resource
{
    protected static ?string $model = DbModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Select::make('primaries')
                    ->label('Primary Keys')
                    ->hidden(function(?DbModel $record) {
                        return $record == null;
                    })
                    ->relationship('properties_primary', 'name' )
                    ->options(function(?DbModel $record) {
                        return $record->properties->filter(function(DbProperty $property) {
                            return $property->type->can_primary();
                        })->pluck('name', 'id');
                    })
                    ->multiple(),
                Forms\Components\Fieldset::make('options')
                    ->schema([
                        Forms\Components\Toggle::make('with_timestamps')->default(true),
                        Forms\Components\Toggle::make('with_migration')->default(true),
                        Forms\Components\Toggle::make('with_seeder'),
                        Forms\Components\Toggle::make('with_factory'),
                        Forms\Components\Toggle::make('with_controller'),
                        Forms\Components\Toggle::make('with_resource'),
                    ])->columns(3),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\IconColumn::make('with_timestamps')
                    ->boolean(),
                Tables\Columns\IconColumn::make('with_migration')
                    ->boolean(),
                Tables\Columns\IconColumn::make('with_seeder')
                    ->boolean(),
                Tables\Columns\IconColumn::make('with_factory')
                    ->boolean(),
                Tables\Columns\IconColumn::make('with_controller')
                    ->boolean(),
                Tables\Columns\IconColumn::make('with_resource')
                    ->boolean(),
                Tables\Columns\TagsColumn::make('properties_primary.name')->label('Primaries'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PropertiesRelationManager::class,
            RelationManagers\RelationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDbModels::route('/'),
            'create' => Pages\CreateDbModel::route('/create'),
            'edit' => Pages\EditDbModel::route('/{record}/edit'),
        ];
    }
}
