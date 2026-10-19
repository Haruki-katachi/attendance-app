<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Exceptions\AdminMinimumCountException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Override;

/**
 * アカウントのモデル
 */
#[Fillable(['name', 'email', 'password', 'is_enabled', 'is_admin', 'needs_password_change'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'is_enabled' => 'boolean',
            'is_admin' => 'boolean',
            'needs_password_change' => 'boolean',
        ];
    }

    #[Override]
    protected static function booted()
    {
        static::updating(function ($user) {
            if (($user->isDirty('is_admin') && !$user->is_admin) || ($user->isDirty('is_enabled') && !$user->is_enabled)) {
                $admin_count = self::where('id', '!=', $user->id)
                    ->where('is_enabled', true)
                    ->where('is_admin', true)
                    ->count();

                if ($admin_count <= 0) {
                    throw new AdminMinimumCountException('最後の管理者のためこの操作は無効です');
                }
            }
        });
    }
}
