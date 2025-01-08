<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;

class StorePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Store $store): bool
    {
        // Anyone can view active stores
        if ($store->is_active) {
            return true;
        }
        
        // Owner can view their stores regardless of status
        return $user->id === $store->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Store $store): bool
    {
        return $user->id === $store->user_id;
    }

    public function delete(User $user, Store $store): bool
    {
        return $user->id === $store->user_id;
    }

    public function toggleStatus(User $user, Store $store): bool
    {
        return $user->id === $store->user_id;
    }

    public function withdrawBalance(User $user, Store $store): bool
    {
        return $user->id === $store->user_id && $store->balance > 0;
    }

    public function managePacks(User $user, Store $store): bool
    {
        return $user->id === $store->user_id && $store->is_active;
    }
}
