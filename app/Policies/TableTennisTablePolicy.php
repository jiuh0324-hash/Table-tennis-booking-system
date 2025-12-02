<?php

namespace App\Policies;

use App\Models\TableTennisTable;
use App\Models\User;

class TableTennisTablePolicy
{

    public function viewAny(User $user)
    {

        return true;
    }

    public function view(User $user, TableTennisTable $tableTennisTable)
    {

        return true;
    }

    public function create(User $user)
    {

        return $user->isAdmin();
    }


    public function update(User $user, TableTennisTable $tableTennisTable)
    {

        return $user->isAdmin();
    }


    public function delete(User $user, TableTennisTable $tableTennisTable)
    {
        
        return $user->isAdmin();
    }
}