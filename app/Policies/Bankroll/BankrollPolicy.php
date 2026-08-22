<?php

namespace App\Policies\Bankroll;

use App\Models\Bankroll\Bankroll;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankrollPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Bankroll $bankroll): bool
    {
        return $user->id === $bankroll->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Bankroll $bankroll): bool
    {
        return $user->id === $bankroll->user_id;
    }

    public function delete(User $user, Bankroll $bankroll): bool
    {
        return $user->id === $bankroll->user_id;
    }

    public function restore(User $user, Bankroll $bankroll): bool
    {
        return $user->id === $bankroll->user_id;
    }

    public function forceDelete(User $user, Bankroll $bankroll): bool
    {
        return $user->id === $bankroll->user_id;
    }
}
