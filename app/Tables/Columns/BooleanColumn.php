<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

use Filament\Forms\Components\Concerns\HasToggleColors;
use Filament\Forms\Components\Concerns\HasToggleIcons;
use Filament\Tables\Columns\Concerns\CanBeValidated;
use Filament\Tables\Columns\Contracts\Editable;
use Illuminate\Contracts\View\View;

class BooleanColumn extends Column implements Editable
{
    use CanBeValidated;
    use HasToggleColors;
    use HasToggleIcons;

    protected string $view = 'tables::columns.toggle-column';

    protected function setUp(): void
    {
        parent::setUp();
        $this->disableClick();
        $this->rules(['boolean']);
    }

    public function getState(): bool
    {
        return  array_values($this->getRecord()->select($this->getName())->first()->toArray())[0] == "true";
    }

    public function setColumnValue(){
            dd($this->getRecord()->select($this->getName())->first()->toArray());
    }

    public function render(): View
    {
        return parent::render(); // TODO: Change the autogenerated stub
    }
}