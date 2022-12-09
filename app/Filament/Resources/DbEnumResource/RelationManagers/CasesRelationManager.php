<?php

namespace App\Filament\Resources\DbEnumResource\RelationManagers;

use App\Filament\Resources\DbDataObjectResource\RelationManagers\PropertiesRelationManager;
use App\Models\EnumCase;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Validation\Rules\Unique;

class CasesRelationManager extends RelationManager
{
    protected static string $relationship = 'cases';

    protected static ?string $recordTitleAttribute = 'name';


    public static function update_or_create(CasesRelationManager $livewire, array $data): EnumCase
    {
        $enum = $livewire->getOwnerRecord();
        return EnumCase::updateOrCreate([
            "enum_id" => $enum->id,
            "name" => strtoupper($data['name'])
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->unique('enum_cases', 'name',callback: function(CasesRelationManager $livewire, Unique $rule, $get){
                        return $rule->where('enum_id', $livewire->getOwnerRecord()->id);
                    }, ignoreRecord: true)
                    ->required()
                    ->reactive()
                    ->maxLength(255),
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
            ->headerActions([
                Tables\Actions\CreateAction::make()->using(function (array $data, ?EnumCase $record, CasesRelationManager $livewire) {
                    return self::update_or_create($livewire, $data);
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->using(function (array $data, ?EnumCase $record, CasesRelationManager $livewire) {
                    return self::update_or_create($livewire, $data);
                }),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(false),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
