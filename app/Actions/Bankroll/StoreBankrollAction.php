<?php

namespace App\Actions\Bankroll;

use App\Models\Bankroll\Bankroll;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StoreBankrollAction
{
    /**
     * Store a new bankroll for the given user
     */
    public static function handle(User $user, mixed $data): Model|Bankroll
    {
        return $user->bankrolls()->create($data);
    }
}
