<?php

namespace App\Filament\Resources\DbModelResource\RelationManagers;

use App\Datas\PropertyOptions;
use App\Enums\CastType;
use App\Enums\PropertyType;
use App\Enums\RelationType;
use App\Models\DbDataObject;
use App\Models\DbEnum;
use App\Models\DbModel;
use App\Models\DbProperty;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PropertiesRelationManager extends RelationManager
{
    protected static string $relationship = 'properties';

    protected static ?string $recordTitleAttribute = 'name';


    public static function handle_cast_name($name, $get, $set, DbModel $db_Model)
    {
        $name = Str::of($name);
        if (!$get('cast.type') || $name == "") return;
        $cast_type = CastType::from($get('cast.type'));
        if ($cast_type == CastType::ENUM) {
            $set('cast.castable.name', $name->studly()->prepend($db_Model->name)->append('Enum')->__toString());
        } elseif ($cast_type == CastType::DATA_OBJECT) {
            $set('cast.castable.name', $name->studly()->prepend($db_Model->name)->append('Data')->__toString());
        }
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (PropertiesRelationManager $livewire ,$state, $set, $get, $record) {
                        if (!$state) return;
                        $state = Str::of($state);
                        if (!$get('type')) {
                            if ($state->startsWith('is_') || $state->startsWith('has_') || $state->startsWith('can_') || $state->startsWith('should_')) {
                                $type = PropertyType::BOOLEAN;
                            }
                            if (isset($type)) {
                                $set('type', $type->value);
                                $set('cast.type', $type->default_cast_option());
                                if ($type->can_default()) {
                                    $set('default', $type->default_value());
                                }
                            }

                        }
                        self::handle_cast_name($state, $get, $set, $livewire->getOwnerRecord());
                    })
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options(PropertyType::select())
                    ->enum(PropertyType::class)
                    ->afterStateUpdated(function (PropertiesRelationManager $livewire, $state, $get, $set) {
                        if (!$state) return;
                        $type = PropertyType::from($state);
                        if ($type->has_cast()) {
                            $set('cast.type', $type->default_cast_option());
                        } else {
                            $set('cast.type', null);
                        }
                        $set('options', $type->default_options());
                        self::handle_cast_name($get('name'), $get, $set, $livewire->getOwnerRecord());
                    })
                    ->reactive()
                    ->required(),
                Forms\Components\TextInput::make('default')
                    ->hidden(function (\Closure $get) {
                        $property_type = $get('type');
                        if (!$property_type) return true;
                        return !PropertyType::from($property_type)->can_default();
                    })
                    ->maxLength(255),
                Forms\Components\Select::make('foreign_model_id')
                    ->label('Foreign ModelTemplate')
                    ->hidden(function (\Closure $get) {
                        $property_type = $get('type');
                        if (!$property_type) return true;
                        return PropertyType::from($property_type) != PropertyType::FOREIGN_FOR;
                    })
                    ->options(function (\Closure $get) {
                        $property_type = $get('type');
                        if (!$property_type) return [];
                        return DbModel::all()->mapWithKeys(function ($model) {
                            return [$model->id => $model->name];
                        });
                    }),
                Forms\Components\Fieldset::make('options')
                    ->schema([
                        Forms\Components\CheckboxList::make('options')
                            ->afterStateHydrated(function ($state, $get, $set) {
                                $set('options', array_keys(Arr::where($state, function ($value, $key) {
                                    return $value;
                                })));
                            })
                            ->options(function (\Closure $get) {
                                $property_type = $get('type');
                                if (!$property_type) return [];
                                return PropertyOptions::options_from_type($property_type);
                            })->columns(3),

                    ])->columns(1)->columnSpanFull(),
                Forms\Components\Fieldset::make('cast')
                    ->relationship('cast')
                    ->afterStateHydrated(function (?DbProperty $record, $set) {
                        if (!$record?->cast) return;
                        if ($record->cast->type == CastType::ENUM || $record->cast->type == CastType::DATA_OBJECT) {
                            $set('cast.castable.name', $record->cast->castable->name);
                        }
                    })
                    ->saveRelationshipsUsing(function (?DbProperty $record, $state) {
                        $name = $state['castable']['name'];
                        Arr::forget($state, 'castable');
                        Arr::forget($state, 'id');
                        if ($state['type'] == null) return null;
                        $record->cast()->updateOrCreate(['id' => $record->cast?->id], $state);
                        $record->load('cast');
                        if ($record->cast->type == CastType::ENUM || $record->cast->type == CastType::DATA_OBJECT) {
                            if ($record->cast->type == CastType::ENUM) {
                                $enum = DbEnum::updateOrCreate(["name" => $name], ['name' => $name]);
                                $record->cast->castable()->associate($enum);
                            } else {
                                $data = DbDataObject::updateOrCreate(["name" => $name], ['name' => $name]);
                                $record->cast->castable()->associate($data);
                            }
                            $record->cast->save();
                        } else {
                            $record->cast->castable()->delete();
                        }
                    })
                    ->hidden(function (\Closure $get) {
                        $property_type = $get('type');
                        if (!$property_type) return true;
                        return !PropertyType::from($property_type)->has_cast();
                    })
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options(function (\Closure $get, $set) {
                                $property_type = $get('../type');
                                if (!$property_type) {
                                    return [];
                                }
                                return PropertyType::from($property_type)->cast_options();
                            })
                            ->reactive()
                            ->afterStateUpdated(function (PropertiesRelationManager $livewire, $state, $get, $set) {
                                if ((!$state)) return;
                                $cast_type = CastType::from($state);
                                if ($cast_type == CastType::ENUM) {
                                    $set('castable.name', Str::of($get('../name'))->studly()->prepend($livewire->getOwnerRecord())->append('Enum')->toString());
                                } elseif ($cast_type == CastType::DATA_OBJECT) {
                                    $set('castable.name', Str::of($get('../name'))->studly()->prepend($livewire->getOwnerRecord())->append('Data')->toString());
                                }
                            })
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('castable.name')
                            ->reactive()
                            ->hidden(function (\Closure $get) {
                                $cast_type = $get('type');
                                if (!$cast_type) return true;
                                $cast_type = CastType::from($cast_type);
                                return $cast_type != CastType::ENUM && $cast_type != CastType::DATA_OBJECT;
                            })
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type')->formatStateUsing(function (DbProperty $record, $state) {
                    $record->load('foreign_model');
                    return $record->type->value . ($record->type == PropertyType::FOREIGN_FOR ? ' (' . $record->foreign_model->name . ')' : '');
                }),
                Tables\Columns\TextColumn::make('cast.type')->label('Cast Type'),
                Tables\Columns\TextColumn::make('cast.castable.name')->label('Cast'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(false)->using(function (DbProperty $record) {
                    DbProperty::where('id', $record->id)->delete();
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
