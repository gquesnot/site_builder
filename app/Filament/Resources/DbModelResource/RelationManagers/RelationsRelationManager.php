<?php

namespace App\Filament\Resources\DbModelResource\RelationManagers;

use App\Enums\RelationType;
use App\Models\DbModel;
use App\Models\DbProperty;
use App\Models\DbRelation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component;

class RelationsRelationManager extends RelationManager
{
    protected static string $relationship = 'relations';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->unique('db_relations', 'name',callback: function(?DbRelation $record, Unique $rule, $get){
                        $rule = $rule->where('model_id', $get('model_id'));
                        if ($record){
                            $rule = $rule->ignore($record->id);

                        }
                        return $rule;
                    },)
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options(RelationType::select())
                    ->enum(RelationType::class)
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('other_model_id')
                    ->options(function (\Closure $get) {
                        return DbModel::pluck('name', 'id');
                    })
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('other_property_id')
                    ->options(function (\Closure $get) {
                        $model_id = $get('other_model_id');
                        if (!$model_id) return [];
                        return DbProperty::where('model_id', $model_id)->pluck('name', 'id');
                    })
                    ->reactive()
                    ->required(),
                Forms\Components\TextInput::make('reverse_name')
                    ->unique('db_relations', 'name',callback: function(?DbRelation $record, Unique $rule, $get){
                        if (!$get('other_model_id')) return $rule;
                        $rule = $rule->where('model_id', $get('other_model_id'));
                        if ($record){
                            $rule = $rule->ignore($record->reverse_id);
                        }
                        return $rule;
                    })
                    ->afterStateHydrated(function (?DbRelation $record, $set) {
                        if ($record) {
                            $set('reverse_name', $record->reverse?->name);
                        }
                    })
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Fieldset::make('pivot')
                    ->hidden(fn($get) => $get('type') !==  RelationType::MANY_TO_MANY->value && $get('type') !==  RelationType::HAS_MANY_THROUGH->value && $get('type') !==  RelationType::HAS_ONE_THROUGH->value)
                    ->schema([
                Forms\Components\Select::make('pivot_model_id')

                    ->options(function (\Closure $get) {
                        return DbModel::pluck('name', 'id');
                    })
                    ->reactive()
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Select::make('pivot_property_id')
                    ->options(function (\Closure $get) {
                        $model_id = $get('pivot_model_id');
                        $remove_id = $get('pivot_other_property_id');
                        if (!$model_id) return [];
                        $query = DbProperty::where('model_id', $model_id);
                        if($remove_id){
                            $query->whereNot('id', $remove_id);
                        }
                        return $query->pluck('name', 'id');
                    })
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('pivot_other_property_id')
                    ->options(function (\Closure $get) {
                        $model_id = $get('pivot_model_id');
                        $remove_id = $get('pivot_property_id');
                        if (!$model_id) return [];
                        $query = DbProperty::where('model_id', $model_id);
                        if($remove_id){
                            $query->whereNot('id', $remove_id);
                        }
                        return $query->pluck('name', 'id');
                    })
                    ->reactive()
                    ->required(),
            ]),

                Forms\Components\Fieldset::make('related_to')
                    ->schema([

                        Forms\Components\Select::make('model_id')
                            ->default(fn(RelationsRelationManager $livewire) => $livewire->getOwnerRecord()->id)
                            ->options(DbModel::pluck('name', 'id'))
                            ->disabled(),
                        Forms\Components\Select::make('property_id')
                            ->options(function (
                                RelationsRelationManager $livewire
                            ) {
                                return DbProperty::where('model_id', $livewire->getOwnerRecord()->id)->pluck('name', 'id');
                            })->default(function ($get) {
                                return DbModel::with('properties_primary')->find($get('model_id'))?->properties_primary->first()?->id;
                            }),
                    ])
            ]);
    }

    public static function create_or_update_record(Component|RelationsRelationManager $livewire, array $data, ?DbRelation $record): DbRelation
    {
        $parent_model = $livewire->getOwnerRecord();
        $reverse_name = Arr::pull($data, 'reverse_name');

        if ($record) {
            $record->reverse()->delete();
            $record->delete();
        }

        $reverse = [
            'model_id' => $data['other_model_id'],
            'property_id' => $data['other_property_id'],
            'other_model_id' => $parent_model->id,
            'other_property_id' => $data['property_id'],
            'name' => $reverse_name,
            'type' => RelationType::from($data['type'])->reverse(),
        ];

        $record = DbRelation::create($data);
        $reverse = DbRelation::create($reverse);

        $record->reverse()->associate($reverse);
        $record->save();

        $reverse->reverse()->associate($record);
        $reverse->save();

        return $record;
    }

    public function auto_create(RelationsRelationManager $livewire)
    {
        $model = $livewire->ownerRecord;
        $model->load('properties');
        dd($model);
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
                Tables\Actions\CreateAction::make()->using(function (RelationsRelationManager $livewire, array $data, ?DbRelation $record) {
                    return self::create_or_update_record($livewire, $data, $record);
                }),
                Tables\Actions\Action::make('auto create')
                    ->action('auto_create'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->using(function (RelationsRelationManager $livewire, array $data, ?DbRelation $record) {
                    return self::create_or_update_record($livewire, $data, $record);
                }),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(false)->using(function (RelationsRelationManager $livewire, array $data, ?DbRelation $record) {
                    $record->reverse()->delete();
                    $record->delete();
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
