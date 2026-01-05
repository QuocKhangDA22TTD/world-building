<?php

namespace App\Policies;

use App\Models\User;
use App\Models\World;

class WorldPolicy
{
    public function view(User $user, World $world)
    {
        return $user->id === $world->owner_id || $user->isAdmin();
    }

    public function update(User $user, World $world)
    {
        return $user->id === $world->owner_id || $user->isAdmin();
    }

    public function delete(User $user, World $world)
    {
        return $user->id === $world->owner_id || $user->isAdmin();
    }
}
