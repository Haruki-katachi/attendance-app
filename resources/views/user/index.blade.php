@extends('layouts.app')

@section('title', '従業員一覧')

@section('content')

<h3>従業員一覧</h3>
<hr>
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="d-flex justify-content-between align-items-center w-100">
    <div>
        <a href="{{ route('user.create') }}" class="btn btn-primary">アカウント作成</a>
    </div>
    <div>
        <form action="{{ route('user.index') }}" method="GET" class="m-0">
            <input type="checkbox" name="status" value="disabled" id="status" class="btn-check" onchange="this.form.submit()" {{ request()->status == 'disabled' ? 'checked' : '' }} />
            <label for="status" class="btn btn-outline-danger">無効アカウントを表示</label>
        </form>
    </div>
</div>
<table class="table">
    <thead>
        <tr>
            <th>名前</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td class="d-flex align-items-center">
                    <a href="{{ route('attendance.admin_month', $user) }}" class="btn btn-primary">記録</a>
                    <a href="{{ route('user.edit', $user) }}" class="btn btn-secondary">編集</a>
                    <form action="{{ route('user.change_is_enabled', $user) }}" method="POST">
                        @csrf
                        @if ($user->is_enabled)
                            <button type="submit" class="btn btn-danger">無効化</button>
                        @else
                            <button type="submit" class="btn btn-success">有効化</button>
                        @endif
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div>
    {{ $users->links() }}
</div>
@endsection
