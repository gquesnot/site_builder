<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DbDataObjectResource\Pages;
use App\Filament\Resources\DbDataObjectResource\RelationManagers;
use App\Filament\Resources\DbDataObjectResource\RelationManagers\PropertiesRelationManager;
use App\Models\DbDataObject;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DbDataObjectResource extends Resource
{
    protected static ?string $model = DbDataObject::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
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
            PropertiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDbDataObjects::route('/'),
            'create' => Pages\CreateDbDataObject::route('/create'),
            'edit' => Pages\EditDbDataObject::route('/{record}/edit'),
        ];
    }
}
