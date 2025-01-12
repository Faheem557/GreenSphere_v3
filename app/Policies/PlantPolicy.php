<?php

namespace App\Policies;

use App\Models\Plant;
use App\Models\User;

class PlantPolicy
{
    public function update(User $user, Plant $plant)
    {
        return $user->id === $plant->user_id;
    }

    public function delete(User $user, Plant $plant)
    {
        return $user->id === $plant->user_id;
    }
} 