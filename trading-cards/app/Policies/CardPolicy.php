<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\User;

class CardPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Card $card): bool
    {
        return $user->id === $card->user_id || $card->is_published;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Card $card): bool
    {
        return $user->id === $card->user_id;
    }

    public function delete(User $user, Card $card): bool
    {
        return $user->id === $card->user_id;
    }

    public function publish(User $user, Card $card): bool
    {
        return $user->id === $card->user_id;
    }

    public function unpublish(User $user, Card $card): bool
    {
        return $user->id === $card->user_id;
    }
}
