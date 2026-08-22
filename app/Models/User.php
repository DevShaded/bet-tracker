<?php

namespace App\Models;

use App\Models\Bankroll\Bankroll;
use App\Models\Bets\Bet;
use App\Models\Bookmaker\Bookmaker;
use App\Models\Tipster\TipCategory;
use App\Models\Tipster\Tipster;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /** @return HasMany<Bookmaker, $this> */
    public function bookmakers(): HasMany
    {
        return $this->hasMany(Bookmaker::class, 'user_id');
    }

    /** @return HasMany<Bankroll, $this> */
    public function bankrolls(): HasMany
    {
        return $this->hasMany(Bankroll::class, 'user_id');
    }

    /** @return HasMany<Tipster, $this> */
    public function tipsters(): HasMany
    {
        return $this->hasMany(Tipster::class, 'user_id');
    }

    /** @return HasMany<TipCategory, $this> */
    public function tipCategories(): HasMany
    {
        return $this->hasMany(TipCategory::class, 'user_id');
    }

    /** @return HasMany<Bet, $this> */
    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class, 'user_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
}
