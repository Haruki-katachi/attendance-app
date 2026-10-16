<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserService
{
    public function listByEnabled()
    {
        return User::where('is_enabled', true)->paginate(10);
    }

    public function listByDisabled()
    {
        return User::where('is_enabled', false)->paginate(10);
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $validated)
    {
        $row_password = Str::password();
        $hashed_password = Hash::make($row_password);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $hashed_password
        ]);

        $user_data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $row_password
        ];

        return $user_data;
    }

    public function update(User $user, array $validated)
    {
        $user->update($validated);
    }

    public function changeIsEnabled(User $user)
    {
        $user->update(['is_enabled' => !$user->is_enabled]);
    }
}
