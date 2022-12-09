<?php

namespace App\Filament\Resources\DbDataObjectResource\RelationManagers;

use App\Enums\DataObjectType;
use App\Enums\DtoPropertyType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Validation\Rules\Unique;

class PropertiesRelationManager extends RelationManager
{
    protected static string $relationship = 'properties';

    protected static ?string $recordTitleAttribute = 'name';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->unique('data_object_properties', 'name', callback: function (PropertiesRelationManager $livewire, Unique $rule, $get) {
                        return $rule->where('data_object_id', $livewire->getOwnerRecord()->id);
                    }, ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options(DtoPropertyType::select()),
                Forms\Components\Toggle::make('is_nullable')
                    ->required()
                    ->reactive()
                    ->columnSpanFull()
                    ->default(false),

                Forms\Components\TextInput::make('default')
                    ->hidden(function ($get) {
                        return $get('is_nullable');
                    })
                    ->reactive()
                    ->required()
                    ->maxLength(255),
            ])->columns(2);
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(false),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
