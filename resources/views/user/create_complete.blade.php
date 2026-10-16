@extends('layouts.app')

@section('title', 'アカウント作成完了')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 my-2">
            <div class="card">
                <div class="card-header">アカウント作成完了</div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>名前</th>
                            <td>{{ $user_data['name'] }}</td>
                        </tr>
                        <tr>
                            <th>メールアドレス</th>
                            <td>{{ $user_data['email'] }}</td>
                        </tr>
                        <tr>
                            <th>パスワード</th>
                            <td>{{ $user_data['password'] }}</td>
                        </tr>
                    </table>
                    <p class="mt-2 text-danger">* パスワードはこの画面でしか確認できません</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
