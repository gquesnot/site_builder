<?php

namespace App\Observers;

use App\Models\DbProperty;

class DbPropertyObserver
{
    public function created(DbProperty $dbProperty)
    {

    }

    public function updated(DbProperty $dbProperty)
    {
    }

    public function deleted(DbProperty $dbProperty)
    {
        $dbProperty->load('relation');

        if ($dbProperty->relation){
            $dbProperty->reverse()->delete();
        }
    }

    public function restored(DbProperty $dbProperty)
    {
    }

    public function forceDeleted(DbProperty $dbProperty)
    {
    }
}
