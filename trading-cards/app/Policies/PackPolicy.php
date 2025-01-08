<?php

namespace App\Policies;

use App\Models\Pack;
use App\Models\User;

class PackPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Pack $pack): bool
    {
        // Anyone can view available packs
        if ($pack->is_available) {
            return true;
        }
        
        // Owner can view their packs regardless of availability
        return $user->id === $pack->user_id;
    }

    public function create(User $user): bool
    {
        // User must have at least one active store to create packs
        return $user->stores()->where('is_active', true)->exists();
    }

    public function update(User $user, Pack $pack): bool
    {
        return $user->id === $pack->user_id;
    }

    public function delete(User $user, Pack $pack): bool
    {
        return $user->id === $pack->user_id;
    }

    public function purchase(User $user, Pack $pack): bool
    {
        // Users can't buy their own packs
        if ($user->id === $pack->user_id) {
            return false;
        }

        // Pack must be available for purchase
        return $pack->is_available;
    }

    public function toggleAvailability(User $user, Pack $pack): bool
    {
        return $user->id === $pack->user_id;
    }
}
