<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * アカウントサービスクラス
 */
class UserService
{
    /**
     * 有効なアカウントのコレクションを返す
     *
     * @return Collection 有効なアカウントのコレクション
     */
    public function listByEnabled(): Collection
    {
        return User::where('is_enabled', true)->paginate(10);
    }

    /**
     * 無効なアカウントのコレクションを返す
     *
     * @return Collection 無効なアカウントのコレクション
     */
    public function listByDisabled(): Collection
    {
        return User::where('is_enabled', false)->paginate(10);
    }

    /**
     * 指定したIDのuserを返す
     *
     * @param int $id
     * @return User アカウント
     */
    public function findById($id): User
    {
        return User::findOrFail($id);
    }

    /**
     * パスワードを生成してuserを登録する
     * 登録されたuser_data（平文のパスワードを含む）を返す
     *
     * @param array $validated
     * @return array user_data（平文のパスワードを含む）
     */
    public function create(array $validated): array
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

    /**
     * アカウントを更新する
     *
     * @param User $user
     * @param array $validated
     * @return void
     */
    public function update(User $user, array $validated)
    {
        $user->update($validated);
    }

    /**
     * アカウントの有効無効を切り替える
     *
     * @param User $user
     * @return void
     */
    public function changeIsEnabled(User $user)
    {
        $user->update(['is_enabled' => !$user->is_enabled]);
    }
}
