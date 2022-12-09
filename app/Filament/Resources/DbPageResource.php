<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DbPageResource\Pages;
use App\Filament\Resources\DbPageResource\RelationManagers;
use App\Models\DbPage;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class DbPageResource extends Resource
{
    protected static ?string $model = DbPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->afterStateUpdated(function ($get, $set){
                        if ($get('name')) return;
                        $set('slug', Str::of($get('name'))->slug());
                    })
                    ->maxLength(191),
                Forms\Components\TextInput::make('slug')
                    ->required()

                    ->disabled()
                    ->maxLength(191),
                Forms\Components\Select::make('model_id')
                    ->options(function (\Closure $get) {
                        return \App\Models\DbModel::pluck('name', 'id');
                    })
                    ->required()
                ->columnSpanFull(),
                Forms\Components\Fieldset::make('options')
            ->columnSpanFull()
            ->schema([
                Forms\Components\Toggle::make('has_view')
                    ->default(true)->columns(1),
                Forms\Components\Toggle::make('has_list')
                    ->default(true)->columns(1),
                Forms\Components\Toggle::make('with_pagination')
                    ->default(true)->columns(1),
                Forms\Components\Toggle::make('with_filter')
                    ->default(true)->columns(1),
            ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('model.name'),
                Tables\Columns\IconColumn::make('has_view')
                    ->boolean(),
                Tables\Columns\IconColumn::make('has_list')
                    ->boolean(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDbPages::route('/'),
            'create' => Pages\CreateDbPage::route('/create'),
            'edit' => Pages\EditDbPage::route('/{record}/edit'),
        ];
    }
}
