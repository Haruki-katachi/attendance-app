<?php

namespace App\Http\Controllers;

use App\Exceptions\AdminMinimumCountException;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * アカウントコントローラー
 */
class UserController extends Controller
{
    /**
     * アカウントの一覧を取得してビューを返す
     *
     * @param Request $request
     * @param UserService $service
     * @return View user.index
     */
    public function index(Request $request, UserService $service): View
    {
        if ($request->has('status') && $request->status == 'disabled') {
            $users = $service->listByDisabled();
        } else {
            $users = $service->listByEnabled();
        }
        return view('user.index', compact('users'));
    }

    /**
     * アカウント作成画面のビューを返す
     *
     * @return View user.create
     */
    public function create(): View
    {
        return view('user.create');
    }

    /**
     * 登録処理を行いセッションにuser_dataを入れて（with処理）ルートuser.create_completeにリダイレクト
     *
     * @param UserRequest $request
     * @param UserService $service
     * @return RedirectResponse user.create_complete
     */
    public function store(UserRequest $request, UserService $service):RedirectResponse
    {
        $user_data = $service->create($request->validated());
        return redirect()->route('user.create_complete')->with('user_data', $user_data);
    }

    /**
     * セッションにあるuser_dataでuser.create_completeのビューを返す
     *
     * @return View user.create_complete
     */
    public function createComplete(): View
    {
        $user_data = session()->get('user_data');
        return view('user.create_complete', compact('user_data'));
    }

    /**
     * アカウント情報編集画面のビューを返す
     *
     * @param User $user
     * @return View user.edit
     */
    public function edit(User $user): View
    {
        return view('user.edit', ['edit_user' => $user]);
    }

    /**
     * 更新処理を行いルートuser.indexにリダイレクトする
     *
     * @param UserRequest $request
     * @param User $user
     * @param UserService $service
     * @return RedirectResponse user.index
     */
    public function update(UserRequest $request, User $user, UserService $service): RedirectResponse
    {
        try {
            $service->update($user, $request->validated());
            return redirect()->route('user.index')->with('success', '更新しました');
        } catch (AdminMinimumCountException $e) {
            return redirect()->route('user.index')->with('error', $e->getMessage());
        }
    }

    /**
     * アカウント有効無効処理を行いルートuser.indexにリダイレクトする
     *
     * @param User $user
     * @param UserService $service
     * @return RedirectResponse user.index
     */
    public function changeIsEnabled(User $user, UserService $service): RedirectResponse
    {
        try {
            $service->changeIsEnabled($user);
            return redirect()->route('user.index')->with('success', '更新しました');
        } catch (AdminMinimumCountException $e) {
            return redirect()->route('user.index')->with('error', $e->getMessage());
        }
    }

    /**
     * パスワードの変更画面のビューを返す
     *
     * @return View user.password
     */
    public function password(): View
    {
        return view('user.password');
    }
}
