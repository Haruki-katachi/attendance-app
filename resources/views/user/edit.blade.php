@extends('layouts.app')

@section('title', 'アカウント作成')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 my-2">
            <div class="card">
                <div class="card-header">アカウント編集</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.update', $edit_user->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">名前</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" step="60" value="{{ old('name', $edit_user->name) }}">
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mailアドレス</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" step="60" value="{{ old('email', $edit_user->email) }}">
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mb-3">
                            <input type="checkbox" id="is_admin" name="is_admin" value="1" {{ old('is_admin', $edit_user->is_admin) ? 'checked' : '' }}>
                            <label for="is_admin" class="form-label">管理者にする</label>
                        </div>
                        <input type="submit" value="更新" class="btn btn-primary" />
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">キャンセル</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
