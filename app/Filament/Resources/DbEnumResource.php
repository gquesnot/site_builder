<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DbEnumResource\Pages;
use App\Filament\Resources\DbEnumResource\RelationManagers;
use App\Filament\Resources\DbEnumResource\RelationManagers\CasesRelationManager;
use App\Models\DbEnum;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DbEnumResource extends Resource
{
    protected static ?string $model = DbEnum::class;

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
            CasesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDbEnums::route('/'),
            'create' => Pages\CreateDbEnum::route('/create'),
            'edit' => Pages\EditDbEnum::route('/{record}/edit'),
        ];
    }
}
