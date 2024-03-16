<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Repository;

class RepositoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function pass(User $user, Repository $repository)
    {
        return $user->id == $repository->user_id;
    }
}
