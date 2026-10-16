<?php

namespace App\Http\Controllers;

use App\Exceptions\AdminMinimumCountException;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request, UserService $service)
    {
        if ($request->has('status') && $request->status == 'disabled') {
            $users = $service->listByDisabled();
        } else {
            $users = $service->listByEnabled();
        }
        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(UserRequest $request, UserService $service)
    {
        $user_data = $service->create($request->validated());
        return redirect()->route('user.create_complete')->with('user_data', $user_data);
    }

    public function createComplete()
    {
        $user_data = session()->get('user_data');
        return view('user.create_complete', compact('user_data'));
    }

    public function edit(User $user)
    {
        return view('user.edit', ['edit_user' => $user]);
    }

    public function update(UserRequest $request, User $user, UserService $service)
    {
        try {
            $service->update($user, $request->validated());
            return redirect()->route('user.index')->with('success', '更新しました');
        } catch (AdminMinimumCountException $e) {
            return redirect()->route('user.index')->with('error', $e->getMessage());
        }
    }

    public function changeIsEnabled(User $user, UserService $service)
    {
        try {
            $service->changeIsEnabled($user);
            return redirect()->route('user.index')->with('success', '更新しました');
        } catch (AdminMinimumCountException $e) {
            return redirect()->route('user.index')->with('error', $e->getMessage());
        }
    }

    public function password()
    {
        return view('user.password');
    }
}
